<?php
namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = [
            'Tiles',
            'Vinyl',
            'Borders',
            'Mosaics',
            'Sanitary Wares',
            'Tile Adhesive, Grout & Epoxy',
            'Tools, Tile Spacers & Levelers'
        ];

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $product = new Product($request->only(['name', 'price', 'description', 'quantity', 'category']));

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = [
            'Tiles',
            'Vinyl',
            'Borders',
            'Mosaics',
            'Sanitary Wares',
            'Tile Adhesive, Grout & Epoxy',
            'Tools, Tile Spacers & Levelers'
        ];

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $product->fill($request->only(['name', 'price', 'description', 'quantity', 'category']));

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
