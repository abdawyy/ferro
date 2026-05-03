<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Language toggle controller.
 * Sets the locale via session — persists across all pages.
 * HTML <html> lang and dir attributes are set in the master layout
 * based on this session value.
 */
class LanguageController extends Controller
{
    private const SUPPORTED_LOCALES = ['en', 'ar'];

    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, self::SUPPORTED_LOCALES)) {
            $locale = 'en';
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        // Redirect back to referring page, maintaining full URL context
        // (e.g., if on /products/serum, stays on /products/serum)
        return redirect()->back()->withHeaders([
            'Vary' => 'Accept-Language',
        ]);
    }
}
