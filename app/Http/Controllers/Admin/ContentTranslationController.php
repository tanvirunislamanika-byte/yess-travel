<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContentTranslation;
use App\Models\ModulesData;
use App\Models\Language;
use App\Models\Modules;
use Illuminate\Support\Str;

class ContentTranslationController extends Controller
{
    /**
     * Show content translations management page
     */
    public function index(Request $request)
    {
        $moduleSlug = $request->get('module', 'hotels');
        $module = Modules::where('slug', $moduleSlug)->first();
        
        if (!$module) {
            abort(404, 'Module not found');
        }

        $items = ModulesData::where('module_id', $module->id)
            ->with('translations')
            ->paginate(20);

        $languages = Language::where('is_active', 1)->get();
        $modules = Modules::where('status', 'active')
            ->whereNotIn('id', [3, 20, 21, 22])
            ->get();
        
        return view('admin.content-translations.index', compact('items', 'languages', 'modules', 'module'));
    }

    /**
     * Show translations for a specific item
     */
    public function show($id)
    {
        $item = ModulesData::with('translations')->findOrFail($id);
        $languages = Language::where('is_active', 1)->get();
        
        // Get available fields for this item
        $availableFields = [];
        
        // Check standard fields
        $standardFields = ['title', 'description', 'meta_title', 'meta_description', 'meta_keywords'];
        foreach ($standardFields as $field) {
            if (!empty($item->$field)) {
                $availableFields[$field] = ucfirst(str_replace('_', ' ', $field)) . ' (' . Str::limit($item->$field, 30) . ')';
            }
        }
        
        // Check extra fields 1-50
        for ($i = 1; $i <= 50; $i++) {
            $fieldName = 'extra_field_' . $i;
            if (!empty($item->$fieldName)) {
                $availableFields[$fieldName] = 'Extra Field ' . $i . ' (' . Str::limit($item->$fieldName, 30) . ')';
            }
        }
        
        return view('admin.content-translations.show', compact('item', 'languages', 'availableFields'));
    }

    /**
     * Store content translation
     */
    public function store(Request $request)
    {
        $request->validate([
            'translatable_id' => 'required|exists:modules_data,id',
            'locale' => 'required|string|max:5',
            'field_name' => 'required|string|max:255',
            'field_value' => 'required|string'
        ]);

        $item = ModulesData::findOrFail($request->translatable_id);
        $item->setTranslation($request->field_name, $request->locale, $request->field_value);

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'Translation has been saved successfully!');
        
        return redirect()->back();
    }

    /**
     * Update content translation
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:content_translations,id',
            'field_value' => 'required|string'
        ]);

        $translation = ContentTranslation::findOrFail($request->id);
        $translation->update(['field_value' => $request->field_value]);

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'Translation has been updated successfully!');
        
        return redirect()->back();
    }

    /**
     * Delete content translation
     */
    public function destroy(Request $request, $id)
    {
        $translation = ContentTranslation::findOrFail($id);
        $translation->delete();

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'Translation has been deleted successfully!');
        
        return redirect()->back();
    }

    /**
     * Bulk translate content
     */
    public function bulkTranslate(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'target_locale' => 'required|string|max:5',
            'fields' => 'required|array'
        ]);

        $module = Modules::findOrFail($request->module_id);
        $items = ModulesData::where('module_id', $module->id)->get();
        
        $translatedCount = 0;
        
        foreach ($items as $item) {
            foreach ($request->fields as $field) {
                if ($field === 'extra_fields') {
                    // Handle all extra fields (1-50)
                    for ($i = 1; $i <= 50; $i++) {
                        $extraField = "extra_field_{$i}";
                        if (!empty($item->$extraField)) {
                            $item->setTranslation($extraField, $request->target_locale, '');
                            $translatedCount++;
                        }
                    }
                } else {
                    // Handle regular fields
                    if (!empty($item->$field)) {
                        $item->setTranslation($field, $request->target_locale, '');
                        $translatedCount++;
                    }
                }
            }
        }

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', "Created {$translatedCount} translation placeholders. Please fill them manually.");
        
        return redirect()->back();
    }
}
