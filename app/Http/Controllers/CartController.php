<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display cart page.
     */
    public function index()
    {
        $cartData = CartService::getValidatedCart();
        return view('cart.index', compact('cartData'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $productId = (int) $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        // Strictly verify product exists in DB using Query Builder
        $product = DB::table('products')
            ->where('id', $productId)
            ->where('status', 1)
            ->first();

        if (!$product) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
            }
            return redirect()->back()->with('error', 'Product not found.');
        }

        $result = CartService::add($productId, $quantity);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart_count' => CartService::getItemCount(),
            ]);
        }

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        if ($request->has('buy_now')) {
            return redirect()->route('cart.index')->with('success', 'Product added! Proceed to checkout.');
        }

        return redirect()->back()->with('success', $result['message']);
    }

    /**
     * Update item quantity in cart.
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        $productId = (int) $request->input('product_id');
        $quantity = (int) $request->input('quantity');

        $result = CartService::updateQuantity($productId, $quantity);

        if ($request->wantsJson()) {
            $cartData = CartService::getValidatedCart();
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cartData' => $cartData,
            ]);
        }

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    /**
     * Remove item from cart.
     */
    public function remove($product)
    {
        $productId = (int) $product;
        $result = CartService::remove($productId);

        return redirect()->route('cart.index')->with('success', $result['message']);
    }
}
