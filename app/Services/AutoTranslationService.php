<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AutoTranslationService
{
    protected $providers = [
        'google' => 'https://translation.googleapis.com/language/translate/v2',
        'deepl' => 'https://api-free.deepl.com/v2/translate',
        'libre' => 'https://libretranslate.com/translate'
    ];

    /**
     * Auto-translate text to target language
     */
    public function translate($text, $targetLanguage, $sourceLanguage = 'en', $provider = 'google')
    {
        if (empty($text) || $targetLanguage === $sourceLanguage) {
            return $text;
        }

        // Check cache first
        $cacheKey = "translation_{$sourceLanguage}_{$targetLanguage}_" . md5($text);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $translation = $this->translateWithProvider($text, $targetLanguage, $sourceLanguage, $provider);
            
            if ($translation) {
                // Cache for 30 days
                Cache::put($cacheKey, $translation, now()->addDays(30));
                return $translation;
            }
        } catch (\Exception $e) {
            Log::error('Translation failed: ' . $e->getMessage());
        }

        return $text; // Fallback to original text
    }

    /**
     * Translate using specified provider
     */
    protected function translateWithProvider($text, $targetLanguage, $sourceLanguage, $provider)
    {
        switch ($provider) {
            case 'google':
                return $this->translateWithGoogle($text, $targetLanguage, $sourceLanguage);
            case 'deepl':
                return $this->translateWithDeepL($text, $targetLanguage, $sourceLanguage);
            case 'libre':
                return $this->translateWithLibre($text, $targetLanguage, $sourceLanguage);
            default:
                return $this->translateWithGoogle($text, $targetLanguage, $sourceLanguage);
        }
    }

    /**
     * Google Translate (requires API key)
     */
    protected function translateWithGoogle($text, $targetLanguage, $sourceLanguage)
    {
        $apiKey = config('services.google.translate_api_key');
        if (!$apiKey) {
            return null;
        }

        $response = Http::post($this->providers['google'], [
            'q' => $text,
            'target' => $targetLanguage,
            'source' => $sourceLanguage,
            'key' => $apiKey
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['data']['translations'][0]['translatedText'] ?? null;
        }

        return null;
    }

    /**
     * DeepL Translate (requires API key)
     */
    protected function translateWithDeepL($text, $targetLanguage, $sourceLanguage)
    {
        $apiKey = config('services.deepl.api_key');
        if (!$apiKey) {
            return null;
        }

        $response = Http::withHeaders([
            'Authorization' => 'DeepL-Auth-Key ' . $apiKey
        ])->post($this->providers['deepl'], [
            'text' => [$text],
            'target_lang' => strtoupper($targetLanguage),
            'source_lang' => strtoupper($sourceLanguage)
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['translations'][0]['text'] ?? null;
        }

        return null;
    }

    /**
     * LibreTranslate (free, can be self-hosted)
     */
    protected function translateWithLibre($text, $targetLanguage, $sourceLanguage)
    {
        $response = Http::post($this->providers['libre'], [
            'q' => $text,
            'source' => $sourceLanguage,
            'target' => $targetLanguage,
            'format' => 'text'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['translatedText'] ?? null;
        }

        return null;
    }

    /**
     * Batch translate multiple fields
     */
    public function translateContent($content, $targetLanguage, $sourceLanguage = 'en', $fields = ['title', 'content', 'meta_description'])
    {
        $translated = [];
        
        foreach ($fields as $field) {
            if (isset($content[$field]) && !empty($content[$field])) {
                $translated[$field] = $this->translate($content[$field], $targetLanguage, $sourceLanguage);
            }
        }

        return $translated;
    }

    /**
     * Get available languages
     */
    public function getAvailableLanguages()
    {
        return [
            'en' => 'English',
            'ar' => 'العربية',
            'es' => 'Español',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'it' => 'Italiano',
            'pt' => 'Português',
            'ru' => 'Русский',
            'zh' => '中文',
            'ja' => '日本語',
            'ko' => '한국어',
            'hi' => 'हिन्दी',
            'tr' => 'Türkçe',
            'nl' => 'Nederlands',
            'pl' => 'Polski',
            'sv' => 'Svenska',
            'da' => 'Dansk',
            'no' => 'Norsk',
            'fi' => 'Suomi',
            'he' => 'עברית',
            'ur' => 'اردو'
        ];
    }
}
