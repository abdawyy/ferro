<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Sets app locale from session on every request.
 * Must be registered in bootstrap/app.php middleware stack.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            $locale = session('admin_locale', 'en');
        } else {
            $locale = session('locale', config('app.locale', 'en'));
        }

        if (in_array($locale, ['en', 'ar'], true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
