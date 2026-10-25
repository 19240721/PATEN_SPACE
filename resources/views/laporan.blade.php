@extends('layouts.app')

@section('title', 'Laporan PATEN')

@section('content')
<div class="app-shell dashboard-page report-page">
    <aside class="sidebar dark-sidebar">
        <a href="{{ route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang" class="brand-mark">
            <span class="brand-text"><span class="brand-title">PATEN SPACE</span><span class="brand-sub">Pelayanan Kecamatan</span></span>
        </a>
        <nav class="sidebar-nav">
            <div class="nav-group-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item"><span>◫</span> Dashboard</a>
            <a href="{{ route('pendaftaran.create') }}" class="nav-item"><span>＋</span> Pendaftaran Baru</a>
            <a href="{{ route('petugas.permohonan.index') }}" class="nav-item"><span>▣</span> Daftar Permohonan @if($newFromCitizen > 0)<span class="nav-counter">{{ $newFromCitizen }}</span>@endif</a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item"><span>▥</span> Semua Data</a>
            <a href="{{ route('laporan') }}" class="nav-item active" aria-current="page"><span>▥</span> Data dan Laporan</a>
            <details class="service-menu" open>
                <summary class="nav-item service-menu-toggle"><span>▤</span> Jenis Layanan <span class="menu-chevron">⌄</span></summary>
                <div class="service-submenu">
                    @foreach ($services as $service)
                        <a href="{{ route('pendaftaran.create', ['jenis_layanan' => $service['type']]) }}" class="nav-item service-nav-item">{{ $service['title'] }}</a>
                    @endforeach
                </div>
            </details>
        </nav>
        <a href="{{ route('account.profile') }}" class="sidebar-profile sidebar-profile-link" aria-label="Buka profil {{ auth()->user()->name }}">
            <span class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <span><strong>{{ auth()->user()->name }}</strong><small>Operator Kecamatan</small></span>
        </a>
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
                <div><h1>Laporan Pelayanan</h1><p>Ringkasan data pelayanan PATEN Kecamatan Jatisari.</p></div>
                <span class="chart-year">{{ now()->translatedFormat('l, d F Y') }}</span>
            </section>

            <section class="dashboard-stats report-stats" aria-label="Ringkasan laporan">
                <article class="dashboard-stat total-stat"><span class="stat-symbol">▤</span><div><span>Total Pendaftar</span><strong>{{ number_format($total, 0, ',', '.') }}</strong></div></article>
                <article class="dashboard-stat"><span class="stat-symbol">▥</span><div><span>PRR / KTP Baru</span><strong>{{ number_format($prr, 0, ',', '.') }}</strong></div></article>
                <article class="dashboard-stat"><span class="stat-symbol">▣</span><div><span>KTP / Pembaharuan</span><strong>{{ number_format($ktp, 0, ',', '.') }}</strong></div></article>
                <article class="dashboard-stat"><span class="stat-symbol">♧</span><div><span>Kartu Keluarga</span><strong>{{ number_format($kk, 0, ',', '.') }}</strong></div></article>
                <article class="dashboard-stat"><span class="stat-symbol">▤</span><div><span>Kartu Kuning</span><strong>{{ number_format($kartuKuning, 0, ',', '.') }}</strong></div></article>
            </section>

            <section class="dashboard-panel recent-panel report-panel">
                <div class="panel-heading"><div><h2>Daftar Pendaftaran</h2><p>{{ $pendaftarans->count() }} data ditampilkan</p></div></div>
                <div class="table-scroll"><table class="report-table">
                    <thead><tr><th>No</th><th>Nama</th><th>Jenis Layanan</th><th>Petugas</th><th>Tanggal Daftar</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($pendaftarans as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="person-name">{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->jenis_layanan === 'KK' ? 'Kartu Keluarga' : $item->jenis_layanan }}</td>
                                <td>{{ $item->petugas ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d-m-Y') }}</td>
                                <td><span class="status-pill {{ strtolower($item->status ?? 'baru') }}">{{ $item->status ?? 'Baru' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty-state">Belum ada data laporan.</td></tr>
                        @endforelse
                    </tbody>
                </table></div>
            </section>
        </div>
    </main>
</div>
@endsection
