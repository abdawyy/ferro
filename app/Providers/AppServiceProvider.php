<?php

namespace App\Providers;

use App\Models\ContactSetting;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Admin product routes use `{product}` by id; include soft-deleted so staff can edit / restore.
        Route::bind('product', function (string $value) {
            return Product::withTrashed()->whereKey($value)->firstOrFail();
        });

        View::composer(
            ['layouts.app', 'contact', 'partials.footer', 'partials.social-follow-links', 'emails._layout', 'pdf.invoice'],
            function ($view): void {
                $view->with('contactSetting', ContactSetting::current());
            }
        );
    }
}
