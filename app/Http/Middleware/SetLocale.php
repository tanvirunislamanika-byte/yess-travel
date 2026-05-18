<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session
        if (session()->has('locale')) {
            $locale = session('locale');
            
            // Validate that the locale exists in our languages table
            $language = \App\Models\Language::where('code', $locale)->where('is_active', true)->first();
            
            if ($language) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}
