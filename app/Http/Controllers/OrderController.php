<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    // OrderController.php
    public function show(Order $order)
    {
        // Fetch order items related to this order, with their products
        $orderItems = OrderItem::where('order_id', $order->id)
                               ->with('product')
                               ->get();
    
        return view('user.order-details', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);
    }


    public function viewInvoice(Order $order)
    {
        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ])->loadView('pdf.invoice', compact('order'));


        return $pdf->stream("invoice-{$order->id}.pdf");
    }


}
