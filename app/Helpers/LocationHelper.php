<?php

namespace App\Helpers;

use App\Models\Country;
use App\Models\State;
use App\Models\City;

class LocationHelper
{
    /**
     * Get countries for dropdown
     */
    public static function getCountries()
    {
        return Country::select('name', 'id')->orderBy('name')->pluck('name', 'id')->toArray();
    }

    /**
     * Get states by country for dropdown
     */
    public static function getStatesByCountry($countryId)
    {
        return State::where('country_id', $countryId)
                   ->select('name', 'id')
                   ->orderBy('name')
                   ->pluck('name', 'id')
                   ->toArray();
    }

    /**
     * Get cities by state for dropdown
     */
    public static function getCitiesByState($stateId)
    {
        return City::where('state_id', $stateId)
                  ->select('name', 'id')
                  ->orderBy('name')
                  ->pluck('name', 'id')
                  ->toArray();
    }

    /**
     * Get cities by country for dropdown
     */
    public static function getCitiesByCountry($countryId)
    {
        return City::where('country_id', $countryId)
                  ->select('name', 'id')
                  ->orderBy('name')
                  ->pluck('name', 'id')
                  ->toArray();
    }

    /**
     * Get location data for module extra fields
     */
    public static function getLocationDataForModule()
    {
        return [
            'countries' => self::getCountries(),
            'states' => self::getStatesByCountry(null), // Will be filtered by JS
            'cities' => self::getCitiesByState(null),   // Will be filtered by JS
        ];
    }
}
