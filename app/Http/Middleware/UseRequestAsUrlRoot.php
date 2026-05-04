<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Aligns generated absolute URLs (route(), url(), asset with full URL) with the
 * actual request host/port/path so JS fetch targets the same origin as the page
 * (fixes APP_URL vs php artisan serve / reverse proxy mismatches).
 */
class UseRequestAsUrlRoot
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getHost() !== '') {
            $root = rtrim($request->getSchemeAndHttpHost().$request->getBasePath(), '/');
            URL::forceRootUrl($root);
        }

        return $next($request);
    }
}
