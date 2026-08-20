@extends('layouts.admin')

@section('title', 'Edit Product #' . $product->id . ' - Admin - StoreCraft')
@section('page_title', 'Edit Product: ' . $product->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Products Catalog
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Product Information
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Product Name -->
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-semibold text-dark">Product Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $product->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="col-md-4">
                            <label for="category_id" class="form-label fw-semibold text-dark">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Regular Price -->
                        <div class="col-md-4">
                            <label for="price" class="form-label fw-semibold text-dark">Regular Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   step="0.01" 
                                   name="price" 
                                   id="price" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price', $product->price) }}" 
                                   required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Discount Price -->
                        <div class="col-md-4">
                            <label for="discount_price" class="form-label fw-semibold text-dark">Discount Price (₹) <span class="text-muted">(Optional)</span></label>
                            <input type="number" 
                                   step="0.01" 
                                   name="discount_price" 
                                   id="discount_price" 
                                   class="form-control @error('discount_price') is-invalid @enderror" 
                                   value="{{ old('discount_price', $product->discount_price) }}">
                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Level -->
                        <div class="col-md-2">
                            <label for="stock" class="form-label fw-semibold text-dark">Stock <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="stock" 
                                   id="stock" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   value="{{ old('stock', $product->stock) }}" 
                                   min="0" 
                                   required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-2">
                            <label for="status" class="form-label fw-semibold text-dark">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>

                        <!-- Existing Gallery Images Grid -->
                        @if(isset($galleryImages) && count($galleryImages) > 0)
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Current Product Gallery Photos ({{ count($galleryImages) }})</label>
                                <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded-3 border">
                                    @foreach($galleryImages as $gImg)
                                        <div class="position-relative border bg-white rounded-3 p-2 shadow-sm text-center" style="width: 100px;">
                                            <img src="{{ str_starts_with($gImg->image_path, 'http') ? $gImg->image_path : asset('storage/' . $gImg->image_path) }}" 
                                                 alt="Gallery" 
                                                 class="rounded" 
                                                 style="width: 100%; height: 75px; object-fit: contain;">
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.products.images.destroy', ['product' => $product->id, 'image' => $gImg->id]) }}" method="POST" class="mt-2" onsubmit="return confirm('Delete this gallery photo?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2 small rounded-pill mt-1" title="Delete Photo">
                                                    <i class="fa-solid fa-trash-can fs-8"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Product Cover Image -->
                        <div class="col-md-6">
                            <label for="image" class="form-label fw-bold text-dark">Replace Main Cover Image</label>
                            @if($product->image)
                                <div class="mb-2 d-flex align-items-center gap-2">
                                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                                         alt="Current Cover" 
                                         class="rounded border bg-light" 
                                         style="width: 48px; height: 48px; object-fit: contain;">
                                    <span class="small text-muted">Current Primary Image</span>
                                </div>
                            @endif
                            <input type="file" 
                                   name="image" 
                                   id="image" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Add Additional Gallery Images -->
                        <div class="col-md-6">
                            <label for="images" class="form-label fw-bold text-dark">Add More Gallery Images <span class="badge bg-primary-subtle text-primary border rounded-pill">Upload Multiple</span></label>
                            <input type="file" 
                                   name="images[]" 
                                   id="images" 
                                   class="form-control @error('images.*') is-invalid @enderror" 
                                   accept="image/*" 
                                   multiple>
                            <div class="form-text small">Upload additional images to add to gallery.</div>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Short Description -->
                        <div class="col-12">
                            <label for="short_description" class="form-label fw-semibold text-dark">Short Summary / Highlights</label>
                            <input type="text" 
                                   name="short_description" 
                                   id="short_description" 
                                   class="form-control @error('short_description') is-invalid @enderror" 
                                   value="{{ old('short_description', $product->short_description) }}">
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Full Description -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold text-dark">Full Product Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="5" 
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
