<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display Admin Analytics Dashboard.
     */
    public function index()
    {
        // Calculate key metrics strictly using Query Builder
        $totalOrders = DB::table('orders')->count();
        
        $totalRevenue = DB::table('orders')
            ->whereIn('payment_status', ['paid', 'cod'])
            ->where('order_status', '!=', 'cancelled')
            ->sum('total_amount');

        $totalProducts = DB::table('products')->count();

        $outOfStockCount = DB::table('products')
            ->where('stock', '<=', 0)
            ->count();

        $pendingOrdersCount = DB::table('orders')
            ->where('order_status', 'pending')
            ->count();

        // Fetch recent 5 orders
        $recentOrders = DB::table('orders')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'outOfStockCount',
            'pendingOrdersCount',
            'recentOrders'
        ));
    }
}
