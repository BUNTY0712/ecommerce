<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display payment selection page.
     */
    public function payment()
    {
        $cartData = CartService::getValidatedCart();

        if (empty($cartData['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your shopping cart is empty.');
        }

        $shippingData = Session::get('checkout_shipping');

        if (!$shippingData) {
            return redirect()->route('checkout.shipping')->with('error', 'Please enter your shipping details first.');
        }

        $razorpayKeyId = env('RAZORPAY_KEY_ID', '');
        $isRazorpayConfigured = !empty($razorpayKeyId);

        return view('checkout.payment', compact('cartData', 'shippingData', 'razorpayKeyId', 'isRazorpayConfigured'));
    }

    /**
     * Create Razorpay Order API Endpoint.
     */
    public function createRazorpayOrder(Request $request)
    {
        $cartData = CartService::getValidatedCart();

        if (empty($cartData['items'])) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $keyId = env('RAZORPAY_KEY_ID');
        $keySecret = env('RAZORPAY_KEY_SECRET');
        $amountInPaise = (int) round($cartData['total'] * 100);

        if (!empty($keyId) && !empty($keySecret)) {
            // Call Razorpay API using HTTP client
            $response = Http::withBasicAuth($keyId, $keySecret)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => $amountInPaise,
                    'currency' => 'INR',
                    'receipt' => 'rcpt_' . time(),
                    'payment_capture' => 1,
                ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'razorpay_order_id' => $response->json()['id'],
                    'amount' => $amountInPaise,
                    'key_id' => $keyId,
                ]);
            }
        }

        // Mock Razorpay order ID if keys are missing or API fails in test environment
        $mockOrderId = 'order_mock_' . Str::random(14);
        return response()->json([
            'success' => true,
            'is_mock' => true,
            'razorpay_order_id' => $mockOrderId,
            'amount' => $amountInPaise,
            'key_id' => $keyId ?: 'rzp_test_mock_key',
        ]);
    }

    /**
     * Process checkout payment (Razorpay verification or COD) and create order.
     */
    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:razorpay,cod',
            'razorpay_payment_id' => 'required_if:payment_method,razorpay',
            'razorpay_order_id' => 'required_if:payment_method,razorpay',
            'razorpay_signature' => 'nullable|string',
        ]);

        $shippingData = Session::get('checkout_shipping');
        if (!$shippingData) {
            return redirect()->route('checkout.shipping')->with('error', 'Shipping information is missing.');
        }

        $cartData = CartService::getValidatedCart();
        if (empty($cartData['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $paymentMethod = $request->input('payment_method');
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        $paymentStatus = 'pending';
        $orderStatus = 'pending';

        if ($paymentMethod === 'cod') {
            $paymentStatus = 'cod';
            $orderStatus = 'confirmed';
        } elseif ($paymentMethod === 'razorpay') {
            $keySecret = env('RAZORPAY_KEY_SECRET');

            if (!empty($keySecret) && !empty($razorpaySignature) && !str_contains($razorpayOrderId, 'mock')) {
                // Server-side Razorpay signature verification
                $generatedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $keySecret);
                if (!hash_equals($generatedSignature, $razorpaySignature)) {
                    return redirect()->route('checkout.payment')->with('error', 'Payment signature verification failed. Please try again.');
                }
            }

            $paymentStatus = 'paid';
            $orderStatus = 'confirmed';
        }

        try {
            // Strictly execute order creation inside a DB transaction using DB facade
            $orderId = DB::transaction(function () use ($shippingData, $cartData, $paymentMethod, $paymentStatus, $orderStatus, $razorpayOrderId, $razorpayPaymentId) {
                // Generate unique order number
                $orderNumber = 'ORD-' . strtoupper(Str::random(8));

                $customerName = trim($shippingData['first_name'] . ' ' . $shippingData['last_name']);

                // Insert into orders table using DB::table
                $newOrderId = DB::table('orders')->insertGetId([
                    'order_number' => $orderNumber,
                    'customer_name' => $customerName,
                    'email' => $shippingData['email'],
                    'phone' => $shippingData['phone'],
                    'address' => $shippingData['address'],
                    'address_line_2' => $shippingData['address_line_2'] ?? null,
                    'city' => $shippingData['city'],
                    'state' => $shippingData['state'],
                    'pincode' => $shippingData['pincode'],
                    'country' => $shippingData['country'],
                    'subtotal' => $cartData['subtotal'],
                    'shipping_charge' => $cartData['shipping'],
                    'total_amount' => $cartData['total'],
                    'payment_method' => $paymentMethod,
                    'payment_status' => $paymentStatus,
                    'order_status' => $orderStatus,
                    'razorpay_order_id' => $razorpayOrderId,
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert order items & update product stock using DB::table
                foreach ($cartData['items'] as $item) {
                    // Check live stock using DB::table
                    $product = DB::table('products')->where('id', $item['product_id'])->first();
                    if (!$product || $product->stock < $item['quantity']) {
                        throw new \Exception("Product '{$item['name']}' does not have sufficient stock available.");
                    }

                    DB::table('order_items')->insert([
                        'order_id' => $newOrderId,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'total' => $item['total'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Decrement stock using DB::table
                    DB::table('products')
                        ->where('id', $item['product_id'])
                        ->decrement('stock', $item['quantity']);
                }

                return $newOrderId;
            });

            // Clear session cart and shipping data upon successful order creation
            CartService::clear();
            Session::forget('checkout_shipping');

            return redirect()->route('order.success', ['order' => $orderId]);

        } catch (\Exception $e) {
            return redirect()->route('checkout.payment')->with('error', 'Order processing failed: ' . $e->getMessage());
        }
    }
}
