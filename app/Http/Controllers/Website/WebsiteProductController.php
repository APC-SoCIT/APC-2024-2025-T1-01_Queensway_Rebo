<?php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WebsiteProductController extends Controller
{
    // Landing page - Display 6 latest products
    public function landingPage()
    {
        $latestProducts = Product::latest()->take(6)->get(); // Fetch 6 latest products
        return view('welcome', compact('latestProducts')); // Pass variable to the welcome view
    }

    // Products page - Display all products with sorting and pagination   // Shop page - Display all products
    public function products(Request $request)
    {
        $sort = $request->query('sort', 'latest');
        $category = $request->query('category'); // <-- get category filter from query string

        $query = Product::query();

        // Apply category filter
        if ($category) {
            $query->where('category', $category);
        }

        // Apply sorting
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(10)->appends($request->query()); // keep filters in pagination links

        return view('website.products', compact('products', 'sort', 'category'));
    }


    // Single product detail page
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('website.product-detail', compact('product'));
    }


}
