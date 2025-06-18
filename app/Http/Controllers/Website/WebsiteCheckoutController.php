<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Mail\OrderInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class WebsiteCheckoutController extends Controller
{
    public function show()
    {
        $cartItems = session('cart', []);

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems)); // VAT-inclusive
        $subtotal = $total / 1.12; // Exclusive of VAT
        $vat = $total - $subtotal;
        $grandTotal = $total;

        return view('website.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'vat' => $vat,
            'grandTotal' => $grandTotal,
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
            $addressParts = [
                $data['shipping']['address_line_1'] ?? '',
                $data['shipping']['address_line_2'] ?? '',
                $data['shipping']['city'] ?? '',
                $data['shipping']['state'] ?? '',
                $data['shipping']['postal_code'] ?? '',
                $data['shipping']['country'] ?? '',
            ];

            $fullAddress = implode(', ', array_filter($addressParts));

            $order = Order::create([
                'order_number' => $uniqueOrderNumber,
                'user_id' => auth()->id(),
                'total_amount' => $data['totalAmount'], // Already VAT-inclusive
                'order_status' => 'paid',
                'payment_status' => 'completed',
                'paypal_order_id' => $data['orderID'],
                'recipient_name' => $data['shipping']['name'] ?? null,
                'shipping_address' => $fullAddress,
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::find($item['id']);
            
                if ($product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'sku' => $product->sku,
                        'product_image' => $product->image,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                    ]);
            
                    if ($product->quantity >= $item['quantity']) {
                        $product->quantity -= $item['quantity'];
                        $product->save();
                    }
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

        session()->forget('cart');

        $user = $order->user;

        if ($user) {
            $pdf = Pdf::setOptions([
                'defaultFont' => 'DejaVu Sans'
            ])->loadView('pdf.invoice', compact('order'));

            Mail::to($user->email)->send(new OrderInvoiceMail($order, $pdf));
        }

        return view('website.payment-success', compact('order'));
    }
}
