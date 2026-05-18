<?php

namespace App\Helpers;

use App\Models\Language;
use App\Models\LanguageTranslation;
use App\Services\AutoTranslationService;
use Illuminate\Support\Facades\Cache;

class TranslationHelper
{
    public static function t($key, $group = 'frontend', $fallback = null)
    {
        $locale = app()->getLocale();
        
        // First try to get from database
        $translation = LanguageTranslation::whereHas('language', function($query) use ($locale) {
            $query->where('code', $locale);
        })->where('group_name', $group)
        ->where('key_name', $key)
        ->first();

        if ($translation) {
            return $translation->value;
        }

        // Fallback to Laravel's translation system
        $laravelTranslation = __($group . '.' . $key);
        if ($laravelTranslation !== $group . '.' . $key) {
            return $laravelTranslation;
        }

        // Return fallback or key
        return $fallback ?: $key;
    }

    public static function getCurrentLanguage()
    {
        $locale = app()->getLocale();
        return Language::where('code', $locale)->first();
    }

    public static function getAvailableLanguages()
    {
        return Language::active()->ordered()->get();
    }

    public static function isCurrentLanguage($code)
    {
        return app()->getLocale() === $code;
    }

    public static function getCurrentLanguageDirection()
    {
        $language = self::getCurrentLanguage();
        return $language && $language->is_rtl ? 'rtl' : 'ltr';
    }
}

if (!function_exists('translate_content')) {
    /**
     * Translate dynamic content (hotels, tours, CMS pages)
     * Usage: translate_content($hotel, 'title', 'ar')
     */
    function translate_content($model, $field, $locale = null, $autoTranslate = true)
    {
        if (!$locale) {
            $locale = app()->getLocale();
        }

        // If same language, return original
        if ($locale === 'en') {
            return $model->$field ?? '';
        }

        // Use the model's translation method if available
        if (method_exists($model, 'getTranslatedField')) {
            return $model->getTranslatedField($field, $locale);
        }

        // Check if we have a stored translation
        $translation = get_stored_translation($model, $field, $locale);
        if ($translation) {
            return $translation;
        }

        // Auto-translate if enabled and no stored translation
        if ($autoTranslate && config('app.auto_translate', true)) {
            $translation = auto_translate_content($model->$field ?? '', $locale);
            if ($translation && $translation !== $model->$field) {
                // Store the auto-translation for future use
                store_translation($model, $field, $locale, $translation);
                return $translation;
            }
        }

        // Fallback to original content
        return $model->$field ?? '';
    }
}

if (!function_exists('get_stored_translation')) {
    /**
     * Get stored translation from database
     */
    function get_stored_translation($model, $field, $locale)
    {
        $cacheKey = "translation_{$model->getTable()}_{$model->id}_{$field}_{$locale}";
        
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($model, $field, $locale) {
            // Check if translation exists in database
            $translation = \DB::table('content_translations')
                ->where('translatable_type', get_class($model))
                ->where('translatable_id', $model->id)
                ->where('locale', $locale)
                ->where('field_name', $field)
                ->value('field_value');
            
            return $translation;
        });
    }
}

if (!function_exists('store_translation')) {
    /**
     * Store translation in database
     */
    function store_translation($model, $field, $locale, $value)
    {
        try {
            \DB::table('content_translations')->updateOrInsert(
                [
                    'translatable_type' => get_class($model),
                    'translatable_id' => $model->id,
                    'locale' => $locale,
                    'field_name' => $field,
                ],
                [
                    'field_value' => $value,
                    'updated_at' => now(),
                ]
            );

            // Clear cache
            $cacheKey = "translation_{$model->getTable()}_{$model->id}_{$field}_{$locale}";
            Cache::forget($cacheKey);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to store translation: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('auto_translate_content')) {
    /**
     * Auto-translate content using translation service
     */
    function auto_translate_content($text, $targetLocale, $sourceLocale = 'en')
    {
        if (empty($text) || $targetLocale === $sourceLocale) {
            return $text;
        }

        try {
            $translationService = app(AutoTranslationService::class);
            return $translationService->translate($text, $targetLocale, $sourceLocale);
        } catch (\Exception $e) {
            \Log::error('Auto-translation failed: ' . $e->getMessage());
            return $text;
        }
    }
}

if (!function_exists('translate_menu')) {
    /**
     * Translate menu items
     */
    function translate_menu($menu, $locale = null)
    {
        if (!$locale) {
            $locale = app()->getLocale();
        }

        if ($locale === 'en') {
            return $menu->title;
        }

        // Check stored menu translation
        $translation = \DB::table('menu_translations')
            ->where('menu_id', $menu->id)
            ->where('locale', $locale)
            ->value('title');

        return $translation ?: $menu->title;
    }
}

if (!function_exists('batch_translate_content')) {
    /**
     * Batch translate multiple content items
     */
    function batch_translate_content($items, $fields = ['title', 'content'], $targetLocale = null)
    {
        if (!$targetLocale) {
            $targetLocale = app()->getLocale();
        }

        if ($targetLocale === 'en') {
            return $items;
        }

        $translationService = app(AutoTranslationService::class);
        
        foreach ($items as $item) {
            foreach ($fields as $field) {
                if (isset($item->$field) && !empty($item->$field)) {
                    $translation = $translationService->translate($item->$field, $targetLocale);
                    if ($translation && $translation !== $item->$field) {
                        store_translation($item, $field, $targetLocale, $translation);
                    }
                }
            }
        }

        return $items;
    }
}
