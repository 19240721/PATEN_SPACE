@extends('layouts.app')

@section('title', 'Data ' . $serviceTitle . ' - PATEN SPACE')

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
            <a href="{{ route('pendaftaran.index') }}" class="nav-item"><span>▥</span> Semua Data</a>
            <a href="{{ route('laporan') }}" class="nav-item"><span>▥</span> Laporan</a>
            <details class="service-menu" open>
                <summary class="nav-item service-menu-toggle"><span>▤</span> Jenis Layanan <span class="menu-chevron">⌄</span></summary>
                <div class="service-submenu">
                    @foreach ($services as $slug => $option)
                        <a href="{{ route('pelayanan.index', $slug) }}" class="nav-item service-nav-item {{ $slug === $serviceSlug ? 'active' : '' }}">{{ $option['title'] }}</a>
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
                <div><h1>Data {{ $serviceTitle }}</h1><p>Daftar pelayanan kategori {{ $serviceTitle }}.</p></div>
                <a href="{{ route('pendaftaran.create', ['jenis_layanan' => $serviceType]) }}" class="dashboard-primary-link"><span>+</span> Tambah Data</a>
            </section>

            <section class="dashboard-panel recent-panel service-list-panel">
                <div class="panel-heading"><div><h2>Daftar Pendaftar</h2><p>{{ $pendaftarans->count() }} data pelayanan</p></div></div>
                <div class="table-scroll"><table class="service-list-table">
                    <thead><tr><th>No</th>@foreach ($columns as $column)<th>{{ $column['label'] }}</th>@endforeach</tr></thead>
                    <tbody>
                        @forelse ($pendaftarans as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                @foreach ($columns as $column)
                                    @php($value = data_get($item, $column['field']))
                                    <td class="{{ in_array($column['field'], ['nama_lengkap', 'nama_alm', 'nama_anak_lahir', 'nama_kedatangan', 'nama_pindah'], true) ? 'person-name' : '' }}">
                                        @if (!blank($value) && ($column['date'] ?? false))
                                            {{ \Illuminate\Support\Facades\Date::parse($value)->format('d/m/Y') }}
                                        @else
                                            {{ filled($value) ? $value : '—' }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr><td colspan="{{ count($columns) + 1 }}" class="empty-state">Belum ada data untuk layanan {{ $serviceTitle }}.</td></tr>
                        @endforelse
                    </tbody>
                </table></div>
            </section>
        </div>
    </main>
</div>
@endsection
