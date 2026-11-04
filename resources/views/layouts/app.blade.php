<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Manajemen Statistik Riau' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('templates/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 290px;
            --topbar-height: 64px;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: #f8fafc;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Topbar Header */
        .topbar {
            position: sticky;
            top: 0;
            height: var(--topbar-height);
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 900;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: margin-left 0.3s ease;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-toggle-sidebar {
            background: #eff6ff;
            color: var(--primary);
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 8px 14px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-toggle-sidebar:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        .app-heading {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .app-heading i {
            color: var(--primary);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .autohide-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 9999px;
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .autohide-badge:hover {
            background: #d1fae5;
        }

        /* Edge hover zone on the left screen border */
        .edge-hover-zone {
            position: fixed;
            top: 0;
            left: 0;
            width: 14px;
            height: 100vh;
            z-index: 999;
        }

        /* Sidebar Overlay Backdrop */
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        .sidebar-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: #e2e8f0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.25);
            transform: translateX(-100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        /* Pinned Mode for Desktop */
        body.sidebar-pinned .sidebar {
            transform: translateX(0);
            box-shadow: 1px 0 0 #334155;
        }

        body.sidebar-pinned .main-wrapper {
            margin-left: var(--sidebar-width);
        }

        body.sidebar-pinned .topbar {
            margin-left: var(--sidebar-width);
        }

        body.sidebar-pinned .edge-hover-zone {
            display: none;
        }

        body.sidebar-pinned .sidebar-backdrop {
            display: none !important;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
        }

        .sidebar-brand i {
            font-size: 20px;
            color: #3b82f6;
        }

        .sidebar-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-sidebar-action {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #cbd5e1;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-sidebar-action:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-sidebar-action.pinned {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        /* Mode notice in Sidebar */
        .sidebar-mode-notice {
            background: rgba(37, 99, 235, 0.12);
            border-left: 3px solid #3b82f6;
            padding: 8px 14px;
            margin: 12px 14px 4px 14px;
            border-radius: 0 6px 6px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
            color: #93c5fd;
        }

        .sidebar-mode-notice button {
            background: none;
            border: none;
            color: #60a5fa;
            font-weight: 600;
            font-size: 11.5px;
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
        }

        /* Sidebar Nav and Items */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0 20px 0;
        }

        .sidebar-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            padding: 16px 20px 8px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sidebar-menu-list li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 16px;
            margin: 1px 12px;
            border-radius: 8px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .sidebar-menu-list li a i {
            width: 18px;
            text-align: center;
            font-size: 14px;
            color: #94a3b8;
            transition: color 0.15s ease;
        }

        .sidebar-menu-list li a:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            transform: translateX(3px);
        }

        .sidebar-menu-list li a:hover i {
            color: #60a5fa;
        }

        .sidebar-menu-list li a.active {
            background: var(--primary);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        .sidebar-menu-list li a.active i {
            color: #ffffff;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 12px;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            transition: margin-left 0.3s ease;
            min-height: calc(100vh - var(--topbar-height));
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            padding: 24px 30px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        .card-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            padding: 28px;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            color: #64748b;
            font-size: 13px;
            padding-bottom: 20px;
        }

        /* Common table & form styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        th {
            background: #1e293b;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
        }
        tr:hover {
            background: #f8fafc;
        }
        form select, form input {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        form select:focus, form input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .message-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            border-left: 4px solid #22c55e;
        }
    </style>
</head>
<body>
    <!-- Edge hover sensor zone to trigger sidebar in autohide mode -->
    <div class="edge-hover-zone" id="edgeHoverZone" title="Arahkan kursor ke sini untuk membuka menu sidebar"></div>

    <!-- Sidebar Overlay Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar Menu Navigation -->
    <aside class="sidebar" id="appSidebar">
        <div class="sidebar-header">
            <a href="{{ url('/') }}" class="sidebar-brand">
                <i class="fas fa-chart-line"></i>
                <span>Statistik Riau</span>
            </a>
            <div class="sidebar-actions">
                <button type="button" class="btn-sidebar-action" id="pinToggleBtn" title="Kunci Menu (Pin / Autohide)">
                    <i class="fas fa-thumbtack"></i>
                </button>
                <button type="button" class="btn-sidebar-action" id="sidebarCloseBtn" title="Tutup Menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="sidebar-mode-notice">
            <span id="sidebarModeText"><i class="fas fa-magic"></i> Mode Autohide Aktif</span>
            <button type="button" id="switchModeBtn">Ubah ke Tetap</button>
        </div>

        <nav class="sidebar-nav">
            <!-- Beranda -->
            <div class="sidebar-section-title">
                <i class="fas fa-home"></i> Beranda
            </div>
            <ul class="sidebar-menu-list">
                <li>
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i> Dashboard Analitik
                    </a>
                </li>
            </ul>

            <!-- Pengaturan & Referensi -->
            <div class="sidebar-section-title">
                <i class="fas fa-cog"></i> Pengaturan & Referensi
            </div>
            <ul class="sidebar-menu-list">
                <li>
                    <a href="{{ route('kode-urut.index') }}" class="{{ request()->routeIs('kode-urut.*') ? 'active' : '' }}">
                        <i class="fas fa-list-ol"></i> Kode Urut PMDN77 APBD
                    </a>
                </li>
                <li>
                    <a href="{{ route('kode-urut-13.index') }}" class="{{ request()->routeIs('kode-urut-13.*') ? 'active' : '' }}">
                        <i class="fas fa-list-ol"></i> Kode Urut PMDN13 APBD
                    </a>
                </li>
                <li>
                    <a href="{{ route('kode-catatan.index') }}" class="{{ request()->routeIs('kode-catatan.*') ? 'active' : '' }}">
                        <i class="fas fa-sticky-note"></i> Kode Catatan LKPD APBD
                    </a>
                </li>
                <li>
                    <a href="{{ route('jenis-apbd.index') }}" class="{{ request()->routeIs('jenis-apbd.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i> Jenis APBD
                    </a>
                </li>
                <li>
                    <a href="{{ route('level-kode.index') }}" class="{{ request()->routeIs('level-kode.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i> Level Kode
                    </a>
                </li>
            </ul>

            <!-- Input Data -->
            <div class="sidebar-section-title">
                <i class="fas fa-edit"></i> Input Data
            </div>
            <ul class="sidebar-menu-list">
                <li>
                    <a href="{{ route('input-apbd.index') }}" class="{{ request()->routeIs('input-apbd.*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i> Input Data APBD
                    </a>
                </li>
                <li>
                    <a href="{{ route('input-lkpd.index') }}" class="{{ request()->routeIs('input-lkpd.*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i> Input Data LKPD
                    </a>
                </li>
            </ul>

            <!-- Lampiran Dokumen -->
            <div class="sidebar-section-title">
                <i class="fas fa-paperclip"></i> Lampiran Dokumen
            </div>
            <ul class="sidebar-menu-list">
                <li>
                    <a href="{{ route('apbd-lampiran-1.index') }}" class="{{ request()->routeIs('apbd-lampiran-1.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i> Lampiran 1 - APBD
                    </a>
                </li>
                <li>
                    <a href="{{ route('lkpd-apbd') }}" class="{{ request()->routeIs('lkpd-apbd*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> Lampiran 1 - LKPD
                    </a>
                </li>
            </ul>

            <!-- Data Utama -->
            <div class="sidebar-section-title">
                <i class="fas fa-database"></i> Data Utama
            </div>
            <ul class="sidebar-menu-list">
                <li>
                    <a href="{{ url('/kinerja-keuangan-detail') }}" class="{{ request()->is('kinerja-keuangan-detail*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> DETAIL KINERJA KEUANGAN
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <span>&copy; {{ date('Y') }} Pemprov Riau</span>
            <span style="font-size: 11px; color: #475569;">v1.0</span>
        </div>
    </aside>

    <!-- Top Navigation Bar -->
    <header class="topbar">
        <div class="topbar-left">
            <button type="button" class="btn-toggle-sidebar" id="sidebarToggle">
                <i class="fas fa-bars"></i>
                <span>Menu</span>
            </button>
            <a href="{{ url('/') }}" class="app-heading">
                <i class="fas fa-database"></i>
                <span>Manajemen Data Statistik Riau</span>
            </a>
        </div>
        <div class="topbar-right">
            <div class="autohide-badge" id="topbarModeBadge" title="Klik untuk mengubah mode sidebar">
                <i class="fas fa-magic"></i>
                <span id="topbarModeText">Autohide: Aktif</span>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <main class="main-content">
            <div class="card-container">
                @yield('content')
            </div>

            <footer>
                <p>&copy; {{ date('Y') }} Pemerintah Provinsi Riau - Aplikasi Manajemen Data Statistik</p>
            </footer>
        </main>
    </div>

    <!-- Script for Sidebar Autohide & Interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('appSidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarCloseBtn');
            const backdrop = document.getElementById('sidebarBackdrop');
            const pinBtn = document.getElementById('pinToggleBtn');
            const switchModeBtn = document.getElementById('switchModeBtn');
            const topbarModeBadge = document.getElementById('topbarModeBadge');
            const sidebarModeText = document.getElementById('sidebarModeText');
            const topbarModeText = document.getElementById('topbarModeText');
            const edgeHoverZone = document.getElementById('edgeHoverZone');

            // Autohide is default (isPinned = false)
            let isPinned = localStorage.getItem('sidebar_pinned') === 'true';
            let hideTimeout = null;

            function updateModeUI() {
                if (isPinned) {
                    document.body.classList.add('sidebar-pinned');
                    sidebar.classList.add('open');
                    if (pinBtn) {
                        pinBtn.classList.add('pinned');
                        pinBtn.title = "Ubah ke Mode Autohide";
                    }
                    if (sidebarModeText) {
                        sidebarModeText.innerHTML = '<i class="fas fa-thumbtack"></i> Menu Tetap (Pinned)';
                    }
                    if (switchModeBtn) {
                        switchModeBtn.textContent = 'Ubah ke Autohide';
                    }
                    if (topbarModeText) {
                        topbarModeText.textContent = 'Menu: Tetap';
                    }
                    if (backdrop) {
                        backdrop.classList.remove('active');
                    }
                } else {
                    document.body.classList.remove('sidebar-pinned');
                    sidebar.classList.remove('open');
                    if (pinBtn) {
                        pinBtn.classList.remove('pinned');
                        pinBtn.title = "Kunci Menu agar Tetap Terbuka";
                    }
                    if (sidebarModeText) {
                        sidebarModeText.innerHTML = '<i class="fas fa-magic"></i> Autohide Aktif';
                    }
                    if (switchModeBtn) {
                        switchModeBtn.textContent = 'Ubah ke Tetap';
                    }
                    if (topbarModeText) {
                        topbarModeText.textContent = 'Autohide: Aktif';
                    }
                }
            }

            // Apply saved or default state
            updateModeUI();

            function openSidebar() {
                if (hideTimeout) clearTimeout(hideTimeout);
                sidebar.classList.add('open');
                if (!isPinned && backdrop) {
                    backdrop.classList.add('active');
                }
            }

            function closeSidebar() {
                if (!isPinned) {
                    sidebar.classList.remove('open');
                    if (backdrop) backdrop.classList.remove('active');
                }
            }

            function togglePin() {
                isPinned = !isPinned;
                localStorage.setItem('sidebar_pinned', isPinned ? 'true' : 'false');
                updateModeUI();
            }

            // Toggle button in Topbar
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (isPinned) {
                        togglePin();
                    } else {
                        if (sidebar.classList.contains('open')) {
                            closeSidebar();
                        } else {
                            openSidebar();
                        }
                    }
                });
            }

            // Close button inside sidebar
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    closeSidebar();
                });
            }

            // Backdrop click
            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    closeSidebar();
                });
            }

            // Pin button and mode switcher
            if (pinBtn) pinBtn.addEventListener('click', togglePin);
            if (switchModeBtn) switchModeBtn.addEventListener('click', togglePin);
            if (topbarModeBadge) topbarModeBadge.addEventListener('click', togglePin);

            // Autohide: hide on mouseleave with delay
            sidebar.addEventListener('mouseenter', function () {
                if (hideTimeout) clearTimeout(hideTimeout);
            });

            sidebar.addEventListener('mouseleave', function () {
                if (!isPinned) {
                    hideTimeout = setTimeout(function () {
                        closeSidebar();
                    }, 350);
                }
            });

            // Edge hover zone: hover on the left edge automatically reveals sidebar in autohide mode
            if (edgeHoverZone) {
                edgeHoverZone.addEventListener('mouseenter', function () {
                    if (!isPinned) {
                        openSidebar();
                    }
                });
            }

            // Close on escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !isPinned) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>
</html>
