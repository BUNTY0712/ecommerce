@php
    $adminThemePrimary = \App\Models\Setting::get('theme_primary_color', '#4f46e5');
    $adminThemeSecondary = \App\Models\Setting::get('theme_secondary_color', '#7c3aed');
    $siteName = \App\Models\Setting::get('site_name', 'StoreCraft');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - ' . $siteName . ' Control Panel')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --admin-sidebar-width: 260px;
            --admin-bg: #f1f5f9;
            --admin-dark: #0f172a;
            --admin-primary: {{ $adminThemePrimary }};
            --admin-primary-hover: {{ $adminThemeSecondary }};
            --font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        .text-primary { color: {{ $adminThemePrimary }} !important; }
        .bg-primary { background-color: {{ $adminThemePrimary }} !important; }
        .bg-primary-subtle { background-color: {{ $adminThemePrimary }}18 !important; }
        .btn-primary { 
            background: linear-gradient(135deg, {{ $adminThemePrimary }} 0%, {{ $adminThemeSecondary }} 100%) !important; 
            border: none !important; 
        }

        body {
            font-family: var(--font-family);
            background-color: var(--admin-bg);
            color: #334155;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Mobile Overlay */
        .admin-sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(2px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .admin-sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background-color: #0f172a;
            color: #94a3b8;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1050;
            overflow-y: auto;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-brand {
            padding: 1.25rem 1.25rem;
            font-size: 1.35rem;
            font-weight: 800;
            color: #ffffff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-nav-item {
            padding: 0.75rem 1.25rem;
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.925rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .admin-nav-item:hover, .admin-nav-item.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.06);
            border-left-color: var(--admin-primary);
        }

        .admin-nav-item i {
            font-size: 1.1rem;
            width: 20px;
            text-anchor: middle;
        }

        /* Responsive Breakpoints for Sidebar & Content */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-wrapper {
                margin-left: 0 !important;
            }

            .admin-topbar {
                padding: 0.75rem 1rem !important;
            }
        }

        @media (min-width: 992px) {
            .admin-sidebar {
                transform: translateX(0) !important;
            }
            
            .admin-wrapper {
                margin-left: var(--admin-sidebar-width);
            }

            .admin-sidebar-close-btn {
                display: none !important;
            }
        }

        /* Main Content wrapper */
        .admin-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Admin Topbar */
        .admin-topbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        /* Stat Cards */
        .stat-card {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 1.5rem;
            box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.04);
            height: 100%;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        @media (max-width: 575.98px) {
            .container-fluid {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Backdrop Overlay for Mobile Sidebar -->
    <div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar shadow-lg">
        <div class="admin-brand">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center gap-2">
                <i class="fa-solid fa-gauge-high text-primary fs-3"></i>
                <span>Admin<span class="text-primary">Panel</span></span>
            </a>
            <button type="button" class="btn-close btn-close-white d-lg-none admin-sidebar-close-btn" id="sidebarClose" aria-label="Close sidebar"></button>
        </div>

        <div class="py-3">
            <div class="px-3 pb-2 text-uppercase fs-8 fw-bold text-muted tracking-wider" style="letter-spacing: 0.08em; font-size: 0.7rem;">Main Overview</div>
            
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>

            <div class="px-3 pt-4 pb-2 text-uppercase fs-8 fw-bold text-muted tracking-wider" style="letter-spacing: 0.08em; font-size: 0.7rem;">E-Commerce Store</div>

            <a href="{{ route('admin.orders.index') }}" class="admin-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i> Orders Management
            </a>

            <a href="{{ route('admin.products.index') }}" class="admin-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fa-solid fa-boxes-stacked"></i> Products Catalog
            </a>

            <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i> Categories
            </a>

            <a href="{{ route('admin.banners.index') }}" class="admin-nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="fa-solid fa-images"></i> Home Banners
            </a>

            <a href="{{ route('admin.settings.index') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-palette"></i> Theme & Logo Settings
            </a>

            <a href="{{ route('admin.pincodes.index') }}" class="admin-nav-item {{ request()->routeIs('admin.pincodes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-truck-ramp-box"></i> Delivery Pincodes
            </a>

            <div class="px-3 pt-4 pb-2 text-uppercase fs-8 fw-bold text-muted tracking-wider" style="letter-spacing: 0.08em; font-size: 0.7rem;">Quick Links</div>

            <a href="{{ route('home') }}" target="_blank" class="admin-nav-item">
                <i class="fa-solid fa-store text-info"></i> View Live Store <i class="fa-solid fa-up-right-from-square fs-8 ms-auto text-muted"></i>
            </a>

            <!-- Admin Logout -->
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-4 px-3">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-bold py-2">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Admin Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="admin-wrapper">
        <!-- Topbar Header -->
        <header class="admin-topbar">
            <div class="d-flex align-items-center gap-2 me-2">
                <button type="button" class="btn btn-light btn-sm border d-lg-none shadow-sm px-2 py-1" id="sidebarToggle" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars fs-5 text-dark"></i>
                </button>
                <h5 class="fw-bold text-dark mb-0 fs-6 fs-sm-5 text-truncate">@yield('page_title', 'Dashboard Overview')</h5>
            </div>

            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm rounded-pill px-2 px-sm-3 d-flex align-items-center gap-1">
                    <i class="fa-solid fa-store"></i>
                    <span class="d-none d-sm-inline">Customer Front Store</span>
                    <span class="d-inline d-sm-none">Store</span>
                </a>
                <div class="vr mx-1"></div>
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7 shadow-sm" style="width: 36px; height: 36px; min-width: 36px;">
                        {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'AD' }}
                    </div>
                    <span class="fw-semibold text-dark small d-none d-md-inline">{{ Auth::check() ? Auth::user()->name : 'Administrator' }}</span>
                </div>
            </div>
        </header>

        <!-- Flash Alerts -->
        <div class="container-fluid px-3 px-md-4 mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 py-3 px-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 py-3 px-4" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Main View Area -->
        <main class="container-fluid px-3 px-md-4 py-3 py-md-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.admin-sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarClose');
            const overlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                if (sidebar && overlay) {
                    sidebar.classList.add('show');
                    overlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeSidebar() {
                if (sidebar && overlay) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (sidebar.classList.contains('show')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', closeSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
                    closeSidebar();
                }
            });

            // Close sidebar when window is resized to desktop width
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    closeSidebar();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

