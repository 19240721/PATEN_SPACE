@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - PATEN SPACE')

@section('content')
<div class="app-shell form-page-shell">
    <aside class="sidebar">
        <div class="brand-wrap" style="display: flex; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div class="brand-mark">P</div>
                <div class="brand-text">
                    <div class="brand-title">PATEN SPACE</div>
                    <div class="brand-sub">Kecamatan Jatisari</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-group-label">Dashboard</div>
            <a href="{{ route('dashboard') }}" class="nav-item">Dashboard</a>

            <div class="nav-group-label">Layanan PATEN</div>
            <a href="{{ route('pendaftaran.create') }}" class="nav-item active">Registrasi / Pendaftaran</a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item">Semua Data</a>
            <a href="{{ route('laporan') }}" class="nav-item">Laporan</a>
        </nav>

        @auth
        <a href="{{ route('account.profile') }}" class="sidebar-profile sidebar-profile-link" aria-label="Buka profil {{ auth()->user()->name }}">
            <span class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <span><strong>{{ auth()->user()->name }}</strong><small>{{ ucfirst(auth()->user()->role) }} PATEN</small></span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn" type="submit">Keluar</button>
        </form>
        @endauth
    </aside>

    <main class="main-panel form-page-main">
        <header class="topbar form-topbar">
            <div style="display: flex; align-items: center; gap: 14px;">
                <button type="button" class="btn-sidebar-toggle" title="Buka / Tutup Sidebar" aria-label="Buka atau tutup sidebar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div>
                    <h1>Data Berhasil Ditambahkan</h1>
                    <p>Registrasi layanan PATEN telah berhasil disimpan ke sistem.</p>
                </div>
            </div>
            <div class="date-pill">{{ now()->translatedFormat('d F Y') }}</div>
        </header>

        <section style="display: flex; justify-content: center; padding: 20px 0 40px;">
            <div class="success-card-box">
                {{-- Icon Lingkaran Sukses --}}
                <div class="success-icon-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </div>

                <h2 class="success-title">Pendaftaran Berhasil!</h2>
                <p class="success-subtitle">
                    {{ session('success') ?? 'Data permohonan layanan telah berhasil disimpan dan tercatat ke sistem.' }}
                </p>

                @if($pendaftaran)
                    <div class="success-receipt">
                        <div class="receipt-header">
                            <span class="receipt-title">📋 Ringkasan Pendaftaran</span>
                            <span class="receipt-badge">✓ Tersimpan</span>
                        </div>

                        <div class="receipt-grid">
                            <div class="receipt-item">
                                <span class="receipt-label">Nama Pemohon</span>
                                <strong class="receipt-value">{{ $pendaftaran->nama_lengkap ?? '-' }}</strong>
                            </div>

                            <div class="receipt-item">
                                <span class="receipt-label">Nomor Induk Kependudukan (NIK)</span>
                                <strong class="receipt-value monospace">{{ $pendaftaran->nik ?? '-' }}</strong>
                            </div>

                            <div class="receipt-item">
                                <span class="receipt-label">Jenis Layanan</span>
                                <span class="service-pill-badge">{{ $pendaftaran->jenis_layanan ?? '-' }}</span>
                            </div>

                            <div class="receipt-item">
                                <span class="receipt-label">Tanggal Pendaftaran</span>
                                <strong class="receipt-value">{{ $pendaftaran->tanggal_daftar ? \Illuminate\Support\Facades\Date::parse($pendaftaran->tanggal_daftar)->translatedFormat('d F Y') : '-' }}</strong>
                            </div>

                            @if($pendaftaran->nomor_kk)
                            <div class="receipt-item">
                                <span class="receipt-label">Nomor Kartu Keluarga (KK)</span>
                                <strong class="receipt-value monospace">{{ $pendaftaran->nomor_kk }}</strong>
                            </div>
                            @endif

                            @if($pendaftaran->alasan_pembaruan)
                            <div class="receipt-item">
                                <span class="receipt-label">Alasan Pembaruan</span>
                                <strong class="receipt-value">{{ $pendaftaran->alasan_pembaruan }}</strong>
                            </div>
                            @endif

                            @if($pendaftaran->desa)
                            <div class="receipt-item">
                                <span class="receipt-label">Desa / Kelurahan</span>
                                <strong class="receipt-value">{{ $pendaftaran->desa }}</strong>
                            </div>
                            @endif

                            @if($pendaftaran->no_telepon)
                            <div class="receipt-item">
                                <span class="receipt-label">Nomor Telepon</span>
                                <strong class="receipt-value">{{ $pendaftaran->no_telepon }}</strong>
                            </div>
                            @endif

                            @if($pendaftaran->petugas)
                            <div class="receipt-item">
                                <span class="receipt-label">Petugas Input</span>
                                <strong class="receipt-value">{{ $pendaftaran->petugas }}</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="success-button-group">
                    <a href="{{ route('pendaftaran.create', ['jenis_layanan' => $pendaftaran?->jenis_layanan]) }}" class="btn-success-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Data Lagi
                    </a>

                    @if($pendaftaran)
                    <a href="{{ route('pendaftaran.formulir', $pendaftaran) }}" target="_blank" class="btn-success-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        Cetak Formulir
                    </a>
                    @endif

                    <a href="{{ route('pendaftaran.index') }}" class="btn-success-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Semua Data
                    </a>

                    <a href="{{ route('dashboard') }}" class="btn-success-secondary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>
        </section>
    </main>
</div>

<style>
    .success-card-box {
        background: #ffffff;
        border: 1px solid #e2d7c3;
        border-radius: 20px;
        box-shadow: 0 12px 32px rgba(91, 75, 45, 0.08);
        padding: 40px 36px;
        max-width: 660px;
        width: 100%;
        text-align: center;
        box-sizing: border-box;
    }

    .success-icon-badge {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        box-shadow: 0 10px 24px rgba(16, 185, 129, 0.3);
    }
    .success-icon-badge svg {
        width: 38px;
        height: 38px;
    }

    .success-title {
        font-size: 1.55rem;
        font-weight: 800;
        color: #2d2416;
        margin: 0 0 8px;
        letter-spacing: -0.01em;
    }

    .success-subtitle {
        font-size: 0.95rem;
        color: #6b7280;
        margin: 0 0 26px;
        line-height: 1.5;
    }

    .success-receipt {
        background: #faf8f5;
        border: 1.5px solid #ebe5d8;
        border-radius: 14px;
        padding: 20px 22px;
        text-align: left;
        margin-bottom: 30px;
    }

    .receipt-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 12px;
        margin-bottom: 14px;
        border-bottom: 1px dashed #dcd5c4;
    }

    .receipt-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #5b4b2d;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .receipt-badge {
        font-size: 0.78rem;
        font-weight: 700;
        color: #047857;
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        padding: 3px 10px;
        border-radius: 9999px;
    }

    .receipt-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 18px;
    }

    .receipt-item {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .receipt-label {
        font-size: 0.78rem;
        color: #78716c;
        font-weight: 600;
    }

    .receipt-value {
        font-size: 0.95rem;
        color: #1c1917;
        font-weight: 700;
        word-break: break-word;
    }

    .receipt-value.monospace {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        letter-spacing: 0.04em;
        font-weight: 700;
    }

    .service-pill-badge {
        display: inline-block;
        align-self: flex-start;
        padding: 3px 12px;
        background: #f2ebd9;
        color: #5c4728;
        border: 1px solid #dfd4be;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.84rem;
    }

    .success-button-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        align-items: center;
    }

    .btn-success-primary {
        background: linear-gradient(135deg, #796743, #5b4b2d);
        color: #ffffff !important;
        padding: 11px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(91, 75, 45, 0.25);
        transition: all 0.18s ease;
    }
    .btn-success-primary:hover {
        background: linear-gradient(135deg, #8a764e, #685634);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(91, 75, 45, 0.32);
    }

    .btn-success-outline {
        background: #ffffff;
        color: #5b4b2d !important;
        border: 1.5px solid #d5cebf;
        padding: 11px 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.18s ease;
    }
    .btn-success-outline:hover {
        background: #f7f4ed;
        border-color: #a89878;
        color: #3b2f19 !important;
        transform: translateY(-1px);
    }

    .btn-success-secondary {
        background: #f1f5f9;
        color: #475569 !important;
        border: 1px solid #cbd5e1;
        padding: 11px 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.18s ease;
    }
    .btn-success-secondary:hover {
        background: #e2e8f0;
        color: #1e293b !important;
        transform: translateY(-1px);
    }

    @media (max-width: 640px) {
        .success-card-box {
            padding: 28px 18px;
            border-radius: 16px;
        }
        .receipt-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .success-button-group {
            flex-direction: column;
            width: 100%;
        }
        .btn-success-primary,
        .btn-success-outline,
        .btn-success-secondary {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection
