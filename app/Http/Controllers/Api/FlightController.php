<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FlightBooking;
use App\Models\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\DuffelService;
use App\Models\ModulesData; // Added this import for ModulesData

class FlightController extends Controller
{
    protected $duffelService;

    public function __construct(DuffelService $duffelService)
    {
        $this->duffelService = $duffelService;
    }

    /**
     * Search flights
     */
    public function search(Request $request)
{
    $validator = Validator::make($request->all(), [
        'origin' => 'required|string|max:10',
        'destination' => 'required|string|max:10',
        'departure_date' => 'required|date|after:today',
        'return_date' => 'nullable|date|after:departure_date',
        'adults' => 'required|integer|min:1|max:9',
        'children' => 'sometimes|integer|min:0|max:9',
        'infants' => 'sometimes|integer|min:0|max:9',
        'cabin_class' => 'sometimes|in:Economy,PremiumEconomy,Business,First',
        'currency' => 'sometimes|string|size:3',
        'trip_type' => 'sometimes|in:one-way,two-way,Multicity'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        // Validate airports exist
        $originAirport = Airport::where('iata_code', $request->origin)->first();
        $destinationAirport = Airport::where('iata_code', $request->destination)->first();

        if (!$originAirport || !$destinationAirport) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid airport codes'
            ], 400);
        }

        // Prepare search parameters
        $searchParams = [
            'origin' => $request->origin,
            'destination' => $request->destination,
            'departure_date' => $request->departure_date,
            'return_date' => $request->return_date,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'infants' => $request->infants ?? 0,
            'cabin_class' => $request->cabin_class ?? 'Economy',
            'currency' => $request->currency ?? 'USD',
            'trip_type' => $request->trip_type ?? 'one-way'
        ];

        Log::info('Flight search initiated:', $searchParams);

        // Call Duffel service
        $results = $this->duffelService->searchFlights($searchParams);

        // Extract airlines from the offers
        $airlines = [];
        if (isset($results['offers']) && is_array($results['offers'])) {
            $airlines = collect($results['offers'])
                ->pluck('slices.*.segments.*.operating_carrier.name')
                ->flatten()
                ->unique()
                ->sort()
                ->filter()
                ->values()
                ->all();
        }

        return response()->json([
            'success' => true,
            'data' => $results,
            'airlines' => $airlines, // Add airlines to response
            'meta' => [
                'search_params' => $searchParams,
                'total_offers' => count($results['offers'] ?? []),
                'origin_airport' => $originAirport,
                'destination_airport' => $destinationAirport
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Flight search error:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to search flights',
            'error' => $e->getMessage()
        ], 500);
    }
}


    /**
     * Get flight offers
     */
    public function getOffers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_ids' => 'required|array',
            'offer_ids.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $offerIds = $request->offer_ids;
            
            Log::info('Getting flight offers:', ['offer_ids' => $offerIds]);

            // Call Duffel service to get offers
            $offers = $this->duffelService->getOffers($offerIds);

            return response()->json([
                'success' => true,
                'data' => $offers
            ]);

        } catch (\Exception $e) {
            Log::error('Get offers error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get flight offers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get popular routes
     */
    public function popularRoutes()
    {
        try {
            // This would typically come from your analytics or booking data
            $popularRoutes = [
                [
                    'origin' => 'JFK',
                    'destination' => 'LAX',
                    'origin_city' => 'New York',
                    'destination_city' => 'Los Angeles',
                    'frequency' => 'Daily'
                ],
                [
                    'origin' => 'LHR',
                    'destination' => 'CDG',
                    'origin_city' => 'London',
                    'destination_city' => 'Paris',
                    'frequency' => 'Multiple daily'
                ],
                [
                    'origin' => 'SIN',
                    'destination' => 'BKK',
                    'origin_city' => 'Singapore',
                    'destination_city' => 'Bangkok',
                    'frequency' => 'Daily'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $popularRoutes
            ]);

        } catch (\Exception $e) {
            Log::error('Popular routes error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch popular routes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get flight details
     */
    public function show($id)
    {
        try {
            $booking = FlightBooking::with('user')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Flight booking not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get featured airlines (popular airlines from welcome page)
     */
    public function getFeaturedAirlines(Request $request)
    {
        try {
            $locale = $request->get('locale', app()->getLocale());
            
            // Get featured airlines from moduleF(4) - same as welcome.blade.php
            $featuredAirlines = ModulesData::where('module_id', 4)
                ->where('status', 'active')
                ->where('extra_field_2', '406') // This is the filter used in moduleF function
                ->orderBy('created_at', 'desc')
                ->limit(12)
                ->get()
                ->map(function ($airline) use ($locale) {
                    return [
                        'id' => $airline->id,
                        'title' => $airline->getTranslatedTitle($locale),
                        'image' => $airline->image ? asset('images/' . $airline->image) : null,
                        'description' => $airline->getTranslatedDescription($locale),
                        'created_at' => $airline->created_at,
                        'updated_at' => $airline->updated_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $featuredAirlines
            ]);

        } catch (\Exception $e) {
            Log::error('Featured airlines error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured airlines',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 