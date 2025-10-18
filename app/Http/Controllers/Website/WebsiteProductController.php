<?php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class WebsiteProductController extends Controller
{
    // Landing page - Display 6 latest products
    public function landingPage()
    {
        $latestProducts = Product::latest()->take(6)->get();

        // ✅ Get 3 unique users’ latest & highest-rated reviews
        $reviews = Review::select('reviews.*')
            ->join(
                DB::raw('(SELECT user_id, MAX(created_at) AS latest_review 
                         FROM reviews 
                         GROUP BY user_id) AS latest_per_user'),
                function ($join) {
                    $join->on('reviews.user_id', '=', 'latest_per_user.user_id')
                        ->on('reviews.created_at', '=', 'latest_per_user.latest_review');
                }
            )
            ->with('user')
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('welcome', compact('latestProducts', 'reviews'));
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

        // Map categories to tutorial videos
        $videoMap = [
            'Tiles' => [
                'Tiles/QUEENSWAY Vinyl Tiles Installation.mp4',
            ],
            'WPC Panels & Wall Cladding' => [
                'WPC Panels & Wall Cladding/QUEENSWAY WPC Flutted Wall Panel Installation.mp4',
                'WPC Panels & Wall Cladding/QUEENSWAY Parametric Wall Cladding Installation.mp4',
                'WPC Panels & Wall Cladding/QUEENSWAY PVC Laminate Ceiling Panel Installation.mp4'
            ],
            'Vinyl' => [
                'Vinyl/QUEENSWAY Vinyl Tiles Installation.mp4',
            ],
            'Borders' => [],
            'Mosaics' => [],
            'Sanitary Wares' => [],
            'Tile Adhesive, Grout & Epoxy' => [],
            'Tools, Tile Spacers & Levelers' => [],
        ];

        $tutorialVideos = $videoMap[$product->category] ?? [];

        return view('website.product-detail', compact('product', 'relatedProducts', 'tutorialVideos'));
    }



}
