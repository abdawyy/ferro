<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminLanguageController extends Controller
{
    private const LOCALES = ['en', 'ar'];

    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, self::LOCALES, true)) {
            $locale = 'en';
        }

        session(['admin_locale' => $locale]);
        app()->setLocale($locale);

        return redirect()->back();
    }
}
