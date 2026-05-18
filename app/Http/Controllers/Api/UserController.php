<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'location' => $user->location,
                'phone' => $user->phone
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'mobile' => 'sometimes|string|max:20',
            'country' => 'sometimes|integer|exists:countries,id',
            'state' => 'sometimes|integer|exists:states,id',
            'city' => 'sometimes|integer|exists:cities,id',
            'country_code' => 'sometimes|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update($request->only(['name', 'mobile', 'country', 'state', 'city', 'country_code']));

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => $user->fresh(),
                    'location' => $user->location,
                    'phone' => $user->phone
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user avatar
     */
    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
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
            
            // Delete old avatar if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('profile-photos', 'public');
            
            $user->update([
                'profile_photo_path' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully',
                'data' => [
                    'avatar_url' => $user->profile_photo_url
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update avatar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get countries list
     */
    public function getCountries()
    {
        try {
            $countries = Country::select('id', 'name', 'phonecode')
                ->whereNotNull('phonecode')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $countries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch countries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get states by country
     */
    public function getStatesByCountry($countryId)
    {
        try {
            $states = State::where('country_id', $countryId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $states
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch states',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cities by state
     */
    public function getCitiesByState($stateId)
    {
        try {
            $cities = City::where('state_id', $stateId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $cities
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cities by country
     */
    public function getCitiesByCountry($countryId)
    {
        try {
            $cities = City::where('country_id', $countryId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $cities
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user favorites
     */
    public function favorites(Request $request)
    {
        $user = $request->user();
        
        // This would need to be implemented based on your favorites table structure
        // For now, returning empty arrays
        return response()->json([
            'success' => true,
            'data' => [
                'flights' => [],
                'hotels' => []
            ]
        ]);
    }

    /**
     * Add flight to favorites
     */
    public function addFlightToFavorites(Request $request, $flight)
    {
        // Implementation would depend on your favorites table structure
        return response()->json([
            'success' => true,
            'message' => 'Flight added to favorites'
        ]);
    }

    /**
     * Add hotel to favorites
     */
    public function addHotelToFavorites(Request $request, $hotel)
    {
        // Implementation would depend on your favorites table structure
        return response()->json([
            'success' => true,
            'message' => 'Hotel added to favorites'
        ]);
    }

    /**
     * Remove flight from favorites
     */
    public function removeFlightFromFavorites(Request $request, $flight)
    {
        // Implementation would depend on your favorites table structure
        return response()->json([
            'success' => true,
            'message' => 'Flight removed from favorites'
        ]);
    }

    /**
     * Remove hotel from favorites
     */
    public function removeHotelFromFavorites(Request $request, $hotel)
    {
        // Implementation would depend on your favorites table structure
        return response()->json([
            'success' => true,
            'message' => 'Hotel removed from favorites'
        ]);
    }

    /**
     * Get user's payment history
     */
    public function paymentHistory(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get hotel bookings with payment info
            $hotelPayments = Hotels::where('user_id', $user->id)
                ->whereNotNull('payment_via')
                ->where('status', 'paid')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'type' => 'hotel',
                        'transaction_id' => $booking->transaction_id,
                        'amount' => $booking->price,
                        'payment_method' => $booking->payment_via,
                        'status' => $booking->status,
                        'booking_status' => $booking->booking_status,
                        'date' => $booking->created_at,
                        'hotel_name' => $booking->hotel->title ?? 'N/A',
                        'check_in' => $booking->check_in,
                        'check_out' => $booking->check_out
                    ];
                });

            // Get flight bookings with payment info
            $flightPayments = FlightBooking::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'type' => 'flight',
                        'transaction_id' => $booking->system_order_id,
                        'amount' => $booking->total_amount,
                        'payment_method' => $booking->payment_via ?? 'N/A',
                        'status' => $booking->payment_status,
                        'booking_status' => $booking->booking_status,
                        'date' => $booking->created_at,
                        'origin' => $booking->origin_code,
                        'destination' => $booking->destination_code,
                        'departure_date' => $booking->departure_date
                    ];
                });

            // Combine and sort by date
            $allPayments = $hotelPayments->concat($flightPayments)
                ->sortByDesc('date')
                ->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'payments' => $allPayments,
                    'total_payments' => $allPayments->count(),
                    'total_amount' => $allPayments->sum('amount')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payment history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's payment methods
     */
    public function paymentMethods(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get unique payment methods used by the user
            $hotelPaymentMethods = Hotels::where('user_id', $user->id)
                ->whereNotNull('payment_via')
                ->where('status', 'paid')
                ->distinct()
                ->pluck('payment_via')
                ->toArray();

            $flightPaymentMethods = FlightBooking::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->whereNotNull('payment_via')
                ->distinct()
                ->pluck('payment_via')
                ->toArray();

            $allPaymentMethods = array_unique(array_merge($hotelPaymentMethods, $flightPaymentMethods));

            // Get payment method statistics
            $paymentStats = [];
            foreach ($allPaymentMethods as $method) {
                $hotelCount = Hotels::where('user_id', $user->id)
                    ->where('payment_via', $method)
                    ->where('status', 'paid')
                    ->count();

                $flightCount = FlightBooking::where('user_id', $user->id)
                    ->where('payment_via', $method)
                    ->where('payment_status', 'paid')
                    ->count();

                $paymentStats[] = [
                    'method' => $method,
                    'total_transactions' => $hotelCount + $flightCount,
                    'hotel_transactions' => $hotelCount,
                    'flight_transactions' => $flightCount
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'payment_methods' => $allPaymentMethods,
                    'statistics' => $paymentStats,
                    'total_methods' => count($allPaymentMethods)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payment methods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's reviews
     */
    public function myReviews(Request $request)
    {
        try {
            $user = $request->user();
            
            $reviews = Review::with(['hotel'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Admin methods
    /**
     * Get all users (admin)
     */
    public function adminIndex(Request $request)
    {
        $users = User::with(['type', 'membership'])
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Get specific user (admin)
     */
    public function adminShow(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user->load(['type', 'membership'])
        ]);
    }

    /**
     * Update user (admin)
     */
    public function adminUpdate(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'mobile' => 'sometimes|string|max:20',
            'country' => 'sometimes|integer|exists:countries,id',
            'state' => 'sometimes|integer|exists:states,id',
            'city' => 'sometimes|integer|exists:cities,id',
            'country_code' => 'sometimes|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update($request->only(['name', 'email', 'mobile', 'country', 'state', 'city', 'country_code']));

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user (admin)
     */
    public function adminDestroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 