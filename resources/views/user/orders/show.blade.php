@extends('layouts.app')

@section('title', 'Order Details #' . $order->order_number . ' - StoreCraft')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb & Header -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.myOrders') }}" class="text-decoration-none text-muted">My Orders</a></li>
            <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">#{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                Order Details <span class="text-primary">#{{ $order->order_number }}</span>
            </h1>
            <p class="text-muted small mb-0">
                Placed on {{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y \a\t h:i A') }}
            </p>
        </div>
        <div>
            <a href="{{ route('orders.myOrders') }}" class="btn btn-outline-secondary fw-semibold shadow-xs">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to My Orders
            </a>
        </div>
    </div>

    <!-- Order Progress Tracker Timeline -->
    @php
        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
        $currentStatus = strtolower($order->order_status);
        $currentIndex = array_search($currentStatus, $statuses);
        if ($currentIndex === false) { $currentIndex = 0; }
        $isCancelled = ($currentStatus === 'cancelled');
    @endphp

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-dark mb-4">Order Progress Status</h6>
            
            @if($isCancelled)
                <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-0 rounded-3 py-3 px-4 d-flex align-items-center gap-3">
                    <i class="fa-solid fa-circle-xmark fs-3"></i>
                    <div>
                        <strong class="d-block">Order Cancelled</strong>
                        <span class="small">This order has been cancelled and will not be fulfilled. If you have questions, please contact support.</span>
                    </div>
                </div>
            @else
                <div class="position-relative px-2 px-md-5 my-3">
                    <!-- Progress Line -->
                    <div class="progress position-absolute top-50 start-0 end-0 translate-middle-y z-0" style="height: 4px; margin: 0 10%;">
                        <div class="progress-bar bg-primary" 
                             role="progressbar" 
                             style="width: {{ max(0, min(100, ($currentIndex / (count($statuses) - 1)) * 100)) }}%;" 
                             aria-valuenow="{{ $currentIndex }}" 
                             aria-valuemin="0" 
                             aria-valuemax="4"></div>
                    </div>

                    <!-- Steps Dots -->
                    <div class="d-flex justify-content-between position-relative z-1">
                        <!-- Step 1: Placed -->
                        <div class="text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm {{ $currentIndex >= 0 ? 'bg-primary text-white' : 'bg-light text-muted border' }}" style="width: 44px; height: 44px;">
                                <i class="fa-solid fa-file-invoice fs-6"></i>
                            </div>
                            <span class="d-block small fw-bold mt-2 {{ $currentIndex >= 0 ? 'text-primary' : 'text-muted' }}">Placed</span>
                        </div>

                        <!-- Step 2: Confirmed -->
                        <div class="text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm {{ $currentIndex >= 1 ? 'bg-primary text-white' : 'bg-light text-muted border' }}" style="width: 44px; height: 44px;">
                                <i class="fa-solid fa-circle-check fs-6"></i>
                            </div>
                            <span class="d-block small fw-bold mt-2 {{ $currentIndex >= 1 ? 'text-primary' : 'text-muted' }}">Confirmed</span>
                        </div>

                        <!-- Step 3: Processing -->
                        <div class="text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm {{ $currentIndex >= 2 ? 'bg-primary text-white' : 'bg-light text-muted border' }}" style="width: 44px; height: 44px;">
                                <i class="fa-solid fa-box-open fs-6"></i>
                            </div>
                            <span class="d-block small fw-bold mt-2 {{ $currentIndex >= 2 ? 'text-primary' : 'text-muted' }}">Processing</span>
                        </div>

                        <!-- Step 4: Shipped -->
                        <div class="text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm {{ $currentIndex >= 3 ? 'bg-primary text-white' : 'bg-light text-muted border' }}" style="width: 44px; height: 44px;">
                                <i class="fa-solid fa-truck-fast fs-6"></i>
                            </div>
                            <span class="d-block small fw-bold mt-2 {{ $currentIndex >= 3 ? 'text-primary' : 'text-muted' }}">Shipped</span>
                        </div>

                        <!-- Step 5: Delivered -->
                        <div class="text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm {{ $currentIndex >= 4 ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="width: 44px; height: 44px;">
                                <i class="fa-solid fa-house-chimney-check fs-6"></i>
                            </div>
                            <span class="d-block small fw-bold mt-2 {{ $currentIndex >= 4 ? 'text-success' : 'text-muted' }}">Delivered</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Ordered Items Table & Shipping Address -->
        <div class="col-lg-8">
            
            <!-- Ordered Items List Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-boxes-packing text-primary me-2"></i>Ordered Products ({{ $orderItems->count() }})
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-4 py-3">Product</th>
                                <th class="text-center py-3">Price</th>
                                <th class="text-center py-3">Quantity</th>
                                <th class="text-end pe-4 py-3">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($orderItems as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            @if(!empty($item->product_image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->product_image))
                                                <img src="{{ asset('storage/' . $item->product_image) }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="rounded-3 border object-fit-cover shadow-xs" 
                                                     style="width: 54px; height: 54px;">
                                            @else
                                                <div class="bg-light text-secondary rounded-3 border d-flex align-items-center justify-content-center" 
                                                     style="width: 54px; height: 54px;">
                                                    <i class="fa-solid fa-box fs-4"></i>
                                                </div>
                                            @endif
                                            <div>
                                                @if(!empty($item->product_slug))
                                                    <a href="{{ route('products.show', $item->product_slug) }}" class="fw-bold text-dark text-decoration-none hover-primary d-block">
                                                        {{ $item->product_name }}
                                                    </a>
                                                @else
                                                    <span class="fw-bold text-dark d-block">{{ $item->product_name }}</span>
                                                @endif
                                                <span class="text-muted small">Item ID: #{{ $item->product_id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-3 fw-semibold text-muted text-nowrap">
                                        ₹{{ number_format($item->price, 2) }}
                                    </td>
                                    <td class="text-center py-3 fw-bold text-dark">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="text-end pe-4 py-3 fw-extrabold text-primary text-nowrap">
                                        ₹{{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Shipping Address Details Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-truck-ramp-box text-primary me-2"></i>Shipping & Delivery Destination
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="text-muted small d-block mb-1">Recipient Name</span>
                            <strong class="text-dark fs-6 d-block">{{ $order->customer_name }}</strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block mb-1">Contact Information</span>
                            <span class="d-block text-dark fw-medium"><i class="fa-solid fa-phone me-1.5 text-muted small"></i> {{ $order->phone }}</span>
                            <span class="d-block text-dark fw-medium"><i class="fa-solid fa-envelope me-1.5 text-muted small"></i> {{ $order->email }}</span>
                        </div>
                        <div class="col-12"><hr class="my-1 text-subtle"></div>
                        <div class="col-12">
                            <span class="text-muted small d-block mb-1">Delivery Address</span>
                            <p class="text-dark fw-medium mb-0">
                                {{ $order->address }}
                                @if($order->address_line_2), {{ $order->address_line_2 }} @endif <br>
                                {{ $order->city }}, {{ $order->state }} - <strong>{{ $order->pincode }}</strong><br>
                                {{ $order->country }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Order Summary & Payment info -->
        <div class="col-lg-4">
            
            <!-- Order Information Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-circle-info text-primary me-2"></i>Order Summary
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3 small">
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Order Number</span>
                            <strong class="text-dark font-monospace">#{{ $order->order_number }}</strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Order Date</span>
                            <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Order Status</span>
                            @if($order->order_status === 'delivered')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-bold text-uppercase fs-7">
                                    Delivered
                                </span>
                            @elseif($order->order_status === 'shipped')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1 rounded-pill fw-bold text-uppercase fs-7">
                                    Shipped
                                </span>
                            @elseif($order->order_status === 'processing' || $order->order_status === 'confirmed')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fw-bold text-uppercase fs-7">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            @elseif($order->order_status === 'cancelled')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fw-bold text-uppercase fs-7">
                                    Cancelled
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 rounded-pill fw-bold text-uppercase fs-7">
                                    Pending
                                </span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Payment Method</span>
                            <strong class="text-uppercase text-dark">{{ $order->payment_method }}</strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Payment Status</span>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success text-white px-2 py-1 rounded-3">Paid</span>
                            @else
                                <span class="badge bg-secondary text-white px-2 py-1 rounded-3">{{ strtoupper($order->payment_status) }}</span>
                            @endif
                        </li>
                        @if($order->razorpay_payment_id)
                            <li class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Razorpay Payment ID</span>
                                <span class="text-dark font-monospace small text-truncate" style="max-width: 140px;">{{ $order->razorpay_payment_id }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Payment / Price Breakdown Card -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-receipt text-primary me-2"></i>Price Breakdown
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Items Subtotal</span>
                        <span class="fw-semibold text-dark">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small">Shipping Charge</span>
                        @if($order->shipping_charge == 0)
                            <span class="text-success fw-bold">FREE</span>
                        @else
                            <span class="fw-semibold text-dark">₹{{ number_format($order->shipping_charge, 2) }}</span>
                        @endif
                    </div>
                    <hr class="my-3 text-subtle">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">Grand Total</span>
                        <span class="fs-4 fw-extrabold text-primary">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
