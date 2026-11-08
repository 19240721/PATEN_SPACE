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
            <details class="service-menu" open>
                <summary class="nav-item service-menu-toggle"><span>▤</span> Jenis Layanan <span class="menu-chevron">⌄</span></summary>
                <div class="service-submenu">
                    @foreach ($services as $service)
                        <a href="{{ route('pendaftaran.create', ['jenis_layanan' => $service['type']]) }}" class="nav-item service-nav-item">{{ $service['title'] }}</a>
                    @endforeach
                </div>
            </details>
        </nav>
        <a href="{{ route('profil') }}" class="sidebar-profile sidebar-profile-link" aria-label="Buka profil {{ auth()->user()->name }}">
            @if(auth()->user()?->foto_profil_url)
                <img src="{{ auth()->user()->foto_profil_url }}" alt="{{ auth()->user()->name }}" class="profile-avatar" style="object-fit: cover;">
            @else
                <span class="profile-avatar">{{ auth()->user()?->inisial ?? strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            @endif
            <span><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->peran_layanan === 'Operator' ? 'Operator Kecamatan' : 'Petugas Loket' }}</small></span>
        </a>
    </aside>

    <main class="main-panel">
        <header class="dashboard-topbar">
            <div style="display: flex; flex-direction: row; align-items: center; gap: 12px;">
                <button type="button" class="btn-sidebar-toggle" title="Buka / Tutup Sidebar" aria-label="Buka atau tutup sidebar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div style="display: flex; flex-direction: column; gap: 1px;">
                    <strong>Kecamatan Jatisari</strong>
                    <span>Kabupaten Karawang</span>
                </div>
            </div>
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
