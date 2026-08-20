@extends('layouts.app')

@section('title', 'My Orders - StoreCraft')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb & Header -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">My Orders</li>
        </ol>
    </nav>

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="fa-solid fa-box-open text-primary me-2"></i>My Orders
            </h1>
            <p class="text-muted small mb-0">Manage and track your purchases and order status.</p>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary fw-semibold shadow-xs">
                <i class="fa-solid fa-cart-plus me-1.5"></i> Continue Shopping
            </a>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('orders.myOrders') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control bg-light border-start-0 shadow-none" 
                               placeholder="Search by Order # (e.g. ORD-12345)..." 
                               value="{{ $search }}">
                    </div>
                </div>

                <div class="col-md-4 col-lg-4">
                    <select name="status" class="form-select bg-light shadow-none" onchange="this.form.submit()">
                        <option value="">All Order Statuses</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-2 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-semibold flex-grow-1 shadow-xs">
                        Filter
                    </button>
                    @if($search || $status)
                        <a href="{{ route('orders.myOrders') }}" class="btn btn-light border text-muted" title="Reset Filters">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Listing -->
    @if($orders->count() > 0)
        <div class="d-flex flex-column gap-3 mb-4">
            @foreach($orders as $order)
                @php
                    $count = $itemCounts[$order->id] ?? 0;
                    $previews = $orderPreviews[$order->id] ?? collect();
                @endphp
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden product-card-hover">
                    <div class="card-header bg-light bg-gradient border-bottom py-3 px-3 px-md-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div>
                                <span class="text-muted small d-block" style="font-size: 0.75rem;">ORDER REFERENCE</span>
                                <a href="{{ route('orders.showUser', $order->id) }}" class="fw-bold text-dark text-decoration-none fs-6">
                                    #{{ $order->order_number }}
                                </a>
                            </div>
                            <span class="text-muted opacity-50 d-none d-sm-inline">|</span>
                            <div>
                                <span class="text-muted small d-block" style="font-size: 0.75rem;">PLACED ON</span>
                                <span class="fw-semibold text-secondary small">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y \a\t h:i A') }}
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <!-- Order Status Badge -->
                            @if($order->order_status === 'delivered')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill fw-bold text-uppercase fs-7">
                                    <i class="fa-solid fa-circle-check me-1"></i> Delivered
                                </span>
                            @elseif($order->order_status === 'shipped')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1.5 rounded-pill fw-bold text-uppercase fs-7">
                                    <i class="fa-solid fa-truck-fast me-1"></i> Shipped
                                </span>
                            @elseif($order->order_status === 'processing' || $order->order_status === 'confirmed')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 rounded-pill fw-bold text-uppercase fs-7">
                                    <i class="fa-solid fa-arrows-rotate me-1"></i> {{ ucfirst($order->order_status) }}
                                </span>
                            @elseif($order->order_status === 'cancelled')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded-pill fw-bold text-uppercase fs-7">
                                    <i class="fa-solid fa-ban me-1"></i> Cancelled
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1.5 rounded-pill fw-bold text-uppercase fs-7">
                                    <i class="fa-solid fa-clock me-1"></i> Pending
                                </span>
                            @endif

                            <!-- Payment Status Badge -->
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success text-white px-2 py-1 rounded-3 small" title="Paid">Paid</span>
                            @else
                                <span class="badge bg-secondary text-white px-2 py-1 rounded-3 small" title="{{ strtoupper($order->payment_method) }}">
                                    {{ strtoupper($order->payment_method) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-3 p-md-4">
                        <div class="row align-items-center g-3">
                            <!-- Left: Items Preview List -->
                            <div class="col-md-8 col-lg-8">
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    @foreach($previews->take(3) as $preview)
                                        <div class="d-flex align-items-center gap-2.5 bg-light p-2 rounded-3 border" style="max-width: 240px;">
                                            @if(!empty($preview->image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($preview->image))
                                                <img src="{{ asset('storage/' . $preview->image) }}" 
                                                     alt="{{ $preview->product_name }}" 
                                                     class="rounded-2 object-fit-cover" 
                                                     style="width: 42px; height: 42px;">
                                            @else
                                                <div class="bg-secondary-subtle text-secondary rounded-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 42px; height: 42px;">
                                                    <i class="fa-solid fa-box fs-5"></i>
                                                </div>
                                            @endif
                                            <div class="overflow-hidden">
                                                <span class="d-block fw-semibold text-dark small text-truncate" style="max-width: 160px;">
                                                    {{ $preview->product_name }}
                                                </span>
                                                <span class="text-muted small">Qty: {{ $preview->quantity }}</span>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($previews->count() > 3)
                                        <span class="badge bg-light text-secondary border px-3 py-2 rounded-3 fw-semibold">
                                            +{{ $previews->count() - 3 }} more item(s)
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Total Price & CTA -->
                            <div class="col-md-4 col-lg-4 text-md-end border-start-md pt-3 pt-md-0">
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Total Amount ({{ $count }} {{ Str::plural('item', $count) }})</span>
                                    <span class="fs-4 fw-extrabold text-primary">₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <a href="{{ route('orders.showUser', $order->id) }}" class="btn btn-outline-primary btn-sm fw-semibold rounded-3 px-3 shadow-xs">
                                    View Order Details <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top py-2 px-3 px-md-4 text-muted small d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fa-solid fa-location-dot me-1.5 text-secondary"></i>
                            Deliver to: <strong class="text-dark">{{ $order->customer_name }}</strong>, {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}
                        </div>
                        <div class="d-none d-sm-block">
                            Payment: <span class="text-uppercase fw-semibold text-dark">{{ $order->payment_method }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm rounded-4 text-center py-5 px-3">
            <div class="card-body py-4">
                <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-box-open fs-1"></i>
                </div>
                @if($search || $status)
                    <h4 class="fw-bold text-dark mb-2">No matching orders found</h4>
                    <p class="text-muted mb-4">We couldn't find any orders matching your selected filter or search criteria.</p>
                    <a href="{{ route('orders.myOrders') }}" class="btn btn-primary fw-semibold px-4 shadow-xs">
                        Clear Filters
                    </a>
                @else
                    <h4 class="fw-bold text-dark mb-2">You haven't placed any orders yet</h4>
                    <p class="text-muted mb-4" style="max-width: 480px; margin-left: auto; margin-right: auto;">
                        Looks like your order history is currently empty. Explore our full catalog and start shopping today!
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary fw-semibold px-4 py-2 rounded-3 shadow-sm">
                        <i class="fa-solid fa-bag-shopping me-2"></i> Start Shopping
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
