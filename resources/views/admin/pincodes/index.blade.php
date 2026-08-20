@extends('layouts.admin')

@section('title', 'Manage Delivery Pincodes - StoreCraft Admin')
@section('page_title', 'Delivery Area & Pincode Restriction')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-11">

        <!-- Card 1: Delivery Mode Switcher -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-truck-ramp-box fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Delivery Scope & Restriction Settings</h5>
                        <p class="text-muted small mb-0">Choose whether to deliver nationwide across India or restrict delivery to specific local pincodes</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.pincodes.updateMode') }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-4">
                        <!-- Mode 1: All India Delivery -->
                        <div class="col-md-6">
                            <label class="border rounded-4 p-4 d-block cursor-pointer h-100 delivery-option {{ $deliveryMode === 'all' ? 'border-primary bg-primary-subtle shadow-sm' : 'bg-light' }}">
                                <div class="d-flex align-items-start gap-3">
                                    <input type="radio" name="delivery_mode" value="all" class="form-check-input mt-1" {{ $deliveryMode === 'all' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <div>
                                        <div class="fw-bold text-dark fs-6 mb-1">
                                            <i class="fa-solid fa-earth-asia text-success me-1"></i> All India Accept (Everywhere)
                                        </div>
                                        <p class="text-muted small mb-0">
                                            Accept orders from all valid pincodes across India. No restriction applies on customer address pincodes.
                                        </p>
                                        <span class="badge bg-success-subtle text-success border border-success mt-2">Active Nationwide</span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Mode 2: Restricted Local Pincodes Only -->
                        <div class="col-md-6">
                            <label class="border rounded-4 p-4 d-block cursor-pointer h-100 delivery-option {{ $deliveryMode === 'restricted' ? 'border-primary bg-primary-subtle shadow-sm' : 'bg-light' }}">
                                <div class="d-flex align-items-start gap-3">
                                    <input type="radio" name="delivery_mode" value="restricted" class="form-check-input mt-1" {{ $deliveryMode === 'restricted' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <div>
                                        <div class="fw-bold text-dark fs-6 mb-1">
                                            <i class="fa-solid fa-location-dot text-danger me-1"></i> Restricted / Local Pincodes Only
                                        </div>
                                        <p class="text-muted small mb-0">
                                            Only accept orders if the customer enters an allowed pincode from your list below. Unallowed pincodes will be blocked at checkout.
                                        </p>
                                        <span class="badge bg-warning-subtle text-warning border border-warning mt-2">{{ $activePincodes }} Active Pincodes Allowed</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="row align-items-center g-3 border-top pt-3">
                        <div class="col-md-8">
                            <label for="delivery_restricted_message" class="form-label fw-bold text-dark small mb-1">Unserviceable Pincode Error Message</label>
                            <input type="text" name="delivery_restricted_message" id="delivery_restricted_message" class="form-control form-control-sm" value="{{ old('delivery_restricted_message', $restrictedMessage) }}" placeholder="e.g. Sorry, delivery is not available in your area.">
                        </div>
                        <div class="col-md-4 text-md-end pt-md-4">
                            <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Delivery Mode
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section 2: Add Pincodes & Search -->
        <div class="row g-4 mb-4">

            <!-- Form: Add Single Pincode -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-plus-circle me-1 text-primary"></i> Add Single Allowed Pincode</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.pincodes.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="pincode" class="form-label fw-semibold text-dark">Pincode / ZIP Code <span class="text-danger">*</span></label>
                                <input type="text" name="pincode" id="pincode" class="form-control @error('pincode') is-invalid @enderror" placeholder="e.g. 110001" required>
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label for="city" class="form-label fw-semibold text-dark small">City Name (Optional)</label>
                                    <input type="text" name="city" id="city" class="form-control" placeholder="e.g. New Delhi">
                                </div>
                                <div class="col-6">
                                    <label for="area_name" class="form-label fw-semibold text-dark small">Area Name (Optional)</label>
                                    <input type="text" name="area_name" id="area_name" class="form-control" placeholder="e.g. Connaught Place">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="fa-solid fa-check me-1"></i> Add Pincode
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Form: Bulk Import Pincodes -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-file-import me-1 text-primary"></i> Bulk Import Pincodes</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.pincodes.storeBulk') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="bulk_pincodes" class="form-label fw-semibold text-dark">Pincodes List <span class="text-danger">*</span></label>
                                <textarea name="bulk_pincodes" id="bulk_pincodes" rows="3" class="form-control font-monospace @error('bulk_pincodes') is-invalid @enderror" placeholder="Paste pincodes separated by comma or new lines:
110001, 110002, 110003
400001, 400002" required></textarea>
                                <div class="form-text small">Duplicates are automatically skipped.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bulk_city" class="form-label fw-semibold text-dark small">City Name for Batch (Optional)</label>
                                <input type="text" name="bulk_city" id="bulk_city" class="form-control form-control-sm" placeholder="e.g. Mumbai">
                            </div>
                            <button type="submit" class="btn btn-outline-primary w-100 fw-bold">
                                <i class="fa-solid fa-upload me-1"></i> Import Bulk Pincodes
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <!-- Section 3: Allowed Pincodes Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-white py-3 px-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 border-bottom">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Allowed Delivery Pincodes Catalog</h5>
                    <p class="text-muted small mb-0">Showing {{ $pincodes->total() }} total configured pincodes</p>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Search Form -->
                    <form action="{{ route('admin.pincodes.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm" style="max-width: 260px;">
                            <input type="text" name="search" class="form-control" placeholder="Search pincode or city..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                        @if(request('search'))
                            <a href="{{ route('admin.pincodes.index') }}" class="btn btn-light btn-sm border">Reset</a>
                        @endif
                    </form>

                    <!-- Delete All Button -->
                    @if($totalPincodes > 0)
                        <form action="{{ route('admin.pincodes.destroyAll') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete ALL pincodes?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                                <i class="fa-solid fa-trash-can me-1"></i> Clear All
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase fs-8 text-muted fw-bold">
                        <tr>
                            <th class="ps-4">Pincode</th>
                            <th>City / District</th>
                            <th>Area Name</th>
                            <th>Status</th>
                            <th>Added Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pincodes as $item)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-dark fs-7 px-3 py-2 font-monospace">{{ $item->pincode }}</span>
                                </td>
                                <td class="fw-semibold text-dark">
                                    {{ $item->city ?? '—' }}
                                </td>
                                <td class="text-muted small">
                                    {{ $item->area_name ?? '—' }}
                                </td>
                                <td>
                                    @if($item->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-1">Active</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3 py-1">Disabled</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $item->created_at ? $item->created_at->format('d M Y') : '—' }}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <!-- Toggle Status -->
                                        <form action="{{ route('admin.pincodes.toggle', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $item->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="Toggle Active Status">
                                                <i class="fa-solid {{ $item->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Delete Pincode -->
                                        <form action="{{ route('admin.pincodes.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete pincode {{ $item->pincode }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Pincode">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-map-location-dot fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                    <h5>No Pincodes Found</h5>
                                    <p class="small mb-0">
                                        @if($deliveryMode === 'all')
                                            Delivery mode is currently set to <strong>All India Accept</strong>. You can add specific pincodes if you switch to Restricted mode.
                                        @else
                                            No pincodes added yet. Add pincodes using the form above to enable local delivery.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pincodes->hasPages())
                <div class="card-footer bg-white py-3 px-4 border-top">
                    {{ $pincodes->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
