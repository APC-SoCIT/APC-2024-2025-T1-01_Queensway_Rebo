<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalRevenue = Order::sum('total_amount');
        $pendingOrders = Order::where('order_status', 'paid')->count();
        $totalProducts = Product::count();

        $rangeDays = 30;
        $salesData = $this->getSalesData($rangeDays);

        // Sorting params with defaults
        $sortBy = $request->input('sort_by', 'sales_count');
        $sortOrder = $request->input('sort_order', 'desc');

        // Validate sorting inputs
        $allowedSorts = ['name', 'price', 'sales_count'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'sales_count';
        }
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

        $search = $request->input('search');

        $query = Product::select('products.id', 'products.name', 'products.price', 
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as sales_count'))
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function ($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->where('orders.order_status', 'paid');
            })
            ->groupBy('products.id', 'products.name', 'products.price');

        if ($search) {
            $query->where('products.name', 'like', "%{$search}%");
        }

        $products = $query->orderBy($sortBy, $sortOrder)
                          ->paginate(10)
                          ->withQueryString();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRevenue',
            'pendingOrders',
            'totalProducts',
            'salesData',
            'products',
            'search',
            'sortBy',
            'sortOrder'
        ));
    }

    // The rest remains the same...

    public function salesData(Request $request)
    {
        $rangeDays = (int) $request->input('range', 30);
        $salesData = $this->getSalesData($rangeDays);

        return response()->json([
            'success' => true,
            'data' => $salesData
        ]);
    }

    private function getSalesData(int $days)
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $sales = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dates = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $dates[$date] = $sales->has($date) ? (float)$sales[$date]->revenue : 0;
        }

        return collect($dates)->map(fn($revenue, $date) => ['date' => $date, 'revenue' => $revenue])->values()->all();
    }
}
