<?php
// ─────────────────────────────────────────────────────────────────────────────
// FERRO — Web Routes
// Sitemap:
//   /                     Homepage (hero, featured products, brand story, waitlist)
//   /shop                 Product listing
//   /shop/{slug}          Product Detail Page (PDP) — bilingual
//   /about                Brand story & mission
//   /quiz                 Skincare quiz (Advanced Feature #2)
//   /waitlist             General waitlist landing page
//   /lang/{locale}        Language toggle endpoint
//   /cart                 Cart
//   /checkout             Checkout funnel (3 steps: info → shipping → payment)
//   /account              Customer account (orders, subscriptions, loyalty)
//   /orders/{number}      Order status tracking
//   /invoices/{number}    Invoice download
// ─────────────────────────────────────────────────────────────────────────────

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// ── Language toggle ────────────────────────────────────────────────────────
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
     ->name('lang.switch')
     ->where('locale', 'en|ar');

// ── Public storefront ──────────────────────────────────────────────────────
Route::get('/', HomeController::class)->name('home');

Route::get('/shop', [ProductController::class, 'index'])->name('products.index');
Route::get('/shop/{slug}', [ProductController::class, 'show'])->name('products.show');

// ── Lead capture ───────────────────────────────────────────────────────────
Route::post('/waitlist', [LeadController::class, 'storeWaitlist'])->name('waitlist.store');
Route::post('/quiz/capture', [LeadController::class, 'storeQuizLead'])->name('quiz.capture');
Route::post('/cart/abandon', [LeadController::class, 'trackAbandonedCart'])->name('cart.abandon');

// ── Static pages ───────────────────────────────────────────────────────────
Route::view('/about', 'about')->name('about');
Route::view('/quiz', 'quiz')->name('quiz');
Route::view('/contact', 'contact')->name('contact');

// ── Authenticated routes ───────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::view('/cart', 'cart')->name('cart');
    Route::view('/checkout', 'checkout.index')->name('checkout');
    Route::get('/account', fn () => view('account.index'))->name('account');
    Route::get('/orders/{orderNumber}', fn (string $orderNumber) => view('account.order', [
        'order' => \App\Models\Order::where('order_number', $orderNumber)
                    ->where('user_id', auth()->id())
                    ->firstOrFail(),
    ]))->name('orders.show');

    // Invoice PDF download
    Route::get('/invoices/{orderNumber}', function (string $orderNumber) {
        $order = \App\Models\Order::where('order_number', $orderNumber)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        if (! $order->invoice_pdf_path || ! \Storage::disk('local')->exists($order->invoice_pdf_path)) {
            abort(404, 'Invoice not found.');
        }

        return response()->download(
            storage_path('app/' . $order->invoice_pdf_path),
            "FERRO_Invoice_{$order->invoice_number}.pdf"
        );
    })->name('invoices.download');
});

// ── Admin portal ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Products — full CRUD + image management
        Route::resource('products', Admin\ProductController::class);
        Route::post('products/{product}/images', [Admin\ProductController::class, 'uploadImage'])->name('products.images.upload');
        Route::delete('products/{product}/images/{index}', [Admin\ProductController::class, 'deleteImage'])->name('products.images.delete');

        // Orders
        Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::get('orders/{order}/invoice', [Admin\OrderController::class, 'downloadInvoice'])->name('orders.invoice');

        // Users
        Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
        Route::patch('users/{user}/block', [Admin\UserController::class, 'block'])->name('users.block');
        Route::patch('users/{user}/unblock', [Admin\UserController::class, 'unblock'])->name('users.unblock');

        // Leads & Waitlist
        Route::get('leads', [Admin\LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/export', [Admin\LeadController::class, 'export'])->name('leads.export');
        Route::get('leads/waitlist/export', [Admin\LeadController::class, 'exportWaitlist'])->name('leads.waitlist.export');

        // Pages / CMS
        Route::resource('pages', Admin\PageController::class)->except(['show']);
    });
