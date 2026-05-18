<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Get all countries with translations
     */
    public function getCountries(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $countries = Country::select('id', 'name', 'code', 'phone_code', 'flag')
            ->orderBy('name')
            ->get()
            ->map(function ($country) use ($locale) {
                return [
                    'id' => $country->id,
                    'name' => $country->getTranslatedName($locale),
                    'code' => $country->code,
                    'phone_code' => $country->phone_code,
                    'flag' => $country->flag,
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    /**
     * Get states by country ID with translations
     */
    public function getStatesByCountry($countryId, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $states = State::where('country_id', $countryId)
            ->select('id', 'name', 'country_id')
            ->orderBy('name')
            ->get()
            ->map(function ($state) use ($locale) {
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
     * Get cities by state ID with translations
     */
    public function getCitiesByState($stateId, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $cities = City::where('state_id', $stateId)
            ->select('id', 'name', 'state_id', 'country_id')
            ->orderBy('name')
            ->get()
            ->map(function ($city) use ($locale) {
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
     * Get cities by country ID with translations
     */
    public function getCitiesByCountry($countryId, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $cities = City::where('country_id', $countryId)
            ->select('id', 'name', 'state_id', 'country_id')
            ->orderBy('name')
            ->get()
            ->map(function ($city) use ($locale) {
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
     * Get country by ID with translations
     */
    public function getCountry($id, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $country = Country::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $country->id,
                'name' => $country->getTranslatedName($locale),
                'code' => $country->code,
                'phone_code' => $country->phone_code,
                'flag' => $country->flag,
            ]
        ]);
    }

    /**
     * Get state by ID with translations
     */
    public function getState($id, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $state = State::with('country')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $state->id,
                'name' => $state->getTranslatedName($locale),
                'country_id' => $state->country_id,
                'country' => [
                    'id' => $state->country->id,
                    'name' => $state->country->getTranslatedName($locale),
                    'code' => $state->country->code,
                ]
            ]
        ]);
    }

    /**
     * Get city by ID with translations
     */
    public function getCity($id, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        $city = City::with(['state.country'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $city->id,
                'name' => $city->getTranslatedName($locale),
                'state_id' => $city->state_id,
                'country_id' => $city->country_id,
                'state' => [
                    'id' => $city->state->id,
                    'name' => $city->state->getTranslatedName($locale),
                ],
                'country' => [
                    'id' => $city->state->country->id,
                    'name' => $city->state->country->getTranslatedName($locale),
                    'code' => $city->state->country->code,
                ]
            ]
        ]);
    }
}
