@php
    $themePrimary = \App\Models\Setting::get('theme_primary_color', '#4f46e5');
    $themeSecondary = \App\Models\Setting::get('theme_secondary_color', '#7c3aed');
    $siteName = \App\Models\Setting::get('site_name', 'StoreCraft');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName . ' - Premium E-Commerce Destination')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Pro / Icons 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom Professional Styling -->
    <style>
        :root {
            --brand-primary: {{ $themePrimary }};
            --brand-primary-hover: {{ $themePrimary }};
            --brand-secondary: {{ $themeSecondary }};
            --brand-dark: #0f172a;
            --brand-accent: #f59e0b;
            --bg-canvas: #f8fafc;
            --surface-card: #ffffff;
            --border-subtle: #e2e8f0;
            --text-heading: #0f172a;
            --text-body: #475569;
            --text-muted: #94a3b8;
            --radius-sm: 0.5rem;
            --radius-md: 0.85rem;
            --radius-lg: 1.25rem;
            --shadow-subtle: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            --gradient-primary: linear-gradient(135deg, {{ $themePrimary }} 0%, {{ $themeSecondary }} 100%);
            --gradient-dark: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        /* Dynamic Theme Custom Color Overrides */
        .text-primary { color: {{ $themePrimary }} !important; }
        .bg-primary { background-color: {{ $themePrimary }} !important; }
        .bg-primary-subtle { background-color: {{ $themePrimary }}18 !important; }
        .border-primary { border-color: {{ $themePrimary }} !important; }
        .btn-primary { 
            background: linear-gradient(135deg, {{ $themePrimary }} 0%, {{ $themeSecondary }} 100%) !important; 
            border: none !important; 
            box-shadow: 0 4px 12px {{ $themePrimary }}40 !important;
        }
        .btn-primary:hover, .btn-primary:focus { 
            opacity: 0.92;
            transform: translateY(-1px);
        }
        .btn-outline-primary { 
            color: {{ $themePrimary }} !important; 
            border-color: {{ $themePrimary }} !important; 
        }
        .btn-outline-primary:hover { 
            background-color: {{ $themePrimary }} !important; 
            color: #ffffff !important; 
        }
        .badge-category {
            background-color: {{ $themePrimary }}15 !important;
            color: {{ $themePrimary }} !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-body);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        main {
            flex: 1;
        }

        /* Top Announcement Bar */
        .announcement-bar {
            background: var(--gradient-dark);
            color: #f8fafc;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        .announcement-bar code {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 0.15rem 0.4rem;
            border-radius: 0.25rem;
        }

        /* Modern Navigation Header */
        .site-header {
            background-color: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-subtle);
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.03em;
            color: var(--brand-dark);
            text-decoration: none;
        }

        .brand-logo i {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            font-weight: 600;
            color: #475569;
            font-size: 0.925rem;
            padding: 0.5rem 0.85rem !important;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--brand-primary);
            background-color: #f1f5f9;
        }

        /* Card System */
        .card {
            border-radius: var(--radius-md);
            border: 1px solid var(--border-subtle);
            background: var(--surface-card);
            box-shadow: var(--shadow-subtle);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card-hover {
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .product-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: #cbd5e1;
        }

        .product-img-wrapper {
            background: #f8fafc;
            position: relative;
            overflow: hidden;
            border-top-left-radius: var(--radius-md);
            border-top-right-radius: var(--radius-md);
        }

        .product-img-wrapper img {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card-hover:hover .product-img-wrapper img {
            transform: scale(1.06);
        }

        /* Buttons */
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            color: #ffffff;
            font-weight: 600;
            border-radius: var(--radius-sm);
            padding: 0.65rem 1.25rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.2s ease;
        }

        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
        }

        .btn-outline-primary {
            color: var(--brand-primary);
            border: 1.5px solid #c7d2fe;
            background: #ffffff;
            font-weight: 600;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            background: #eef2ff;
            color: var(--brand-primary-hover);
            border-color: var(--brand-primary);
        }

        .btn-accent {
            background-color: #f59e0b;
            border: none;
            color: #ffffff;
            font-weight: 700;
            border-radius: var(--radius-sm);
        }

        .btn-accent:hover {
            background-color: #d97706;
            color: #ffffff;
        }

        /* Rating Stars */
        .rating-stars {
            color: #f59e0b;
            font-size: 0.85rem;
        }

        /* Badges */
        .badge-discount {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.725rem;
            padding: 0.35em 0.7em;
            border-radius: 0.4rem;
            letter-spacing: 0.03em;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .badge-category {
            background-color: #eef2ff;
            color: var(--brand-primary);
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.3em 0.75em;
            border-radius: 2rem;
        }

        /* Checkout Steps Bar */
        .checkout-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2.5rem;
            gap: 1.25rem;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 600;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .step-item.active {
            color: var(--brand-primary);
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #e2e8f0;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .step-item.active .step-number {
            background: var(--gradient-primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .step-divider {
            height: 2px;
            width: 50px;
            background-color: #e2e8f0;
            border-radius: 2px;
        }

        /* Feature Badges Bar */
        .feature-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #eef2ff;
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header / Navigation Component -->
    @unless(request()->routeIs('login') || request()->is('login'))
        @include('components.header')
    @endunless

    <!-- Flash Alerts -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 py-3 px-4 d-flex align-items-center gap-3" role="alert" style="background-color: #f0fdf4; color: #166534; border-left: 4px solid #22c55e !important;">
                <i class="fa-solid fa-circle-check fs-4 text-success"></i>
                <div class="fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 py-3 px-4 d-flex align-items-center gap-3" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                <i class="fa-solid fa-circle-exclamation fs-4 text-danger"></i>
                <div class="fw-medium">{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 py-3 px-4" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                <div class="d-flex align-items-center gap-2 mb-2 fw-bold">
                    <i class="fa-solid fa-triangle-exclamation text-danger"></i> Please resolve the following errors:
                </div>
                <ul class="mb-0 ps-3 small fw-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer Component -->
    @include('components.footer')

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
