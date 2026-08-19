@extends('layouts.app')

@section('title', 'Order Placed Successfully - StoreCraft')

@section('content')
<div class="container">
    
    <!-- Checkout Steps -->
    <div class="checkout-steps">
        <div class="step-item">
            <div class="step-number"><i class="fa-solid fa-check fs-7"></i></div>
            <span>Shipping</span>
        </div>
        <div class="step-divider"></div>
        <div class="step-item">
            <div class="step-number"><i class="fa-solid fa-check fs-7"></i></div>
            <span>Payment</span>
        </div>
        <div class="step-divider"></div>
        <div class="step-item active">
            <div class="step-number"><i class="fa-solid fa-check fs-7"></i></div>
            <span>Success</span>
        </div>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <!-- Receipt Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                
                <!-- Success Banner Header -->
                <div class="text-white text-center py-5 px-4 position-relative" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="bg-white text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 84px; height: 84px;">
                        <i class="fa-solid fa-check display-5"></i>
                    </div>
                    <h2 class="fw-extrabold mb-1">Order Placed Successfully!</h2>
                    <p class="mb-0 text-white-50 fs-6">Thank you for your purchase. We have received your order.</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    <!-- Metadata Summary Bar -->
                    <div class="row g-3 bg-light rounded-3 p-3 mb-4 text-center border">
                        <div class="col-6 col-sm-3 border-end">
                            <span class="text-muted small d-block">Order Reference</span>
                            <strong class="text-dark fs-6">#{{ $order->order_number }}</strong>
                        </div>
                        <div class="col-6 col-sm-3 border-end-sm">
                            <span class="text-muted small d-block">Payment Method</span>
                            <strong class="text-uppercase text-dark fs-6">{{ $order->payment_method }}</strong>
                        </div>
                        <div class="col-6 col-sm-3 border-end">
                            <span class="text-muted small d-block">Payment Status</span>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">Paid</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-bold">Cash on Delivery</span>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <span class="text-muted small d-block">Total Paid</span>
                            <strong class="text-primary fs-6">₹{{ number_format($order->total_amount, 2) }}</strong>
                        </div>
                    </div>

                    <!-- Itemized Invoice Table -->
                    <h6 class="fw-bold text-dark mb-3">Itemized Receipt</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Item</th>
                                    <th class="text-center">Unit Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderItems as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $item->product_name }}</span>
                                        </td>
                                        <td class="text-center">₹{{ number_format($item->price, 2) }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end fw-bold">₹{{ number_format($item->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-semibold">Subtotal:</td>
                                    <td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-semibold">Shipping Charge:</td>
                                    <td class="text-end">
                                        @if($order->shipping_charge == 0)
                                            <span class="text-success fw-bold">FREE</span>
                                        @else
                                            ₹{{ number_format($order->shipping_charge, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end fw-bold fs-6">Grand Total:</td>
                                    <td class="text-end fw-bold fs-5 text-primary">₹{{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Shipping Address Card -->
                    <div class="border rounded-3 p-4 mb-4 bg-light">
                        <h6 class="fw-bold text-dark mb-2">
                            <i class="fa-solid fa-location-dot me-2 text-primary"></i> Shipping & Delivery Details
                        </h6>
                        <p class="mb-0 text-secondary small">
                            <strong>{{ $order->customer_name }}</strong><br>
                            Email: {{ $order->email }} | Phone: {{ $order->phone }}<br>
                            {{ $order->address }} {{ $order->address_line_2 ? ', ' . $order->address_line_2 : '' }}<br>
                            {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}, {{ $order->country }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <button onclick="window.print()" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
                            <i class="fa-solid fa-print me-1"></i> Print Invoice
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-store me-2"></i> Continue Shopping
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
