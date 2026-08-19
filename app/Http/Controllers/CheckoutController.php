<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Display shipping details page.
     */
    public function shipping()
    {
        $cartData = CartService::getValidatedCart();

        if (empty($cartData['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your shopping cart is empty.');
        }

        $shippingData = Session::get('checkout_shipping', []);

        return view('checkout.shipping', compact('cartData', 'shippingData'));
    }

    /**
     * Store customer shipping information in session after validation.
     */
    public function storeShipping(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        Session::put('checkout_shipping', $validated);

        return redirect()->route('checkout.payment');
    }
}
