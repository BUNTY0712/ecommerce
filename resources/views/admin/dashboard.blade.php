@extends('layouts.admin')

@section('title', 'Admin Dashboard - StoreCraft')
@section('page_title', 'Dashboard & Store Analytics')

@section('content')
<!-- Analytics Metric Cards -->
<div class="row g-4 mb-4">
    <!-- Card 1: Total Revenue -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">TOTAL REVENUE</span>
                <h3 class="fw-extrabold text-dark mb-0">₹{{ number_format($totalRevenue, 2) }}</h3>
                <span class="text-success small fw-semibold"><i class="fa-solid fa-arrow-trend-up me-1"></i> Paid & COD Orders</span>
            </div>
            <div class="stat-icon bg-primary-subtle text-primary">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Orders -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">TOTAL ORDERS</span>
                <h3 class="fw-extrabold text-dark mb-0">{{ $totalOrders }}</h3>
                <span class="text-info small fw-semibold"><i class="fa-solid fa-clock me-1"></i> {{ $pendingOrdersCount }} Pending</span>
            </div>
            <div class="stat-icon bg-info-subtle text-info">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Products -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">CATALOG PRODUCTS</span>
                <h3 class="fw-extrabold text-dark mb-0">{{ $totalProducts }}</h3>
                <span class="text-secondary small fw-semibold"><i class="fa-solid fa-box me-1"></i> Active Inventory</span>
            </div>
            <div class="stat-icon bg-success-subtle text-success">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>
    </div>

    <!-- Card 4: Out of Stock -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">OUT OF STOCK</span>
                <h3 class="fw-extrabold text-dark mb-0">{{ $outOfStockCount }}</h3>
                <span class="text-danger small fw-semibold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Requires Restock</span>
            </div>
            <div class="stat-icon bg-danger-subtle text-danger">
                <i class="fa-solid fa-box-open"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Shortcuts Bar -->
<div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-4 shadow-sm border">
    <div class="fw-bold text-dark">
        <i class="fa-solid fa-bolt me-2 text-warning"></i> Quick Actions
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm fw-semibold">
            <i class="fa-solid fa-plus me-1"></i> Add New Product
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
            <i class="fa-solid fa-list-check me-1"></i> Manage Orders
        </a>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 px-4 border-0 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0 text-dark">Recent Customer Orders</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-link btn-sm text-decoration-none fw-semibold">
            View All Orders <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-4">Order #</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3 text-center">Payment Method</th>
                        <th class="py-3 text-center">Payment Status</th>
                        <th class="py-3 text-center">Order Status</th>
                        <th class="py-3 text-end">Total Amount</th>
                        <th class="py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="px-4 py-3 fw-bold text-dark">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-dark text-decoration-none hover-primary">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div>
                                    <strong class="text-dark d-block">{{ $order->customer_name }}</strong>
                                    <span class="small text-muted">{{ $order->email }}</span>
                                </div>
                            </td>
                            <td class="text-center text-uppercase fw-semibold small">
                                {{ $order->payment_method }}
                            </td>
                            <td class="text-center">
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Paid</span>
                                @elseif($order->payment_status === 'cod')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">COD</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 text-capitalize">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary fw-semibold">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No orders recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
