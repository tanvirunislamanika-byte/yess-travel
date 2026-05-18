<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $countries = Country::select('name', 'id')->orderBy('name')->pluck('name', 'id')->toArray();
        
        $states = [];
        $cities = [];
        
        if ($user->country) {
            $states = State::where('country_id', $user->country)
                ->select('name', 'id')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }
        
        if ($user->state) {
            $cities = City::where('state_id', $user->state)
                ->select('name', 'id')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }
        
        return view('profile.show', compact('countries', 'states', 'cities', 'user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'country_code' => 'nullable|string|max:10',
            'country' => 'nullable|exists:countries,id',
            'state' => 'nullable|exists:states,id',
            'city' => 'nullable|exists:cities,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            \Log::info('Photo upload started for user: ' . $user->id);
            \Log::info('File details: ' . $request->file('photo')->getClientOriginalName());
            \Log::info('File size: ' . $request->file('photo')->getSize());
            \Log::info('File mime type: ' . $request->file('photo')->getMimeType());
            
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                // Construct the full path from the filename stored in database
                $oldPhotoPath = public_path('profile-photos/' . $user->profile_photo_path);
                \Log::info('Old photo path: ' . $oldPhotoPath);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                    \Log::info('Old photo deleted');
                } else {
                    \Log::warning('Old photo file not found at: ' . $oldPhotoPath);
                }
            }
            
            // Store new photo in public folder
            $photo = $request->file('photo');
            $photoName = time() . '_' . $user->id . '.' . $photo->getClientOriginalExtension();
            
            \Log::info('New photo filename: ' . $photoName);
            \Log::info('Full upload path: ' . public_path('profile-photos') . '/' . $photoName);
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('profile-photos');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
                \Log::info('Created directory: ' . $uploadPath);
            }
            
            // Move photo to public folder
            try {
                $result = $photo->move($uploadPath, $photoName);
                \Log::info('Photo move result: ' . ($result ? 'Success' : 'Failed'));
                \Log::info('File moved to: ' . $uploadPath . '/' . $photoName);
                
                // Check if file actually exists
                if (file_exists($uploadPath . '/' . $photoName)) {
                    \Log::info('File confirmed to exist after move');
                } else {
                    \Log::error('File does not exist after move!');
                }
            } catch (\Exception $e) {
                \Log::error('Error moving photo: ' . $e->getMessage());
            }
            
            // Update user with just the filename (not the full path)
            $user->profile_photo_path = $photoName;
            \Log::info('User profile_photo_path updated to filename: ' . $photoName);
        }
        
        // Combine country code and mobile number
        $fullMobileNumber = null;
        if ($request->country_code && $request->mobile) {
            $fullMobileNumber = '+' . $request->country_code . ' ' . $request->mobile;
            \Log::info('Combined mobile number: ' . $fullMobileNumber);
        }
        
        $user->update([
            'name' => $request->name,
            'mobile' => $fullMobileNumber,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'profile_photo_path' => $user->profile_photo_path, // Include the photo path
        ]);
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    
    public function deleteProfilePhoto()
    {
        $user = Auth::user();
        
        if ($user->profile_photo_path) {
            // Construct the full path from the filename stored in database
            $fullPhotoPath = public_path('profile-photos/' . $user->profile_photo_path);
            \Log::info('Deleting photo at: ' . $fullPhotoPath);
            
            if (file_exists($fullPhotoPath)) {
                unlink($fullPhotoPath);
                \Log::info('Photo file deleted successfully');
            } else {
                \Log::warning('Photo file not found at: ' . $fullPhotoPath);
            }
            
            $user->update(['profile_photo_path' => null]);
            \Log::info('Profile photo path cleared from database');
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'No photo to delete']);
    }
    
    public function getStates(Request $request)
    {
        \Log::info('getStates called with country_id: ' . $request->country_id);
        
        $states = State::where('country_id', $request->country_id)
            ->select('name', 'id')
            ->orderBy('name')
            ->get();
            
        \Log::info('States found: ' . $states->count());
        
        return response()->json($states);
    }
    
    public function getCities(Request $request)
    {
        \Log::info('getCities called with state_id: ' . $request->state_id);
        
        $cities = City::where('state_id', $request->state_id)
            ->select('name', 'id')
            ->orderBy('name')
            ->get();
            
        \Log::info('Cities found: ' . $cities->count());
        
        return response()->json($cities);
    }
}
