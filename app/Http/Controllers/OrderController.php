<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Show order details in user view
    public function show(Order $order)
    {
        $orderItems = OrderItem::where('order_id', $order->id)->get();


        return view('user.order-details', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);
    }

    // Generate and stream invoice PDF
    public function viewInvoice(Order $order)
    {
        $order->load('items.product'); // eager load for performance

        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ])->loadView('pdf.invoice', compact('order'));

        return $pdf->stream("invoice-{$order->order_number}.pdf");
    }
}
