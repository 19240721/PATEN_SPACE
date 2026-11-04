<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'PATEN_SPACE')</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @endif
    </head>
    <body>
        @yield('content')

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            if (!sidebar) return;
            if (localStorage.getItem('sidebar-collapsed') === '1') {
                sidebar.classList.add('collapsed');
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            }
            if (toggle) {
                toggle.addEventListener('click', function () {
                    const isCollapsed = sidebar.classList.toggle('collapsed');
                    toggle.setAttribute('aria-expanded', String(!isCollapsed));
                    localStorage.setItem('sidebar-collapsed', isCollapsed ? '1' : '0');
                });
            }
        });
        </script>
    </body>
</html>
