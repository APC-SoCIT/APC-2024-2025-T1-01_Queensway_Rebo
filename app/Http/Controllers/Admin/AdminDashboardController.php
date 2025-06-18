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
    
        // Sorting and search
        $sortBy = $request->input('sort_by', 'sales_count');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSorts = ['name', 'price', 'sales_count'];
    
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'sales_count';
        }
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';
        $search = $request->input('search');
    
        // Main Product Query
        $query = Product::select(
            'products.id',
            'products.name',
            'products.price',
            'products.quantity',
            DB::raw('COALESCE(SUM(order_items.quantity), 0) as sales_count')
        )
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->leftJoin('orders', function ($join) {
            $join->on('order_items.order_id', '=', 'orders.id')
                 ->where('orders.order_status', 'paid');
        })
        ->groupBy('products.id', 'products.name', 'products.price', 'products.quantity');
    
        if ($search) {
            $query->where('products.name', 'like', "%{$search}%");
        }
    
        $products = $query->orderBy($sortBy, $sortOrder)->paginate(perPage: 10)->withQueryString();
    
        // Additional Lists for Tabs
        $topSellingProducts = (clone $query)->havingRaw('SUM(order_items.quantity) >= 50')->orderByDesc('sales_count')->limit(10)->get();
        $criticallyLowProducts = Product::where('quantity', '>', 0)->where('quantity', '<=', 50)->get();
        $outOfStockProducts = Product::where('quantity', 0)->get();
    
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRevenue',
            'pendingOrders',
            'totalProducts',
            'salesData',
            'products',
            'search',
            'sortBy',
            'sortOrder',
            'topSellingProducts',
            'criticallyLowProducts',
            'outOfStockProducts'
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
            $dates[$date] = $sales->has($date) ? (float) $sales[$date]->revenue : 0;
        }

        return collect($dates)->map(fn($revenue, $date) => ['date' => $date, 'revenue' => $revenue])->values()->all();
    }
}
