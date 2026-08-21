@extends('layouts.admin')

@section('title', 'Manage Home Page Banners - Admin Panel')
@section('page_title', 'Home Page Banners & Carousel')

@section('content')
<div class="row g-4">

    <!-- Size & Specification Alert -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: #ffffff;">
            <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow" style="width: 54px; height: 54px;">
                        <i class="fa-solid fa-file-image fs-3 text-indigo"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1 text-white">
                            <i class="fa-solid fa-sparkles me-1 text-warning"></i> Banner Image Specifications & Size Guidance
                        </h5>
                        <p class="mb-0 text-white-50 small">
                            For best visual results across desktop and mobile screens, please adhere to the recommended banner dimensions:
                        </p>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 text-nowrap">
                    <span class="badge bg-white text-dark fw-bold px-3 py-2 rounded-3 fs-7 border shadow-xs">
                        <i class="fa-solid fa-ruler-combined text-primary me-1"></i> Recommended: 1920 &times; 600 px
                    </span>
                    <span class="badge bg-white text-dark fw-bold px-3 py-2 rounded-3 fs-7 border shadow-xs">
                        <i class="fa-solid fa-crop-simple text-success me-1"></i> Ratio: 3:1 Landscape
                    </span>
                    <span class="badge bg-white text-dark fw-bold px-3 py-2 rounded-3 fs-7 border shadow-xs">
                        <i class="fa-solid fa-hard-drive text-danger me-1"></i> Max Size: 4 MB
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Form Card (Multiple Banner Upload Support) -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i>Upload New Banner(s)
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Multiple Image Upload Input -->
                    <div class="mb-3">
                        <label for="images" class="form-label fw-bold text-dark small">
                            Banner Image(s) <span class="text-danger">*</span>
                        </label>
                        <input type="file" 
                               name="images[]" 
                               id="images" 
                               class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                               accept="image/*" 
                               multiple 
                               required>
                        <div class="form-text text-muted small">
                            <i class="fa-solid fa-circle-info me-1"></i> Select 1 or multiple banner images. Formats: JPG, PNG, WEBP, GIF.
                        </div>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-3 text-subtle">
                    <p class="fw-bold text-dark small mb-2">Optional Slide Content & Text Overlay</p>

                    <!-- Badge Text -->
                    <div class="mb-3">
                        <label for="badge_text" class="form-label small fw-semibold text-muted">Badge / Tagline</label>
                        <input type="text" 
                               name="badge_text" 
                               id="badge_text" 
                               class="form-control form-control-sm" 
                               placeholder="e.g. New Season Arrivals 2026">
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label small fw-semibold text-muted">Headline / Title</label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="form-control form-control-sm" 
                               placeholder="e.g. Elevate Your Lifestyle with Premium Goods">
                    </div>

                    <!-- Subtitle / Description -->
                    <div class="mb-3">
                        <label for="subtitle" class="form-label small fw-semibold text-muted">Subtitle / Description</label>
                        <textarea name="subtitle" 
                                  id="subtitle" 
                                  rows="2" 
                                  class="form-control form-control-sm" 
                                  placeholder="e.g. Explore curated collections of top-rated electronics..."></textarea>
                    </div>

                    <!-- Button Text & Link -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="button_text" class="form-label small fw-semibold text-muted">Button Text</label>
                            <input type="text" 
                                   name="button_text" 
                                   id="button_text" 
                                   class="form-control form-control-sm" 
                                   placeholder="e.g. Shop Now">
                        </div>
                        <div class="col-6">
                            <label for="button_url" class="form-label small fw-semibold text-muted">Button Link URL</label>
                            <input type="text" 
                                   name="button_url" 
                                   id="button_url" 
                                   class="form-control form-control-sm" 
                                   placeholder="e.g. #products-grid or /products">
                        </div>
                    </div>

                    <!-- Display Sort Order -->
                    <div class="mb-4">
                        <label for="sort_order" class="form-label small fw-semibold text-muted">Display Sort Order</label>
                        <input type="number" 
                               name="sort_order" 
                               id="sort_order" 
                               class="form-control form-control-sm" 
                               value="0" 
                               min="0">
                        <span class="form-text small">Lower numbers appear first in the carousel slider.</span>
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold w-100 py-2 shadow-sm">
                        <i class="fa-solid fa-plus me-1.5"></i> Upload Banner Slide(s)
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Active Banners List / Gallery -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="fa-solid fa-images text-primary me-2"></i>Uploaded Home Banners ({{ $banners->count() }})
                </h6>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-semibold">
                    Carousel Active
                </span>
            </div>
            <div class="card-body p-4">
                @if($banners->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($banners as $banner)
                            <div class="card border rounded-3 overflow-hidden shadow-xs">
                                <div class="row g-0 align-items-center">
                                    <!-- Banner Thumbnail Preview -->
                                    <div class="col-md-5 position-relative bg-dark" style="min-height: 140px;">
                                        @if(!empty($banner->image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($banner->image))
                                            <img src="{{ asset('storage/' . $banner->image) }}" 
                                                 alt="{{ $banner->title ?? 'Banner' }}" 
                                                 class="w-100 h-100 object-fit-cover position-absolute top-0 start-0">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white-50">
                                                <i class="fa-solid fa-image fs-1"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-dark bg-opacity-75 text-white fw-bold px-2 py-1 fs-8">
                                                Sort: {{ $banner->sort_order }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Banner Info & Controls -->
                                    <div class="col-md-7">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                                <div>
                                                    @if($banner->badge_text)
                                                        <span class="badge bg-warning-subtle text-warning-emphasis fw-bold mb-1" style="font-size: 0.7rem;">
                                                            {{ $banner->badge_text }}
                                                        </span>
                                                    @endif
                                                    <h6 class="fw-bold text-dark mb-1 text-truncate" style="max-width: 220px;">
                                                        {{ $banner->title ?: 'Banner Slide #' . $banner->id }}
                                                    </h6>
                                                </div>
                                                
                                                <!-- Status Toggle -->
                                                <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST">
                                                    @csrf
                                                    @if($banner->status == 1)
                                                        <button type="submit" class="btn btn-success btn-xs fw-bold px-2.5 py-1 rounded-pill" title="Click to Deactivate">
                                                            Active
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-secondary btn-xs fw-bold px-2.5 py-1 rounded-pill" title="Click to Activate">
                                                            Inactive
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>

                                            @if($banner->subtitle)
                                                <p class="text-muted small mb-2 text-truncate-2" style="font-size: 0.8rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                    {{ $banner->subtitle }}
                                                </p>
                                            @endif

                                            @if($banner->button_text)
                                                <div class="mb-2">
                                                    <span class="badge bg-light text-dark border font-monospace small">
                                                        <i class="fa-solid fa-link text-muted me-1"></i> {{ $banner->button_text }} &rarr; {{ $banner->button_url ?: '#' }}
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-2">
                                                <span class="text-muted small" style="font-size: 0.72rem;">
                                                    Added: {{ \Carbon\Carbon::parse($banner->created_at)->format('M d, Y') }}
                                                </span>
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <!-- Edit Modal Trigger -->
                                                    <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2 fs-7" data-bs-toggle="modal" data-bs-target="#editBannerModal{{ $banner->id }}">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                                    </button>
                                                    
                                                    <!-- Delete Form -->
                                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner image?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2 fs-7">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal for Banner #{{ $banner->id }} -->
                            <div class="modal fade" id="editBannerModal{{ $banner->id }}" tabindex="-1" aria-labelledby="editBannerModalLabel{{ $banner->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-bottom py-3 px-4">
                                            <h6 class="modal-title fw-bold text-dark" id="editBannerModalLabel{{ $banner->id }}">
                                                Edit Banner Slide #{{ $banner->id }}
                                            </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <!-- Replace Image Option -->
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-dark">Replace Image (Optional)</label>
                                                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                                                    <span class="form-text small text-muted">Leave empty to keep existing image. Recommended size: 1920x600 px.</span>
                                                </div>

                                                <!-- Badge Text -->
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">Badge / Tagline</label>
                                                    <input type="text" name="badge_text" class="form-control form-control-sm" value="{{ $banner->badge_text }}">
                                                </div>

                                                <!-- Title -->
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">Headline / Title</label>
                                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $banner->title }}">
                                                </div>

                                                <!-- Subtitle -->
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">Subtitle / Description</label>
                                                    <textarea name="subtitle" rows="2" class="form-control form-control-sm">{{ $banner->subtitle }}</textarea>
                                                </div>

                                                <!-- Button Text & Link -->
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold text-muted">Button Text</label>
                                                        <input type="text" name="button_text" class="form-control form-control-sm" value="{{ $banner->button_text }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold text-muted">Button Link URL</label>
                                                        <input type="text" name="button_url" class="form-control form-control-sm" value="{{ $banner->button_url }}">
                                                    </div>
                                                </div>

                                                <!-- Sort Order -->
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">Display Sort Order</label>
                                                    <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $banner->sort_order }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top py-2 px-4">
                                                <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm fw-bold">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="bg-light text-muted rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px;">
                            <i class="fa-solid fa-images fs-1"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">No custom home banners uploaded yet</h6>
                        <p class="text-muted small mb-0" style="max-width: 360px; margin-left: auto; margin-right: auto;">
                            Upload banner images using the form on the left. The live homepage carousel will automatically activate!
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
