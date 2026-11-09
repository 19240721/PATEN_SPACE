@extends('layouts.app')

@section('title', 'Profil Pengguna - PATEN SPACE')

@section('content')
@if (auth()->user()->role === 'masyarakat')
{{-- ========================================================
     TAMPILAN KHUSUS MASYARAKAT (MOBILE SMARTPHONE FRAME)
     ======================================================== --}}
<style>
    :root {
        --khaki-darker: #5c4728;
        --khaki-dark: #785e3a;
        --khaki-primary: #8a6c42;
        --khaki-mid: #9c7b4f;
        --khaki-light: #b89768;
        --khaki-soft: #f2ebd9;
        --khaki-bg: #f7f4ec;
        --khaki-canvas: #ebe3d3;
        --khaki-border: #e2d7c3;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--khaki-canvas);
        font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        color: #292524;
        min-height: 100vh;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    /* ── Mobile Shell ── */
    .mob-shell {
        width: 100%;
        max-width: 430px;
        height: 100vh;
        height: 100dvh;
        background: var(--khaki-bg);
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(92, 71, 40, 0.15);
    }
    @media (min-width: 481px) {
        .mob-shell {
            height: min(94vh, 880px);
            border-radius: 46px;
            overflow: hidden;
            border: 10px solid #282017;
            outline: 2px solid #9c7b4f;
            box-shadow: 0 25px 65px rgba(50, 36, 18, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.2) inset;
        }
    }

    /* ── Status Bar Ponsel (Jam, Dynamic Island / Notch, 5G, Wi-Fi, Bar Baterai) ── */
    .phone-status-bar {
        width: 100%;
        height: 38px;
        min-height: 38px;
        padding: 6px 16px 2px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.75rem;
        font-weight: 700;
        color: #ffffff;
        background: var(--khaki-dark);
        position: relative;
        flex-shrink: 0;
        z-index: 100;
        user-select: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .psb-clock { font-weight: 800; font-size: 0.82rem; letter-spacing: -0.02em; min-width: 44px; }
    .psb-notch {
        position: absolute; left: 50%; transform: translateX(-50%); top: 6px;
        width: 96px; height: 22px; background: #17130e; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.6);
    }
    .psb-speaker { width: 32px; height: 3.5px; background: #2b251d; border-radius: 3px; }
    .psb-camera-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #0d1a2d; border: 1.5px solid #201a14;
        box-shadow: inset 0 0 2px #3b82f6;
    }
    .psb-indicators { display: flex; align-items: center; gap: 6px; }
    .psb-signal { display: inline-flex; align-items: center; gap: 2px; }
    .psb-net-type { font-size: 0.65rem; font-weight: 900; line-height: 1; }
    .psb-wifi { display: inline-flex; align-items: center; }
    .psb-battery-unit { display: inline-flex; align-items: center; gap: 3px; }
    .psb-battery-text { font-size: 0.68rem; font-weight: 800; }
    .psb-battery-outline {
        width: 22px; height: 11px; border: 1.5px solid #fff;
        border-radius: 3.5px; padding: 1px; display: flex; align-items: center;
    }
    .psb-battery-level { width: 85%; height: 100%; background: #22c55e; border-radius: 1.5px; }
    .psb-battery-cap { width: 2px; height: 5px; background: #fff; border-radius: 0 1.5px 1.5px 0; }

    /* ── Header Mobile Khaki ── */
    .mob-prof-header {
        flex-shrink: 0;
        background: linear-gradient(145deg, var(--khaki-darker) 0%, var(--khaki-dark) 55%, var(--khaki-primary) 100%);
        color: #ffffff;
        padding: 14px 18px 22px;
        position: relative;
        overflow: hidden;
        border-bottom-left-radius: 26px;
        border-bottom-right-radius: 26px;
        box-shadow: 0 6px 20px rgba(92, 71, 40, 0.22);
        z-index: 10;
    }
    .mob-prof-header::before {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 180px; height: 180px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .mob-prof-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 1;
    }
    .mob-prof-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 6px 12px;
        border-radius: 999px;
        transition: background 0.15s, transform 0.1s;
    }
    .mob-prof-back:hover,
    .mob-prof-back:active {
        background: rgba(255, 255, 255, 0.28);
        color: #ffffff;
        transform: translateY(-1px);
    }
    .mob-prof-head-brand {
        text-align: right;
    }
    .mob-prof-head-brand h1 {
        font-size: 0.95rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.2;
    }
    .mob-prof-head-brand p {
        font-size: 0.68rem;
        opacity: 0.88;
        color: #ffffff;
    }

    /* ── Scrollable Body ── */
    .mob-prof-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        scrollbar-width: thin;
        -webkit-overflow-scrolling: touch;
    }

    /* ── Profile Identity Card ── */
    .mob-card {
        background: #ffffff;
        border: 1px solid var(--khaki-border);
        border-radius: 20px;
        padding: 18px 16px;
        box-shadow: 0 4px 16px rgba(92, 71, 40, 0.07);
    }
    .mob-avatar-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .mob-avatar-box {
        width: 68px;
        height: 68px;
        min-width: 68px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--khaki-primary), var(--khaki-darker));
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.55rem;
        font-weight: 800;
        border: 3px solid #eee8d7;
        box-shadow: 0 4px 12px rgba(92, 71, 40, 0.18);
        overflow: hidden;
    }
    .mob-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .mob-user-info {
        flex: 1;
        min-width: 0;
    }
    .mob-user-info h2 {
        font-size: 1.18rem;
        font-weight: 800;
        color: #1c1917;
        line-height: 1.25;
        margin-bottom: 2px;
        word-break: break-word;
    }
    .mob-user-info p {
        font-size: 0.74rem;
        color: #78716c;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .mob-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
    }
    .mob-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #22c55e;
    }

    /* ── Quick Action Buttons ── */
    .mob-action-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px dashed var(--khaki-border);
    }
    .mob-act-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 10px;
        border-radius: 12px;
        font-size: 0.74rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.15s;
        text-align: center;
    }
    .mob-act-primary {
        background: var(--khaki-primary);
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(138, 108, 66, 0.22);
    }
    .mob-act-primary:hover,
    .mob-act-primary:active {
        background: var(--khaki-dark);
        color: #ffffff;
        transform: translateY(1px);
    }
    .mob-act-secondary {
        background: var(--khaki-soft);
        color: var(--khaki-darker);
        border: 1px solid var(--khaki-border);
    }
    .mob-act-secondary:hover,
    .mob-act-secondary:active {
        background: #e6ddc8;
        color: var(--khaki-darker);
    }

    /* ── Detail Card Rows ── */
    .mob-detail-title {
        font-size: 0.88rem;
        font-weight: 800;
        color: var(--khaki-darker);
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px dashed var(--khaki-border);
    }
    .mob-detail-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .mob-detail-item {
        background: #faf8f5;
        border: 1px solid var(--khaki-border);
        border-radius: 12px;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .mob-detail-label {
        font-size: 0.68rem;
        font-weight: 600;
        color: #78716c;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .mob-detail-val {
        font-size: 0.88rem;
        font-weight: 700;
        color: #292524;
        word-break: break-word;
    }

    /* ── Info Notice Box ── */
    .mob-info-notice {
        background: #fbf9f4;
        border: 1px dashed #d1c5ad;
        border-radius: 14px;
        padding: 12px 14px;
        font-size: 0.74rem;
        color: #6c5a3e;
        line-height: 1.5;
    }
    .mob-info-notice strong {
        color: var(--khaki-darker);
    }

    /* ── Logout Button Box ── */
    .mob-logout-box {
        margin-top: 4px;
        padding-bottom: 8px;
    }
    .mob-btn-logout {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #fef2f2;
        border: 1.5px solid #fecaca;
        color: #dc2626;
        padding: 11px 16px;
        border-radius: 14px;
        font-size: 0.82rem;
        font-weight: 750;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
        font-family: inherit;
    }
    .mob-btn-logout:hover,
    .mob-btn-logout:active {
        background: #fee2e2;
        transform: translateY(1px);
    }

    /* ── Bottom Nav ── */
    .mob-bottom-nav {
        flex-shrink: 0;
        width: 100%;
        background: #ffffff;
        border-top: 1px solid var(--khaki-border);
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 8px 6px 6px;
        box-shadow: 0 -4px 16px rgba(92, 71, 40, 0.08);
        z-index: 90;
        position: relative;
    }
    .mbn-link {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
        text-decoration: none;
        color: #78716c;
        font-size: 0.68rem;
        font-weight: 700;
        transition: color 0.15s;
    }
    .mbn-link.active, .mbn-link:hover { color: var(--khaki-darker); }
    .mbn-icon { font-size: 1.18rem; }
    .mbn-fab-wrap {
        position: relative;
        top: -12px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
    }
    .mbn-fab {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--khaki-dark), var(--khaki-primary));
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(92, 71, 40, 0.35);
        border: 2px solid #ffffff;
        transition: transform 0.15s;
    }
    .mbn-fab:active { transform: scale(0.94); }
</style>

<div class="mob-shell">
    {{-- Status Bar Ponsel (Jam, Dynamic Island / Notch, 5G, Wi-Fi, Bar Baterai) --}}
    <div class="phone-status-bar">
        <div class="psb-clock" id="phone-clock">{{ now()->format('H:i') }}</div>
        <div class="psb-notch">
            <span class="psb-speaker"></span>
            <span class="psb-camera-dot"></span>
        </div>
        <div class="psb-indicators">
            <span class="psb-signal" title="Sinyal 5G">
                <svg width="15" height="11" viewBox="0 0 15 11" fill="currentColor">
                    <rect x="0" y="8" width="2" height="3" rx="0.5"/>
                    <rect x="3.2" y="6" width="2" height="5" rx="0.5"/>
                    <rect x="6.4" y="4" width="2" height="7" rx="0.5"/>
                    <rect x="9.6" y="2" width="2" height="9" rx="0.5"/>
                    <rect x="12.8" y="0" width="2" height="11" rx="0.5"/>
                </svg>
                <span class="psb-net-type">5G</span>
            </span>
            <span class="psb-wifi" title="Wi-Fi">
                <svg width="14" height="11" viewBox="0 0 16 12" fill="currentColor">
                    <path d="M8 9.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3zm-4.24-3.53a6 6 0 0 1 8.48 0l-1.41 1.41a4 4 0 0 0-5.66 0L3.76 5.97zm-2.83-2.83a10 10 0 0 1 14.14 0l-1.41 1.41a8 8 0 0 0-11.32 0L.93 3.14z"/>
                </svg>
            </span>
            <div class="psb-battery-unit" title="Baterai 98%">
                <span class="psb-battery-text">98%</span>
                <div class="psb-battery-outline">
                    <div class="psb-battery-level"></div>
                </div>
                <div class="psb-battery-cap"></div>
            </div>
        </div>
    </div>

    {{-- Header Mobile Khaki --}}
    <header class="mob-prof-header">
        <div class="mob-prof-topbar">
            <a href="{{ route('masyarakat.dashboard') }}" class="mob-prof-back" title="Kembali ke Beranda">
                <span>←</span>
                <span>Beranda</span>
            </a>
            <div class="mob-prof-head-brand">
                <h1>Profil Pengguna</h1>
                <p>Kecamatan Jatisari</p>
            </div>
        </div>
    </header>

    {{-- Scrollable Profile Content --}}
    <main class="mob-prof-scroll">
        @if (session('success'))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 10px 14px; border-radius: 12px; font-size: 0.78rem; font-weight: 600;">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- Kartu Ringkasan Identitas --}}
        <section class="mob-card">
            <div class="mob-avatar-wrap">
                <div class="mob-avatar-box">
                    @if ($user->foto_profil_url)
                        <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}">
                    @else
                        <span>{{ $user->inisial }}</span>
                    @endif
                </div>
                <div class="mob-user-info">
                    <h2>{{ $user->name }}</h2>
                    <p>Masyarakat (Warga Pemohon Layanan)</p>
                    <span class="mob-status-pill">
                        <span class="mob-status-dot"></span>
                        Akun Aktif
                    </span>
                </div>
            </div>

            <div class="mob-action-grid">
                <a href="{{ route('account.profile.data') }}" class="mob-act-btn mob-act-primary">
                    <span>✎</span>
                    <span>Ubah Kontak</span>
                </a>
                <a href="{{ route('account.profile.password') }}" class="mob-act-btn mob-act-secondary">
                    <span>🔒</span>
                    <span>Ganti Password</span>
                </a>
            </div>
        </section>

        {{-- Kartu Rincian Informasi Akun --}}
        <section class="mob-card">
            <div class="mob-detail-title">
                <span>📋</span>
                <span>Rincian Informasi Akun</span>
            </div>

            <div class="mob-detail-list">
                <div class="mob-detail-item">
                    <span class="mob-detail-label">Nama Lengkap</span>
                    <span class="mob-detail-val">{{ $user->name }}</span>
                </div>

                <div class="mob-detail-item">
                    <span class="mob-detail-label">Status Pengguna</span>
                    <span class="mob-detail-val">Masyarakat</span>
                </div>

                <div class="mob-detail-item">
                    <span class="mob-detail-label">Nomor Induk Kependudukan (NIK)</span>
                    <span class="mob-detail-val">{{ $user->nik ?: 'Belum diisi' }}</span>
                </div>

                <div class="mob-detail-item">
                    <span class="mob-detail-label">Alamat Email</span>
                    <span class="mob-detail-val">{{ $user->email }}</span>
                </div>

                <div class="mob-detail-item">
                    <span class="mob-detail-label">Nomor Telepon / WhatsApp</span>
                    <span class="mob-detail-val">{{ $user->phone ?: 'Belum diisi' }}</span>
                </div>

                <div class="mob-detail-item">
                    <span class="mob-detail-label">Wilayah Pelayanan</span>
                    <span class="mob-detail-val">Kecamatan Jatisari, Kabupaten Karawang</span>
                </div>
            </div>
        </section>

        {{-- Catatan Perlindungan Data --}}
        <div class="mob-info-notice">
            <strong>🔒 Perlindungan Data Resmi:</strong> Nama lengkap dan NIK merupakan identitas resmi kependudukan yang diverifikasi sistem PATEN SPACE. Untuk memperbarui email, nomor telepon, foto profil, atau kata sandi, silakan gunakan tombol aksi di atas.
        </div>

        {{-- Tombol Logout / Keluar --}}
        <div class="mob-logout-box">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0; padding: 0;">
                @csrf
                <button type="button" class="mob-btn-logout btn-trigger-logout" onclick="openLogoutModal(event)">
                    <span></span>
                    <span>Keluar dari Akun</span>
                </button>
            </form>
        </div>
    </main>

    {{-- Bottom Nav --}}
    <nav class="mob-bottom-nav">
        <a class="mbn-link" href="{{ route('masyarakat.dashboard') }}">
            <span class="mbn-icon">🏠</span>
            <span>Beranda</span>
        </a>
        <a class="mbn-link" href="{{ route('masyarakat.permohonan.create') }}">
            <span class="mbn-icon">📝</span>
            <span>Ajukan</span>
        </a>
        <a class="mbn-fab-wrap" href="{{ route('masyarakat.permohonan.create') }}#dokumen">
            <div class="mbn-fab" title="Scan Dokumen">📷</div>
        </a>
        <a class="mbn-link" href="{{ route('masyarakat.riwayat') }}">
            <span class="mbn-icon">📋</span>
            <span>Riwayat</span>
        </a>
        <a class="mbn-link active" href="{{ route('profil') }}">
            <span class="mbn-icon">👤</span>
            <span>Profil</span>
        </a>
    </nav>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const clockEl = document.getElementById('phone-clock');
        if (clockEl) clockEl.textContent = `${hours}:${minutes}`;
    }
    setInterval(updateClock, 10000);
</script>

@else
{{-- ========================================================
     TAMPILAN STANDAR DESKTOP (PETUGAS / OPERATOR / CAMAT)
     ======================================================== --}}
<div class="app-shell dashboard-page account-profile-page">
    {{-- SIDEBAR --}}
    <aside class="sidebar dark-sidebar">
        <a href="{{ route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang" class="brand-mark">
            <span class="brand-text">
                <span class="brand-title">PATEN SPACE</span>
                <span class="brand-sub">Pelayanan Kecamatan</span>
            </span>
        </a>

        <nav class="sidebar-nav" aria-label="Menu utama">
            <div class="nav-group-label">Menu Utama</div>
            @if (auth()->user()->role === 'camat')
                <a href="{{ route('camat.dashboard') }}" class="nav-item"><span aria-hidden="true">◫</span> Dashboard</a>
                <a href="{{ route('camat.monitoring') }}" class="nav-item"><span aria-hidden="true">▣</span> Monitoring Pelayanan</a>
                <a href="{{ route('camat.statistik') }}" class="nav-item"><span aria-hidden="true">📈</span> Statistik</a>
                <a href="{{ route('camat.laporan') }}" class="nav-item"><span aria-hidden="true">▥</span> Laporan</a>
                <a href="{{ route('camat.aktivitas') }}" class="nav-item"><span aria-hidden="true">⏱</span> Aktivitas Pelayanan</a>
            @else
                <a href="{{ route('dashboard') }}" class="nav-item"><span aria-hidden="true">◫</span> Dashboard</a>
            @endif
        </nav>

        {{-- Identitas Pengguna di Bagian Bawah Sidebar (Dapat Diklik ke Profil) --}}
        <a href="{{ route('profil') }}" class="sidebar-profile sidebar-profile-link is-active" aria-current="page" aria-label="Profil {{ $user->name }}">
            @if ($user->foto_profil_url)
                <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" class="profile-avatar" style="object-fit: cover;">
            @else
                <span class="profile-avatar">{{ $user->inisial }}</span>
            @endif
            <span>
                <strong>{{ $user->name }}</strong>
                <small>
                    @if ($user->role === 'camat')
                        Pimpinan / Camat
                    @elseif ($user->peran_layanan === 'Operator')
                        Operator Kecamatan
                    @else
                        Petugas Loket
                    @endif
                </small>
            </span>
        </a>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-panel">
        <div class="dashboard-content" style="max-width: 980px; margin: 0 auto;">
            {{-- HEADER HALAMAN PROFIL --}}
            <header class="profile-page-heading" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 22px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <span class="profile-heading-avatar" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="3.5"></circle>
                            <path d="M5 20a7 7 0 0 1 14 0"></path>
                        </svg>
                    </span>
                    <div>
                        <strong style="font-size: 1.25rem; color: #1e3a5f; display: block;">Profil Pengguna</strong>
                        <small style="color: #64748b; font-size: 0.84rem;">Informasi identitas resmi akun dalam sistem PATEN SPACE</small>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <a href="{{ route('account.profile') }}" class="dashboard-primary-link" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; font-size: 0.82rem; font-weight: 700; border-radius: 8px;">
                        <span>⚙</span> Pengaturan Akun
                    </a>
                    <a href="{{ route('dashboard') }}" class="profile-dashboard-link" style="padding: 9px 15px; font-size: 0.82rem;">
                        Kembali ke Dashboard
                    </a>
                </div>
            </header>

            @if (session('success'))
                <div class="profile-success" role="status" style="margin-bottom: 20px;">{{ session('success') }}</div>
            @endif

            {{-- KARTU RINGKASAN IDENTITAS --}}
            <section class="dashboard-panel" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; margin-bottom: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 18px;">
                        @if ($user->foto_profil_url)
                            <img src="{{ $user->foto_profil_url }}"
                                 alt="{{ $user->name }}"
                                 style="width: 82px; height: 82px; min-width: 82px; border-radius: 50%; object-fit: cover; border: 3px solid #eee8d7; box-shadow: 0 4px 14px rgba(121, 103, 67, 0.18);">
                        @else
                            <div style="width: 82px; height: 82px; min-width: 82px; border-radius: 50%; background: linear-gradient(135deg, #796743, #5c4728); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.7rem; font-weight: 800; box-shadow: 0 4px 14px rgba(121, 103, 67, 0.18);">
                                {{ $user->inisial }}
                            </div>
                        @endif

                        <div>
                            <h1 style="margin: 0 0 4px; font-size: 1.35rem; color: #1e3a5f; font-weight: 750;">{{ $user->name }}</h1>
                            <div style="font-size: 0.86rem; color: #64748b; font-weight: 600; margin-bottom: 8px;">
                                @if ($user->role === 'camat')
                                    Pimpinan / Camat
                                @elseif ($user->peran_layanan === 'Operator')
                                    Operator Kecamatan (Pengelola Pelayanan Utama)
                                @else
                                    Petugas Loket (Pendamping Pelayanan)
                                @endif
                            </div>
                            <span class="profile-active-badge" style="display: inline-flex; align-items: center; gap: 5px; background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; padding: 3px 10px; border-radius: 20px; font-size: 0.74rem; font-weight: 700;">
                                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #22c55e;"></span>
                                Akun Aktif
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('account.profile.data') }}" class="report-filter-button" style="text-decoration: none; padding: 9px 15px; font-size: 0.8rem;">
                            ✎ Ubah Email & Kontak
                        </a>
                    </div>
                </div>
            </section>

            {{-- KARTU RINCIAN DATA PROFIL LENGKAP --}}
            <section class="dashboard-panel" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; margin-bottom: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <h2 style="margin: 0 0 4px; font-size: 1.08rem; color: #1e3a5f; font-weight: 750;">Rincian Informasi Akun</h2>
                    <p style="margin: 0; font-size: 0.82rem; color: #64748b;">Informasi kepegawaian, kontak, dan penempatan resmi pengguna</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                    {{-- Nama Lengkap --}}
                    <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                        <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Nama Lengkap</div>
                        <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">{{ $user->name }}</div>
                    </div>

                    {{-- Peran / Jabatan --}}
                    <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                        <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Peran / Jabatan Sistem</div>
                        <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">
                            @if ($user->role === 'camat')
                                Pimpinan / Camat
                            @elseif ($user->peran_layanan === 'Operator')
                                Operator Kecamatan
                            @else
                                Petugas Loket
                            @endif
                        </div>
                    </div>

                    @if ($user->role === 'camat')
                        {{-- NIP Camat --}}
                        <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                            <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Nomor Induk Pegawai (NIP)</div>
                            <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">{{ $user->nip ?: '—' }}</div>
                        </div>

                        {{-- Instansi --}}
                        <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                            <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Instansi</div>
                            <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">Pemerintah Kabupaten Karawang</div>
                        </div>
                    @else
                        {{-- Instansi --}}
                        <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                            <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Instansi</div>
                            <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">Pemerintah Kabupaten Karawang</div>
                        </div>

                        {{-- Wilayah Kerja --}}
                        <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                            <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Kecamatan / Wilayah Penugasan</div>
                            <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">Kecamatan Jatisari</div>
                        </div>
                    @endif

                    {{-- Email Akun --}}
                    <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                        <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Alamat Email</div>
                        <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">{{ $user->email }}</div>
                    </div>

                    {{-- Nomor Telepon --}}
                    <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                        <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Nomor Telepon / WhatsApp</div>
                        <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">{{ $user->phone ?: 'Belum diisi' }}</div>
                    </div>
                </div>

                {{-- Catatan Keamanan Identitas --}}
                <div style="margin-top: 18px; padding: 12px 16px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; font-size: 0.78rem; color: #64748b; line-height: 1.5;">
                    <span style="font-weight: 700; color: #334155;">🔒 Catatan Perlindungan Data:</span> Data nama lengkap, NIP/NIK, jabatan, instansi, dan wilayah penugasan merupakan data resmi sistem yang tidak dapat diubah mandiri. Jika Anda perlu memperbarui alamat email, nomor telepon, foto profil, atau password login, silakan gunakan menu <strong>Pengaturan Akun</strong>.
                </div>
            </section>
        </div>
    </main>
</div>
@endif
@endsection
