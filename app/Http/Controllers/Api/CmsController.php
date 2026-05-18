<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModulesData;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    /**
     * Get all CMS pages with translations
     */
    public function index(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $type = $request->get('type');

        $query = ModulesData::where('module_id', 8) // CMS pages module
            ->where('status', 'active');

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        if ($type) {
            $query->where('extra_field_1', $type);
        }

        $pages = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $pages->getCollection()->transform(function ($page) use ($locale) {
            return [
                'id' => $page->id,
                'title' => $page->getTranslatedTitle($locale),
                'description' => $page->getTranslatedDescription($locale),
                'slug' => $page->slug,
                'image' => $page->image,
                'type' => $page->extra_field_1,
                'content' => $page->getTranslatedExtraField(2, $locale),
                'meta_title' => $page->getTranslatedMetaTitle($locale),
                'meta_description' => $page->getTranslatedMetaDescription($locale),
                'created_at' => $page->created_at,
                'updated_at' => $page->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Get CMS page by ID with translations
     */
    public function show($id, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $page = ModulesData::where('module_id', 8)
            ->where('status', 'active')
            ->findOrFail($id);

        $pageData = [
            'id' => $page->id,
            'title' => $page->getTranslatedTitle($locale),
            'description' => $page->getTranslatedDescription($locale),
            'slug' => $page->slug,
            'image' => $page->image,
            'type' => $page->extra_field_1,
            'content' => $page->getTranslatedExtraField(2, $locale),
            'additional_content' => $page->getTranslatedExtraField(3, $locale),
            'meta_title' => $page->getTranslatedMetaTitle($locale),
            'meta_description' => $page->getTranslatedMetaDescription($locale),
            'meta_keywords' => $page->getTranslatedMetaKeywords($locale),
            'created_at' => $page->created_at,
            'updated_at' => $page->updated_at,
        ];

        return response()->json([
            'success' => true,
            'data' => $pageData
        ]);
    }

    /**
     * Get CMS page by slug with translations
     */
    public function showBySlug($slug, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $page = ModulesData::where('module_id', 8)
            ->where('status', 'active')
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->show($page->id, $request);
    }

    /**
     * Get specific CMS pages by type
     */
    public function getByType($type, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $pages = ModulesData::where('module_id', 8)
            ->where('status', 'active')
            ->where('extra_field_1', $type)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($page) use ($locale) {
                return [
                    'id' => $page->id,
                    'title' => $page->getTranslatedTitle($locale),
                    'description' => $page->getTranslatedDescription($locale),
                    'slug' => $page->slug,
                    'image' => $page->image,
                    'type' => $page->extra_field_1,
                    'content' => $page->getTranslatedExtraField(2, $locale),
                    'created_at' => $page->created_at,
                    'updated_at' => $page->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Get about us page
     */
    public function getAboutUs(Request $request)
    {
        return $this->getByType('about_us', $request);
    }

    /**
     * Get terms and conditions page
     */
    public function getTermsAndConditions(Request $request)
    {
        return $this->getByType('terms_conditions', $request);
    }

    /**
     * Get privacy policy page
     */
    public function getPrivacyPolicy(Request $request)
    {
        return $this->getByType('privacy_policy', $request);
    }

    /**
     * Get FAQ page
     */
    public function getFaq(Request $request)
    {
        return $this->getByType('faq', $request);
    }

    /**
     * Get contact information page
     */
    public function getContactInfo(Request $request)
    {
        return $this->getByType('contact_info', $request);
    }

    /**
     * Get help/support page
     */
    public function getHelp(Request $request)
    {
        return $this->getByType('help', $request);
    }
}
