<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Country;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        $countries = Country::whereNotNull('currency')
                           ->select('id', 'name', 'currency', 'currency_symbol', 'currency_name')
                           ->orderBy('name')
                           ->get();
        
        return view('admin.settings.index', compact('settings', 'countries'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'default_currency' => 'required|exists:countries,id',
        ]);

        // Get the selected country's currency details
        $selectedCountry = Country::find($request->default_currency);
        
        // Update currency settings
        Setting::setValue('default_currency', $selectedCountry->currency, 'string', 'Default currency code');
        Setting::setValue('default_currency_symbol', $selectedCountry->currency_symbol, 'string', 'Default currency symbol');
        Setting::setValue('default_currency_name', $selectedCountry->currency_name, 'string', 'Default currency name');
        Setting::setValue('default_currency_country_id', $selectedCountry->id, 'integer', 'Default currency country ID');

        return redirect()->route('admin.currency-settings.index')
            ->with('success', 'Currency settings updated successfully.');
    }
}
