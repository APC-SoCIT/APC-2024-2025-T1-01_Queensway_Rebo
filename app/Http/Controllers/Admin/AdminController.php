<?php


namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    // public function dashboard()
    // {
    //     // Fetch the data for the dashboard
    //     $totalUsers = User::count();
    //     $totalRevenue = Order::where('order_status', 'paid')->sum('total_amount');
    //     $pendingOrders = Order::where('order_status', 'pending')->count();
    //     $totalProducts = Product::count();

    //     // Pass the data to the view
    //     return view('admin.dashboard', compact('totalUsers', 'totalRevenue', 'pendingOrders', 'totalProducts'));
    // }
}