<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'PATEN_SPACE')</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
        @endif

        <style>
            /* Tombol Toggle Sidebar (Buka/Tutup) Rapi & Sejajar */
            .btn-sidebar-toggle {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 36px !important;
                height: 36px !important;
                border-radius: 8px !important;
                background: #ffffff !important;
                border: 1px solid #d5cebf !important;
                color: #5b4b2d !important;
                cursor: pointer !important;
                box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
                transition: all 0.18s ease !important;
                flex-shrink: 0 !important;
                padding: 0 !important;
            }
            .btn-sidebar-toggle:hover {
                background: #f7f4ed !important;
                border-color: #a89878 !important;
                color: #3b2f19 !important;
                box-shadow: 0 2px 6px rgba(0,0,0,0.1) !important;
            }
            .btn-sidebar-toggle:active {
                transform: scale(0.95) !important;
            }
            .btn-sidebar-toggle svg {
                display: block;
                width: 20px;
                height: 20px;
                stroke: currentColor;
            }

            /* Perbaikan tata letak topbar agar selalu sejajar horizontal */
            .dashboard-topbar > div:first-child {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                gap: 12px !important;
            }

            /* Transisi Halus Buka-Tutup Sidebar */
            .sidebar {
                transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                            max-width 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                            flex-basis 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                            padding 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                            margin 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                            opacity 0.22s ease !important;
            }

            /* Kondisi Sidebar Tertutup (Collapsed) */
            .app-shell.sidebar-collapsed .sidebar,
            .sidebar.collapsed,
            .dashboard-page .sidebar.collapsed {
                width: 0 !important;
                min-width: 0 !important;
                max-width: 0 !important;
                flex: 0 0 0px !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-left: 0 !important;
                overflow: hidden !important;
                opacity: 0 !important;
                visibility: hidden !important;
                pointer-events: none !important;
                border: none !important;
            }

            .app-shell.sidebar-collapsed .main-panel,
            .sidebar.collapsed ~ .main-panel {
                flex: 1 1 100% !important;
                width: 100% !important;
                min-width: 0 !important;
                transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }
        </style>
    </head>
    <body>
        @yield('content')

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const appShell = document.querySelector('.app-shell');
            const sidebar = document.querySelector('.sidebar');
            const toggles = document.querySelectorAll('.btn-sidebar-toggle, .sidebar-toggle, .sidebar-close-btn');

            if (!sidebar) return;

            // Pulihkan status sidebar dari localStorage
            if (localStorage.getItem('paten-sidebar-collapsed') === '1') {
                sidebar.classList.add('collapsed');
                if (appShell) appShell.classList.add('sidebar-collapsed');
            }

            toggles.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const isCollapsed = sidebar.classList.toggle('collapsed');
                    if (appShell) {
                        appShell.classList.toggle('sidebar-collapsed', isCollapsed);
                    }
                    localStorage.setItem('paten-sidebar-collapsed', isCollapsed ? '1' : '0');
                });
            });
        });
        </script>

        @include('partials.logout-modal')
    </body>
</html>
