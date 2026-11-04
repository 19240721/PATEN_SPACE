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
            /* Tombol Toggle Sidebar (Buka/Tutup) di Header & Sidebar */
            .btn-sidebar-toggle {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border-radius: 9px;
                background: #ffffff;
                border: 1.5px solid #d6dde5;
                color: #1e293b;
                cursor: pointer;
                box-shadow: 0 1px 3px rgba(0,0,0,0.08);
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                flex-shrink: 0;
            }
            .btn-sidebar-toggle:hover {
                background: #f1f5f9;
                border-color: #94a3b8;
                color: #0f172a;
                box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            }
            .btn-sidebar-toggle:active {
                transform: scale(0.95);
            }
            .btn-sidebar-toggle svg {
                display: block;
                width: 22px;
                height: 22px;
                stroke: currentColor;
            }

            /* Tombol Tutup di dalam Sidebar */
            .sidebar-close-btn {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                width: 34px;
                height: 34px;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.25);
                color: #ffffff;
                cursor: pointer;
                margin-left: auto;
                transition: all 0.2s ease;
                flex-shrink: 0;
            }
            .sidebar-close-btn:hover {
                background: rgba(255, 255, 255, 0.25);
                transform: scale(1.05);
            }
            .sidebar-close-btn svg {
                display: block;
                width: 18px;
                height: 18px;
                stroke: currentColor;
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

            // Pulihkan posisi sidebar sebelumnya dari localStorage
            if (localStorage.getItem('paten-sidebar-collapsed') === '1') {
                sidebar.classList.add('collapsed');
                if (appShell) appShell.classList.add('sidebar-collapsed');
            }

            // Pasang event listener ke semua tombol toggle buka/tutup
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
    </body>
</html>
