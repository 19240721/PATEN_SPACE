@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil')

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
            <a href="{{ route('dashboard') }}" class="nav-item">Dashboard</a>

            <div class="nav-group-label">Layanan PATEN</div>
            <a href="{{ route('register') }}" class="sidebar-button active">Registrasi / Pendaftaran</a>
            <a href="{{ route('laporan') }}" class="nav-item">Laporan</a>
        </div>
    </aside>

    <main class="main-panel">
        <header class="topbar">
            <div>
                <h1>Data Berhasil Ditambahkan</h1>
                <p>Registrasi layanan PATEN telah berhasil disimpan ke sistem.</p>
            </div>
        </header>

        <section class="success-card-wrap">
            <div class="success-card">
                <div class="success-icon">✓</div>
                <h2>Pendaftaran Berhasil</h2>
                <p>
                    @if(session('success'))
                        {{ session('success') }}
                    @else
                        Data pendaftaran berhasil disimpan.
                    @endif
                </p>

                @if($pendaftaran)
                    <div class="success-summary">
                        <div><span>Nama</span><strong>{{ $pendaftaran->nama_lengkap }}</strong></div>
                        <div><span>NIK</span><strong>{{ $pendaftaran->nik }}</strong></div>
                        <div><span>Layanan</span><strong>{{ $pendaftaran->jenis_layanan }}</strong></div>
                        <div><span>Tanggal</span><strong>{{ \Illuminate\Support\Facades\Date::parse($pendaftaran->tanggal_daftar)->format('d-m-Y') }}</strong></div>
                    </div>
                @endif

                <div class="success-actions">
                    <a href="{{ route('register') }}" class="primary-btn">Tambah Data Lagi</a>
                    <a href="{{ route('dashboard') }}" class="secondary-btn">Kembali ke Dashboard</a>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
