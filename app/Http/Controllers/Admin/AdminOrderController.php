<?php
namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'user'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }
    

    public function markedPreparing(Order $order)
    {
        $order->order_status = 'preparing';
        $order->save();

        return redirect()->back()->with('success', 'Order marked as preparing.');
    }

    public function markShipped(Order $order)
    {
        $order->order_status = 'shipped';
        $order->save();

        return redirect()->back()->with('success', 'Order marked as shipped.');
    }
}
