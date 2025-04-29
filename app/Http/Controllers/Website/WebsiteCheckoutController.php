<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class WebsiteCartController extends Controller
{
    // Display the shopping cart
// WebsiteCartController.php

    public function index()
    {
        $cartItems = session('cart', []);
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('website.cart', compact('cartItems', 'total'));
    }

    // Add a product to the cart
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Fetch the product from the database
        $product = Product::find($productId);

        if ($product) {
            // Get the current cart from the session
            $cart = session('cart', []);

            // Check if the product already exists in the cart
            if (isset($cart[$productId])) {
                // Update quantity if the product is already in the cart
                $cart[$productId]['quantity'] += $quantity;
            } else {
                // Add new product to the cart
                $cart[$productId] = [
                    'id' => $product->id,        // Add the product ID here
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'image' => $product->image,
                ];
            }

            // Store the updated cart in the session
            session(['cart' => $cart]);

            return redirect()->route('cart.index')->with('success', 'Product added to cart!');
        }

        return redirect()->route('product.show', $productId)->with('error', 'Product not found!');
    }


    // Update the quantity of a cart item
    public function update(Request $request)
    {
        $cart = session('cart', []);
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Update the quantity of the item in the cart
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session(['cart' => $cart]);

            $subtotal = $cart[$productId]['price'] * $quantity;
            $total = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));

            return response()->json([
                'success' => true,
                'subtotal' => $subtotal,
                'total' => $total,
            ]);
        }

        return response()->json(['success' => false]);
    }

    // Remove an item from the cart
    public function remove(Request $request)
    {
        $cart = session('cart', []);
        $productId = $request->input('product_id');

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);

            $total = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));

            return response()->json([
                'success' => true,
                'total' => $total,
            ]);
        }

        return response()->json(['success' => false]);
    }
}
