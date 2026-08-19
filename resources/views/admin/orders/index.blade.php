@extends('layouts.admin')

@section('title', 'Manage Orders - Admin - StoreCraft')
@section('page_title', 'Orders Management')

@section('content')
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <!-- Search & Filter Form -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-6 col-lg-7">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control bg-light border-start-0 ps-0" 
                           placeholder="Search by order #, customer name, email, or phone..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary px-4">Search</button>
                </div>
            </div>

            <div class="col-md-6 col-lg-5">
                <select name="status" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="">All Order Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-4">Order #</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3 text-center">Payment</th>
                        <th class="py-3 text-center">Order Status</th>
                        <th class="py-3 text-end">Total</th>
                        <th class="py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-4 py-3 fw-bold text-dark">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-dark text-decoration-none hover-primary">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td class="small text-muted">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}
                            </td>
                            <td>
                                <div>
                                    <strong class="text-dark d-block">{{ $order->customer_name }}</strong>
                                    <span class="small text-muted">{{ $order->email }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="d-block text-uppercase small fw-semibold">{{ $order->payment_method }}</span>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success-subtle text-success fs-8">Paid</span>
                                @elseif($order->payment_status === 'cod')
                                    <span class="badge bg-info-subtle text-info fs-8">COD</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning fs-8">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-warning-subtle text-warning border-warning-subtle',
                                        'confirmed' => 'bg-info-subtle text-info border-info-subtle',
                                        'processing' => 'bg-primary-subtle text-primary border-primary-subtle',
                                        'shipped' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                        'delivered' => 'bg-success-subtle text-success border-success-subtle',
                                        'cancelled' => 'bg-danger-subtle text-danger border-danger-subtle',
                                    ];
                                    $badgeClass = $statusClasses[$order->order_status] ?? 'bg-light text-dark';
                                @endphp
                                <span class="badge {{ $badgeClass }} border px-2 py-1 text-capitalize fs-7">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark pe-3">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary fw-semibold">
                                    <i class="fa-solid fa-eye me-1"></i> Details & Status
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt fs-1 text-muted opacity-50 mb-2 d-block"></i>
                                No orders found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
