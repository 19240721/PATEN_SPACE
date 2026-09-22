@extends('layouts.app')

@section('title', 'Laporan PATEN')

@section('content')
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand-wrap">
            <div class="brand-mark">P</div>
            <div class="brand-text">
                <div class="brand-title">PATEN SPACE</div>
                <div class="brand-sub">Kecamatan Jatisari</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-group-label">Dashboard</div>
            <a href="{{ route('dashboard') }}" class="nav-item">Dashboard</a>
            <div class="nav-group-label">Layanan PATEN</div>
            <a href="{{ route('register') }}" class="nav-item">Registrasi / Pendaftaran</a>
            <a href="{{ route('laporan') }}" class="nav-item active">Laporan</a>
        </nav>

        <button class="logout-btn">Keluar</button>
    </aside>

    <main class="main-panel">
        <header class="topbar">
            <div>
                <h1>Laporan</h1>
                <p>Ringkasan data pelayanan PATEN Kecamatan Jatisari.</p>
            </div>
            <div class="date-pill">{{ now()->translatedFormat('l, d F Y') }}</div>
        </header>

        <section class="stats-grid">
            <article class="stat-card">
                <div class="stat-icon green">📊</div>
                <div class="stat-content">
                    <div class="stat-label">Total Pendaftar</div>
                    <div class="stat-value">{{ $total }}</div>
                    <div class="stat-trend up">Semua data</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon mint">👥</div>
                <div class="stat-content">
                    <div class="stat-label">PRR / KTP Baru</div>
                    <div class="stat-value">{{ $prr }}</div>
                    <div class="stat-trend up">Data terdaftar</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon violet">🪪</div>
                <div class="stat-content">
                    <div class="stat-label">KTP / Pembaharuan</div>
                    <div class="stat-value">{{ $ktp }}</div>
                    <div class="stat-trend up">Data terdaftar</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon violet">👨‍👩‍👧‍👦</div>
                <div class="stat-content">
                    <div class="stat-label">KK</div>
                    <div class="stat-value">{{ $kk }}</div>
                    <div class="stat-trend up">Data terdaftar</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon orange">📘</div>
                <div class="stat-content">
                    <div class="stat-label">Kartu Kuning</div>
                    <div class="stat-value">{{ $kartuKuning }}</div>
                    <div class="stat-trend up">Data terdaftar</div>
                </div>
            </article>
        </section>

        <section class="table-panel">
            <div class="section-header">
                <h2>Daftar Pendaftaran</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis Layanan</th>
                        <th>Petugas</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendaftarans as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->jenis_layanan }}</td>
                            <td>{{ $item->petugas ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d-m-Y') }}</td>
                            <td>
                                <span class="badge {{ strtolower($item->status) === 'selesai' ? 'selected' : 'processing' }}">
                                    {{ $item->status ?? 'Baru' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; color:#667085; padding: 24px;">Belum ada data laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>
</div>
@endsection
