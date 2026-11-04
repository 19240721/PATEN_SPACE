@extends('layouts.app')

@section('title', 'Semua Data - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page">
    <aside class="sidebar dark-sidebar">
        <div class="brand-wrap" style="display: flex; align-items: center; justify-content: space-between;">
            <a href="{{ route('dashboard') }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: inherit; min-width: 0;">
                <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang" class="brand-mark">
                <span class="brand-text"><span class="brand-title">PATEN SPACE</span><span class="brand-sub">Pelayanan Kecamatan</span></span>
            </a>
            <button class="sidebar-close-btn" type="button" aria-label="Tutup sidebar" title="Tutup Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-group-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item"><span>◫</span> Dashboard</a>
            <a href="{{ route('pendaftaran.create') }}" class="nav-item"><span>＋</span> Pendaftaran Baru</a>
            <a href="{{ route('petugas.permohonan.index') }}" class="nav-item"><span>▣</span> Daftar Permohonan @if($newFromCitizen > 0)<span class="nav-counter">{{ $newFromCitizen }}</span>@endif</a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item active" aria-current="page"><span>▥</span> Semua Data</a>
            <a href="{{ route('laporan') }}" class="nav-item"><span>▥</span> Data dan Laporan</a>
            <details class="service-menu" open>
                <summary class="nav-item service-menu-toggle"><span>▤</span> Jenis Layanan <span class="menu-chevron">⌄</span></summary>
                <div class="service-submenu">
                    @foreach ([
                        ['type' => 'PRR', 'title' => 'PRR / KTP Baru'],
                        ['type' => 'KTP', 'title' => 'KTP'],
                        ['type' => 'KK', 'title' => 'Kartu Keluarga'],
                        ['type' => 'Akta Lahir', 'title' => 'Akta Kelahiran'],
                        ['type' => 'Akta Kematian', 'title' => 'Akta Kematian'],
                        ['type' => 'Kedatangan', 'title' => 'Kedatangan'],
                        ['type' => 'Pindah', 'title' => 'Pindah'],
                    ] as $service)
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
            <div style="display: flex; align-items: center; gap: 14px;">
                <button type="button" class="btn-sidebar-toggle" title="Buka / Tutup Sidebar" aria-label="Buka atau tutup sidebar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div><strong>Kecamatan Jatisari</strong><span>Kabupaten Karawang</span></div>
            </div>
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
                    <thead><tr><th>No</th><th>Nama</th><th>NIK</th><th>Jenis Layanan</th><th>Asal</th><th>Petugas</th><th>Dokumen</th><th>Tanggal Daftar</th><th>Status</th><th>Edit</th></tr></thead>
                    <tbody>
                        @forelse ($pendaftarans as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="person-name">{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->jenis_layanan }}</td>
                                <td>{{ $item->intake_source === 'masyarakat' ? 'Daring' : 'Loket' }}</td>
                                <td>{{ $item->petugas ?: '—' }}</td>
                                <td>
                                    @forelse ($item->documents ?? [] as $documentIndex => $document)
                                        <a href="{{ route('pendaftaran.document', [$item, $documentIndex]) }}" class="table-action edit">{{ $document['original_name'] }}</a><br>
                                    @empty
                                        —
                                    @endforelse
                                </td>
                                <td>{{ \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d/m/Y') }}</td>
                                <td><span class="status-pill {{ strtolower($item->status ?? 'baru') }}">{{ $item->status ?? 'Baru' }}</span></td>
                                <td><a href="{{ route('pendaftaran.edit', ['pendaftaran' => $item, 'jenis_layanan' => $item->jenis_layanan]) }}" class="table-action edit">Edit</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="empty-state">Belum ada data yang sesuai.</td></tr>
                        @endforelse
                    </tbody>
                </table></div>
            </section>
        </div>
    </main>
</div>
@endsection