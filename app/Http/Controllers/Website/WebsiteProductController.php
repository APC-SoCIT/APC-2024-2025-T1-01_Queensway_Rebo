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
        // Get sort query parameter (default to 'latest')
        $sort = $request->query('sort', 'latest');

        // Prepare query
        $query = Product::query();

        // Apply sorting based on $sort value
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        // Get paginated products
        $products = $query->paginate(10);

        // Return the view with the products and sort variable
        return view('website.products', compact('products', 'sort'));
    }

    // Single product detail page
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('website.product-detail', compact('product'));
    }
}
