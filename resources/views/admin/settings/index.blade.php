@extends('layouts.admin')

@section('title', 'Website Theme & Logo Settings - StoreCraft Admin')
@section('page_title', 'Website Customization & Branding')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        <!-- Form Card -->
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Section 1: Logo & Branding -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fa-solid fa-image fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Website Logo & Branding</h5>
                            <p class="text-muted small mb-0">Update your store logo, website title, and announcement bar header</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Current Logo Preview & Upload -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Website Logo</label>
                            <div class="border rounded-3 p-3 bg-light text-center mb-3">
                                @if(!empty($settings['site_logo']))
                                    <div class="p-3 bg-white rounded border d-inline-block shadow-sm mb-2">
                                        <img id="logoPreviewImg" src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Site Logo" style="max-height: 60px; object-fit: contain;">
                                    </div>
                                    <div class="form-check d-flex align-items-center justify-content-center gap-2 mt-1">
                                        <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="removeLogoCheck">
                                        <label class="form-check-label text-danger fw-semibold small" for="removeLogoCheck">
                                            <i class="fa-solid fa-trash-can me-1"></i> Remove custom uploaded logo (revert to text logo)
                                        </label>
                                    </div>
                                @else
                                    <div class="p-3 bg-white rounded border d-inline-block shadow-sm mb-2 text-dark">
                                        <div class="d-flex align-items-center gap-2 fw-extrabold fs-4" id="logoTextPreview">
                                            <i class="fa-solid fa-bag-shopping text-primary"></i>
                                            <span>{{ $settings['site_name'] }}</span>
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-0">No custom image logo uploaded yet. Showing text/icon logo.</p>
                                @endif
                            </div>

                            <input type="file" name="site_logo" id="siteLogoInput" class="form-control @error('site_logo') is-invalid @enderror" accept="image/*">
                            <div class="form-text small">Recommended: Transparent PNG or SVG logo file (Max: 2MB).</div>
                            @error('site_logo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Site Name & Announcement -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_name" class="form-label fw-bold text-dark">Store / Website Name</label>
                                <input type="text" name="site_name" id="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $settings['site_name']) }}" required>
                                @error('site_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="announcement_text" class="form-label fw-bold text-dark">Announcement Bar Text</label>
                                <input type="text" name="announcement_text" id="announcement_text" class="form-control @error('announcement_text') is-invalid @enderror" value="{{ old('announcement_text', $settings['announcement_text']) }}" placeholder="e.g. 🎉 Get 10% OFF on all items!">
                                <div class="form-text small">Appears at the top notification banner across the website.</div>
                                @error('announcement_text')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Theme Colors & Styling -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fa-solid fa-palette fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Website Theme Color Palette</h5>
                            <p class="text-muted small mb-0">Select a pre-built color theme or customize your brand colors dynamically</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Preset Color Palettes -->
                    <label class="form-label fw-bold text-dark mb-2">Preset Theme Palettes</label>
                    <div class="row row-cols-2 row-cols-sm-4 g-3 mb-4">

                        <!-- Indigo -->
                        <div class="col">
                            <button type="button" class="btn btn-outline-light text-dark w-100 p-3 text-start rounded-3 border preset-btn {{ $settings['theme_primary_color'] === '#4f46e5' ? 'border-primary shadow-sm active-preset' : '' }}" 
                                    onclick="setPreset('#4f46e5', '#7c3aed', 'indigo')">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #4f46e5;"></span>
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #7c3aed;"></span>
                                </div>
                                <div class="fw-bold fs-7">Indigo Royal</div>
                                <div class="text-muted fs-8">Default Brand</div>
                            </button>
                        </div>

                        <!-- Emerald Green -->
                        <div class="col">
                            <button type="button" class="btn btn-outline-light text-dark w-100 p-3 text-start rounded-3 border preset-btn {{ $settings['theme_primary_color'] === '#059669' ? 'border-primary shadow-sm active-preset' : '' }}" 
                                    onclick="setPreset('#059669', '#10b981', 'emerald')">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #059669;"></span>
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #10b981;"></span>
                                </div>
                                <div class="fw-bold fs-7">Emerald Green</div>
                                <div class="text-muted fs-8">Eco & Fresh</div>
                            </button>
                        </div>

                        <!-- Ocean Blue -->
                        <div class="col">
                            <button type="button" class="btn btn-outline-light text-dark w-100 p-3 text-start rounded-3 border preset-btn {{ $settings['theme_primary_color'] === '#0284c7' ? 'border-primary shadow-sm active-preset' : '' }}" 
                                    onclick="setPreset('#0284c7', '#2563eb', 'ocean')">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #0284c7;"></span>
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #2563eb;"></span>
                                </div>
                                <div class="fw-bold fs-7">Ocean Blue</div>
                                <div class="text-muted fs-8">Clean & Tech</div>
                            </button>
                        </div>

                        <!-- Crimson Red -->
                        <div class="col">
                            <button type="button" class="btn btn-outline-light text-dark w-100 p-3 text-start rounded-3 border preset-btn {{ $settings['theme_primary_color'] === '#dc2626' ? 'border-primary shadow-sm active-preset' : '' }}" 
                                    onclick="setPreset('#dc2626', '#e11d48', 'crimson')">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #dc2626;"></span>
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #e11d48;"></span>
                                </div>
                                <div class="fw-bold fs-7">Crimson Red</div>
                                <div class="text-muted fs-8">Bold & Dynamic</div>
                            </button>
                        </div>

                        <!-- Sunset Amber -->
                        <div class="col">
                            <button type="button" class="btn btn-outline-light text-dark w-100 p-3 text-start rounded-3 border preset-btn {{ $settings['theme_primary_color'] === '#d97706' ? 'border-primary shadow-sm active-preset' : '' }}" 
                                    onclick="setPreset('#d97706', '#f59e0b', 'amber')">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #d97706;"></span>
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #f59e0b;"></span>
                                </div>
                                <div class="fw-bold fs-7">Sunset Amber</div>
                                <div class="text-muted fs-8">Warm & Gold</div>
                            </button>
                        </div>

                        <!-- Royal Purple -->
                        <div class="col">
                            <button type="button" class="btn btn-outline-light text-dark w-100 p-3 text-start rounded-3 border preset-btn {{ $settings['theme_primary_color'] === '#9333ea' ? 'border-primary shadow-sm active-preset' : '' }}" 
                                    onclick="setPreset('#9333ea', '#c026d3', 'purple')">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #9333ea;"></span>
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #c026d3;"></span>
                                </div>
                                <div class="fw-bold fs-7">Royal Purple</div>
                                <div class="text-muted fs-8">Luxury & Premium</div>
                            </button>
                        </div>

                        <!-- Midnight Dark -->
                        <div class="col">
                            <button type="button" class="btn btn-outline-light text-dark w-100 p-3 text-start rounded-3 border preset-btn {{ $settings['theme_primary_color'] === '#0f172a' ? 'border-primary shadow-sm active-preset' : '' }}" 
                                    onclick="setPreset('#0f172a', '#334155', 'midnight')">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #0f172a;"></span>
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #334155;"></span>
                                </div>
                                <div class="fw-bold fs-7">Midnight Dark</div>
                                <div class="text-muted fs-8">Sleek & Modern</div>
                            </button>
                        </div>

                        <!-- Tealeaf Cyan -->
                        <div class="col">
                            <button type="button" class="btn btn-outline-light text-dark w-100 p-3 text-start rounded-3 border preset-btn {{ $settings['theme_primary_color'] === '#0d9488' ? 'border-primary shadow-sm active-preset' : '' }}" 
                                    onclick="setPreset('#0d9488', '#06b6d4', 'teal')">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #0d9488;"></span>
                                    <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: #06b6d4;"></span>
                                </div>
                                <div class="fw-bold fs-7">Tealeaf Cyan</div>
                                <div class="text-muted fs-8">Vibrant & Vibrant</div>
                            </button>
                        </div>

                    </div>

                    <input type="hidden" name="theme_preset" id="theme_preset" value="{{ old('theme_preset', $settings['theme_preset']) }}">

                    <!-- Custom Color Selection & Live Preview -->
                    <div class="border rounded-3 p-4 bg-light">
                        <h6 class="fw-bold text-dark mb-3">Custom Hex Color Pickers</h6>
                        <div class="row g-4 align-items-center">
                            
                            <!-- Primary Color Picker -->
                            <div class="col-md-4">
                                <label for="theme_primary_color" class="form-label fw-bold text-dark">Primary Brand Color</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="primaryColorPicker" value="{{ old('theme_primary_color', $settings['theme_primary_color']) }}" onchange="updateCustomColors()">
                                    <input type="text" name="theme_primary_color" id="theme_primary_color" class="form-control font-monospace" value="{{ old('theme_primary_color', $settings['theme_primary_color']) }}" required oninput="syncColorPickers()">
                                </div>
                            </div>

                            <!-- Secondary Color Picker -->
                            <div class="col-md-4">
                                <label for="theme_secondary_color" class="form-label fw-bold text-dark">Secondary / Gradient Color</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="secondaryColorPicker" value="{{ old('theme_secondary_color', $settings['theme_secondary_color']) }}" onchange="updateCustomColors()">
                                    <input type="text" name="theme_secondary_color" id="theme_secondary_color" class="form-control font-monospace" value="{{ old('theme_secondary_color', $settings['theme_secondary_color']) }}" oninput="syncColorPickers()">
                                </div>
                            </div>

                            <!-- Live Color Preview Badge -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Live Theme Preview</label>
                                <div id="liveThemePreview" class="p-3 text-white rounded-3 shadow-sm text-center fw-bold" style="background: linear-gradient(135deg, {{ $settings['theme_primary_color'] }} 0%, {{ $settings['theme_secondary_color'] }} 100%);">
                                    <i class="fa-solid fa-sparkles me-2"></i> Theme Active Preview
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="d-flex align-items-center justify-content-end gap-3 mb-5">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light border px-4 py-2 fw-semibold">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2.5 fw-bold shadow">
                    <i class="fa-solid fa-floppy-disk me-2"></i> Save Settings & Apply Theme
                </button>
            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
function setPreset(primary, secondary, presetName) {
    document.getElementById('theme_primary_color').value = primary;
    document.getElementById('theme_secondary_color').value = secondary;
    document.getElementById('primaryColorPicker').value = primary;
    document.getElementById('secondaryColorPicker').value = secondary;
    document.getElementById('theme_preset').value = presetName;

    updatePreview(primary, secondary);
}

function updateCustomColors() {
    const primary = document.getElementById('primaryColorPicker').value;
    const secondary = document.getElementById('secondaryColorPicker').value;
    
    document.getElementById('theme_primary_color').value = primary;
    document.getElementById('theme_secondary_color').value = secondary;
    document.getElementById('theme_preset').value = 'custom';

    updatePreview(primary, secondary);
}

function syncColorPickers() {
    const primary = document.getElementById('theme_primary_color').value;
    const secondary = document.getElementById('theme_secondary_color').value;

    if(/^#[0-9A-F]{6}$/i.test(primary)) {
        document.getElementById('primaryColorPicker').value = primary;
    }
    if(/^#[0-9A-F]{6}$/i.test(secondary)) {
        document.getElementById('secondaryColorPicker').value = secondary;
    }
    updatePreview(primary, secondary);
}

function updatePreview(primary, secondary) {
    const preview = document.getElementById('liveThemePreview');
    if (preview) {
        preview.style.background = `linear-gradient(135deg, ${primary} 0%, ${secondary} 100%)`;
    }
}
</script>
@endpush
