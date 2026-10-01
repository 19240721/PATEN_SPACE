@extends('layouts.app')

@section('title', 'Semua Data - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page">
    <aside class="sidebar dark-sidebar">
        <a href="{{ route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('image/logo-kecamatan.png') }}" alt="Logo Kecamatan Jatisari" class="brand-mark">
            <span class="brand-text"><span class="brand-title">PATEN SPACE</span><span class="brand-sub">Pelayanan Kecamatan</span></span>
        </a>
        <nav class="sidebar-nav">
            <div class="nav-group-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item"><span>◫</span> Dashboard</a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item active"><span>▥</span> Semua Data</a>
            <a href="{{ route('laporan') }}" class="nav-item"><span>▧</span> Laporan</a>
            <details class="service-menu" open>
                <summary class="nav-item service-menu-toggle"><span>▤</span> Jenis Layanan <span class="menu-chevron">⌄</span></summary>
                <div class="service-submenu">
                    @foreach ($services as $slug => $service)
                        <a href="{{ route('pelayanan.index', $slug) }}" class="nav-item service-nav-item">{{ $service['title'] }}</a>
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
                <div><h1>Semua Data Pelayanan</h1><p>Daftar gabungan seluruh pelayanan PATEN.</p></div>
                <a href="{{ route('pendaftaran.create') }}" class="dashboard-primary-link"><span>+</span> Tambah Data</a>
            </section>

            <section class="dashboard-stats report-stats" aria-label="Jumlah data">
                <article class="dashboard-stat total-stat"><span class="stat-symbol">▥</span><div><span>Total Data</span><strong>{{ number_format($total, 0, ',', '.') }}</strong></div></article>
            </section>

            <section class="dashboard-panel recent-panel report-panel">
                <div class="panel-heading"><div><h2>Daftar Pendaftaran</h2><p>{{ $pendaftarans->count() }} data ditampilkan</p></div></div>
                <form method="GET" action="{{ route('pendaftaran.index') }}" class="report-filters">
                    <label class="report-search"><span aria-hidden="true">⌕</span><input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIK..."></label>
                    <select name="jenis_layanan" aria-label="Filter jenis layanan">
                        <option value="">Semua Layanan</option>
                        @foreach ($services as $slug => $service)
                            <option value="{{ $slug }}" {{ request('jenis_layanan') === $slug ? 'selected' : '' }}>{{ $service['title'] }}</option>
                        @endforeach
                    </select>
                    <select name="status" aria-label="Filter status">
                        <option value="">Semua Status</option>
                        @foreach (['Baru', 'Proses', 'Selesai'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="report-filter-button">Terapkan</button>
                    @if (request()->filled('q') || request()->filled('jenis_layanan') || request()->filled('status'))
                        <a href="{{ route('pendaftaran.index') }}" class="report-reset">Reset</a>
                    @endif
                </form>
                <div class="table-scroll"><table class="report-table">
                    <thead><tr><th>No</th><th>Nama</th><th>NIK</th><th>Jenis Layanan</th><th>Petugas</th><th>Tanggal Daftar</th><th>Status</th><th>Edit</th></tr></thead>
                    <tbody>
                        @forelse ($pendaftarans as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="person-name">{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->jenis_layanan }}</td>
                                <td>{{ $item->petugas ?: '—' }}</td>
                                <td>{{ \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d/m/Y') }}</td>
                                <td><span class="status-pill {{ strtolower($item->status ?? 'baru') }}">{{ $item->status ?? 'Baru' }}</span></td>
                                <td><a href="{{ route('pendaftaran.edit', ['pendaftaran' => $item, 'jenis_layanan' => $item->jenis_layanan]) }}" class="table-action edit">Edit</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="empty-state">Belum ada data yang sesuai.</td></tr>
                        @endforelse
                    </tbody>
                </table></div>
            </section>
        </div>
    </main>
</div>
@endsection