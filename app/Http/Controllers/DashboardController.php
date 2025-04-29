<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

 
    public function dashboard()
    {
        $orders = Order::where('user_id', Auth::id())
                       ->with('items.product')
                       ->get();
    
        return view('user.dashboard', compact('orders'));
    }
    
    
    
}
