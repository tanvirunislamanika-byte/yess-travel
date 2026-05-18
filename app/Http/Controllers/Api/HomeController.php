<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModulesData;

class HomeController extends Controller
{
    // Top Destinations
    public function destinations(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $locations = module(19); // Assuming module(19) returns destinations
        $data = [];

        if ($locations) {
            foreach ($locations as $location) {
                $num_hotels = ModulesData::where('extra_field_24', $location->id)->count();

                $data[] = [
                    'id' => $location->id,
                    'title' => $location->getTranslatedTitle($locale),
                    'image' => asset('images/' . $location->image),
                    'hotel_count' => $num_hotels,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'destinations' => $data
        ]);
    }

    // Top Airlines
    public function airlines(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $airlines = moduleF(4);
        $data = [];

        if ($airlines) {
            foreach ($airlines as $airline) {
                $data[] = [
                    'id' => $airline->id,
                    'title' => $airline->getTranslatedTitle($locale),
                    'image' => asset('images/' . $airline->image),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'airlines' => $data
        ]);
    }

    // Hotels by Cities
    public function hotelbycity()
    {
        $cities = toCitiesH(); // Assumed to return array of city names
        $data = [];

        if ($cities) {
            foreach ($cities as $city) {
                $data[] = [
                    'city' => $city,
                    'url' => url('/hotels?searchlocation=' . urlencode($city))
                ];
            }
        }

        return response()->json([
            'success' => true,
            'cities' => $data
        ]);
    }

    // Hotels by Countries
    public function hotelbycountry()
    {
        $countries = toCountriesH(); // Assumed to return associative array: key => country name
        $data = [];

        if ($countries) {
            foreach ($countries as $key => $country) {
                $data[] = [
                    'country_id' => $key,
                    'name' => $country,
                    'url' => url('/hotels?location=' . $key)
                ];
            }
        }

        return response()->json([
            'success' => true,
            'countries' => $data
        ]);
    }
}
