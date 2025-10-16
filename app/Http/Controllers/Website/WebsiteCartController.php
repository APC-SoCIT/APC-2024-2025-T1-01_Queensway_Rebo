<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class WebsiteCartController extends Controller
{
    // Helper to get the cart (session for guests, DB for logged-in users)
    private function getCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['items' => []]
            );
        }
        return null;
    }

    // Display the shopping cart
    public function index()
    {
        // Cart items
        $cartItems = session('cart', []);

        $cartCount = count($cartItems);

        if (Auth::check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['items' => []]
            );
            $cartItems = $cart->items;
            $cartCount = count($cartItems);
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Pass cartCount to the view
        return view('website.cart', compact('cartItems', 'total', 'cartCount'));
    }

    // Add a product to the cart
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $product = Product::find($productId);
        if (!$product) {
            return redirect()->route('product.show', $productId)->with('error', 'Product not found!');
        }

        $cartItems = session('cart', []);
        if (isset($cartItems[$productId])) {
            $cartItems[$productId]['quantity'] += $quantity;
        } else {
            $cartItems[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
                'sku' => $product->sku,
            ];
        }

        session(['cart' => $cartItems]);

        if (Auth::check()) {
            $cart = $this->getCart();
            $cart->items = $cartItems;
            $cart->save();
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    // Update the quantity of a cart item
    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cartItems = session('cart', []);
        if (Auth::check()) {
            $cart = $this->getCart();
            $cartItems = $cart->items;
        }

        if (!isset($cartItems[$productId])) {
            return response()->json(['success' => false]);
        }

        $cartItems[$productId]['quantity'] = $quantity;
        session(['cart' => $cartItems]);

        if (Auth::check()) {
            $cart->items = $cartItems;
            $cart->save();
        }

        $subtotal = $cartItems[$productId]['price'] * $quantity;
        $total = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cartItems));

        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'total' => $total,
            'cartCount' => count($cartItems), // ✅ add this
        ]);

    }

    // Remove an item from the cart
    public function remove(Request $request)
    {
        $productId = $request->input('product_id');

        $cartItems = session('cart', []);
        if (Auth::check()) {
            $cart = $this->getCart();
            $cartItems = $cart->items;
        }

        if (!isset($cartItems[$productId])) {
            return response()->json(['success' => false]);
        }

        unset($cartItems[$productId]);
        session(['cart' => $cartItems]);

        if (Auth::check()) {
            $cart->items = $cartItems;
            $cart->save();
        }

        $total = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cartItems));

        return response()->json([
            'success' => true,
            'total' => $total,
            'cartCount' => count($cartItems), // ✅ add this
        ]);
    }
}
