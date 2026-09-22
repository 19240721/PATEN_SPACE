@extends('layouts.app')

@section('title', 'Dashboard PATEN_SPACE')

@section('content')
<div class="app-shell">
    <aside class="sidebar dark-sidebar">
        <div class="brand-wrap">
            <div class="brand-mark">P</div>
            <div class="brand-text">
                <div class="brand-title">PATEN SPACE</div>
                <div class="brand-sub">Kecamatan Jatisari</div>
            </div>
        </div>

        <div class="sidebar-nav">
            <div class="nav-group-label">Dashboard</div>
            <a href="{{ route('dashboard') }}" class="nav-item active">Dashboard</a>

            <div class="nav-group-label">Layanan PATEN</div>
            <a href="{{ route('pendaftaran.create') }}" class="sidebar-button active">Registrasi / Pendaftaran</a>
            <a href="#" class="nav-item">Data Penduduk</a>

            <div class="nav-group-label">Pengelolaan</div>
            <a href="#" class="nav-item">Data Pengguna</a>
            <a href="#" class="nav-item">Laporan</a>
            <a href="#" class="nav-item">Pengaturan</a>
        </div>

        <div class="filter-bar">
            <button type="button" class="filter-tab">PRR / KTP Baru</button>
            <button type="button" class="filter-tab active">KK</button>
            <button type="button" class="filter-tab">KTP / Pembaharuan</button>
            <button type="button" class="filter-tab">AKTA KEMATIAN</button>
            <button type="button" class="filter-tab">AKTA LAHIR</button>
            <button type="button" class="filter-tab">KEDATANGAN</button>
            <button type="button" class="filter-tab">PINDAH</button>
        </div>
    </aside>

    <main class="main-panel">
        <header class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Selamat datang, Petugas. Berikut informasi layanan PATEN Kecamatan Jatisari.</p>
            </div>
            <div class="date-pill">Senin, 8 September 2025</div>
        </header>

        @php
            $pendaftarans = \App\Models\Pendaftaran::latest('tanggal_daftar')->get();
            $total = $pendaftarans->count();
            $prr = $pendaftarans->where('jenis_layanan', 'PRR')->count();
            $ktp = $pendaftarans->where('jenis_layanan', 'KTP')->count();
            $kk = $pendaftarans->where('jenis_layanan', 'KK')->count();
            $kartuKuning = $pendaftarans->where('jenis_layanan', 'Kartu Kuning')->count();
        @endphp

        <section class="stats-grid">
            <article class="stat-card">
                <div class="stat-icon green">🧾</div>
                <div class="stat-content">
                    <div class="stat-label">Total Pendaftar</div>
                    <div class="stat-value">{{ $total }}</div>
                    <div class="stat-trend up">↑ Data real-time</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon mint">👥</div>
                <div class="stat-content">
                    <div class="stat-label">PRR / KTP Baru</div>
                    <div class="stat-value">{{ $prr }}</div>
                    <div class="stat-trend up">↑ Data real-time</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon violet">🪪</div>
                <div class="stat-content">
                    <div class="stat-label">KTP / Pembaharuan</div>
                    <div class="stat-value">{{ $ktp }}</div>
                    <div class="stat-trend up">↑ Data real-time</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon violet">👨‍👩‍👧‍👦</div>
                <div class="stat-content">
                    <div class="stat-label">KK</div>
                    <div class="stat-value">{{ $kk }}</div>
                    <div class="stat-trend up">↑ Data real-time</div>
                </div>
            </article>

            <article class="stat-card">
                <div class="stat-icon orange">📘</div>
                <div class="stat-content">
                    <div class="stat-label">Kartu Kuning</div>
                    <div class="stat-value">{{ $kartuKuning }}</div>
                    <div class="stat-trend up">↑ Data real-time</div>
                </div>
            </article>
        </section>

        <section class="content-grid">
            <div class="service-panel">
                <div class="section-header">
                    <h2>Menu Layanan</h2>
                </div>
                <div class="quick-actions">
                    <a href="{{ route('pendaftaran.create') }}" class="action-card">
                        <span class="action-icon blue">👤</span>
                        <strong>Registrasi Pendataran</strong>
                        <small>Input data pendaftar</small>
                    </a>
                    <a href="#" class="action-card">
                        <span class="action-icon green">📋</span>
                        <strong>Data Penduduk</strong>
                        <small>Kelola data penduduk</small>
                    </a>
                    <a href="{{ route('laporan') }}" class="action-card">
                        <span class="action-icon gold">📊</span>
                        <strong>Laporan</strong>
                        <small>Lihat laporan harian</small>
                    </a>
                </div>
            </div>

            <aside class="activity-panel">
                <div class="section-header">
                    <h2>Log Aktivitas Terbaru</h2>
                    <a href="#">Lihat Semua</a>
                </div>
                <ul class="activity-list">
                    <li>
                        <span class="activity-dot"></span>
                        <div class="activity-meta">
                            <strong>Pendaftaran KTP baru</strong>
                            <small>Nama: Andi Saputra</small>
                        </div>
                        <span class="activity-time">08:42</span>
                    </li>
                    <li>
                        <span class="activity-dot"></span>
                        <div class="activity-meta">
                            <strong>Perubahan KK</strong>
                            <small>Nama: Siti Nurhaliza</small>
                        </div>
                        <span class="activity-time">08:17</span>
                    </li>
                    <li>
                        <span class="activity-dot"></span>
                        <div class="activity-meta">
                            <strong>Pendaftaran KTP / Pembaharuan</strong>
                            <small>Nama: Budi Dwi Kurniawan</small>
                        </div>
                        <span class="activity-time">07:55</span>
                    </li>
                    <li>
                        <span class="activity-dot"></span>
                        <div class="activity-meta">
                            <strong>Pendaftaran Kartu Kuning</strong>
                            <small>Nama: Sulastri</small>
                        </div>
                        <span class="activity-time">07:32</span>
                    </li>
                    <li>
                        <span class="activity-dot"></span>
                        <div class="activity-meta">
                            <strong>Pendaftaran KTP baru</strong>
                            <small>Nama: Dwi Suci</small>
                        </div>
                        <span class="activity-time">07:10</span>
                    </li>
                </ul>
            </aside>
        </section>

        <section class="table-panel">
            <div class="section-header">
                <h2>Data Pendaftaran Terbaru</h2>
                <a href="{{ route('laporan') }}">Lihat Semua</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis Layanan</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendaftarans as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->jenis_layanan }}</td>
                            <td>{{ \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d-m-Y') }}</td>
                            <td>
                                <span class="badge {{ strtolower($item->status) === 'selesai' ? 'selected' : 'processing' }}">
                                    {{ $item->status ?? 'Baru' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('pendaftaran.edit', $item) }}" class="table-action edit">Edit</a>
                                    <form action="{{ route('pendaftaran.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="table-action delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; color:#667085; padding: 24px;">Belum ada data pendaftaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>
</div>
@endsection
