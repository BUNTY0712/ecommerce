@extends('layouts.admin')

@section('title', 'Manage Products - Admin - StoreCraft')
@section('page_title', 'Products Catalog')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <p class="text-muted small mb-0">Manage all items in your store inventory</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add New Product
    </a>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-7 col-lg-8">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control bg-light border-start-0 ps-0" 
                           placeholder="Filter products by title..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary px-4">Filter</button>
                </div>
            </div>

            <div class="col-md-5 col-lg-4">
                <select name="category" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string)request('category') === (string)$cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Products Table Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-4">Product Info</th>
                        <th class="py-3">Category</th>
                        <th class="py-3 text-center">Regular Price</th>
                        <th class="py-3 text-center">Discount Price</th>
                        <th class="py-3 text-center">Stock Level</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            $imagePath = $product->image
                                ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                                : asset('storage/products/placeholder.svg');
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $imagePath }}" 
                                         alt="{{ $product->name }}" 
                                         class="rounded border bg-light" 
                                         style="width: 50px; height: 50px; object-fit: contain;"
                                         onerror="this.src='https://placehold.co/100x100/e2e8f0/475569?text=Image'">
                                    <div>
                                        <strong class="text-dark d-block mb-0">{{ $product->name }}</strong>
                                        <span class="small text-muted">ID: #{{ $product->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ $product->category_name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="text-center fw-semibold">
                                ₹{{ number_format($product->price, 2) }}
                            </td>
                            <td class="text-center">
                                @if($product->discount_price && $product->discount_price < $product->price)
                                    <span class="fw-bold text-success">₹{{ number_format($product->discount_price, 2) }}</span>
                                @else
                                    <span class="text-muted small">&mdash;</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($product->stock > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        {{ $product->stock }} in stock
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                        Out of stock
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($product->status == 1)
                                    <span class="badge bg-success border border-success px-2 py-1">Active</span>
                                @else
                                    <span class="badge bg-secondary border border-secondary px-2 py-1">Draft</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('products.show', $product->id) }}" target="_blank" class="btn btn-sm btn-light border text-secondary" title="View on Store">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Product">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Product">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-box-open fs-1 text-muted opacity-50 mb-2 d-block"></i>
                                No products found in the catalog.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
