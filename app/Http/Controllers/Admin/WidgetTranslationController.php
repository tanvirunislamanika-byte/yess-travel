<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Widgets;
use App\Models\WidgetsData;
use App\Models\WidgetTranslation;
use App\Models\WidgetDataTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WidgetTranslationController extends Controller
{
    /**
     * Display a listing of widgets for translation.
     */
    public function index()
    {
        $widgets = Widgets::with('translations')->paginate(15);
        $languages = Language::where('is_active', true)->get();
        
        return view('admin.widgets.translations', compact('widgets', 'languages'));
    }

    /**
     * Show the form for managing translations of a specific widget.
     */
    public function show($id)
    {
        $widget = Widgets::with(['translations', 'widget_data.translations'])->findOrFail($id);
        $languages = Language::where('is_active', true)->get();
        
        // Get available fields for this widget data
        $availableFields = [];
        if ($widget->widget_data) {
            $widgetData = $widget->widget_data;
            
            // Check description
            if (!empty($widgetData->description)) {
                $availableFields['description'] = 'Description (' . Str::limit($widgetData->description, 30) . ')';
            }
            
            // Check extra fields 1-20
            for ($i = 1; $i <= 20; $i++) {
                $fieldName = 'extra_field_' . $i;
                if (!empty($widgetData->$fieldName)) {
                    $availableFields[$fieldName] = 'Extra Field ' . $i . ' (' . Str::limit($widgetData->$fieldName, 30) . ')';
                }
            }
        }
        
        return view('admin.widgets.translation-detail', compact('widget', 'languages', 'availableFields'));
    }

    /**
     * Store a new widget translation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'widget_id' => 'required|exists:widgets,id',
            'locale' => 'required|string|max:5',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        WidgetTranslation::updateOrCreate(
            [
                'widget_id' => $request->widget_id,
                'locale' => $request->locale
            ],
            [
                'title' => $request->title,
                'description' => $request->description
            ]
        );

        return redirect()->back()->with('success', 'Widget translation saved successfully!');
    }

    /**
     * Update an existing widget translation.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $translation = WidgetTranslation::findOrFail($id);
        $translation->update($request->only(['title', 'description']));

        return redirect()->back()->with('success', 'Widget translation updated successfully!');
    }

    /**
     * Delete a widget translation.
     */
    public function destroy($id)
    {
        $translation = WidgetTranslation::findOrFail($id);
        $translation->delete();

        return redirect()->back()->with('success', 'Widget translation deleted successfully!');
    }

    /**
     * Store a new widget data translation.
     */
    public function storeDataTranslation(Request $request)
    {
        $request->validate([
            'widget_data_id' => 'required|exists:widgets_data,id',
            'locale' => 'required|string|max:5',
            'field_name' => 'required|string',
            'field_value' => 'required|string'
        ]);

        WidgetDataTranslation::updateOrCreate(
            [
                'widget_data_id' => $request->widget_data_id,
                'locale' => $request->locale,
                'field_name' => $request->field_name
            ],
            [
                'field_value' => $request->field_value
            ]
        );

        return redirect()->back()->with('success', 'Widget data translation saved successfully!');
    }

    /**
     * Update an existing widget data translation.
     */
    public function updateDataTranslation(Request $request, $id)
    {
        $request->validate([
            'field_value' => 'required|string'
        ]);

        $translation = WidgetDataTranslation::findOrFail($id);
        $translation->update($request->only(['field_value']));

        return redirect()->back()->with('success', 'Widget data translation updated successfully!');
    }

    /**
     * Delete a widget data translation.
     */
    public function destroyDataTranslation($id)
    {
        $translation = WidgetDataTranslation::findOrFail($id);
        $translation->delete();

        return redirect()->back()->with('success', 'Widget data translation deleted successfully!');
    }

    /**
     * Bulk translate widgets.
     */
    public function bulkTranslate(Request $request)
    {
        $request->validate([
            'widget_ids' => 'required|array',
            'widget_ids.*' => 'exists:widgets,id',
            'locale' => 'required|string|max:5',
            'fields' => 'required|array'
        ]);

        $widgets = Widgets::whereIn('id', $request->widget_ids)->get();
        $locale = $request->locale;

        foreach ($widgets as $widget) {
            if (in_array('title', $request->fields)) {
                $widget->setTranslation($locale, 'title', $widget->title);
            }
            if (in_array('description', $request->fields)) {
                $widget->setTranslation($locale, 'description', $widget->description);
            }
        }

        return redirect()->back()->with('success', 'Bulk translation completed successfully!');
    }
}