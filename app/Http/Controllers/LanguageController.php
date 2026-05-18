<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        $language = Language::where('code', $locale)->where('is_active', true)->first();
        
        if (!$language) {
            return redirect()->back()->with('error', 'Language not available!');
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return redirect()->back();
    }
}
