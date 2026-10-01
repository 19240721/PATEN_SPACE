@extends('layouts.app')

@section('title', 'Dashboard PATEN_SPACE')

@section('content')
@php
    $pendaftarans = \App\Models\Pendaftaran::latest('tanggal_daftar')->get();
    $total = $pendaftarans->count();
    $services = [
    ['label' => 'PRR', 'slug' => 'prr', 'title' => 'PRR / KTP Baru', 'icon' => '▤', 'tone' => 'blue'],
        ['label' => 'KTP', 'slug' => 'ktp', 'title' => 'KTP', 'icon' => '▣', 'tone' => 'blue'],
        ['label' => 'KK', 'slug' => 'kartu-keluarga', 'title' => 'Kartu Keluarga', 'icon' => '♧', 'tone' => 'green'],
        ['label' => 'Akta Lahir', 'slug' => 'akta-kelahiran', 'title' => 'Akta Kelahiran', 'icon' => '✳', 'tone' => 'gold'],
        ['label' => 'Akta Kematian', 'slug' => 'akta-kematian', 'title' => 'Akta Kematian', 'icon' => '▧', 'tone' => 'blue'],
        ['label' => 'Kedatangan', 'slug' => 'kedatangan', 'title' => 'Kedatangan', 'icon' => '⌂', 'tone' => 'green'],
        ['label' => 'Pindah', 'slug' => 'pindah', 'title' => 'Pindah', 'icon' => '→', 'tone' => 'blue'],
    ];
    foreach ($services as &$service) {
        $service['count'] = $pendaftarans->where('jenis_layanan', $service['label'])->count();
    }
    unset($service);
    $chartMax = max(1, max(array_column($services, 'count')));
    $year = now()->year;
    $monthlyCounts = collect(range(1, 12))->map(fn ($month) => $pendaftarans
        ->filter(fn ($item) => $item->tanggal_daftar && \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->year === $year && \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->month === $month)
        ->count());
    $monthlyMax = max(1, $monthlyCounts->max());
    $chartPoints = $monthlyCounts->map(fn ($count, $index) => (24 + ($index * 432 / 11)) . ',' . (128 - ($count / $monthlyMax * 100)))->implode(' ');
    $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
@endphp

<div class="app-shell dashboard-page">
    <aside class="sidebar dark-sidebar">
        <a href="{{ route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('image/logo-kecamatan.png') }}" alt="Logo Kecamatan Jatisari" class="brand-mark">
            <span class="brand-text"><span class="brand-title">PATEN SPACE</span><span class="brand-sub">Pelayanan Kecamatan</span></span>
        </a>
        <nav class="sidebar-nav">
            <div class="nav-group-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item active"><span>◫</span> Dashboard</a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item"><span>▥</span> Semua Data</a>
            <a href="{{ route('laporan') }}" class="nav-item"><span>▥</span> Laporan</a>
            <details class="service-menu" open>
                <summary class="nav-item service-menu-toggle"><span>▤</span> Jenis Layanan <span class="menu-chevron">⌄</span></summary>
                <div class="service-submenu">
                    @foreach ($services as $service)
                        <a href="{{ route('pelayanan.index', $service['slug']) }}" class="nav-item service-nav-item">{{ $service['title'] }}</a>
                    @endforeach
                </div>
            </details>
        </nav>
        <div class="sidebar-profile"><span class="profile-avatar">OP</span><span><strong>Operator Kecamatan</strong><small>Administrator</small></span></div>
    </aside>

    <main class="main-panel">
        <header class="dashboard-topbar">
            <div><strong>Kecamatan Jatisari</strong><span>Kabupaten Karawang</span></div>
            <div class="operator-chip"><span>Petugas PATEN</span><b>OP</b></div>
        </header>

        <div class="dashboard-content">
            <section class="dashboard-heading">
                <div><h1>Dashboard Pelayanan</h1><p>Ringkasan pelayanan administrasi Kecamatan Jatisari</p></div>
                <a href="{{ route('pendaftaran.create') }}" class="dashboard-primary-link"><span>+</span> Pendaftaran Baru</a>
            </section>

            <section class="dashboard-stats" aria-label="Ringkasan layanan">
                <a href="{{ route('pendaftaran.index') }}" class="dashboard-stat dashboard-stat-link total-stat"><span class="stat-symbol">▤</span><div><span>Total Pelayanan</span><strong>{{ number_format($total, 0, ',', '.') }}</strong></div></a>
                @foreach ($services as $service)
                    <a href="{{ route('pelayanan.index', $service['slug']) }}" class="dashboard-stat dashboard-stat-link"><span class="stat-symbol {{ $service['tone'] }}">{{ $service['icon'] }}</span><div><span>{{ $service['title'] }}</span><strong>{{ number_format($service['count'], 0, ',', '.') }}</strong></div></a>
                @endforeach
            </section>

            <section class="dashboard-charts">
                <article class="dashboard-panel chart-panel">
                    <div class="panel-heading"><div><h2>Pelayanan Berdasarkan Jenis</h2><p>Rekap jumlah layanan yang tercatat</p></div><span class="chart-year">{{ $year }}</span></div>
                    <div class="service-chart" role="img" aria-label="Grafik jumlah pendaftar menurut jenis pelayanan">
                        @foreach ($services as $service)
                            <div class="service-bar-column"><span class="bar-value">{{ $service['count'] }}</span><div class="service-bar" style="height: {{ max(4, $service['count'] / $chartMax * 100) }}%"></div><span class="bar-label">{{ $service['label'] === 'Akta Kematian' ? 'Akta Mati' : ($service['label'] === 'Akta Lahir' ? 'Akta Lahir' : $service['label']) }}</span></div>
                        @endforeach
                    </div>
                </article>

                <article class="dashboard-panel chart-panel">
                    <div class="panel-heading"><div><h2>Tren Pelayanan Bulanan</h2><p>Jumlah pendaftaran per bulan</p></div><span class="chart-year">{{ $year }}</span></div>
                    <div class="trend-chart" role="img" aria-label="Grafik tren pelayanan bulanan">
                        <svg viewBox="0 0 480 160" preserveAspectRatio="none" aria-hidden="true">
                            <line x1="24" y1="28" x2="456" y2="28" /><line x1="24" y1="78" x2="456" y2="78" /><line x1="24" y1="128" x2="456" y2="128" />
                            <polyline points="{{ $chartPoints }}" />
                            @foreach ($monthlyCounts as $index => $count)
                                <circle cx="{{ 24 + ($index * 432 / 11) }}" cy="{{ 128 - ($count / $monthlyMax * 100) }}" r="3.5" />
                            @endforeach
                        </svg>
                        <div class="month-labels">@foreach ($monthLabels as $label)<span>{{ $label }}</span>@endforeach</div>
                    </div>
                </article>
            </section>

            <section class="dashboard-panel recent-panel">
                <div class="panel-heading"><div><h2>Data Pelayanan Terbaru</h2><p>Aktivitas pendaftaran yang baru dicatat</p></div></div>
                <div class="table-scroll"><table>
                    <thead><tr><th>No</th><th>Tanggal</th><th>Nama</th><th>Jenis Pelayanan</th><th>Status</th><th>Edit</th></tr></thead>
                    <tbody>
                        @forelse ($pendaftarans->take(8) as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d/m/Y') }}</td>
                                <td class="person-name">{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->jenis_layanan }}</td>
                                <td><span class="status-pill {{ strtolower($item->status ?? 'baru') }}">{{ $item->status ?? 'Baru' }}</span></td>
                                <td><a href="{{ route('pendaftaran.edit', ['pendaftaran' => $item, 'jenis_layanan' => $item->jenis_layanan]) }}" class="table-action edit">Edit</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty-state">Belum ada data pendaftaran.</td></tr>
                        @endforelse
                    </tbody>
                </table></div>
            </section>
        </div>
    </main>
</div>
@endsection
