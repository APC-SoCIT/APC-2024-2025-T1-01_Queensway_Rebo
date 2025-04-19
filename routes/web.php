<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Website\WebsiteProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Models\Admin;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Website\WebsiteCartController;


// Home Page Route (Publicly accessible)
Route::get('/', function () {
    return view('welcome'); // Public homepage view
})->name('home');

// Products List (Publicly accessible)
Route::get('/products', [ProductController::class, 'getProducts'])->name('products');

// Show verify email notice
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Actual verification link
Route::get('/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $user = User::findOrFail($id);

    if (!URL::hasValidSignature($request)) {
        abort(403, 'Invalid or expired verification link.');
    }

    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid email hash.');
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }
    return redirect('/login')->with('message', 'Email verified successfully!');

})->middleware('signed')->name('verification.verify');


// Resend link
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Admin email verification route
Route::get('/admin/email/verify', function () {
    return view('admin.verify-email');
})->middleware('auth:admin')->name('admin.verification.notice');

// Admin email verification link
Route::get('/admin/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $admin = Admin::findOrFail($id);

    if (!URL::hasValidSignature($request)) {
        abort(403, 'Invalid or expired verification link.');
    }

    if (!hash_equals((string) $hash, sha1($admin->getEmailForVerification()))) {
        abort(403, 'Invalid email hash.');
    }

    if (!$admin->hasVerifiedEmail()) {
        $admin->markEmailAsVerified();
        event(new Verified($admin));
    }

    return redirect('/admin/login')->with('message', 'Email verified successfully!');
})->middleware('signed')->name('admin.verification.verify');

Route::post('/admin/email/verification-notification', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $admin = Admin::where('email', $request->email)->first();

    if (!$admin) {
        return back()->with('message', 'No admin found with that email.');
    }

    if ($admin->hasVerifiedEmail()) {
        return back()->with('message', 'Email already verified.');
    }

    $admin->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware('throttle:6,1')->name('admin.verification.send');



// User Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// User Registration
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.submit');

// User Dashboard (only accessible for logged-in users)
Route::middleware(['auth', 'verified'])->get('user/dashboard', function () {
    return view('user.dashboard');
})->name('user.dashboard');

// Admin Routes
Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::get('admin/register', [AdminAuthController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('admin/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');

// Admin Dashboard (only accessible for logged-in admins)
Route::middleware(['auth:admin'])->get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Show all products
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    
    // Show form to create a new product
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    
    // Store a new product
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    
    // Show form to edit a product
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    
    // Update a product
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    
    // Delete a product
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

Route::get('product/{id}', [WebsiteProductController::class, 'show'])->name('product.show');


// Cart routes
Route::prefix('cart')->namespace('Website')->group(function () {
    Route::get('/', [WebsiteCartController::class, 'index'])->name('cart.index');
    Route::post('/add', [WebsiteCartController::class, 'add'])->name('cart.add'); // Add product to cart
    Route::post('/update', [WebsiteCartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [WebsiteCartController::class, 'remove'])->name('cart.remove');
});

