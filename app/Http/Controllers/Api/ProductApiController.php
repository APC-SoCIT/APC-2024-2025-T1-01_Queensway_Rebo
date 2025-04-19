<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Product::all());
    }

    public function latest(): JsonResponse
    {
        $latestProducts = Product::latest()->take(6)->get();
        return response()->json($latestProducts);
    }

}
