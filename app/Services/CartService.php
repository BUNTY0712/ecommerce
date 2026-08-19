<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const CART_KEY = 'shopping_cart';

    /**
     * Get raw cart array from session.
     */
    public static function getCart(): array
    {
        return Session::get(self::CART_KEY, []);
    }

    /**
     * Get total count of items in cart.
     */
    public static function getItemCount(): int
    {
        $cart = self::getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += (int) ($item['quantity'] ?? 0);
        }
        return $count;
    }

    /**
     * Get synchronized cart data with latest database prices and stock levels.
     */
    public static function getValidatedCart(): array
    {
        $cart = self::getCart();
        if (empty($cart)) {
            return [
                'items' => [],
                'subtotal' => 0.00,
                'shipping' => 0.00,
                'total' => 0.00,
                'item_count' => 0,
            ];
        }

        $productIds = array_keys($cart);

        // Strictly using DB::table as required
        $products = DB::table('products')
            ->whereIn('id', $productIds)
            ->where('status', 1)
            ->get()
            ->keyBy('id');

        $validatedItems = [];
        $subtotal = 0.00;
        $updatedCartSession = [];

        foreach ($cart as $productId => $item) {
            if (!$products->has($productId)) {
                // Product no longer exists or inactive -> automatically remove
                continue;
            }

            $product = $products->get($productId);

            if ($product->stock <= 0) {
                // Out of stock -> skip
                continue;
            }

            $effectivePrice = $product->discount_price && $product->discount_price < $product->price
                ? (float) $product->discount_price
                : (float) $product->price;

            $quantity = min((int) $item['quantity'], (int) $product->stock);

            if ($quantity <= 0) {
                continue;
            }

            $lineTotal = $effectivePrice * $quantity;
            $subtotal += $lineTotal;

            $itemData = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $effectivePrice,
                'original_price' => (float) $product->price,
                'quantity' => $quantity,
                'max_stock' => (int) $product->stock,
                'image' => $product->image,
                'total' => $lineTotal,
            ];

            $validatedItems[$product->id] = $itemData;
            $updatedCartSession[$product->id] = [
                'quantity' => $quantity,
            ];
        }

        // Update session to reflect validated state
        Session::put(self::CART_KEY, $updatedCartSession);

        $shipping = ShippingService::calculateCharge($subtotal);
        $total = $subtotal + $shipping;

        return [
            'items' => $validatedItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'item_count' => array_sum(array_column($validatedItems, 'quantity')),
        ];
    }

    /**
     * Add or update product in cart.
     */
    public static function add(int $productId, int $quantity = 1): array
    {
        // Using DB::table
        $product = DB::table('products')
            ->where('id', $productId)
            ->where('status', 1)
            ->first();

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found or unavailable.'];
        }

        if ($product->stock < 1) {
            return ['success' => false, 'message' => 'Sorry, this product is currently out of stock.'];
        }

        $cart = self::getCart();
        $currentQty = isset($cart[$productId]) ? (int) $cart[$productId]['quantity'] : 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->stock) {
            return [
                'success' => false,
                'message' => "Only {$product->stock} unit(s) available in stock. You already have {$currentQty} in cart.",
            ];
        }

        $cart[$productId] = [
            'quantity' => $newQty,
        ];

        Session::put(self::CART_KEY, $cart);

        return ['success' => true, 'message' => 'Product added to cart successfully!'];
    }

    /**
     * Update quantity for product.
     */
    public static function updateQuantity(int $productId, int $quantity): array
    {
        if ($quantity <= 0) {
            return self::remove($productId);
        }

        // Using DB::table
        $product = DB::table('products')
            ->where('id', $productId)
            ->where('status', 1)
            ->first();

        if (!$product) {
            self::remove($productId);
            return ['success' => false, 'message' => 'Product no longer available.'];
        }

        if ($quantity > $product->stock) {
            return [
                'success' => false,
                'message' => "Cannot request {$quantity} units. Only {$product->stock} in stock.",
            ];
        }

        $cart = self::getCart();
        $cart[$productId] = [
            'quantity' => $quantity,
        ];

        Session::put(self::CART_KEY, $cart);

        return ['success' => true, 'message' => 'Cart updated successfully.'];
    }

    /**
     * Remove item from cart.
     */
    public static function remove(int $productId): array
    {
        $cart = self::getCart();
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put(self::CART_KEY, $cart);
        }

        return ['success' => true, 'message' => 'Product removed from cart.'];
    }

    /**
     * Clear cart session.
     */
    public static function clear(): void
    {
        Session::forget(self::CART_KEY);
    }
}
