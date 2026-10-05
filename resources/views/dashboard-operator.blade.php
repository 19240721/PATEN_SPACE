@extends('layouts.app')

@section('title', 'Dashboard Operator - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page">
    <aside class="sidebar dark-sidebar">
        <a href="{{ route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang" class="brand-mark">
            <span class="brand-text"><span class="brand-title">PATEN SPACE</span><span class="brand-sub">Pelayanan Kecamatan</span></span>
        </a>
        <nav class="sidebar-nav">
            <div class="nav-group-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item active"><span>◫</span> Dashboard</a>
            <a href="{{ route('pendaftaran.create') }}" class="nav-item"><span>＋</span> Pendaftaran Baru</a>
            <a href="{{ route('laporan') }}" class="nav-item"><span>▥</span> Data dan Laporan</a>
            <div class="nav-group-label">Jenis Layanan</div>
            @foreach ($services as $service)
                <a href="{{ route('pendaftaran.create', ['jenis_layanan' => $service['type']]) }}" class="nav-item service-nav-item">{{ $service['title'] }}</a>
            @endforeach
        </nav>
        <div class="sidebar-profile">
            <span class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <span><strong>{{ auth()->user()->name }}</strong><small>Operator Kecamatan</small></span>
        </div>
    </aside>

    <main class="main-panel">
        <header class="dashboard-topbar">
            <div><strong>Kecamatan Jatisari</strong><span>Kabupaten Karawang</span></div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="report-filter-button">Keluar</button>
            </form>
        </header>

        <div class="dashboard-content">
            <section class="dashboard-heading">
                <div><h1>Dashboard Pelayanan</h1><p>Ringkasan pelayanan administrasi Kecamatan Jatisari.</p></div>
                <a href="{{ route('pendaftaran.create') }}" class="dashboard-primary-link"><span>+</span> Pendaftaran Baru</a>
            </section>

            <section class="dashboard-stats" aria-label="Ringkasan layanan">
                <article class="dashboard-stat total-stat"><span class="stat-symbol">▤</span><div><span>Total Pelayanan</span><strong>{{ number_format($total, 0, ',', '.') }}</strong></div></article>
                @foreach ($services as $service)
                    <article class="dashboard-stat"><span class="stat-symbol {{ $service['tone'] }}">{{ $service['icon'] }}</span><div><span>{{ $service['title'] }}</span><strong>{{ number_format($service['count'], 0, ',', '.') }}</strong></div></article>
                @endforeach
            </section>

            <section class="dashboard-charts">
                <article class="dashboard-panel chart-panel">
                    <div class="panel-heading"><div><h2>Pelayanan Berdasarkan Jenis</h2><p>Jumlah pendaftaran tercatat</p></div><span class="chart-year">{{ $year }}</span></div>
                    <div class="service-chart" role="img" aria-label="Grafik jumlah pendaftar menurut jenis pelayanan">
                        @foreach ($services as $service)
                            <div class="service-bar-column"><span class="bar-value">{{ $service['count'] }}</span><div class="service-bar" style="height: {{ max(4, $service['count'] / max(1, max(array_column($services, 'count'))) * 100) }}%"></div><span class="bar-label">{{ $service['label'] }}</span></div>
                        @endforeach
                    </div>
                </article>

                <article class="dashboard-panel chart-panel">
                    <div class="panel-heading"><div><h2>Tren Pelayanan Bulanan</h2><p>Jumlah pendaftaran per bulan</p></div><span class="chart-year">{{ $year }}</span></div>
                    <div class="trend-chart" role="img" aria-label="Grafik tren pelayanan bulanan">
                        <svg viewBox="0 0 480 160" preserveAspectRatio="none" aria-hidden="true">
                            <line x1="24" y1="28" x2="456" y2="28"/><line x1="24" y1="78" x2="456" y2="78"/><line x1="24" y1="128" x2="456" y2="128"/>
                            <polyline points="{{ $chartPoints }}"/>
                            @foreach ($monthlyCounts as $index => $count)
                                <circle cx="{{ 24 + ($index * 432 / 11) }}" cy="{{ 128 - ($count / $monthlyMax * 100) }}" r="3.5"/>
                            @endforeach
                        </svg>
                        <div class="month-labels">@foreach ($monthLabels as $label)<span>{{ $label }}</span>@endforeach</div>
                    </div>
                </article>
            </section>

            <section class="dashboard-panel recent-panel">
                <div class="panel-heading"><div><h2>Data Pelayanan Terbaru</h2><p>{{ min($pendaftarans->count(), 8) }} data terbaru</p></div><a href="{{ route('laporan') }}" class="panel-link">Lihat laporan</a></div>
                <div class="table-scroll"><table>
                    <thead><tr><th>No</th><th>Tanggal</th><th>Nama</th><th>Jenis Pelayanan</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse ($pendaftarans->take(8) as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->tanggal_daftar ? \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d/m/Y') : '—' }}</td>
                                <td class="person-name">{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->jenis_layanan }}</td>
                                <td><span class="status-pill {{ strtolower($item->status ?? 'baru') }}">{{ $item->status ?? 'Baru' }}</span></td>
                                <td><a href="{{ route('pendaftaran.edit', $item) }}" class="table-action edit">Edit</a></td>
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