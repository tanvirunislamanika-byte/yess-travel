<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModulesData;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TourController extends Controller
{
    /**
     * Strip HTML tags and decode HTML entities from text
     */
    private function stripHtml($text)
    {
        if (empty($text)) {
            return $text;
        }
        // Strip HTML tags and decode HTML entities
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Clean up multiple whitespaces and trim
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    /**
     * Get all tours with translations
     */
    public function index(Request $request)
    {
        try {
        $locale = $request->get('locale', app()->getLocale());
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $tourType = $request->get('tour_type');
        $transportType = $request->get('transport_type');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $departureCountry = $request->get('departure_country');
        $departureState = $request->get('departure_state');
        $departureCity = $request->get('departure_city');
        $minDays = $request->get('min_days');
        $maxDays = $request->get('max_days');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $sort = $request->get('sort', 'latest');

        $query = ModulesData::where('module_id', 34) // Tours module (correct ID)
            ->where('status', 'active')
                ->with(['departureCountry', 'departureState', 'departureCity']);
            
            // Load tourType and transportType separately if needed, as they have where clauses
            // These will be loaded manually in the transform if needed

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        // Destination filter (matches mobile app)
        $destination = $request->get('destination');
        if ($destination) {
            // Join with countries and cities tables to search destination
            $query->where(function($q) use ($destination) {
                $q->whereHas('departureCountry', function($countryQuery) use ($destination) {
                    $countryQuery->where('name', 'LIKE', '%' . $destination . '%');
                })
                ->orWhereHas('departureCity', function($cityQuery) use ($destination) {
                    $cityQuery->where('name', 'LIKE', '%' . $destination . '%');
                })
                ->orWhereHas('departureState', function($stateQuery) use ($destination) {
                    $stateQuery->where('name', 'LIKE', '%' . $destination . '%');
                });
            });
        }

        if ($tourType) {
            $query->where('extra_field_10', $tourType);
        }

        if ($transportType) {
            $query->where('extra_field_11', $transportType);
        }

        if ($departureCountry) {
            $query->where('extra_field_5', $departureCountry);
        }

        if ($departureState) {
            $query->where('extra_field_6', $departureState);
        }

        if ($departureCity) {
            $query->where('extra_field_7', $departureCity);
        }

        if ($minPrice) {
            $query->whereRaw("CAST(extra_field_8 AS UNSIGNED) >= ?", [$minPrice]);
        }

        if ($maxPrice) {
            $query->whereRaw("CAST(extra_field_8 AS UNSIGNED) <= ?", [$maxPrice]);
        }

        if ($minDays) {
            $query->whereRaw("CAST(extra_field_3 AS UNSIGNED) >= ?", [$minDays]);
        }

        if ($maxDays) {
            $query->whereRaw("CAST(extra_field_3 AS UNSIGNED) <= ?", [$maxDays]);
        }

        if ($startDate) {
            $query->where('extra_field_1', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('extra_field_2', '<=', $endDate);
        }

        // Sort options
        switch ($sort) {
            case 'price_low':
                $query->orderByRaw("CAST(extra_field_8 AS UNSIGNED) ASC");
                break;
            case 'price_high':
                $query->orderByRaw("CAST(extra_field_8 AS UNSIGNED) DESC");
                break;
            case 'duration_short':
                $query->orderByRaw("CAST(extra_field_3 AS UNSIGNED) ASC");
                break;
            case 'duration_long':
                $query->orderByRaw("CAST(extra_field_3 AS UNSIGNED) DESC");
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Apply pagination - Laravel will handle the count automatically
        $tours = $query->paginate($perPage);

        // Pre-load tour types and transport types to avoid N+1 queries
        $tourTypeIds = $tours->getCollection()->pluck('extra_field_10')->filter()->unique()->values()->all();
        $transportTypeIds = $tours->getCollection()->pluck('extra_field_11')->filter()->unique()->values()->all();
        
        $tourTypes = [];
        $transportTypes = [];
        
        if (!empty($tourTypeIds)) {
            try {
                $tourTypesCollection = ModulesData::where('module_id', 35)
                    ->whereIn('id', $tourTypeIds)
                    ->get();
                foreach ($tourTypesCollection as $type) {
                    $tourTypes[$type->id] = $type->getTranslatedTitle($locale);
                }
            } catch (\Exception $e) {
                Log::warning('Error loading tour types: ' . $e->getMessage());
            }
        }
        
        if (!empty($transportTypeIds)) {
            try {
                $transportTypesCollection = ModulesData::where('module_id', 36)
                    ->whereIn('id', $transportTypeIds)
                    ->get();
                foreach ($transportTypesCollection as $type) {
                    $transportTypes[$type->id] = $type->getTranslatedTitle($locale);
                }
            } catch (\Exception $e) {
                Log::warning('Error loading transport types: ' . $e->getMessage());
            }
        }

        $tours->getCollection()->transform(function ($tour) use ($locale, $tourTypes, $transportTypes) {
            try {
                // Build destination string from country/city
                $destinationParts = [];
                try {
                    if ($tour->departureCity && isset($tour->departureCity)) {
                        $destinationParts[] = $tour->departureCity->getTranslatedName($locale);
                    }
                    if ($tour->departureCountry && isset($tour->departureCountry)) {
                        $countryName = $tour->departureCountry->getTranslatedName($locale);
                        if (!in_array($countryName, $destinationParts)) {
                            $destinationParts[] = $countryName;
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Error building destination for tour ' . $tour->id . ': ' . $e->getMessage());
                }
                $destination = !empty($destinationParts) ? implode(', ', $destinationParts) : null;
                
                // Get duration (days)
                $duration = $tour->getTranslatedExtraField(3, $locale) ?: null;
                
                // Get price (use price_adult as main price)
                $price = $tour->extra_field_8 ?: '0';
                
                // Get highlights, included, excluded, itinerary from extra fields if available
                $highlights = $tour->getTranslatedExtraField(13, $locale) ?: '';
                $included = $tour->getTranslatedExtraField(14, $locale) ?: '';
                $excluded = $tour->getTranslatedExtraField(15, $locale) ?: '';
                $itinerary = $tour->getTranslatedExtraField(16, $locale) ?: '';
                $groupSize = $tour->getTranslatedExtraField(17, $locale) ?: null;
                $difficulty = $tour->getTranslatedExtraField(18, $locale) ?: null;
                
                // Get tour type and transport type from pre-loaded arrays
                $tourTypeName = isset($tour->extra_field_10) && isset($tourTypes[$tour->extra_field_10]) 
                    ? $tourTypes[$tour->extra_field_10] 
                    : null;
                
                $transportTypeName = isset($tour->extra_field_11) && isset($transportTypes[$tour->extra_field_11]) 
                    ? $transportTypes[$tour->extra_field_11] 
                    : null;
                
            return [
                'id' => $tour->id,
                    'title' => $tour->getTranslatedTitle($locale) ?: '',
                    'description' => $this->stripHtml($tour->getTranslatedDescription($locale) ?: ''),
                    'slug' => $tour->slug ?: '',
                'image' => $tour->image ? asset('images/' . $tour->image) : null,
                    // Mobile app expected fields
                    'price' => $price,
                    'destination' => $destination,
                    'duration' => $duration,
                    'group_size' => $groupSize,
                    'difficulty' => $difficulty,
                    'highlights' => $this->stripHtml($highlights),
                    'included' => $this->stripHtml($included),
                    'excluded' => $this->stripHtml($excluded),
                    'itinerary' => $this->stripHtml($itinerary),
                    // Additional fields for backward compatibility
                    'start_date' => $tour->getTranslatedExtraField(1, $locale) ?: null,
                    'end_date' => $tour->getTranslatedExtraField(2, $locale) ?: null,
                    'days' => $duration,
                    'nights' => $tour->getTranslatedExtraField(4, $locale) ?: null,
                'departure_country_id' => $tour->extra_field_5,
                    'departure_country' => ($tour->departureCountry && isset($tour->departureCountry)) ? $tour->departureCountry->getTranslatedName($locale) : null,
                'departure_state_id' => $tour->extra_field_6,
                    'departure_state' => ($tour->departureState && isset($tour->departureState)) ? $tour->departureState->getTranslatedName($locale) : null,
                'departure_city_id' => $tour->extra_field_7,
                    'departure_city' => ($tour->departureCity && isset($tour->departureCity)) ? $tour->departureCity->getTranslatedName($locale) : null,
                    'price_adult' => $price,
                    'price_child' => $tour->extra_field_9 ?: null,
                'tour_type_id' => $tour->extra_field_10,
                    'tour_type' => $tourTypeName,
                'transport_type_id' => $tour->extra_field_11,
                    'transport_type' => $transportTypeName,
                    'terms_conditions' => $tour->getTranslatedExtraField(12, $locale) ?: null,
                    'meta_title' => $tour->getTranslatedMetaTitle($locale) ?: null,
                    'meta_description' => $tour->getTranslatedMetaDescription($locale) ?: null,
                'created_at' => $tour->created_at,
                'updated_at' => $tour->updated_at,
            ];
            } catch (\Exception $e) {
                Log::error('Error transforming tour ' . ($tour->id ?? 'unknown') . ': ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                // Return a minimal tour object to prevent complete failure
                return [
                    'id' => $tour->id ?? 0,
                    'title' => $tour->title ?? 'Unknown Tour',
                    'description' => $tour->description ?? '',
                    'slug' => $tour->slug ?? '',
                    'image' => $tour->image ? asset('images/' . $tour->image) : null,
                    'price' => $tour->extra_field_8 ?: '0',
                    'destination' => null,
                    'duration' => null,
                    'error' => 'Error loading tour details'
                ];
            }
        });

        return response()->json([
            'success' => true,
            'data' => $tours
        ]);
        } catch (\Exception $e) {
            Log::error('TourController@index Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load tours',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred while loading tours'
            ], 500);
        }
    }

    /**
     * Get tour by ID with translations
     */
    public function show($id, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $tour = ModulesData::where('module_id', 34)
            ->where('status', 'active')
            ->with(['departureCountry', 'departureState', 'departureCity'])
            ->findOrFail($id);

        // Build destination string
        $destinationParts = [];
        if ($tour->departureCity) {
            $destinationParts[] = $tour->departureCity->getTranslatedName($locale);
        }
        if ($tour->departureCountry && !in_array($tour->departureCountry->getTranslatedName($locale), $destinationParts)) {
            $destinationParts[] = $tour->departureCountry->getTranslatedName($locale);
        }
        $destination = !empty($destinationParts) ? implode(', ', $destinationParts) : null;
        
        $duration = $tour->getTranslatedExtraField(3, $locale);
        $price = $tour->extra_field_8 ?: '0';
        $highlights = $tour->getTranslatedExtraField(13, $locale) ?: '';
        $included = $tour->getTranslatedExtraField(14, $locale) ?: '';
        $excluded = $tour->getTranslatedExtraField(15, $locale) ?: '';
        $itinerary = $tour->getTranslatedExtraField(16, $locale) ?: '';
        $groupSize = $tour->getTranslatedExtraField(17, $locale) ?: null;
        $difficulty = $tour->getTranslatedExtraField(18, $locale) ?: null;

        // Load tour type and transport type manually
        $tourTypeName = null;
        if ($tour->extra_field_10) {
            try {
                $tourType = ModulesData::where('module_id', 35)
                    ->where('id', $tour->extra_field_10)
                    ->first();
                if ($tourType) {
                    $tourTypeName = $tourType->getTranslatedTitle($locale);
                }
            } catch (\Exception $e) {
                Log::warning('Error loading tour type for tour ' . $tour->id . ': ' . $e->getMessage());
            }
        }
        
        $transportTypeName = null;
        if ($tour->extra_field_11) {
            try {
                $transportType = ModulesData::where('module_id', 36)
                    ->where('id', $tour->extra_field_11)
                    ->first();
                if ($transportType) {
                    $transportTypeName = $transportType->getTranslatedTitle($locale);
                }
            } catch (\Exception $e) {
                Log::warning('Error loading transport type for tour ' . $tour->id . ': ' . $e->getMessage());
            }
        }

        // Parse images field (can be JSON string or comma-separated)
        $imagesArray = [];
        if ($tour->images && trim($tour->images) !== '') {
            // Try to decode as JSON first
            $decoded = json_decode($tour->images, true);
            if (is_array($decoded) && !empty($decoded)) {
                $imagesArray = $decoded;
            } else {
                // If not JSON, try comma-separated
                $exploded = explode(',', $tour->images);
                $imagesArray = array_filter(array_map('trim', $exploded));
            }
            // Convert to full URLs
            if (!empty($imagesArray)) {
                $imagesArray = array_map(function($img) {
                    $trimmed = trim($img);
                    return $trimmed ? asset('images/' . $trimmed) : null;
                }, $imagesArray);
                $imagesArray = array_filter($imagesArray); // Remove nulls
                $imagesArray = array_values($imagesArray); // Reindex
            }
        }
        // Always include the main image as first if it exists and not already in array
        if ($tour->image) {
            $mainImageUrl = asset('images/' . $tour->image);
            if (!in_array($mainImageUrl, $imagesArray)) {
                array_unshift($imagesArray, $mainImageUrl);
            }
        }

        $tourData = [
            'id' => $tour->id,
            'title' => $tour->getTranslatedTitle($locale),
            'description' => $this->stripHtml($tour->getTranslatedDescription($locale)),
            'slug' => $tour->slug,
            'image' => $tour->image ? asset('images/' . $tour->image) : null,
            'images' => $imagesArray,
            // Mobile app expected fields
            'price' => $price,
            'destination' => $destination,
            'duration' => $duration,
            'group_size' => $groupSize,
            'difficulty' => $difficulty,
            'highlights' => $this->stripHtml($highlights),
            'included' => $this->stripHtml($included),
            'excluded' => $this->stripHtml($excluded),
            'itinerary' => $this->stripHtml($itinerary),
            // Additional fields
            'start_date' => $tour->getTranslatedExtraField(1, $locale),
            'end_date' => $tour->getTranslatedExtraField(2, $locale),
            'days' => $duration,
            'nights' => $tour->getTranslatedExtraField(4, $locale),
            'departure_country_id' => $tour->extra_field_5,
            'departure_country' => $tour->departureCountry ? $tour->departureCountry->getTranslatedName($locale) : null,
            'departure_state_id' => $tour->extra_field_6,
            'departure_state' => $tour->departureState ? $tour->departureState->getTranslatedName($locale) : null,
            'departure_city_id' => $tour->extra_field_7,
            'departure_city' => $tour->departureCity ? $tour->departureCity->getTranslatedName($locale) : null,
            'price_adult' => $price,
            'price_child' => $tour->extra_field_9,
            'tour_type_id' => $tour->extra_field_10,
            'tour_type' => $tourTypeName,
            'transport_type_id' => $tour->extra_field_11,
            'transport_type' => $transportTypeName,
            'terms_conditions' => $tour->getTranslatedExtraField(12, $locale),
            'meta_title' => $tour->getTranslatedMetaTitle($locale),
            'meta_description' => $tour->getTranslatedMetaDescription($locale),
            'meta_keywords' => $tour->getTranslatedMetaKeywords($locale),
            'created_at' => $tour->created_at,
            'updated_at' => $tour->updated_at,
        ];

        return response()->json([
            'success' => true,
            'data' => $tourData
        ]);
    }

    /**
     * Get tour by slug with translations
     */
    public function showBySlug($slug, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $tour = ModulesData::where('module_id', 34)
            ->where('status', 'active')
            ->where('slug', $slug)
            ->with(['departureCountry', 'departureState', 'departureCity', 'tourType', 'transportType'])
            ->firstOrFail();

        return $this->show($tour->id, $request);
    }

    /**
     * Get featured tours with translations
     */
    public function getFeatured(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $limit = $request->get('limit', 6);

        $tours = ModulesData::where('module_id', 34)
            ->where('status', 'active')
            ->where('featured', 1)
            ->with(['departureCountry', 'departureState', 'departureCity', 'tourType', 'transportType'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($tour) use ($locale) {
                return [
                    'id' => $tour->id,
                    'title' => $tour->getTranslatedTitle($locale),
                    'description' => $this->stripHtml($tour->getTranslatedDescription($locale)),
                    'slug' => $tour->slug,
                    'image' => $tour->image ? asset('images/' . $tour->image) : null,
                    'start_date' => $tour->getTranslatedExtraField(1, $locale),
                    'end_date' => $tour->getTranslatedExtraField(2, $locale),
                    'days' => $tour->getTranslatedExtraField(3, $locale),
                    'nights' => $tour->getTranslatedExtraField(4, $locale),
                    'departure_country' => $tour->departureCountry ? $tour->departureCountry->getTranslatedName($locale) : null,
                    'departure_city' => $tour->departureCity ? $tour->departureCity->getTranslatedName($locale) : null,
                    'price_adult' => $tour->extra_field_8,
                    'price_child' => $tour->extra_field_9,
                    'tour_type' => $tour->tourType ? $tour->tourType->getTranslatedTitle($locale) : null,
                    'transport_type' => $tour->transportType ? $tour->transportType->getTranslatedTitle($locale) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $tours
        ]);
    }

    /**
     * Get latest tours with translations
     */
    public function getLatest(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $limit = $request->get('limit', 3);

        $tours = ModulesData::where('module_id', 34)
            ->where('status', 'active')
            ->with(['departureCountry', 'departureState', 'departureCity', 'tourType', 'transportType'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($tour) use ($locale) {
                return [
                    'id' => $tour->id,
                    'title' => $tour->getTranslatedTitle($locale),
                    'description' => $this->stripHtml($tour->getTranslatedDescription($locale)),
                    'slug' => $tour->slug,
                    'image' => $tour->image ? asset('images/' . $tour->image) : null,
                    'start_date' => $tour->getTranslatedExtraField(1, $locale),
                    'end_date' => $tour->getTranslatedExtraField(2, $locale),
                    'days' => $tour->getTranslatedExtraField(3, $locale),
                    'nights' => $tour->getTranslatedExtraField(4, $locale),
                    'departure_country' => $tour->departureCountry ? $tour->departureCountry->getTranslatedName($locale) : null,
                    'departure_city' => $tour->departureCity ? $tour->departureCity->getTranslatedName($locale) : null,
                    'price_adult' => $tour->extra_field_8,
                    'price_child' => $tour->extra_field_9,
                    'tour_type' => $tour->tourType ? $tour->tourType->getTranslatedTitle($locale) : null,
                    'transport_type' => $tour->transportType ? $tour->transportType->getTranslatedTitle($locale) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $tours
        ]);
    }

    /**
     * Get tour types with translations
     */
    public function getTourTypes(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $tourTypes = ModulesData::where('module_id', 35) // Tour types module
            ->where('status', 'active')
            ->get()
            ->map(function ($type) use ($locale) {
                return [
                    'id' => $type->id,
                    'name' => $type->getTranslatedTitle($locale),
                    'description' => $this->stripHtml($type->getTranslatedDescription($locale)),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $tourTypes
        ]);
    }

    /**
     * Get transport types with translations
     */
    public function getTransportTypes(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $transportTypes = ModulesData::where('module_id', 36) // Transport types module
            ->where('status', 'active')
            ->get()
            ->map(function ($type) use ($locale) {
                return [
                    'id' => $type->id,
                    'name' => $type->getTranslatedTitle($locale),
                    'description' => $this->stripHtml($type->getTranslatedDescription($locale)),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $transportTypes
        ]);
    }

    /**
     * Search tours with advanced filters
     */
    public function search(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $perPage = $request->get('per_page', 15);

        $query = ModulesData::where('module_id', 34)
            ->where('status', 'active')
            ->with(['departureCountry', 'departureState', 'departureCity', 'tourType', 'transportType']);

        // Apply search filters
        if ($request->has('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->keyword.'%')
                  ->orWhere('description', 'like', '%'.$request->keyword.'%');
            });
        }

        if ($request->has('tour_type')) {
            $query->where('extra_field_10', $request->tour_type);
        }

        if ($request->has('transport_type')) {
            $query->where('extra_field_11', $request->transport_type);
        }

        if ($request->has('departure_country')) {
            $query->where('extra_field_5', $request->departure_country);
        }

        if ($request->has('departure_state')) {
            $query->where('extra_field_6', $request->departure_state);
        }

        if ($request->has('departure_city')) {
            $query->where('extra_field_7', $request->departure_city);
        }

        if ($request->has('min_price')) {
            $query->whereRaw("CAST(extra_field_8 AS UNSIGNED) >= ?", [$request->min_price]);
        }

        if ($request->has('max_price')) {
            $query->whereRaw("CAST(extra_field_8 AS UNSIGNED) <= ?", [$request->max_price]);
        }

        if ($request->has('min_days')) {
            $query->whereRaw("CAST(extra_field_3 AS UNSIGNED) >= ?", [$request->min_days]);
        }

        if ($request->has('max_days')) {
            $query->whereRaw("CAST(extra_field_3 AS UNSIGNED) <= ?", [$request->max_days]);
        }

        if ($request->has('start_date')) {
            $query->where('extra_field_1', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('extra_field_2', '<=', $request->end_date);
        }

        $tours = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $tours->getCollection()->transform(function ($tour) use ($locale) {
            return [
                'id' => $tour->id,
                'title' => $tour->getTranslatedTitle($locale),
                'description' => $this->stripHtml($tour->getTranslatedDescription($locale)),
                'slug' => $tour->slug,
                'image' => $tour->image ? asset('images/' . $tour->image) : null,
                'start_date' => $tour->getTranslatedExtraField(1, $locale),
                'end_date' => $tour->getTranslatedExtraField(2, $locale),
                'days' => $tour->getTranslatedExtraField(3, $locale),
                'nights' => $tour->getTranslatedExtraField(4, $locale),
                'departure_country' => $tour->departureCountry ? $tour->departureCountry->getTranslatedName($locale) : null,
                'departure_state' => $tour->departureState ? $tour->departureState->getTranslatedName($locale) : null,
                'departure_city' => $tour->departureCity ? $tour->departureCity->getTranslatedName($locale) : null,
                'price_adult' => $tour->extra_field_8,
                'price_child' => $tour->extra_field_9,
                'tour_type' => $tour->tourType ? $tour->tourType->getTranslatedTitle($locale) : null,
                'transport_type' => $tour->transportType ? $tour->transportType->getTranslatedTitle($locale) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $tours,
            'filters' => $request->only(['keyword', 'tour_type', 'transport_type', 'departure_country', 'departure_state', 'departure_city', 'min_price', 'max_price', 'min_days', 'max_days', 'start_date', 'end_date'])
        ]);
    }
}
