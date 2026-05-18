<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\LanguageTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::ordered()->get();
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:languages,code',
            'flag' => 'nullable|string|max:10',
            'flag_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_rtl' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $flagImagePath = null;
        if ($request->hasFile('flag_image')) {
            try {
                $flagImage = $request->file('flag_image');
                
                // Validate file
                if (!$flagImage->isValid()) {
                    return back()->withErrors(['flag_image' => 'Invalid file upload.'])->withInput();
                }
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('images/langflag');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                $flagImageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $flagImage->getClientOriginalName());
                $flagImage->move($uploadPath, $flagImageName);
                $flagImagePath = 'images/langflag/' . $flagImageName;
            } catch (\Exception $e) {
                return back()->withErrors(['flag_image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
            }
        }

        $language = Language::create([
            'name' => $request->name,
            'code' => $request->code,
            'flag' => $request->flag,
            'flag_image' => $flagImagePath,
            'is_rtl' => $request->input('is_rtl') == '1',
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true
        ]);

        // Create language directory and file
        $this->createLanguageFile($language->code);

        // Update config file after creation - DISABLED to prevent corruption
        // $this->updateConfigFile();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language created successfully!');
    }

    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:languages,code,' . $language->id,
            'flag' => 'nullable|string|max:10',
            'flag_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_rtl' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $flagImagePath = $language->flag_image;
        if ($request->hasFile('flag_image')) {
            try {
                $flagImage = $request->file('flag_image');
                
                // Validate file
                if (!$flagImage->isValid()) {
                    return back()->withErrors(['flag_image' => 'Invalid file upload.'])->withInput();
                }
                
                // Delete old image if exists
                if ($language->flag_image && file_exists(public_path($language->flag_image))) {
                    unlink(public_path($language->flag_image));
                }
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('images/langflag');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                $flagImageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $flagImage->getClientOriginalName());
                $flagImage->move($uploadPath, $flagImageName);
                $flagImagePath = 'images/langflag/' . $flagImageName;
            } catch (\Exception $e) {
                return back()->withErrors(['flag_image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
            }
        }

        $language->update([
            'name' => $request->name,
            'code' => $request->code,
            'flag' => $request->flag,
            'flag_image' => $flagImagePath,
            'is_rtl' => $request->input('is_rtl') == '1',
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        // Update config file - DISABLED to prevent corruption
        // $this->updateConfigFile();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated successfully!');
    }

    public function destroy(Language $language)
    {
        // Don't allow deletion of English (default language)
        if ($language->code === 'en') {
            return redirect()->route('admin.languages.index')
                ->with('error', 'Cannot delete the default English language!');
        }

        // Delete flag image if exists
        if ($language->flag_image && file_exists(public_path($language->flag_image))) {
            unlink(public_path($language->flag_image));
        }

        // Delete language file
        $this->deleteLanguageFile($language->code);

        // Delete translations
        $language->translations()->delete();

        // Delete language
        $language->delete();

        // Update config file - DISABLED to prevent corruption
        // $this->updateConfigFile();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language deleted successfully!');
    }

    public function translations(Language $language)
    {
        $translations = $language->translations()
            ->where('group_name', 'frontend')
            ->orderBy('key_name')
            ->get()
            ->groupBy('key_name');

        $keys = LanguageTranslation::where('group_name', 'frontend')
            ->distinct()
            ->pluck('key_name')
            ->sort()
            ->values();

        return view('admin.languages.translations', compact('language', 'translations', 'keys'));
    }

    public function updateTranslations(Request $request, Language $language)
    {
        $request->validate([
            'translations' => 'required|array',
            'translations.*' => 'required|string'
        ]);

        foreach ($request->translations as $key => $value) {
            LanguageTranslation::updateOrCreate(
                [
                    'language_id' => $language->id,
                    'group_name' => 'frontend',
                    'key_name' => $key
                ],
                ['value' => $value]
            );
        }

        // Update language file
        $this->updateLanguageFile($language->code);

        return redirect()->route('admin.languages.translations', $language)
            ->with('success', 'Translations updated successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'language_file' => 'required|file|mimes:json'
        ]);

        $file = $request->file('language_file');
        $content = json_decode(file_get_contents($file->getPathname()), true);

        if (!$content) {
            return back()->with('error', 'Invalid JSON file!');
        }

        $languageCode = $request->input('language_code');
        $language = Language::where('code', $languageCode)->first();

        if (!$language) {
            return back()->with('error', 'Language not found!');
        }

        foreach ($content as $key => $value) {
            LanguageTranslation::updateOrCreate(
                [
                    'language_id' => $language->id,
                    'group_name' => 'frontend',
                    'key_name' => $key
                ],
                ['value' => $value]
            );
        }

        // Update language file
        $this->updateLanguageFile($language->code);

        return back()->with('success', 'Translations imported successfully!');
    }

    public function export(Language $language, $group = 'frontend')
    {
        $translations = $language->translations()
            ->where('group_name', $group)
            ->pluck('value', 'key_name')
            ->toArray();

        $filename = "{$language->code}_{$group}_translations.json";
        
        return response()->json($translations)
            ->header('Content-Disposition', "attachment; filename={$filename}")
            ->header('Content-Type', 'application/json');
    }



    private function createLanguageFile($code)
    {
        $path = lang_path($code);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $frontendFile = $path . '/frontend.php';
        if (!File::exists($frontendFile)) {
            $content = "<?php\n\nreturn [\n    // Add your translations here\n];\n";
            File::put($frontendFile, $content);
        }
    }

    private function updateLanguageFile($code)
    {
        $translations = LanguageTranslation::whereHas('language', function($query) use ($code) {
            $query->where('code', $code);
        })->where('group_name', 'frontend')->get();

        $content = "<?php\n\nreturn [\n";
        foreach ($translations as $translation) {
            $value = str_replace("'", "\\'", $translation->value);
            $content .= "    '{$translation->key_name}' => '{$value}',\n";
        }
        $content .= "];\n";

        $frontendFile = lang_path($code . '/frontend.php');
        File::put($frontendFile, $content);
    }

    private function deleteLanguageFile($code)
    {
        $path = lang_path($code);
        if (File::exists($path)) {
            File::deleteDirectory($path);
        }
    }

    // DISABLED: This method was corrupting the config file
    // private function updateConfigFile()
    // {
    //     try {
    //         $languages = Language::active()->ordered()->get();
    //         $config = [];
    // 
    //         foreach ($languages as $language) {
    //         $config[$language->code] = [
    //             'name' => $language->name,
    //             'rtl' => $language->is_rtl
    //         ];
    //     }
    // 
    //         $configFile = config_path('app.php');
    //         $appConfig = file_get_contents($configFile);
    //         
    //         // Create the new available_locales content
    //         $newLocalesContent = "    'available_locales' => [\n";
    //         foreach ($config as $code => $data) {
    //         $newLocalesContent .= "        '{$code}' => ['name' => '{$data['name']}', 'rtl' => " . ($data['rtl'] ? 'true' : 'false') . "],\n";
    //         }
    //         $newLocalesContent .= "    ],";
    //         
    //         // Find and replace the available_locales section with a more specific pattern
    //         $pattern = "/('available_locales' => \[)[\s\S]*?(\],)/";
    //         $replacement = "$1\n" . trim($newLocalesContent, "\n") . "\n    $2";
    //         
    //         $appConfig = preg_replace($pattern, $replacement, $appConfig);
    //         
    //         if ($appConfig !== null) {
    //         file_put_contents($configFile, $appConfig);
    //     }
    //     } catch (\Exception $e) {
    //         // Log error but don't break the application
    //         \Log::error('Failed to update config file: ' . $e->getMessage());
    //     }
    // }


}
