@extends('layouts.app')

@section('title', 'Order Placed Successfully - StoreCraft')

@section('content')
<div class="container px-3 px-sm-4">
    
    <!-- Checkout Steps -->
    <div class="checkout-steps mb-4 flex-wrap gap-2 gap-sm-4 justify-content-center">
        <div class="step-item">
            <div class="step-number"><i class="fa-solid fa-check fs-7"></i></div>
            <span class="text-nowrap">Shipping</span>
        </div>
        <div class="step-divider d-none d-sm-block"></div>
        <div class="step-item">
            <div class="step-number"><i class="fa-solid fa-check fs-7"></i></div>
            <span class="text-nowrap">Payment</span>
        </div>
        <div class="step-divider d-none d-sm-block"></div>
        <div class="step-item active">
            <div class="step-number"><i class="fa-solid fa-check fs-7"></i></div>
            <span class="text-nowrap">Success</span>
        </div>
    </div>

    <div class="row justify-content-center mb-4 mb-md-5">
        <div class="col-lg-8">
            <!-- Receipt Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                
                <!-- Success Banner Header -->
                <div class="text-white text-center py-4 py-sm-5 px-3 px-sm-4 position-relative" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="bg-white text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 72px; height: 72px;">
                        <i class="fa-solid fa-check fs-2"></i>
                    </div>
                    <h2 class="fw-extrabold mb-1 fs-3 fs-md-2">Order Placed Successfully!</h2>
                    <p class="mb-0 text-white-50 small fs-sm-6">Thank you for your purchase. We have received your order.</p>
                </div>

                <div class="card-body p-3 p-sm-4 p-md-5">
                    
                    <!-- Metadata Summary Bar -->
                    <div class="row g-2 g-sm-3 bg-light rounded-3 p-2.5 p-sm-3 mb-4 text-center border">
                        <div class="col-6 col-sm-3 border-end">
                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Order Reference</span>
                            <strong class="text-dark fs-7 fs-sm-6 text-truncate d-block">#{{ $order->order_number }}</strong>
                        </div>
                        <div class="col-6 col-sm-3 border-end-sm">
                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Payment Method</span>
                            <strong class="text-uppercase text-dark fs-7 fs-sm-6 text-truncate d-block">{{ $order->payment_method }}</strong>
                        </div>
                        <div class="col-6 col-sm-3 border-end">
                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Payment Status</span>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold" style="font-size: 0.72rem;">Paid</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-bold" style="font-size: 0.72rem;">COD</span>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Total Paid</span>
                            <strong class="text-primary fs-7 fs-sm-6 text-truncate d-block">₹{{ number_format($order->total_amount, 2) }}</strong>
                        </div>
                    </div>

                    <!-- Itemized Invoice Table -->
                    <h6 class="fw-bold text-dark mb-3 fs-6">Itemized Receipt</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th class="py-2">Product Item</th>
                                    <th class="text-center py-2">Unit Price</th>
                                    <th class="text-center py-2">Qty</th>
                                    <th class="text-end py-2">Total</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @foreach($orderItems as $item)
                                    <tr>
                                        <td class="py-2">
                                            <span class="fw-semibold text-dark d-block text-truncate" style="max-width: 200px;">{{ $item->product_name }}</span>
                                        </td>
                                        <td class="text-center py-2 text-nowrap">₹{{ number_format($item->price, 2) }}</td>
                                        <td class="text-center py-2">{{ $item->quantity }}</td>
                                        <td class="text-end py-2 fw-bold text-nowrap">₹{{ number_format($item->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="small">
                                <tr>
                                    <td colspan="3" class="text-end fw-semibold py-2">Subtotal:</td>
                                    <td class="text-end py-2 text-nowrap">₹{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-semibold py-2">Shipping Charge:</td>
                                    <td class="text-end py-2 text-nowrap">
                                        @if($order->shipping_charge == 0)
                                            <span class="text-success fw-bold">FREE</span>
                                        @else
                                            ₹{{ number_format($order->shipping_charge, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end fw-bold py-2 fs-6">Grand Total:</td>
                                    <td class="text-end fw-bold py-2 fs-6 text-primary text-nowrap">₹{{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Shipping Address Card -->
                    <div class="border rounded-3 p-3 p-sm-4 mb-4 bg-light">
                        <h6 class="fw-bold text-dark mb-2 fs-6">
                            <i class="fa-solid fa-location-dot me-2 text-primary"></i> Shipping & Delivery Details
                        </h6>
                        <p class="mb-0 text-secondary small" style="font-size: 0.825rem;">
                            <strong>{{ $order->customer_name }}</strong><br>
                            Email: {{ $order->email }} | Phone: {{ $order->phone }}<br>
                            {{ $order->address }} {{ $order->address_line_2 ? ', ' . $order->address_line_2 : '' }}<br>
                            {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}, {{ $order->country }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 gap-sm-3">
                        <button onclick="window.print()" class="btn btn-outline-secondary px-4 py-2.5 fw-semibold text-nowrap w-100 w-sm-auto text-center">
                            <i class="fa-solid fa-print me-1"></i> Print Invoice
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-primary px-4 px-sm-5 py-2.5 fw-bold shadow-sm text-nowrap w-100 w-sm-auto text-center">
                            <i class="fa-solid fa-store me-2"></i> Continue Shopping
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
