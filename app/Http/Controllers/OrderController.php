<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Display customer order history list.
     */
    public function userOrders(Request $request)
    {
        $user = Auth::user();
        $search = trim($request->input('search', ''));
        $status = $request->input('status', '');

        $query = DB::table('orders')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('email', $user->email);
            });

        if (!empty($search)) {
            $query->where('order_number', 'like', '%' . $search . '%');
        }

        if (!empty($status)) {
            $query->where('order_status', $status);
        }

        $orders = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Attach item counts and preview items for each order
        $orderIds = $orders->pluck('id')->toArray();
        $itemCounts = [];
        $orderPreviews = [];

        if (!empty($orderIds)) {
            $counts = DB::table('order_items')
                ->select('order_id', DB::raw('SUM(quantity) as total_items'))
                ->whereIn('order_id', $orderIds)
                ->groupBy('order_id')
                ->get();
            foreach ($counts as $c) {
                $itemCounts[$c->order_id] = $c->total_items;
            }

            $items = DB::table('order_items')
                ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
                ->select('order_items.order_id', 'order_items.product_name', 'order_items.quantity', 'products.image')
                ->whereIn('order_id', $orderIds)
                ->get()
                ->groupBy('order_id');

            foreach ($items as $ordId => $group) {
                $orderPreviews[$ordId] = $group;
            }
        }

        return view('user.orders.index', compact('orders', 'itemCounts', 'orderPreviews', 'search', 'status'));
    }

    /**
     * Display customer order details.
     */
    public function userOrderDetails($id)
    {
        $user = Auth::user();
        $orderId = (int) $id;

        $order = DB::table('orders')
            ->where('id', $orderId)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('email', $user->email);
            })
            ->first();

        if (!$order) {
            abort(404, 'Order not found or access denied.');
        }

        $orderItems = DB::table('order_items')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->select('order_items.*', 'products.image as product_image', 'products.slug as product_slug')
            ->where('order_items.order_id', $orderId)
            ->get();

        return view('user.orders.show', compact('order', 'orderItems'));
    }
}

