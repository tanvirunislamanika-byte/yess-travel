<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Get authenticated user's profile
     */
    public function getProfile(Request $request)
    {
        $user = Auth::user();
        $locale = $request->get('locale', app()->getLocale());

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $country = null;
        $state = null;
        $city = null;

        // Use 'country' field (not 'country_id') to match database schema
        $countryId = $user->country_id ?? $user->country;
        if ($countryId) {
            $countryModel = Country::find($countryId);
            if ($countryModel) {
                $country = [
                    'id' => $countryModel->id,
                    'name' => $countryModel->getTranslatedName($locale),
                    'code' => $countryModel->sortname,
                    'phone_code' => $countryModel->phonecode,
                ];
            }
        }

        // Use 'state' field (not 'state_id') to match database schema
        $stateId = $user->state_id ?? $user->state;
        if ($stateId) {
            $stateModel = State::find($stateId);
            if ($stateModel) {
                $state = [
                    'id' => $stateModel->id,
                    'name' => $stateModel->getTranslatedName($locale),
                    'country_id' => $stateModel->country_id,
                ];
            }
        }

        // Use 'city' field (not 'city_id') to match database schema
        $cityId = $user->city_id ?? $user->city;
        if ($cityId) {
            $cityModel = City::find($cityId);
            if ($cityModel) {
                $city = [
                    'id' => $cityModel->id,
                    'name' => $cityModel->getTranslatedName($locale),
                    'state_id' => $cityModel->state_id,
                    'country_id' => $cityModel->country_id,
                ];
            }
        }

        // Get phone from mobile field (User model has accessor for phone that returns mobile)
        $phone = $user->phone ?? $user->mobile ?? null;
        
        // Get avatar from profile_photo_url accessor or profile_photo_path
        $avatar = $user->profile_photo_url ?? null;
        if (!$avatar && $user->profile_photo_path) {
            $avatar = asset('storage/profile-photos/' . $user->profile_photo_path);
        }

        $responseData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $phone,
            'mobile' => $user->mobile,
            'address' => $user->address ?? null,
            'country' => $countryId, // ID value (mobile app checks country_id || country)
            'country_id' => $countryId, // ID value for backward compatibility
            'state' => $stateId, // ID value (mobile app checks state_id || state)
            'state_id' => $stateId, // ID value for backward compatibility
            'city' => $cityId, // ID value (mobile app checks city_id || city)
            'city_id' => $cityId, // ID value for backward compatibility
            'zipcode' => $user->zipcode ?? null,
            'avatar' => $avatar,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
        
        // Add nested objects with full details (for display purposes)
        // Note: These will override the ID values above, but mobile app uses country_id for IDs
        if ($country) {
            $responseData['country'] = $country; // Object with name, id, code, phone_code
        }
        if ($state) {
            $responseData['state'] = $state; // Object with name, id, country_id
        }
        if ($city) {
            $responseData['city'] = $city; // Object with name, id, state_id, country_id
        }

        return response()->json([
            'success' => true,
            'data' => $responseData
        ]);
    }

    /**
     * Update user's profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'country_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
            'country' => 'nullable|exists:countries,id',
            'country_id' => 'nullable|exists:countries,id',
            'state' => 'nullable|exists:states,id',
            'state_id' => 'nullable|exists:states,id',
            'city' => 'nullable|exists:cities,id',
            'city_id' => 'nullable|exists:cities,id',
            'zipcode' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            
            // Handle phone/mobile - combine country_code and mobile if both provided
            // Format: "+92 300-123-4567" (matches web side format)
            $fullMobileNumber = null;
            if ($request->country_code && $request->mobile) {
                $fullMobileNumber = '+' . $request->country_code . ' ' . $request->mobile;
            } elseif ($request->has('mobile')) {
                $fullMobileNumber = $request->mobile;
            } elseif ($request->has('phone')) {
                $fullMobileNumber = $request->phone;
            }
            
            if ($fullMobileNumber !== null) {
                $user->mobile = $fullMobileNumber;
            }
            
            $user->address = $request->address ?? $user->address;
            
            // Handle country - accept both 'country' and 'country_id' fields
            $countryValue = $request->country ?? $request->country_id;
            if ($countryValue) {
                $user->country = $countryValue;
            }
            
            // Handle state - accept both 'state' and 'state_id' fields
            $stateValue = $request->state ?? $request->state_id;
            if ($stateValue) {
                $user->state = $stateValue;
            }
            
            // Handle city - accept both 'city' and 'city_id' fields
            $cityValue = $request->city ?? $request->city_id;
            if ($cityValue) {
                $user->city = $cityValue;
            }
            
            $user->zipcode = $request->zipcode ?? $user->zipcode;

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete('profile-photos/' . $user->profile_photo_path);
                }

                // Store avatar in profile-photos directory (matching web side)
                $avatarPath = $request->file('avatar')->store('profile-photos', 'public');
                // Extract just the filename (without directory)
                $filename = basename($avatarPath);
                $user->profile_photo_path = $filename;
            }

            $user->save();

            $locale = $request->get('locale', app()->getLocale());
            return $this->getProfile($request);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user's password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all countries for profile
     */
    public function getCountries(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $countries = Country::orderBy('name')->get()->map(function($country) use ($locale) {
            return [
                'id' => $country->id,
                'name' => $country->getTranslatedName($locale),
                'code' => $country->sortname,
                'phone_code' => $country->phonecode,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    /**
     * Get states by country for profile
     */
    public function getStatesByCountry(Request $request, $countryId)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $states = State::where('country_id', $countryId)
            ->orderBy('name')
            ->get()
            ->map(function($state) use ($locale) {
                return [
                    'id' => $state->id,
                    'name' => $state->getTranslatedName($locale),
                    'country_id' => $state->country_id,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $states
        ]);
    }

    /**
     * Get cities by state for profile
     */
    public function getCitiesByState(Request $request, $stateId)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $cities = City::where('state_id', $stateId)
            ->orderBy('name')
            ->get()
            ->map(function($city) use ($locale) {
                return [
                    'id' => $city->id,
                    'name' => $city->getTranslatedName($locale),
                    'state_id' => $city->state_id,
                    'country_id' => $city->country_id,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    /**
     * Get cities by country for profile
     */
    public function getCitiesByCountry(Request $request, $countryId)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $cities = City::where('country_id', $countryId)
            ->orderBy('name')
            ->get()
            ->map(function($city) use ($locale) {
                return [
                    'id' => $city->id,
                    'name' => $city->getTranslatedName($locale),
                    'state_id' => $city->state_id,
                    'country_id' => $city->country_id,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect'
            ], 422);
        }

        try {
            // Delete avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Delete user
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

