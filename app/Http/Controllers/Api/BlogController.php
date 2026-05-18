<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModulesData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Get all published blogs
     */
    public function index(Request $request)
    {
        try {
            $locale = $request->get('locale', app()->getLocale());
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search');
            $category = $request->get('category');

            $query = ModulesData::where('module_id', 9) // Blogs module
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

            $blogs = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $blogs->getCollection()->transform(function ($blog) use ($locale) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->getTranslatedTitle($locale),
                    'description' => $blog->getTranslatedDescription($locale),
                    'slug' => $blog->slug,
                    'image' => $blog->image,
                    'category' => $blog->extra_field_1,
                    'author' => $blog->extra_field_2,
                    'content' => $blog->getTranslatedExtraField(3, $locale),
                    'excerpt' => $blog->getTranslatedExtraField(4, $locale),
                    'tags' => $blog->getTranslatedExtraField(5, $locale),
                    'meta_title' => $blog->getTranslatedMetaTitle($locale),
                    'meta_description' => $blog->getTranslatedMetaDescription($locale),
                    'created_at' => $blog->created_at,
                    'updated_at' => $blog->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $blogs
            ]);

        } catch (\Exception $e) {
            Log::error('Get blogs error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch blogs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific blog
     */
    public function show($id, Request $request)
    {
        try {
            $locale = $request->get('locale', app()->getLocale());
            
            $blog = ModulesData::where(function($q) use ($id) {
                $q->where('id', $id)->orWhere('slug', $id);
            })
            ->where('module_id', 9)
            ->where('status', 'active')
            ->first();

            if (!$blog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog not found'
                ], 404);
            }

            $blogData = [
                'id' => $blog->id,
                'title' => $blog->getTranslatedTitle($locale),
                'description' => $blog->getTranslatedDescription($locale),
                'slug' => $blog->slug,
                'image' => $blog->image,
                'category' => $blog->extra_field_1,
                'author' => $blog->extra_field_2,
                'content' => $blog->getTranslatedExtraField(3, $locale),
                'excerpt' => $blog->getTranslatedExtraField(4, $locale),
                'tags' => $blog->getTranslatedExtraField(5, $locale),
                'meta_title' => $blog->getTranslatedMetaTitle($locale),
                'meta_description' => $blog->getTranslatedMetaDescription($locale),
                'meta_keywords' => $blog->getTranslatedMetaKeywords($locale),
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
            ];

            return response()->json([
                'success' => true,
                'data' => $blogData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new blog (admin only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'excerpt' => 'sometimes|string|max:500',
            'featured_image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'tags' => 'sometimes|array',
            'tags.*' => 'string',
            'status' => 'sometimes|in:draft,published'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $blogData = [
                'module_id' => 3, // Blog module
                'title' => $request->title,
                'content' => $request->content,
                'excerpt' => $request->excerpt ?? substr($request->content, 0, 200),
                'status' => $request->status ?? 'draft',
                'slug' => \Str::slug($request->title)
            ];

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('blogs', 'public');
                $blogData['featured_image'] = $path;
            }

            // Handle tags
            if ($request->has('tags')) {
                $blogData['tags'] = json_encode($request->tags);
            }

            $blog = ModulesData::create($blogData);

            Log::info('Blog created:', [
                'blog_id' => $blog->id,
                'title' => $request->title
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Blog created successfully',
                'data' => $blog
            ], 201);

        } catch (\Exception $e) {
            Log::error('Create blog error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update blog (admin only)
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|min:10',
            'excerpt' => 'sometimes|string|max:500',
            'featured_image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'tags' => 'sometimes|array',
            'tags.*' => 'string',
            'status' => 'sometimes|in:draft,published'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $blog = ModulesData::where('id', $id)
                ->where('module_id', 3)
                ->first();

            if (!$blog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog not found'
                ], 404);
            }

            $updateData = $request->only(['title', 'content', 'excerpt', 'status']);

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                // Delete old image if exists
                if ($blog->featured_image) {
                    Storage::disk('public')->delete($blog->featured_image);
                }
                
                $path = $request->file('featured_image')->store('blogs', 'public');
                $updateData['featured_image'] = $path;
            }

            // Handle tags
            if ($request->has('tags')) {
                $updateData['tags'] = json_encode($request->tags);
            }

            // Update slug if title changed
            if ($request->has('title')) {
                $updateData['slug'] = \Str::slug($request->title);
            }

            $blog->update($updateData);

            Log::info('Blog updated:', [
                'blog_id' => $blog->id,
                'title' => $blog->title
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Blog updated successfully',
                'data' => $blog
            ]);

        } catch (\Exception $e) {
            Log::error('Update blog error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete blog (admin only)
     */
    public function destroy($id)
    {
        try {
            $blog = ModulesData::where('id', $id)
                ->where('module_id', 3)
                ->first();

            if (!$blog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog not found'
                ], 404);
            }

            // Delete featured image if exists
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            $blog->delete();

            Log::info('Blog deleted:', [
                'blog_id' => $id,
                'title' => $blog->title
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Blog deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Delete blog error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured blogs
     */
    public function featured()
    {
        try {
            $featuredBlogs = ModulesData::where('module_id', 3)
                ->where('status', 'published')
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $featuredBlogs
            ]);

        } catch (\Exception $e) {
            Log::error('Get featured blogs error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured blogs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search blogs
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = $request->input('query');

            $blogs = ModulesData::where('module_id', 3)
                ->where('status', 'published')
                ->where(function($q) use ($query) {
                    $q->where('title', 'LIKE', '%' . $query . '%')
                      ->orWhere('content', 'LIKE', '%' . $query . '%')
                      ->orWhere('excerpt', 'LIKE', '%' . $query . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $blogs,
                'meta' => [
                    'search_query' => $query,
                    'total_results' => $blogs->total()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Search blogs error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to search blogs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 