<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;

class StatesController extends Controller
{
    public function index(Request $request)
    {
        $query = State::with('country');

        // Search by name
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        // Search by country
        if ($request->filled('search_country')) {
            $query->where('country_id', $request->search_country);
        }

        // Search by state code
        if ($request->filled('search_state_code')) {
            $query->where('state_code', 'like', '%' . $request->search_state_code . '%');
        }

        // Search by type
        if ($request->filled('search_type')) {
            $query->where('type', 'like', '%' . $request->search_type . '%');
        }

        $states = $query->latest()->paginate(15);
        $countries = Country::orderBy('name')->pluck('name', 'id');

        return view('admin.states.index', compact('states', 'countries'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->pluck('name', 'id');
        return view('admin.states.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'state_code' => 'nullable|string|max:10',
            'type' => 'nullable|string|max:191',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        State::create($request->all());

        return redirect()->route('admin.states.index')
            ->with('success', 'State created successfully.');
    }

    public function edit($id)
    {
        $state = State::findOrFail($id);
        $countries = Country::orderBy('name')->pluck('name', 'id');
        return view('admin.states.edit', compact('state', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'state_code' => 'nullable|string|max:10',
            'type' => 'nullable|string|max:191',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        $state = State::findOrFail($id);
        $state->update($request->all());

        return redirect()->route('admin.states.index')
            ->with('success', 'State updated successfully.');
    }

    public function destroy($id)
    {
        $state = State::findOrFail($id);
        $state->delete();

        return redirect()->route('admin.states.index')
            ->with('success', 'State deleted successfully.');
    }

    public function getStatesByCountry($countryId)
    {
        $states = State::where('country_id', $countryId)
            ->orderBy('name')
            ->pluck('name', 'id');
        
        return response()->json($states);
    }
}
