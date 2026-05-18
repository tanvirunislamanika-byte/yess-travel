<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportsController extends Controller
{
    public function index(Request $request)
    {
        $query = Airport::orderBy('name', 'asc');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%$search%")
                  ->orWhere('city', 'like', "%$search%")
                  ->orWhere('iata_code', 'like', "%$search%")
                  ->orWhere('country', 'like', "%$search%");
        }

        $airports = $query->paginate(10);

        return view('admin.airports.index', compact('airports'));
    }

    public function create()
    {
        return view('admin.airports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'iata_code' => 'required|string|max:3|unique:airports',
            'country' => 'required|string|max:255'
        ]);

        Airport::create($request->all());

        return redirect()->route('admin.airports.index')
            ->with('success', 'Airport created successfully.');
    }

    public function edit($id)
    {
        $airport = Airport::findOrFail($id);
        return view('admin.airports.edit', compact('airport'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'iata_code' => 'required|string|max:3|unique:airports,iata_code,' . $id,
            'country' => 'required|string|max:255'
        ]);

        $airport = Airport::findOrFail($id);
        $airport->update($request->all());

        return redirect()->route('admin.airports.index')
            ->with('success', 'Airport updated successfully.');
    }

    public function destroy($id)
    {
        $airport = Airport::findOrFail($id);
        $airport->delete();

        return redirect()->route('admin.airports.index')
            ->with('success', 'Airport deleted successfully.');
    }
} 