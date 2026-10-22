@extends('layouts.app')

@section('title', 'Dashboard Petugas - PATEN SPACE')

@section('content')
<div class="app-shell">
    <aside class="sidebar dark-sidebar">
        <button class="sidebar-toggle" type="button" aria-label="Buka atau tutup sidebar" aria-expanded="true">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="brand-wrap">
            <div class="brand-mark">P</div>
            <div class="brand-text">
                <div class="brand-title">PATEN SPACE</div>
                <div class="brand-sub">Kecamatan Jatisari</div>
            </div>
        </div>

        <div class="sidebar-nav">
            <div class="nav-group-label">Dashboard</div>
            <a href="{{ route('petugas.dashboard') }}" class="nav-item active">Dashboard</a>

            <div class="nav-group-label">Permohonan</div>
            <a href="{{ route('petugas.permohonan.index') }}" class="nav-item">
                Permohonan Masuk
                @if($newFromCitizen > 0)
                    <span style="background:#ef4444;color:#fff;border-radius:9999px;padding:2px 8px;font-size:11px;margin-left:6px;font-weight:700;">{{ $newFromCitizen }}</span>
                @endif
            </a>
            <a href="{{ route('pendaftaran.create') }}" class="nav-item">Input Manual</a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item">Semua Data</a>

            <div class="nav-group-label">Laporan</div>
            <a href="{{ route('laporan') }}" class="nav-item">Laporan</a>
        </div>

        <form method="POST" action="{{ route('logout') }}" style="padding: 16px;">
            @csrf
            <button class="logout-btn" type="submit">Keluar</button>
        </form>
    </aside>

    <main class="main-panel">
        <header class="topbar">
            <div>
                <h1>Dashboard Petugas</h1>
                <p>Selamat datang, {{ auth()->user()->name }}. Berikut ringkasan layanan PATEN Kecamatan Jatisari.</p>
            </div>
            <div class="date-pill">{{ now()->translatedFormat('l, d F Y') }}</div>
        </header>

        @if(session('success'))
            <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:12px 16px;border-radius:8px;margin:16px 24px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Stat Cards --}}
        <section class="stats-grid">
            <article class="stat-card">
                <div class="stat-icon green">🧾</div>
                <div class="stat-content">
                    <div class="stat-label">Total Permohonan</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-trend up">↑ Data real-time</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon mint">🆕</div>
                <div class="stat-content">
                    <div class="stat-label">Baru</div>
                    <div class="stat-value">{{ $stats['baru'] }}</div>
                    <div class="stat-trend">Menunggu proses</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon violet">⚙️</div>
                <div class="stat-content">
                    <div class="stat-label">Dalam Proses</div>
                    <div class="stat-value">{{ $stats['proses'] }}</div>
                    <div class="stat-trend">Sedang diproses</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon green">✅</div>
                <div class="stat-content">
                    <div class="stat-label">Selesai</div>
                    <div class="stat-value">{{ $stats['selesai'] }}</div>
                    <div class="stat-trend up">Berhasil diselesaikan</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon orange">❌</div>
                <div class="stat-content">
                    <div class="stat-label">Ditolak</div>
                    <div class="stat-value">{{ $stats['ditolak'] }}</div>
                    <div class="stat-trend">Persyaratan tidak memenuhi</div>
                </div>
            </article>
        </section>

        {{-- Quick Actions --}}
        <section class="content-grid">
            <div class="service-panel">
                <div class="section-header">
                    <h2>Aksi Cepat</h2>
                </div>
                <div class="quick-actions">
                    <a href="{{ route('pendaftaran.create') }}" class="action-card">
                        <span class="action-icon blue">📝</span>
                        <strong>Input Manual</strong>
                        <small>Input data dari masyarakat</small>
                    </a>
                    <a href="{{ route('petugas.permohonan.index') }}" class="action-card">
                        <span class="action-icon green">📋</span>
                        <strong>Lihat Semua Permohonan</strong>
                        <small>
                            Permohonan masuk dari masyarakat
                            @if($newFromCitizen > 0)
                                <span style="color:#ef4444;font-weight:700;">({{ $newFromCitizen }} baru)</span>
                            @endif
                        </small>
                    </a>
                    <a href="{{ route('laporan') }}" class="action-card">
                        <span class="action-icon gold">📊</span>
                        <strong>Laporan</strong>
                        <small>Lihat laporan layanan</small>
                    </a>
                </div>
            </div>
        </section>

        {{-- Tabel Permohonan Terbaru --}}
        <section class="table-panel">
            <div class="section-header">
                <h2>Permohonan Terbaru</h2>
                <a href="{{ route('petugas.permohonan.index') }}">Lihat Semua</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Jenis Layanan</th>
                        <th>Tanggal Daftar</th>
                        <th>Sumber</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendaftarans as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->nik }}</td>
                            <td>{{ $item->jenis_layanan }}</td>
                            <td>{{ \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d-m-Y') }}</td>
                            <td>
                                @if($item->intake_source === 'masyarakat')
                                    <span style="background:#d1fae5;color:#065f46;padding:3px 10px;border-radius:9999px;font-size:12px;font-weight:600;">Mandiri</span>
                                @else
                                    <span style="background:#dbeafe;color:#1e40af;padding:3px 10px;border-radius:9999px;font-size:12px;font-weight:600;">Petugas</span>
                                @endif
                            </td>
                            <td>
                                @php $status = $item->status ?? 'Baru'; @endphp
                                @if($status === 'Selesai')
                                    <span class="badge selected">{{ $status }}</span>
                                @elseif($status === 'Proses')
                                    <span class="badge processing">{{ $status }}</span>
                                @elseif($status === 'Ditolak')
                                    <span class="badge" style="background:#fee2e2;color:#991b1b;">{{ $status }}</span>
                                @else
                                    <span class="badge" style="background:#f3f4f6;color:#374151;">{{ $status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('petugas.permohonan.show', $item) }}" class="table-action edit">Detail</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; color:#667085; padding: 24px;">Belum ada data permohonan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            if (!sidebar || !toggle) return;
            toggle.addEventListener('click', function () {
                const isCollapsed = sidebar.classList.toggle('collapsed');
                toggle.setAttribute('aria-expanded', String(!isCollapsed));
            });
        });
    </script>
</div>
@endsection
