<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AirportController extends Controller
{
    /**
     * Search airports
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'term' => 'required|string|min:2',
            'type' => 'sometimes|in:name,code,city',
            'limit' => 'sometimes|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $term = $request->input('term');
            $type = $request->input('type', 'name');
            $limit = $request->input('limit', 10);
            
            // Log the search parameters
            Log::info('Airport search:', ['term' => $term, 'type' => $type, 'limit' => $limit]);
            
            $query = Airport::query();
            
            switch ($type) {
                case 'code':
                    $query->where('iata_code', 'LIKE', '%' . $term . '%');
                    break;
                case 'city':
                    $query->where('city', 'LIKE', '%' . $term . '%');
                    break;
                default:
                    $query->where(function($q) use ($term) {
                        $q->where('name', 'LIKE', '%' . $term . '%')
                          ->orWhere('city', 'LIKE', '%' . $term . '%')
                          ->orWhere('iata_code', 'LIKE', '%' . $term . '%');
                    });
                    break;
            }
            
            $results = $query->select('id', 'name', 'city', 'iata_code', 'country')
                            ->limit($limit)
                            ->get();
            
            // Log the results count
            Log::info('Search results count:', ['count' => $results->count()]);
            
            return response()->json([
                'success' => true,
                'data' => $results,
                'meta' => [
                    'total' => $results->count(),
                    'term' => $term,
                    'type' => $type
                ]
            ]);

        } catch (\Exception $e) {
            // Log the full error
            Log::error('Airport search error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching airports',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get popular airports
     */
    public function popular()
    {
        try {
            $popularAirports = Airport::select('id', 'name', 'city', 'iata_code', 'country')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $popularAirports
            ]);

        } catch (\Exception $e) {
            Log::error('Popular airports error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch popular airports',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get airport details
     */
    public function show($id)
    {
        try {
            $airport = Airport::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $airport
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Airport not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
} 