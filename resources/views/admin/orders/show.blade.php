@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number . ' - Admin - StoreCraft')
@section('page_title', 'Order Details #' . $order->order_number)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Orders List
    </a>
</div>

<div class="row g-4 mb-5">
    <!-- Left: Order Invoice & Items -->
    <div class="col-lg-8">
        <!-- Order Items Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-boxes-packing me-2 text-primary"></i> Ordered Products</h6>
                <span class="small text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-4">Item</th>
                                <th class="py-3 text-center">Unit Price</th>
                                <th class="py-3 text-center">Qty</th>
                                <th class="py-3 text-end pe-4">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderItems as $item)
                                @php
                                    $imagePath = $item->product_image
                                        ? (str_starts_with($item->product_image, 'http') ? $item->product_image : asset('storage/' . $item->product_image))
                                        : asset('storage/products/placeholder.svg');
                                @endphp
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $imagePath }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="rounded border bg-light" 
                                                 style="width: 50px; height: 50px; object-fit: contain;">
                                            <div>
                                                <strong class="text-dark d-block mb-0">{{ $item->product_name }}</strong>
                                                <span class="small text-muted">ID: {{ $item->product_id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">₹{{ number_format($item->price, 2) }}</td>
                                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold text-dark pe-4">₹{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Subtotal:</td>
                                <td class="text-end pe-4">₹{{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Shipping Charge:</td>
                                <td class="text-end pe-4">₹{{ number_format($order->shipping_charge, 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold fs-6">Grand Total:</td>
                                <td class="text-end fw-bold fs-5 text-primary pe-4">₹{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Information -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-truck me-2 text-primary"></i> Customer & Shipping Address</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <span class="text-muted small d-block">Customer Name</span>
                        <strong class="text-dark fs-6">{{ $order->customer_name }}</strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small d-block">Contact Info</span>
                        <span class="text-dark d-block">Email: {{ $order->email }}</span>
                        <span class="text-dark d-block">Phone: {{ $order->phone }}</span>
                    </div>
                    <div class="col-12 border-top pt-3 mt-2">
                        <span class="text-muted small d-block">Delivery Address</span>
                        <p class="mb-0 text-dark fw-medium">
                            {{ $order->address }} {{ $order->address_line_2 ? ', ' . $order->address_line_2 : '' }}<br>
                            {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}, {{ $order->country }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Update Order Status Panel -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-sliders me-2 text-primary"></i> Manage Order Status</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf

                    <!-- Order Fulfillment Status -->
                    <div class="mb-3">
                        <label for="order_status" class="form-label fw-semibold text-dark">Order Status</label>
                        <select name="order_status" id="order_status" class="form-select">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Payment Status -->
                    <div class="mb-4">
                        <label for="payment_status" class="form-label fw-semibold text-dark">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cod" {{ $order->payment_status === 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Update Status
                    </button>
                </form>

                <hr class="my-4">

                <!-- Payment Details metadata -->
                <div class="small text-secondary">
                    <span class="d-block mb-1"><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</span>
                    @if($order->razorpay_order_id)
                        <span class="d-block mb-1 text-truncate"><strong>Razorpay Order ID:</strong> {{ $order->razorpay_order_id }}</span>
                    @endif
                    @if($order->razorpay_payment_id)
                        <span class="d-block text-truncate"><strong>Razorpay Payment ID:</strong> {{ $order->razorpay_payment_id }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
