<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display order success receipt page.
     */
    public function success($order)
    {
        $orderId = (int) $order;

        // Query order strictly using Query Builder DB facade
        $order = DB::table('orders')
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        // Query order items strictly using Query Builder DB facade
        $orderItems = DB::table('order_items')
            ->where('order_id', $orderId)
            ->get();

        return view('orders.success', compact('order', 'orderItems'));
    }
}
