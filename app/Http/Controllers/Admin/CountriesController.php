<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountriesController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::query();

        // Search by name
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        // Search by ISO3 code
        if ($request->filled('search_iso3')) {
            $query->where('iso3', 'like', '%' . $request->search_iso3 . '%');
        }

        // Search by capital
        if ($request->filled('search_capital')) {
            $query->where('capital', 'like', '%' . $request->search_capital . '%');
        }

        // Search by currency
        if ($request->filled('search_currency')) {
            $query->where('currency', 'like', '%' . $request->search_currency . '%');
        }

        // Search by region
        if ($request->filled('search_region')) {
            $query->where('region', 'like', '%' . $request->search_region . '%');
        }

        $countries = $query->latest()->paginate(15);

        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'iso3' => 'nullable|string|max:3|unique:countries,iso3',
            'iso2' => 'nullable|string|max:2|unique:countries,iso2',
            'numeric_code' => 'nullable|string|max:3',
            'phonecode' => 'nullable|string|max:20',
            'capital' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'currency_name' => 'nullable|string|max:255',
            'currency_symbol' => 'nullable|string|max:10',
            'tld' => 'nullable|string|max:10',
            'native' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'subregion' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'emoji' => 'nullable|string|max:191',
            'emojiU' => 'nullable|string|max:191'
        ]);

        Country::create($request->all());

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country created successfully.');
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'iso3' => 'nullable|string|max:3|unique:countries,iso3,' . $id,
            'iso2' => 'nullable|string|max:2|unique:countries,iso2,' . $id,
            'numeric_code' => 'nullable|string|max:3',
            'phonecode' => 'nullable|string|max:20',
            'capital' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'currency_name' => 'nullable|string|max:255',
            'currency_symbol' => 'nullable|string|max:10',
            'tld' => 'nullable|string|max:10',
            'native' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'subregion' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'emoji' => 'nullable|string|max:191',
            'emojiU' => 'nullable|string|max:191'
        ]);

        $country = Country::findOrFail($id);
        $country->update($request->all());

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country updated successfully.');
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country deleted successfully.');
    }
}
