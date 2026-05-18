<?php

namespace App\Http\Controllers;

use App\Services\DuffelService;
use App\Models\ModulesData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DuffelController extends Controller
{
    protected $duffelService;

    public function __construct(DuffelService $duffelService)
    {
        $this->duffelService = $duffelService;
    }

    public function getHotels(Request $request)
    {
        try {
        $hotels = $this->duffelService->getHotels($request->all());
        
            if (isset($hotels['error'])) {
                return response()->json(['error' => $hotels['error']], 400);
            }

        return response()->json($hotels);
        } catch (\Exception $e) {
            \Log::error('Duffel Controller Error (Hotels): ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function getOfferRequests(Request $request)
    {
        try {
            // Get the search parameters
            $fromType = $request->input('from_type', 'name');
            $toType = $request->input('to_type', 'name');
            $from = $request->input('from');
            $to = $request->input('to');
            $travellingDate = $request->input('travelling_date');
            $cabinClass = $request->input('cabin_class');
            $adults = $request->input('adults', 1);
            $children = $request->input('children', 0);

            // Prepare parameters for DuffelService
            $params = [
                'travelling_date' => $travellingDate,
                'cabin_class' => $cabinClass,
                'adults' => $adults,
                'children' => $children
            ];

            // Handle origin location
            if ($fromType === 'code') {
                $params['from_code'] = $from;
            } else {
                $params['from'] = $from;
            }

            // Handle destination location
            if ($toType === 'code') {
                $params['to_code'] = $to;
            } else {
                $params['to'] = $to;
            }

            // Get offers from DuffelService
            $offerRequests = $this->duffelService->getOfferRequests($params);
            
            if (isset($offerRequests['error'])) {
                return view('flights.offers', [
                    'error' => $offerRequests['error'],
                    'from' => $from,
                    'to' => $to,
                    'travelling_date' => $travellingDate,
                    'cabin_class' => $cabinClass,
                    'adults' => $adults,
                    'children' => $children,
                    'from_type' => $fromType,
                    'to_type' => $toType
                ]);
            }

            return view('flights.offers', [
                'flights' => $offerRequests,
                'from' => $from,
                'to' => $to,
                'travelling_date' => $travellingDate,
                'cabin_class' => $cabinClass,
                'adults' => $adults,
                'children' => $children,
                'from_type' => $fromType,
                'to_type' => $toType
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getOfferRequests: ' . $e->getMessage());
            return view('flights.offers', [
                'error' => 'An error occurred while fetching flight offers. Please try again later.',
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'travelling_date' => $request->input('travelling_date'),
                'cabin_class' => $request->input('cabin_class'),
                'adults' => $request->input('adults', 1),
                'children' => $request->input('children', 0),
                'from_type' => $request->input('from_type', 'name'),
                'to_type' => $request->input('to_type', 'name')
            ]);
        }
    }

    public function getFlights(Request $request)
    {
        try {
        $flights = $this->duffelService->getFlights($request->all());
        
            if (isset($flights['error'])) {
                return response()->json(['error' => $flights['error']], 400);
            }

        return response()->json($flights);
        } catch (\Exception $e) {
            \Log::error('Duffel Controller Error (Flights): ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function searchAirports(Request $request)
    {
        try {
            $query = $request->input('query');
            $airports = $this->duffelService->searchAirports($query);
            return response()->json($airports);
        } catch (\Exception $e) {
            \Log::error('Error in searchAirports: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}
