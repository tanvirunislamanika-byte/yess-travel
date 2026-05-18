<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotels;
use App\Models\ModulesData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    public function getHotelTypes(Request $request)
    {
        try {
            $locale = $request->get('locale', app()->getLocale());
            
            // Assuming module_id 2 contains hotel types
            $types = ModulesData::where('module_id', 2)
                ->where('status', 'active')
                ->get()
                ->map(function ($type) use ($locale) {
                    return [
                        'id' => $type->id,
                        'name' => $type->getTranslatedTitle($locale),
                        'description' => $type->getTranslatedDescription($locale),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $types
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load hotel types.'
            ], 500);
        }
    }

    public function getHotelServices(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $services = ModulesData::where('module_id', 28)
            ->where('status', 'active')
            ->get()
            ->map(function ($service) use ($locale) {
                return [
                    'id' => $service->id,
                    'name' => $service->getTranslatedTitle($locale),
                    'description' => $service->getTranslatedDescription($locale),
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    public function getHotelCuisines(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $cuisines = ModulesData::where('module_id', 29)
            ->where('status', 'active')
            ->get()
            ->map(function ($cuisine) use ($locale) {
                return [
                    'id' => $cuisine->id,
                    'name' => $cuisine->getTranslatedTitle($locale),
                    'description' => $cuisine->getTranslatedDescription($locale),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $cuisines
        ]);
    }

    public function getHotelLocations(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $locations = ModulesData::where('module_id', 19)
            ->where('status', 'active')
            ->get()
            ->map(function ($location) use ($locale) {
                return [
                    'id' => $location->id,
                    'name' => $location->getTranslatedTitle($locale),
                    'description' => $location->getTranslatedDescription($locale),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    /**
     * Search hotels
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required|string|min:2',
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'sometimes|integer|min:0|max:10',
            'rooms' => 'sometimes|integer|min:1|max:10',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0',
            'stars' => 'sometimes|integer|min:1|max:5',
            'amenities' => 'sometimes|array',
            'amenities.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $location = $request->input('location');
            $checkIn = $request->input('check_in');
            $checkOut = $request->input('check_out');
            $adults = $request->input('adults');
            $children = $request->input('children', 0);
            $rooms = $request->input('rooms', 1);

            Log::info('Hotel search initiated:', [
                'location' => $location,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'adults' => $adults,
                'children' => $children,
                'rooms' => $rooms
            ]);

            // Search hotels based on location
            $hotels = ModulesData::where('module_id', 2) // Assuming module_id 2 is for hotels
                ->where('title', 'LIKE', '%' . $location . '%')
                ->orWhere('description', 'LIKE', '%' . $location . '%')
                ->paginate(15);

            // For now, returning mock data since we need to integrate with a hotel API
            $mockHotels = [
                [
                    'id' => 1,
                    'name' => 'Grand Hotel',
                    'location' => $location,
                    'rating' => 4.5,
                    'stars' => 5,
                    'price_per_night' => 150,
                    'currency' => 'USD',
                    'amenities' => ['WiFi', 'Pool', 'Gym', 'Restaurant'],
                    'image' => 'https://example.com/hotel1.jpg',
                    'description' => 'Luxury hotel in the heart of the city'
                ],
                [
                    'id' => 2,
                    'name' => 'Business Inn',
                    'location' => $location,
                    'rating' => 4.2,
                    'stars' => 4,
                    'price_per_night' => 120,
                    'currency' => 'USD',
                    'amenities' => ['WiFi', 'Business Center', 'Restaurant'],
                    'image' => 'https://example.com/hotel2.jpg',
                    'description' => 'Perfect for business travelers'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'hotels' => $mockHotels,
                    'search_params' => [
                        'location' => $location,
                        'check_in' => $checkIn,
                        'check_out' => $checkOut,
                        'adults' => $adults,
                        'children' => $children,
                        'rooms' => $rooms
                    ]
                ],
                'meta' => [
                    'total_results' => count($mockHotels),
                    'search_location' => $location
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Hotel search error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to search hotels',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get hotel details (API version, matches web detail logic)
     */
    public function show($id, Request $request)
    {
        try {
            $locale = $request->get('locale', app()->getLocale());
            
            $hotel = \App\Models\ModulesData::where(function($q) use ($id) {
                $q->where('id', $id)->orWhere('slug', $id);
            })
            ->where('module_id', 1)
            ->where('status', 'active')
            ->first();

            if (!$hotel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hotel not found'
                ], 404);
            }

            // Images
            $images = $hotel->images ? array_map(function($img) {
                return asset('images/' . $img);
            }, array_filter(explode(',', $hotel->images))) : [];

            // Facilities (extra_field_3 to extra_field_17)
            $facilities = [];
            $facilityMap = [
                3 => 'city_view',
                4 => 'master_room',
                5 => 'bar',
                6 => 'free_wifi',
                7 => 'private_bathroom',
                8 => 'air_conditioning',
                9 => 'refrigerator',
                10 => 'telephone',
                11 => 'adults_per_room',
                12 => 'gym',
                13 => 'no_smoking',
                14 => 'room_services',
                15 => 'pick_and_drop',
                16 => 'swimming_pool',
                17 => 'front_desk_24h',
            ];
            foreach ($facilityMap as $key => $label) {
                $val = $hotel->{'extra_field_' . $key} ?? null;
                if ($val && strtolower($val) === '1') {
                    $facilities[] = $label;
                }
            }

            // Services
            $service_ids = $hotel->services ? json_decode($hotel->services, true) : [];
            $services = count($service_ids) > 0 ? \App\Models\ModulesData::whereIn('id', $service_ids)->pluck('title')->toArray() : [];

            // Cuisines
            $cusine_ids = $hotel->cusines ? json_decode($hotel->cusines, true) : [];
            $cusines = count($cusine_ids) > 0 ? \App\Models\ModulesData::whereIn('id', $cusine_ids)->pluck('title')->toArray() : [];

            // Rules
            $rules = [
                'policy' => $hotel->extra_field_21,
                'check_out_time' => $hotel->extra_field_22,
            ];

            // Reviews
            $reviews = $hotel->reviews()->with('user:id,name')->latest()->take(10)->get()->map(function($review) {
                return [
                    'id' => $review->id,
                    'user' => $review->user ? $review->user->name : null,
                    'rating' => $review->rating,
                    'reason' => $review->reason,
                    'review' => $review->review,
                    'created_at' => $review->created_at,
                ];
            });
            $average_rating = $hotel->reviews()->avg('rating');
            $total_reviews = $hotel->reviews()->count();

            // Recent hotels
            $recent_data = \App\Models\ModulesData::where('module_id', 1)
                ->where('status', 'active')
                ->orderBy('id', 'desc')
                ->take(3)
                ->get()
                ->map(function($h) {
                    return [
                        'id' => $h->id,
                        'title' => $h->title,
                        'image' => $h->image ? asset('images/' . $h->image) : null,
                        'slug' => $h->slug,
                    ];
                });
                
                $typeOptions = \App\Models\ModulesData::where('module_id', 2)
    ->pluck('title', 'id') // id => title
    ->toArray();
                
                // Get the type value from the hotel
                $typeValue = $hotel->extra_field_2;

                // Get the type text (label)
                $typeText = $typeOptions[$typeValue] ?? $typeValue;
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $hotel->id,
                    'title' => $hotel->getTranslatedTitle($locale),
                    'slug' => $hotel->slug,
                    'description' => $hotel->getTranslatedDescription($locale),
                    'location' => $hotel->getTranslatedExtraField(18, $locale),
                    'price' => $hotel->extra_field_1,
                    'currency' => 'USD',
                    'type' => $hotel->extra_field_2,
                    'type_text' => $typeText,
                    'stars' => $hotel->extra_field_23,
                    'adults_per_room' => $hotel->extra_field_11,
                    'images' => $images,
                    'facilities' => $facilities,
                    'services' => $services,
                    'cusines' => $cusines,
                    'rules' => $rules,
                    'average_rating' => number_format($average_rating, 1),
                    'total_reviews' => $total_reviews,
                    'reviews' => $reviews,
                    'recent_hotels' => $recent_data,
                    'created_at' => $hotel->created_at,
                    'updated_at' => $hotel->updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hotel not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get popular destinations (module_id 19, as in welcome blade)
     */
    public function popularDestinations()
    {
        try {
            $destinations = \App\Models\ModulesData::where('module_id', 19)
                ->where('status', 'active')
                ->get()
                ->map(function($location) {
                    $hotel_count = \App\Models\ModulesData::where('module_id', 1)
                        ->where('status', 'active')
                        ->where('extra_field_24', $location->id)
                        ->count();
                    return [
                        'id' => $location->id,
                        'title' => $location->title,
                        'image' => $location->image ? asset('images/' . $location->image) : null,
                        'hotel_count' => $hotel_count,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $destinations
            ]);
        } catch (\Exception $e) {
            \Log::error('Popular destinations error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch popular destinations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get hotel amenities
     */
    public function amenities()
    {
        try {
            $amenities = [
                'WiFi',
                'Pool',
                'Gym',
                'Restaurant',
                'Spa',
                'Business Center',
                'Parking',
                'Air Conditioning',
                'Room Service',
                'Concierge'
            ];

            return response()->json([
                'success' => true,
                'data' => $amenities
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch amenities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured hotels (latest hotels from welcome page)
     */
    public function getFeaturedHotels()
    {
        try {
            // Get featured hotels from module(1) - same as welcome.blade.php
            $featuredHotels = ModulesData::where('module_id', 1)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get()
                ->map(function ($hotel) {
                    // Calculate average rating
                    $averageRating = $hotel->reviews()->avg('rating');
                    $averageRating = number_format($averageRating, 1);

                    return [
                        'id' => $hotel->id,
                        'title' => $hotel->title,
                        'slug' => $hotel->slug,
                        'image' => $hotel->image ? asset('images/' . $hotel->image) : null,
                        'location' => $hotel->extra_field_18,
                        'price' => $hotel->extra_field_1,
                        'currency' => 'USD',
                        'type' => $hotel->extra_field_2,
                        'stars' => $hotel->extra_field_23,
                        'people_per_room' => $hotel->extra_field_11,
                        'average_rating' => $averageRating,
                        'description' => $hotel->description,
                        'created_at' => $hotel->created_at,
                        'updated_at' => $hotel->updated_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $featuredHotels
            ]);

        } catch (\Exception $e) {
            Log::error('Featured hotels error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured hotels',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Paginated hotel listing (API version of web index)
     */
    public function indexApi(Request $request)
    {
        $data = \App\Models\ModulesData::where('module_id', 1)->where('status', 'active');

        if ($request->has('keyword')) {
            $data->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('extra_field_18', 'like', '%' . $request->keyword . '%');
            });
        }
        if ($request->has('type')) {
            $data->where('extra_field_2', $request->type);
        }
        if ($request->has('searchlocation')) {
            $data->where('extra_field_18', $request->searchlocation);
        }
        if ($request->has('services') && is_array($request->services)) {
            foreach ($request->services as $service) {
                $data->whereJsonContains('services', $service);
            }
        }
        if ($request->has('cusine') && is_array($request->cusine)) {
            foreach ($request->cusine as $cuisine) {
                $data->whereJsonContains('cusine', $cuisine);
            }
        }
        if ($request->has('location')) {
            $data->where('extra_field_21', $request->location);
        }
        if ($request->has('destination')) {
            $data->where('extra_field_24', $request->destination);
        }
        if ($request->has('city')) {
            $data->where('extra_field_24', $request->city);
        }
        
        if ($request->has('searchlocation')) {
            $data->where('extra_field_18', $request->searchlocation);
        }
                

        if ($request->has('archive')) {
            $data->whereMonth('created_at', date('m', strtotime($request->archive)))
                ->whereYear('created_at', date('Y', strtotime($request->archive)));
        }
        if ($request->has('tag')) {
            $data->whereRaw("FIND_IN_SET($request->tag,tag_ids)");
        }
        if ($request->has('min_price') && $request->has('max_price')) {
            $data->whereRaw("CAST(extra_field_1 AS UNSIGNED) BETWEEN ? AND ?", [$request->min_price, $request->max_price]);
        } elseif ($request->has('min_price')) {
            $data->whereRaw("CAST(extra_field_1 AS UNSIGNED) >= ?", [$request->min_price]);
        } elseif ($request->has('max_price')) {
            $data->whereRaw("CAST(extra_field_1 AS UNSIGNED) <= ?", [$request->max_price]);
        }
        if ($request->has('cate')) {
            $data->whereRaw("FIND_IN_SET($request->cate,category_ids)");
        }

        $hotels = $data->paginate($request->get('per_page', 10));

        $result = $hotels->map(function ($hotel) {
            $averageRating = $hotel->reviews()->avg('rating');
            $averageRating = number_format($averageRating, 1);
            return [
                'id' => $hotel->id,
                'title' => $hotel->title,
                'slug' => $hotel->slug,
                'image' => $hotel->image ? asset('images/' . $hotel->image) : null,
                'location' => $hotel->extra_field_18,
                'price' => $hotel->extra_field_1,
                'currency' => 'USD',
                'type' => $hotel->extra_field_2,
                'stars' => $hotel->extra_field_23,
                'people_per_room' => $hotel->extra_field_11,
                'average_rating' => $averageRating,
                'description' => $hotel->description,
                'created_at' => $hotel->created_at,
                'updated_at' => $hotel->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result,
            'meta' => [
                'current_page' => $hotels->currentPage(),
                'last_page' => $hotels->lastPage(),
                'per_page' => $hotels->perPage(),
                'total' => $hotels->total(),
            ]
        ]);
    }
} 