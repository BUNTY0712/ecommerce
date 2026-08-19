<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Display listing of orders with search and status filter.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $status = $request->input('status', '');

        $query = DB::table('orders');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status)) {
            $query->where('order_status', $status);
        }

        $orders = $query->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'search', 'status'));
    }

    /**
     * Display order details & itemized invoice.
     */
    public function show($id)
    {
        $orderId = (int) $id;

        $order = DB::table('orders')
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        $orderItems = DB::table('order_items')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->select('order_items.*', 'products.image as product_image', 'products.slug as product_slug')
            ->where('order_items.order_id', $orderId)
            ->get();

        return view('admin.orders.show', compact('order', 'orderItems'));
    }

    /**
     * Update order and payment status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,cod',
        ]);

        $orderId = (int) $id;

        $order = DB::table('orders')
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        DB::table('orders')
            ->where('id', $orderId)
            ->update([
                'order_status' => $request->input('order_status'),
                'payment_status' => $request->input('payment_status'),
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', "Order #{$order->order_number} status updated successfully!");
    }
}
