<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with(['state', 'country']);

        // Search by name
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        // Search by country
        if ($request->filled('search_country')) {
            $query->where('country_id', $request->search_country);
        }

        // Search by state
        if ($request->filled('search_state')) {
            $query->where('state_id', $request->search_state);
        }

        $cities = $query->latest()->paginate(15);
        $countries = Country::orderBy('name')->pluck('name', 'id');
        $states = State::orderBy('name')->pluck('name', 'id');

        return view('admin.cities.index', compact('cities', 'countries', 'states'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->pluck('name', 'id');
        $states = collect();
        return view('admin.cities.create', compact('countries', 'states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        City::create($request->all());

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully.');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $countries = Country::orderBy('name')->pluck('name', 'id');
        $states = State::where('country_id', $city->country_id)
            ->orderBy('name')
            ->pluck('name', 'id');
        
        return view('admin.cities.edit', compact('city', 'countries', 'states'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        $city = City::findOrFail($id);
        $city->update($request->all());

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully.');
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted successfully.');
    }

    public function getCitiesByState($stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->orderBy('name')
            ->pluck('name', 'id');
        
        return response()->json($cities);
    }
}
