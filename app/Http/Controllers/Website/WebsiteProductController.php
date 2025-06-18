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
        $categories = $request->query('categories');
    
        if ($categories) {
            $categories = is_array($categories) ? $categories : explode(',', $categories);
        }
    
        $query = Product::query();
    
        // Apply multiple category filters
        if (!empty($categories)) {
            $query->whereIn('category', $categories);
        }
    
        // Apply sorting
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }
    
        $products = $query->paginate(10)->appends($request->query());
    
        return view('website.products', [
            'products' => $products,
            'sort' => $sort,
            'category' => null,
            'selectedCategories' => $categories ?? [],
        ]);
    }
    
    

    // Single product detail page
    public function show($id)
    {
        $product = Product::findOrFail($id);
    
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
    
        return view('website.product-detail', compact('product', 'relatedProducts'));
    }
    


}
