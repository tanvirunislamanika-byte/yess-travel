<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModulesData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Get all services with translations
     */
    public function index(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $category = $request->get('category');

        $query = ModulesData::where('module_id', 6) // Services module
            ->where('status', 'active');

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        if ($category) {
            $query->where('extra_field_1', $category);
        }

        $services = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $services->getCollection()->transform(function ($service) use ($locale) {
            return [
                'id' => $service->id,
                'title' => $service->getTranslatedTitle($locale),
                'description' => $service->getTranslatedDescription($locale),
                'slug' => $service->slug,
                'image' => $service->image,
                'category' => $service->extra_field_1,
                'price' => $service->extra_field_2,
                'duration' => $service->extra_field_3,
                'location' => $service->extra_field_4,
                'features' => $service->getTranslatedExtraField(5, $locale),
                'included' => $service->getTranslatedExtraField(6, $locale),
                'excluded' => $service->getTranslatedExtraField(7, $locale),
                'requirements' => $service->getTranslatedExtraField(8, $locale),
                'cancellation_policy' => $service->getTranslatedExtraField(9, $locale),
                'meta_title' => $service->getTranslatedMetaTitle($locale),
                'meta_description' => $service->getTranslatedMetaDescription($locale),
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Get service by ID with translations
     */
    public function show($id, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $service = ModulesData::where('module_id', 6)
            ->where('status', 'active')
            ->findOrFail($id);

        $serviceData = [
            'id' => $service->id,
            'title' => $service->getTranslatedTitle($locale),
            'description' => $service->getTranslatedDescription($locale),
            'slug' => $service->slug,
            'image' => $service->image,
            'category' => $service->extra_field_1,
            'price' => $service->extra_field_2,
            'duration' => $service->extra_field_3,
            'location' => $service->extra_field_4,
            'features' => $service->getTranslatedExtraField(5, $locale),
            'included' => $service->getTranslatedExtraField(6, $locale),
            'excluded' => $service->getTranslatedExtraField(7, $locale),
            'requirements' => $service->getTranslatedExtraField(8, $locale),
            'cancellation_policy' => $service->getTranslatedExtraField(9, $locale),
            'booking_terms' => $service->getTranslatedExtraField(10, $locale),
            'additional_info' => $service->getTranslatedExtraField(11, $locale),
            'meta_title' => $service->getTranslatedMetaTitle($locale),
            'meta_description' => $service->getTranslatedMetaDescription($locale),
            'meta_keywords' => $service->getTranslatedMetaKeywords($locale),
            'created_at' => $service->created_at,
            'updated_at' => $service->updated_at,
        ];

        return response()->json([
            'success' => true,
            'data' => $serviceData
        ]);
    }

    /**
     * Get service by slug with translations
     */
    public function showBySlug($slug, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $service = ModulesData::where('module_id', 6)
            ->where('status', 'active')
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->show($service->id, $request);
    }

    /**
     * Get featured services with translations
     */
    public function getFeatured(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $limit = $request->get('limit', 6);

        $services = ModulesData::where('module_id', 6)
            ->where('status', 'active')
            ->where('featured', 1)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($service) use ($locale) {
                return [
                    'id' => $service->id,
                    'title' => $service->getTranslatedTitle($locale),
                    'description' => $service->getTranslatedDescription($locale),
                    'slug' => $service->slug,
                    'image' => $service->image,
                    'category' => $service->extra_field_1,
                    'price' => $service->extra_field_2,
                    'duration' => $service->extra_field_3,
                    'location' => $service->extra_field_4,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Get service categories with translations
     */
    public function getCategories(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $categories = ModulesData::where('module_id', 7) // Service categories module
            ->where('status', 'active')
            ->get()
            ->map(function ($category) use ($locale) {
                return [
                    'id' => $category->id,
                    'name' => $category->getTranslatedTitle($locale),
                    'description' => $category->getTranslatedDescription($locale),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Search services with translations
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'sometimes|string',
            'location' => 'sometimes|string',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0',
            'duration' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $locale = $request->get('locale', app()->getLocale());
        $perPage = $request->get('per_page', 15);

        $query = ModulesData::where('module_id', 6)
            ->where('status', 'active');

        // Apply search filters
        if ($request->has('category')) {
            $query->where('extra_field_1', $request->category);
        }

        if ($request->has('location')) {
            $query->where('extra_field_4', 'LIKE', '%' . $request->location . '%');
        }

        if ($request->has('min_price')) {
            $query->where('extra_field_2', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('extra_field_2', '<=', $request->max_price);
        }

        if ($request->has('duration')) {
            $query->where('extra_field_3', 'LIKE', '%' . $request->duration . '%');
        }

        $services = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $services->getCollection()->transform(function ($service) use ($locale) {
            return [
                'id' => $service->id,
                'title' => $service->getTranslatedTitle($locale),
                'description' => $service->getTranslatedDescription($locale),
                'slug' => $service->slug,
                'image' => $service->image,
                'category' => $service->extra_field_1,
                'price' => $service->extra_field_2,
                'duration' => $service->extra_field_3,
                'location' => $service->extra_field_4,
                'features' => $service->getTranslatedExtraField(5, $locale),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $services,
            'filters' => $request->only(['category', 'location', 'min_price', 'max_price', 'duration'])
        ]);
    }
}
