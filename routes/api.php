<?php
// routes/api.php
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductApiController::class, 'index']); // full list
Route::get('/products/latest', [ProductApiController::class, 'latest']); // only top 6

Route::get('/products', [ProductApiController::class, 'getAllProducts']); // Fetch paginated products



