<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Mail\OrderInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class WebsiteCheckoutController extends Controller
{
    public function show()
    {
        $cartItems = session('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));

        return view('website.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'paypalClientId' => config('paypal.client_id'),
        ]);
    }


    public function complete(Request $request)
    {
        $data = $request->all();
        $uniqueOrderNumber = strtoupper(uniqid());
    
        if (!isset($data['items']) || !is_array($data['items']) || !isset($data['totalAmount']) || !isset($data['orderID'])) {
            return response()->json(['success' => false, 'message' => 'Invalid payload'], 400);
        }
    
        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $data['totalAmount'],
                'order_status' => 'paid',
                'payment_status' => 'completed',
                'paypal_order_id' => $data['orderID'],
            ]);
    
            foreach ($data['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);
    
                $product = Product::find($item['id']);
                if ($product && $product->quantity >= $item['quantity']) {
                    $product->quantity -= $item['quantity'];
                    $product->save();
                }
            }
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = Order::with('items')->where('paypal_order_id', $orderId)->firstOrFail();
    
        // Optional: Clear cart
        session()->forget('cart');
    
        $user = $order->user;

    
        // ✅ Generate and send PDF invoice
        if ($user) {
            $pdf = Pdf::loadView('pdf.invoice', compact('order'));
            Mail::to($user->email)->send(new OrderInvoiceMail($order, $pdf));
        }
    
        // Return the success view
        return view('website.payment-success', compact('order'));
    }

}
