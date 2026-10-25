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
            <a href="{{ route('petugas.permohonan.index') }}" class="nav-item">
                <span>📥</span> Daftar Permohonan
                @if(($newCitizenCount ?? 0) > 0)
                    <span style="background:#ef4444;color:#fff;border-radius:999px;padding:2px 7px;font-size:11px;font-weight:700;margin-left:auto;">{{ $newCitizenCount }}</span>
                @endif
            </a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item"><span>📋</span> Semua Data</a>
            <a href="{{ route('laporan') }}" class="nav-item"><span>▥</span> Data dan Laporan</a>
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
            @if(($newCitizenCount ?? 0) > 0)
                <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 1px solid #f59e0b; color: #92400e; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="font-size: 1.5rem;">🔔</span>
                        <div>
                            <strong style="font-size: 0.95rem;">Ada {{ $newCitizenCount }} Permohonan Baru Masuk Otomatis dari Masyarakat!</strong>
                            <p style="font-size: 0.8rem; margin-top: 2px; color: #78350f;">Pengajuan daring warga otomatis masuk ke antrean dan siap diverifikasi.</p>
                        </div>
                    </div>
                    <a href="{{ route('petugas.permohonan.index') }}" style="background: #b45309; color: #ffffff; padding: 8px 16px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; text-decoration: none; white-space: nowrap;">Buka Pengajuan Baru ({{ $newCitizenCount }}) →</a>
                </div>
            @endif

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
                <div class="panel-heading">
                    <div>
                        <h2>Data Pelayanan Terbaru</h2>
                        <p>{{ min($pendaftarans->count(), 8) }} data pendaftaran & pengajuan terbaru</p>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <a href="{{ route('petugas.permohonan.index') }}" class="panel-link">Pengajuan Warga @if(($newCitizenCount ?? 0) > 0)<span style="background:#ef4444;color:#fff;border-radius:999px;padding:1px 6px;font-size:11px;font-weight:700;">{{ $newCitizenCount }}</span>@endif</a>
                        <a href="{{ route('pendaftaran.index') }}" class="panel-link">Semua Data</a>
                        <a href="{{ route('laporan') }}" class="panel-link">Lihat laporan</a>
                    </div>
                </div>
                <div class="table-scroll"><table>
                    <thead><tr><th>No</th><th>Tanggal</th><th>Nama</th><th>Jenis Pelayanan</th><th>Sumber</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse ($pendaftarans->take(8) as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->tanggal_daftar ? \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d/m/Y') : '—' }}</td>
                                <td class="person-name">{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->jenis_layanan }}</td>
                                <td>
                                    @if($item->intake_source === 'masyarakat')
                                        <span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:700;">Daring (Warga)</span>
                                    @else
                                        <span style="background:#e0f2fe;color:#0369a1;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:700;">Loket</span>
                                    @endif
                                </td>
                                <td><span class="status-pill {{ strtolower($item->status ?? 'baru') }}">{{ $item->status ?? 'Baru' }}</span></td>
                                <td>
                                    @if($item->intake_source === 'masyarakat')
                                        <a href="{{ route('petugas.permohonan.show', $item) }}" class="table-action edit" style="background:#059669;color:#fff;">Verifikasi</a>
                                    @else
                                        <a href="{{ route('pendaftaran.edit', $item) }}" class="table-action edit">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="empty-state">Belum ada data pendaftaran.</td></tr>
                        @endforelse
                    </tbody>
                </table></div>
            </section>
        </div>
    </main>
</div>
@endsection