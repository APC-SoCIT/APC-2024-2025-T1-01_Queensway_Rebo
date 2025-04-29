<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;

class WebsiteProductController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('website.product-detail', compact('product'));
    }
}
