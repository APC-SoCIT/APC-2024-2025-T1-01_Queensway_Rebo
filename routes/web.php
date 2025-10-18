<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Admin;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AdminFaqBotController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Website\WebsiteFaqBotController;
use App\Http\Controllers\Website\WebsiteProductController;
use App\Http\Controllers\Website\WebsiteSearchController;
use App\Http\Controllers\Website\WebsiteCartController;
use App\Http\Controllers\Website\WebsiteCheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\UnifiedAuthController;
use App\Http\Controllers\Auth\UnifiedForgotPasswordController;
use App\Http\Controllers\Website\WebsiteReviewController;

// ==============================
// 🌐 PUBLIC ROUTES
// ==============================
Route::get('/', [WebsiteProductController::class, 'landingPage'])->name('home');
Route::get('product/{id}', [WebsiteProductController::class, 'show'])->name('product.show');
Route::get('/shop', [WebsiteProductController::class, 'products'])->name('products.index');

// ==============================
// 🔐 UNIFIED LOGIN / REGISTER
// ==============================
Route::get('/login', [UnifiedAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UnifiedAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [UnifiedAuthController::class, 'logout'])->name('logout');

// User Registration
Route::prefix('user')->group(function () {
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.submit');
});

// ==============================
// 🧩 UNIFIED PASSWORD RESET (User + Admin)
// ==============================

// User password reset
Route::get('password/reset', [UnifiedForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [UnifiedForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [UnifiedForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [UnifiedForgotPasswordController::class, 'reset'])->name('password.update');

// Admin password reset
Route::prefix('admin')->group(function () {
    Route::get('password/reset', [UnifiedForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('password/email', [UnifiedForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('password/reset/{token}', [UnifiedForgotPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('password/reset', [UnifiedForgotPasswordController::class, 'reset'])->name('admin.password.update');
});

// ==============================
// 👤 USER-PROTECTED ROUTES
// ==============================
Route::prefix('user')->middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [UserDashboardController::class, 'dashboard'])->name('user.dashboard');
    Route::get('account-info', [UserController::class, 'accountInfo'])->name('user.account');
    Route::put('account-update-password', [UserController::class, 'updatePassword'])->name('user.update-password');
});

// ==============================
// 🧑‍💼 ADMIN-PROTECTED ROUTES
// ==============================
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('logout', [UnifiedAuthController::class, 'logout'])->name('admin.logout');

    // Product Management
    Route::prefix('products')->name('admin.products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Orders
    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::post('orders/{order}/mark-shipped', [AdminOrderController::class, 'markShipped'])->name('admin.orders.markShipped');
    Route::post('orders/{order}/mark-preparing', [AdminOrderController::class, 'markedPreparing'])->name('admin.orders.markPreparing');

    // FAQ Management
    Route::resource('faqs', AdminFaqBotController::class)->names('admin.faqs');

    // Admin Management
    Route::prefix('admin-management')->name('admin.admin_management.')->group(function () {
        Route::get('/', [AdminManagementController::class, 'index'])->name('index');
        Route::get('/create', [AdminManagementController::class, 'create'])->name('create');
        Route::post('/', [AdminManagementController::class, 'store'])->name('store');
        Route::delete('/{admin}', [AdminManagementController::class, 'destroy'])->name('destroy');
    });

    // Admin Account Info
    Route::get('/account-info', [AdminController::class, 'accountInfo'])->name('admin.account-info');
    Route::put('/update-password', [AdminController::class, 'updatePassword'])->name('admin.update-password');
});

// ==============================
// ✉️ EMAIL VERIFICATION (User & Admin)
// ==============================

// User Verification
Route::post('/email/verification-notification', function (Request $request) {
    $user = User::where('email', $request->email)->first();
    if (!$user) return back()->with('message', 'User not found.');
    if ($user->hasVerifiedEmail()) return back()->with('message', 'Email already verified.');
    $user->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware('throttle:6,1')->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $user = User::findOrFail($id);
    if (!URL::hasValidSignature($request)) abort(403, 'Invalid or expired link.');
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) abort(403, 'Invalid email hash.');
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }
    return redirect('/login')->with('message', 'Email verified successfully!');
})->middleware('signed')->name('verification.verify');

// Admin Verification
Route::get('/admin/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $admin = Admin::findOrFail($id);
    if (!URL::hasValidSignature($request)) abort(403, 'Invalid or expired link.');
    if (!hash_equals((string) $hash, sha1($admin->getEmailForVerification()))) abort(403, 'Invalid email hash.');
    if (!$admin->hasVerifiedEmail()) {
        $admin->markEmailAsVerified();
        event(new Verified($admin));
    }
    return redirect('/login')->with('message', 'Admin email verified successfully!');
})->middleware('signed')->name('admin.verification.verify');

Route::post('/admin/email/verification-notification', function (Request $request) {
    $admin = Admin::where('email', $request->email)->first();
    if (!$admin) return back()->with('message', 'No admin found with that email.');
    if ($admin->hasVerifiedEmail()) return back()->with('message', 'Email already verified.');
    $admin->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware('throttle:6,1')->name('admin.verification.send');

// ==============================
// 🛒 CART & CHECKOUT
// ==============================
Route::prefix('cart')->group(function () {
    Route::get('/', [WebsiteCartController::class, 'index'])->name('cart.index');
    Route::post('/add', [WebsiteCartController::class, 'add'])->name('cart.add');
    Route::post('/update', [WebsiteCartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [WebsiteCartController::class, 'remove'])->name('cart.remove');
});

Route::get('/checkout', [WebsiteCheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout/complete', [WebsiteCheckoutController::class, 'complete'])->name('checkout.complete');
Route::get('/payment-success', [WebsiteCheckoutController::class, 'paymentSuccess']);

// ==============================
// 🔍 SEARCH & FAQ BOT
// ==============================
Route::get('/faq-bot', fn() => view('website.faq-chat'))->name('faq.bot.view');
Route::post('/faq-bot', [WebsiteFaqBotController::class, 'handle'])->name('faq.bot');

Route::get('/tile-calculator', fn() => view('website.tile-calculator'))->name('tile.calculator');
Route::get('/installation-videos', fn() => view('website.installation-videos'))->name('installation.videos');

// Search
Route::post('/search/text', [WebsiteSearchController::class, 'searchText'])->name('search.text');
Route::post('/search/image', [WebsiteSearchController::class, 'searchImage'])->name('search.image');

// ==============================
// 📦 ORDER VIEW
// ==============================
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/order/{order}/invoice', [OrderController::class, 'viewInvoice'])->name('order.invoice');

// Admin Dashboard Data
Route::get('/admin/dashboard/sales-data', [AdminDashboardController::class, 'salesData'])->name('admin.dashboard.salesData');

Route::post('/reviews', [WebsiteReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

Route::post('/ai-finder', [WebsiteSearchController::class, 'handleAiFinder'])->name('ai.finder');

