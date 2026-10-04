<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Camat - PATEN SPACE')</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif

    <style>
        .badge-camat {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .badge-camat.warning {
            background: #fff4e5;
            color: #b76e00;
            border: 1px solid #fed7aa;
        }
        .badge-camat.info {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .badge-camat.danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .badge-camat.success {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .badge-camat.neutral {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .sidebar-counter {
            margin-left: auto;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 12px;
            line-height: 1.2;
        }

        .attention-card {
            border: 1px solid #fed7aa;
            background: linear-gradient(180deg, #fffdfa 0%, #fff8f0 100%);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.05);
            margin-bottom: 24px;
        }
        .attention-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .attention-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .attention-title h2 {
            margin: 0;
            color: #9a3412;
            font-size: 1.05rem;
            font-weight: 700;
        }
        .attention-title p {
            margin: 0;
            color: #c2410c;
            font-size: 0.78rem;
        }
        .attention-icon-badge {
            width: 34px;
            height: 34px;
            background: #ffedd5;
            color: #c2410c;
            border-radius: 8px;
            display: grid;
            place-items: center;
            font-size: 1.1rem;
            font-weight: bold;
        }
        .attention-list {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }
        .attention-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 12px 14px;
            background: #ffffff;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }
        .attention-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(180, 83, 9, 0.08);
        }
        .attention-item-top {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
        }
        .attention-item-top span.warn-icon {
            color: #d97706;
            font-size: 1.15rem;
            line-height: 1;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .attention-item-text {
            color: #431407;
            font-size: 0.82rem;
            line-height: 1.4;
            font-weight: 600;
        }
        .attention-item-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 8px;
            border-top: 1px dashed #fed7aa;
        }
        .attention-link {
            color: #c2410c;
            text-decoration: none;
            font-size: 0.74rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .attention-link:hover {
            text-decoration: underline;
        }

        .status-card-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-top: 16px;
        }
        .status-box {
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .status-box.selesai { border-color: #bbf7d0; background: #f0fdf4; }
        .status-box.proses { border-color: #fed7aa; background: #fffbeb; }
        .status-box.menunggu { border-color: #bfdbfe; background: #eff6ff; }
        .status-box.revisi { border-color: #fecaca; background: #fef2f2; }
        .status-box-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 600;
        }
        .status-box-val {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
        }
        .status-box.selesai .status-box-val { color: #166534; }
        .status-box.proses .status-box-val { color: #9a3412; }
        .status-box.menunggu .status-box-val { color: #1e40af; }
        .status-box.revisi .status-box-val { color: #991b1b; }

        .progress-bar-stacked {
            display: flex;
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
            background: #e2e8f0;
            margin-top: 12px;
        }
        .progress-bar-segment {
            height: 100%;
            transition: width 0.3s ease;
        }
        .progress-bar-segment.selesai { background: #16a34a; }
        .progress-bar-segment.proses { background: #ea580c; }
        .progress-bar-segment.menunggu { background: #3b82f6; }
        .progress-bar-segment.revisi { background: #ef4444; }

        .role-switch-link {
            font-size: 0.74rem;
            color: #0870c9;
            text-decoration: none;
            padding: 4px 8px;
            border-radius: 4px;
            background: #e0f2fe;
            font-weight: 600;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .role-switch-link:hover {
            background: #bae6fd;
            color: #0369a1;
        }

        @media print {
            .sidebar, .dashboard-topbar, .dashboard-primary-link, .report-filters, .table-action, .role-switch-link, .no-print {
                display: none !important;
            }
            .app-shell, .main-panel, .dashboard-content {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
                background: #ffffff !important;
            }
            .dashboard-panel, .attention-card, .dashboard-stat {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                break-inside: avoid;
            }
            .print-header {
                display: block !important;
                margin-bottom: 24px;
                border-bottom: 2px solid #000;
                padding-bottom: 12px;
            }
        }
        .print-header {
            display: none;
        }

        @media (max-width: 900px) {
            .attention-list {
                grid-template-columns: 1fr;
            }
            .status-card-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="app-shell dashboard-page">
        <aside class="sidebar dark-sidebar">
            <a href="{{ route('camat.dashboard') }}" class="brand-wrap">
                <img src="{{ asset('image/logo-kecamatan.png') }}" alt="Logo Kecamatan Jatisari" class="brand-mark">
                <span class="brand-text">
                    <span class="brand-title">PATEN SPACE</span>
                    <span class="brand-sub">Pelayanan Kecamatan</span>
                </span>
            </a>

            <nav class="sidebar-nav">
                <div class="nav-group-label">MENU UTAMA</div>
                <a href="{{ route('camat.dashboard') }}" class="nav-item {{ request()->routeIs('camat.dashboard') ? 'active' : '' }}">
                    <span>◫</span> Dashboard
                </a>
                <a href="{{ route('camat.monitoring') }}" class="nav-item {{ request()->routeIs('camat.monitoring') ? 'active' : '' }}">
                    <span>▣</span> Monitoring Pelayanan
                </a>
                <a href="{{ route('camat.statistik') }}" class="nav-item {{ request()->routeIs('camat.statistik') ? 'active' : '' }}">
                    <span>📈</span> Statistik
                </a>
                <a href="{{ route('camat.laporan') }}" class="nav-item {{ request()->routeIs('camat.laporan') ? 'active' : '' }}">
                    <span>▥</span> Laporan
                </a>
                <a href="{{ route('camat.aktivitas') }}" class="nav-item {{ request()->routeIs('camat.aktivitas') ? 'active' : '' }}">
                    <span>⏱</span> Aktivitas Pelayanan
                </a>

                <div class="nav-group-label">LAINNYA</div>
                <a href="{{ route('camat.notifikasi') }}" class="nav-item {{ request()->routeIs('camat.notifikasi') ? 'active' : '' }}">
                    <span>🔔</span> Notifikasi
                    <span class="sidebar-counter">1</span>
                </a>
                <a href="{{ route('camat.profil') }}" class="nav-item {{ request()->routeIs('camat.profil') ? 'active' : '' }}">
                    <span>👤</span> Profil
                </a>
                <a href="{{ route('dashboard') }}" class="nav-item" title="Kembali ke Dashboard Operator">
                    <span>↪</span> Keluar
                </a>
            </nav>

            <div class="sidebar-profile">
                <span class="profile-avatar">CJ</span>
                <span>
                    <strong>Camat Kecamatan</strong>
                    <small>Pimpinan / Camat</small>
                </span>
            </div>
        </aside>

        <main class="main-panel">
            <header class="dashboard-topbar">
                <div>
                    <strong>Kecamatan Jatisari</strong>
                    <span>Kabupaten Karawang</span>
                </div>
                <div style="display: flex; align-items: center; gap: 14px;">
                    <a href="{{ route('dashboard') }}" class="role-switch-link" title="Buka Dashboard Operator Kecamatan">
                        <span>⇄</span> Beralih ke Operator
                    </a>
                    <div class="operator-chip">
                        <span>Camat Jatisari</span>
                        <b title="Camat Jatisari">CJ</b>
                    </div>
                </div>
            </header>

            <div class="dashboard-content">
                @yield('camat_content')
            </div>
        </main>
    </div>
</body>
</html>
