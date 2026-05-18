<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\ModulesData;

class ReviewController extends Controller
{
    /**
     * Get all reviews
     */
     
     
     public function showHotelReviews($id)
{
    try {
        $hotel = ModulesData::findOrFail($id);

        $reviews = $hotel->reviews()->with(['user'])->latest()->paginate(10);

        $averageRating = $hotel->reviews()->avg('rating');
        $totalReviews = $hotel->reviews()->count();

        // Map reviews to return clean data
        $reviewData = $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'user_name' => $review->user->name ?? 'Anonymous',
                'user_avatar' => $review->user->avatar_url ?? null, // Update field name if needed
                'review' => $review->review,
                'reason' => $review->reason,
                'rating' => $review->rating,
                'created_at' => $review->created_at->toDateString(),
                'reply' => $review->reply, // if your DB has this
            ];
        });

        return response()->json([
            'success' => true,
            'reviews' => $reviewData,
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching hotel reviews',
            'error' => $e->getMessage(),
        ], 500);
    }
}

     
     
    public function index(Request $request)
    {
        try {
            $reviews = Review::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);

        } catch (\Exception $e) {
            Log::error('Get reviews error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific review
     */
    public function show($id)
    {
        try {
            $review = Review::with('user')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $review
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store new review
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'type' => 'required|in:hotel,flight,service',
            'reference_id' => 'required|integer',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            
            // Check if user already reviewed this item
            $existingReview = Review::where('user_id', $user->id)
                ->where('type', $request->type)
                ->where('reference_id', $request->reference_id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this item'
                ], 400);
            }

            $reviewData = [
                'user_id' => $user->id,
                'title' => $request->title,
                'content' => $request->content,
                'rating' => $request->rating,
                'type' => $request->type,
                'reference_id' => $request->reference_id,
                'status' => 'approved'
            ];

            // Handle image uploads if provided
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews', 'public');
                    $images[] = $path;
                }
                $reviewData['images'] = json_encode($images);
            }

            $review = Review::create($reviewData);

            Log::info('Review created:', [
                'review_id' => $review->id,
                'user_id' => $user->id,
                'type' => $request->type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review created successfully',
                'data' => $review->load('user')
            ], 201);

        } catch (\Exception $e) {
            Log::error('Create review error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update review
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|min:10',
            'rating' => 'sometimes|integer|min:1|max:5',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            
            $review = Review::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found or you are not authorized to edit it'
                ], 404);
            }

            $updateData = $request->only(['title', 'content', 'rating']);

            // Handle image uploads if provided
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews', 'public');
                    $images[] = $path;
                }
                $updateData['images'] = json_encode($images);
            }

            $review->update($updateData);

            Log::info('Review updated:', [
                'review_id' => $review->id,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review->load('user')
            ]);

        } catch (\Exception $e) {
            Log::error('Update review error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete review
     */
    public function destroy($id)
    {
        try {
            $user = request()->user();
            
            $review = Review::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found or you are not authorized to delete it'
                ], 404);
            }

            $review->delete();

            Log::info('Review deleted:', [
                'review_id' => $id,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Delete review error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's reviews
     */
    public function userReviews(Request $request)
    {
        try {
            $user = $request->user();
            
            $reviews = Review::with('hotel') // eager load hotel
    ->where('user_id', $user->id)
    ->orderBy('created_at', 'desc')
    ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);

        } catch (\Exception $e) {
            Log::error('Get user reviews error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews by type and reference
     */
    public function getByType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:hotel,flight,service',
            'reference_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $reviews = Review::with('user')
                ->where('type', $request->type)
                ->where('reference_id', $request->reference_id)
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $averageRating = Review::where('type', $request->type)
                ->where('reference_id', $request->reference_id)
                ->where('status', 'approved')
                ->avg('rating');

            return response()->json([
                'success' => true,
                'data' => [
                    'reviews' => $reviews,
                    'average_rating' => round($averageRating, 1),
                    'total_reviews' => $reviews->total()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get reviews by type error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 