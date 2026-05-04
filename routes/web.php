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

use App\Http\Controllers\Admin;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CheckoutOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProductController;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// ── Language toggle ────────────────────────────────────────────────────────
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
    ->name('lang.switch')
    ->where('locale', 'en|ar');

// ── Authentication ─────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── Public storefront ──────────────────────────────────────────────────────
Route::get('/', HomeController::class)->name('home');

Route::get('/shop', [ProductController::class, 'index'])->name('products.index');
Route::get('/shop/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::view('/cart', 'cart')->name('cart');
Route::view('/checkout', 'checkout.index')->name('checkout');
Route::post('/checkout/order', [CheckoutOrderController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('checkout.order');
Route::get('/order/thanks/{order}', [CheckoutOrderController::class, 'thanks'])
    ->middleware('signed')
    ->name('order.thanks');

Route::prefix('api')->group(function () {
    Route::get('/cart/count', [CartController::class, 'count'])->name('api.cart.count');
    Route::post('/cart/add', [CartController::class, 'add'])->name('api.cart.add');
});

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
    Route::get('/account', function () {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('account.index', compact('orders'));
    })->name('account');
    Route::get('/orders/{orderNumber}', fn (string $orderNumber) => view('account.order', [
        'order' => Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail(),
    ]))->name('orders.show');

    // Invoice PDF download (always regenerates so the latest template is used)
    Route::get('/invoices/{orderNumber}', function (string $orderNumber) {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        try {
            app(InvoiceService::class)->generate($order->fresh(['items', 'user', 'lead']));
            $order->refresh();
        } catch (Throwable $e) {
            abort(503, 'Invoice is not available yet. Please try again later.');
        }

        return response()->download(
            Storage::disk('local')->path($order->invoice_pdf_path),
            "FERRO_Invoice_{$order->invoice_number}.pdf",
            ['Content-Type' => 'application/pdf']
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

        // Administrators (create staff + promote customers)
        Route::get('admins', [Admin\UserController::class, 'admins'])->name('admins.index');
        Route::get('admins/create', [Admin\UserController::class, 'createAdmin'])->name('admins.create');
        Route::post('admins', [Admin\UserController::class, 'storeAdmin'])->name('admins.store');

        // Admin privileges
        Route::patch('users/{user}/make-admin', [Admin\UserController::class, 'makeAdmin'])->name('users.make-admin');
        Route::patch('users/{user}/remove-admin', [Admin\UserController::class, 'removeAdmin'])->name('users.remove-admin');

        // Leads & Waitlist
        Route::get('leads', [Admin\LeadController::class, 'index'])->name('leads.index');
        Route::post('leads/waitlist', [Admin\LeadController::class, 'storeWaitlist'])->name('leads.waitlist.store');
        Route::get('leads/export', [Admin\LeadController::class, 'export'])->name('leads.export');
        Route::get('leads/waitlist/export', [Admin\LeadController::class, 'exportWaitlist'])->name('leads.waitlist.export');

        // Skin quiz submissions
        Route::get('quiz-responses', [Admin\QuizResponseController::class, 'index'])->name('quiz-responses.index');
        Route::get('quiz-responses/{quiz_session}', [Admin\QuizResponseController::class, 'show'])->name('quiz-responses.show');

        // Pages / CMS
        Route::resource('pages', Admin\PageController::class)->except(['show']);
    });
