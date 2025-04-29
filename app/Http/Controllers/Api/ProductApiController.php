<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();
    
        // Sorting
        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest(); // default
        }
    
        // Paginate
        $products = $query->paginate(10);
    
        return response()->json($products);
    }
    

    public function latest(): JsonResponse
    {
        $latestProducts = Product::latest()->take(6)->get();
        return response()->json($latestProducts);
    }

}
