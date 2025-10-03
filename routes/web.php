<?php

use App\Http\Controllers\Admin\AdminFaqBotController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Website\WebsiteFaqBotController;
use App\Http\Controllers\Website\WebsiteProductController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Website\WebsiteSearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Website\WebsiteCartController;
use App\Http\Controllers\Website\WebsiteCheckoutController;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Models\Admin;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\User\ForgotPasswordController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\Admin\AdminForgotPasswordController;
use App\Http\Controllers\Admin\AdminResetPasswordController;
use App\Http\Controllers\Admin\AdminManagementController;



// Public Routes
Route::get('/', [WebsiteProductController::class, 'landingPage'])->name('home');

// Product Pages
Route::get('product/{id}', [WebsiteProductController::class, 'show'])->name('product.show');
Route::get('/shop', [WebsiteProductController::class, 'products'])->name('products.index');

// User Authentication & Dashboard Routes
Route::prefix('user')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


});
Route::prefix('user')->middleware(['auth', 'verified'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [UserDashboardController::class, 'dashboard'])->name('user.dashboard');
    Route::get('account-info', [UserController::class, 'accountInfo'])->name('user.account');
Route::put('account-update-password', [UserController::class, 'updatePassword'])->name('user.update-password');

});

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('register', [AdminAuthController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('register', [AdminAuthController::class, 'register'])->name('admin.register.submit');
    Route::get('forgot-password', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('forgot-password', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('reset-password/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('reset-password', [AdminResetPasswordController::class, 'reset'])->name('admin.password.update');
    
});
// Admin Dashboard (only accessible for logged-in admins)
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Product Management
    Route::prefix('products')->name('admin.products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Order Management
    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::post('orders/{order}/mark-shipped', [AdminOrderController::class, 'markShipped'])->name('admin.orders.markShipped');
    Route::post('orders/{order}/mark-preparing', [AdminOrderController::class, 'markedPreparing'])->name('admin.orders.markPreparing');

    Route::resource('faqs', AdminFaqBotController::class)->names('admin.faqs');

    Route::prefix('admin-management')->name('admin.admin_management.')->group(function () {
        Route::get('/', [AdminManagementController::class, 'index'])->name('index');
        Route::get('/create', [AdminManagementController::class, 'create'])->name('create');
        Route::post('/', [AdminManagementController::class, 'store'])->name('store'); // Cleaner than '/store'
        Route::delete('/{admin}', [AdminManagementController::class, 'destroy'])->name('destroy');
    });

    Route::get('/account-info', [AdminController::class, 'accountInfo'])->name('admin.account-info');
    Route::put('/update-password', [AdminController::class, 'updatePassword'])->name('admin.update-password');
    
});


// Email Verification Routes
Route::post('/email/verification-notification', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->with('message', 'User not found.');
    }

    if ($user->hasVerifiedEmail()) {
        return back()->with('message', 'Email already verified.');
    }

    $user->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware('throttle:6,1')->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $user = User::findOrFail($id);
    if (!URL::hasValidSignature($request))
        abort(403, 'Invalid or expired verification link.');
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification())))
        abort(403, 'Invalid email hash.');
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }
    return redirect('/user/login')->with('message', 'Email verified successfully!');
})->middleware('signed')->name('verification.verify');

// Admin Email Verification Routes
Route::get('/admin/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $admin = Admin::findOrFail($id);
    if (!URL::hasValidSignature($request))
        abort(403, 'Invalid or expired verification link.');
    if (!hash_equals((string) $hash, sha1($admin->getEmailForVerification())))
        abort(403, 'Invalid email hash.');
    if (!$admin->hasVerifiedEmail()) {
        $admin->markEmailAsVerified();
        event(new Verified($admin));
    }
    return redirect('/admin/login')->with('message', 'Email verified successfully!');
})->middleware('signed')->name('admin.verification.verify');

Route::post('/admin/email/verification-notification', function (Request $request) {
    $admin = Admin::where('email', $request->email)->first();
    if (!$admin)
        return back()->with('message', 'No admin found with that email.');
    if ($admin->hasVerifiedEmail())
        return back()->with('message', 'Email already verified.');
    $admin->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware('throttle:6,1')->name('admin.verification.send');


// Cart Routes
Route::prefix('cart')->namespace('Website')->group(function () {
    Route::get('/', [WebsiteCartController::class, 'index'])->name('cart.index');
    Route::post('/add', [WebsiteCartController::class, 'add'])->name('cart.add');
    Route::post('/update', [WebsiteCartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [WebsiteCartController::class, 'remove'])->name('cart.remove');
});

// Checkout Routes
Route::get('/checkout', [WebsiteCheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout/complete', [WebsiteCheckoutController::class, 'complete'])->name('checkout.complete');

// Payment Success Route
Route::get('/payment-success', [WebsiteCheckoutController::class, 'paymentSuccess']);




Route::get('/faq-bot', function () {
    return view('website.faq-chat');
})->name('faq.bot.view');

Route::post('/faq-bot', [WebsiteFaqBotController::class, 'handle'])->name('faq.bot');


Route::get('/admin/dashboard/sales-data', [AdminDashboardController::class, 'salesData'])->name('admin.dashboard.salesData');


Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

Route::get('/order/{order}/invoice', [OrderController::class, 'viewInvoice'])->name('order.invoice');

Route::get('/tile-calculator', function () {
    return view('website.tile-calculator');
})->name('tile.calculator');

Route::get('/installation-videos', function () {
    return view('website.installation-videos');
})->name('installation.videos');




Route::post('/faq-bot', [WebsiteFaqBotController::class, 'handle'])->name('faq.bot');






Route::get('/', [WebsiteSearchController::class, 'index'])->name('home');

// Text search
Route::post('/search/text', [WebsiteSearchController::class, 'searchText'])->name('search.text');

// Image search
Route::post('/search/image', [WebsiteSearchController::class, 'searchImage'])->name('search.image');

