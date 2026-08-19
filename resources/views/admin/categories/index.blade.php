@extends('layouts.admin')

@section('title', 'Manage Categories - Admin - StoreCraft')
@section('page_title', 'Category Management')

@section('content')
<div class="row g-4">
    <!-- Left Column: Categories List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-layer-group me-2 text-primary"></i> Category Catalog
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-4">Category Name</th>
                                <th class="py-3">Slug</th>
                                <th class="py-3 text-center">Products Count</th>
                                <th class="py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td class="px-4 py-3">
                                        <strong class="text-dark d-block mb-0">{{ $category->name }}</strong>
                                        @if(!empty($category->description))
                                            <span class="small text-muted text-truncate d-block" style="max-width: 250px;">{{ $category->description }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code class="text-muted small">{{ $category->slug }}</code>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 fw-bold">
                                            {{ $productCounts[$category->id] ?? 0 }} Items
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Category">
                                                <i class="fa-solid fa-trash-can"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No categories created yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Add Category Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-plus me-2 text-primary"></i> Create Category
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-dark">Category Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="e.g. Smart Wearables" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold text-dark">Description <span class="text-muted">(Optional)</span></label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="3" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Short category summary...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                        <i class="fa-solid fa-plus me-1"></i> Save Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
