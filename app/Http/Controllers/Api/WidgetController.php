<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Widgets;
use App\Models\WidgetsData;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    /**
     * Get all widgets with translations
     */
    public function index(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $type = $request->get('type');

        $query = Widgets::where('status', 'active');

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        $widgets = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $widgets->getCollection()->transform(function ($widget) use ($locale) {
            return [
                'id' => $widget->id,
                'title' => $widget->getTranslatedTitle($locale),
                'description' => $widget->getTranslatedDescription($locale),
                'type' => $widget->type,
                'position' => $widget->position,
                'order' => $widget->order,
                'status' => $widget->status,
                'created_at' => $widget->created_at,
                'updated_at' => $widget->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $widgets
        ]);
    }

    /**
     * Get widget by ID with translations
     */
    public function show($id, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $widget = Widgets::findOrFail($id);

        $widgetData = [
            'id' => $widget->id,
            'title' => $widget->getTranslatedTitle($locale),
            'description' => $widget->getTranslatedDescription($locale),
            'type' => $widget->type,
            'position' => $widget->position,
            'order' => $widget->order,
            'status' => $widget->status,
            'created_at' => $widget->created_at,
            'updated_at' => $widget->updated_at,
        ];

        return response()->json([
            'success' => true,
            'data' => $widgetData
        ]);
    }

    /**
     * Get widgets by type with translations
     */
    public function getByType($type, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $widgets = Widgets::where('type', $type)
            ->where('status', 'active')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($widget) use ($locale) {
                return [
                    'id' => $widget->id,
                    'title' => $widget->getTranslatedTitle($locale),
                    'description' => $widget->getTranslatedDescription($locale),
                    'type' => $widget->type,
                    'position' => $widget->position,
                    'order' => $widget->order,
                    'status' => $widget->status,
                    'created_at' => $widget->created_at,
                    'updated_at' => $widget->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $widgets
        ]);
    }

    /**
     * Get widgets by position with translations
     */
    public function getByPosition($position, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $widgets = Widgets::where('position', $position)
            ->where('status', 'active')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($widget) use ($locale) {
                return [
                    'id' => $widget->id,
                    'title' => $widget->getTranslatedTitle($locale),
                    'description' => $widget->getTranslatedDescription($locale),
                    'type' => $widget->type,
                    'position' => $widget->position,
                    'order' => $widget->order,
                    'status' => $widget->status,
                    'created_at' => $widget->created_at,
                    'updated_at' => $widget->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $widgets
        ]);
    }

    /**
     * Get widget data with translations
     */
    public function getWidgetData($widgetId, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $widget = Widgets::findOrFail($widgetId);
        $widgetData = WidgetsData::where('widget_id', $widgetId)
            ->where('status', 'active')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($data) use ($locale) {
                return [
                    'id' => $data->id,
                    'title' => $data->getTranslatedTitle($locale),
                    'description' => $data->getTranslatedDescription($locale),
                    'image' => $data->image,
                    'link' => $data->link,
                    'order' => $data->order,
                    'status' => $data->status,
                    'extra_fields' => $this->getTranslatedExtraFields($data, $locale),
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'widget' => [
                    'id' => $widget->id,
                    'title' => $widget->getTranslatedTitle($locale),
                    'description' => $widget->getTranslatedDescription($locale),
                    'type' => $widget->type,
                    'position' => $widget->position,
                ],
                'widget_data' => $widgetData
            ]
        ]);
    }

    /**
     * Get home page widgets with translations
     */
    public function getHomePageWidgets(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $widgets = Widgets::where('position', 'home')
            ->where('status', 'active')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($widget) use ($locale) {
                $widgetData = WidgetsData::where('widget_id', $widget->id)
                    ->where('status', 'active')
                    ->orderBy('order', 'asc')
                    ->get()
                    ->map(function ($data) use ($locale) {
                        return [
                            'id' => $data->id,
                            'title' => $data->getTranslatedTitle($locale),
                            'description' => $data->getTranslatedDescription($locale),
                            'image' => $data->image,
                            'link' => $data->link,
                            'order' => $data->order,
                            'extra_fields' => $this->getTranslatedExtraFields($data, $locale),
                        ];
                    });

                return [
                    'id' => $widget->id,
                    'title' => $widget->getTranslatedTitle($locale),
                    'description' => $widget->getTranslatedDescription($locale),
                    'type' => $widget->type,
                    'position' => $widget->position,
                    'order' => $widget->order,
                    'data' => $widgetData
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $widgets
        ]);
    }

    /**
     * Get sidebar widgets with translations
     */
    public function getSidebarWidgets(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $widgets = Widgets::where('position', 'sidebar')
            ->where('status', 'active')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($widget) use ($locale) {
                return [
                    'id' => $widget->id,
                    'title' => $widget->getTranslatedTitle($locale),
                    'description' => $widget->getTranslatedDescription($locale),
                    'type' => $widget->type,
                    'position' => $widget->position,
                    'order' => $widget->order,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $widgets
        ]);
    }

    /**
     * Get footer widgets with translations
     */
    public function getFooterWidgets(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $widgets = Widgets::where('position', 'footer')
            ->where('status', 'active')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($widget) use ($locale) {
                return [
                    'id' => $widget->id,
                    'title' => $widget->getTranslatedTitle($locale),
                    'description' => $widget->getTranslatedDescription($locale),
                    'type' => $widget->type,
                    'position' => $widget->position,
                    'order' => $widget->order,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $widgets
        ]);
    }

    /**
     * Helper method to get translated extra fields
     */
    private function getTranslatedExtraFields($data, $locale)
    {
        $extraFields = [];
        for ($i = 1; $i <= 50; $i++) {
            $fieldName = 'extra_field_' . $i;
            if (!empty($data->$fieldName)) {
                $extraFields[$fieldName] = $data->getTranslatedExtraField($i, $locale);
            }
        }
        return $extraFields;
    }
}
