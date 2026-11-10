@extends('layouts.app')

@section('title', 'Pengaturan Akun - PATEN SPACE')

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

    /* ── Status Bar Ponsel ── */
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

    .mob-card {
        background: #ffffff;
        border: 1px solid var(--khaki-border);
        border-radius: 20px;
        padding: 18px 16px;
        box-shadow: 0 4px 16px rgba(92, 71, 40, 0.07);
    }

    /* ── Settings Menu Items ── */
    .mob-menu-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .mob-menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #faf8f5;
        border: 1px solid var(--khaki-border);
        border-radius: 14px;
        padding: 12px 14px;
        text-decoration: none;
        color: #292524;
        transition: background 0.15s, transform 0.1s;
    }
    .mob-menu-item:active {
        background: #f2ede1;
        transform: translateY(1px);
    }
    .mob-menu-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .mob-menu-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: 1.1rem;
        background: var(--khaki-soft);
        color: var(--khaki-darker);
    }
    .mob-menu-text strong {
        display: block;
        font-size: 0.84rem;
        color: #1c1917;
    }
    .mob-menu-text small {
        display: block;
        font-size: 0.7rem;
        color: #78716c;
        margin-top: 1px;
    }
    .mob-menu-arrow {
        font-size: 1.1rem;
        color: #a8a29e;
        font-weight: 700;
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
    {{-- Status Bar Ponsel --}}
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
            <a href="{{ route('profil') }}" class="mob-prof-back" title="Kembali ke Profil">
                <span>←</span>
                <span>Profil</span>
            </a>
            <div class="mob-prof-head-brand">
                <h1>Pengaturan Akun</h1>
                <p>Kecamatan Jatisari</p>
            </div>
        </div>
    </header>

    {{-- Scrollable Settings --}}
    <main class="mob-prof-scroll">
        @if (session('success'))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 10px 14px; border-radius: 12px; font-size: 0.78rem; font-weight: 600;">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- Identitas Ringkas --}}
        <div class="mob-card">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 54px; height: 54px; min-width: 54px; border-radius: 50%; background: linear-gradient(135deg, var(--khaki-primary), var(--khaki-darker)); color: #fff; display: grid; place-items: center; font-size: 1.35rem; font-weight: 800; border: 2.5px solid #eee8d7; overflow: hidden;">
                    @if ($user->foto_profil_url)
                        <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span>{{ $user->inisial }}</span>
                    @endif
                </div>
                <div style="flex: 1; min-width: 0;">
                    <h2 style="font-size: 1.05rem; font-weight: 800; color: #1c1917; margin-bottom: 2px;">{{ $user->name }}</h2>
                    <p style="font-size: 0.72rem; color: #78716c; margin-bottom: 4px;">Masyarakat (Warga Pemohon)</p>
                    <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 20px; font-size: 0.68rem; font-weight: 700;">
                        <span style="width: 5px; height: 5px; border-radius: 50%; background: #22c55e;"></span> Akun Aktif
                    </span>
                </div>
            </div>
        </div>

        {{-- Menu Pengaturan --}}
        <div class="mob-card" style="margin-top: 12px;">
            <div class="mob-menu-list">
                {{-- 1. Ubah Data Profil --}}
                <a href="{{ route('account.profile.data') }}" class="mob-menu-item">
                    <div class="mob-menu-left">
                        <span class="mob-menu-icon">✉️</span>
                        <div class="mob-menu-text">
                            <strong>Ubah Data Profil</strong>
                            <small>Perbarui email, no telepon, dan foto</small>
                        </div>
                    </div>
                    <span class="mob-menu-arrow">›</span>
                </a>

                {{-- 2. Ubah Password --}}
                <a href="{{ route('account.profile.password') }}" class="mob-menu-item">
                    <div class="mob-menu-left">
                        <span class="mob-menu-icon">🔒</span>
                        <div class="mob-menu-text">
                            <strong>Ubah Password</strong>
                            <small>Perbarui kata sandi akun login Anda</small>
                        </div>
                    </div>
                    <span class="mob-menu-arrow">›</span>
                </a>

                {{-- 3. Kembali ke Profil --}}
                <a href="{{ route('profil') }}" class="mob-menu-item">
                    <div class="mob-menu-left">
                        <span class="mob-menu-icon">👤</span>
                        <div class="mob-menu-text">
                            <strong>Kembali ke Profil</strong>
                            <small>Lihat rincian informasi identitas</small>
                        </div>
                    </div>
                    <span class="mob-menu-arrow">›</span>
                </a>
            </div>
        </div>

        {{-- Tombol Keluar --}}
        <div style="margin-top: 4px; padding-bottom: 8px;">
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari akun PATEN SPACE?');">
                @csrf
                <button type="submit" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #fef2f2; border: 1.5px solid #fecaca; color: #dc2626; padding: 11px 16px; border-radius: 14px; font-size: 0.82rem; font-weight: 750; cursor: pointer; font-family: inherit;">
                    <span>🚪</span>
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

@else
{{-- ========================================================
     TAMPILAN STANDAR DESKTOP (PETUGAS / OPERATOR / CAMAT)
     ======================================================== --}}
<div class="app-shell dashboard-page account-profile-page">
    <aside class="sidebar dark-sidebar">
        <a href="{{ route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang" class="brand-mark">
            <span class="brand-text"><span class="brand-title">PATEN SPACE</span><span class="brand-sub">Pelayanan Kecamatan</span></span>
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
                <a href="{{ route('pendaftaran.create') }}" class="nav-item"><span aria-hidden="true">＋</span> Pendaftaran Baru</a>
                <a href="{{ route('petugas.permohonan.index') }}" class="nav-item"><span aria-hidden="true">▣</span> Daftar Permohonan</a>
                <a href="{{ route('pendaftaran.index') }}" class="nav-item"><span aria-hidden="true">▥</span> Semua Data</a>
                <a href="{{ route('laporan') }}" class="nav-item"><span aria-hidden="true">▤</span> Data dan Laporan</a>
            @endif
        </nav>
        {{-- Identitas Pengguna di Bawah Sidebar (Akses ke Profil) --}}
        <a href="{{ route('profil') }}" class="sidebar-profile sidebar-profile-link" aria-label="Buka profil {{ $user->name }}">
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

    <main class="main-panel">
        <div class="dashboard-content">
            <header class="profile-page-heading">
                <span class="profile-heading-avatar" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="m19.4 15 .1.1 1.1.9-1.5 2.6-1.4-.5a7.7 7.7 0 0 1-1.5.9l-.3 1.5h-3l-.3-1.5a7.7 7.7 0 0 1-1.5-.9l-1.4.5-1.5-2.6 1.1-.9a7 7 0 0 1 0-1.8l-1.1-.9 1.5-2.6 1.4.5a7.7 7.7 0 0 1 1.5-.9l.3-1.5h3l.3 1.5a7.7 7.7 0 0 1 1.5.9l1.4-.5 1.5 2.6-1.1.9a7 7 0 0 1 0 1.8Z"></path>
                    </svg>
                </span>
                <span><strong>Profil Saya</strong><small>Pengaturan akun, email, kontak, dan keamanan Anda</small></span>
                <a href="{{ route('profil') }}" class="profile-dashboard-link">Kembali ke Profil</a>
            </header>

            @if (session('success'))
                <div class="profile-success" role="status">{{ session('success') }}</div>
            @endif

            <section class="profile-overview-grid" aria-label="Ringkasan akun">
                <article class="dashboard-panel profile-identity-card">
                    @if ($user->foto_profil_url)
                        <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" class="profile-avatar-large" style="object-fit: cover; border: 3px solid #eee8d7;">
                    @else
                        <div class="profile-avatar-large" aria-hidden="true">{{ $user->inisial }}</div>
                    @endif
                    <div class="profile-identity-copy">
                        <h1>{{ $user->name }}</h1>
                        <p>
                            @if ($user->role === 'camat')
                                Pimpinan / Camat
                            @elseif ($user->peran_layanan === 'Operator')
                                Operator Kecamatan
                            @else
                                Petugas Loket
                            @endif
                        </p>
                        <span class="profile-active-badge"><span></span> Akun Aktif</span>
                        <div class="profile-contact-list">
                            <span><b aria-hidden="true">✉</b>{{ $user->email }}</span>
                            <span><b aria-hidden="true">☎</b>{{ $user->phone ?: 'Nomor telepon belum diisi' }}</span>
                            <span><b aria-hidden="true">⌖</b>Kecamatan Jatisari, Karawang</span>
                        </div>
                    </div>
                </article>

                <aside class="dashboard-panel profile-settings-card" aria-label="Pengaturan akun">
                    <h2>
                        <span class="profile-settings-heading-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="m19.4 15 .1.1 1.1.9-1.5 2.6-1.4-.5a7.7 7.7 0 0 1-1.5.9l-.3 1.5h-3l-.3-1.5a7.7 7.7 0 0 1-1.5-.9l-1.4.5-1.5-2.6 1.1-.9a7 7 0 0 1 0-1.8l-1.1-.9 1.5-2.6 1.4.5a7.7 7.7 0 0 1 1.5-.9l.3-1.5h3l.3 1.5a7.7 7.7 0 0 1 1.5.9l1.4-.5 1.5 2.6-1.1.9a7 7 0 0 1 0 1.8Z"></path>
                            </svg>
                        </span>
                        Pengaturan Akun
                    </h2>

                    {{-- 1. Ubah Data Profil (Email & Kontak) --}}
                    <a href="{{ route('account.profile.data') }}">
                        <span class="profile-menu-icon profile-menu-icon--data" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                        </span>
                        <span class="profile-menu-label">Ubah Data Profil</span><b aria-hidden="true">›</b>
                    </a>

                    {{-- 2. Ubah Password --}}
                    <a href="{{ route('account.profile.password') }}">
                        <span class="profile-menu-icon profile-menu-icon--password" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="10" width="14" height="11" rx="2"></rect>
                                <path d="M8 10V7a4 4 0 1 1 8 0v3M12 14v3"></path>
                            </svg>
                        </span>
                        <span class="profile-menu-label">Ubah Password</span><b aria-hidden="true">›</b>
                    </a>

                    {{-- 3. Pengaturan Akun --}}
                    <a href="{{ route('account.profile.settings') }}">
                        <span class="profile-menu-icon profile-menu-icon--settings" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="m19.4 15 .1.1 1.1.9-1.5 2.6-1.4-.5a7.7 7.7 0 0 1-1.5.9l-.3 1.5h-3l-.3-1.5a7.7 7.7 0 0 1-1.5-.9l-1.4.5-1.5-2.6 1.1-.9a7 7 0 0 1 0-1.8l-1.1-.9 1.5-2.6 1.4.5a7.7 7.7 0 0 1 1.5-.9l.3-1.5h3l.3 1.5a7.7 7.7 0 0 1 1.5.9l1.4-.5 1.5 2.6-1.1.9a7 7 0 0 1 0 1.8Z"></path>
                            </svg>
                        </span>
                        <span class="profile-menu-label">Pengaturan Akun</span><b aria-hidden="true">›</b>
                    </a>

                    {{-- 4. Kembali ke Profil --}}
                    <a href="{{ route('profil') }}">
                        <span class="profile-menu-icon profile-menu-icon--dashboard" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="3.5"></circle>
                                <path d="M5 20a7 7 0 0 1 14 0"></path>
                            </svg>
                        </span>
                        <span class="profile-menu-label">Kembali ke Profil</span><b aria-hidden="true">›</b>
                    </a>

                    {{-- 5. Keluar (Logout dengan Modal Konfirmasi Kustom) --}}
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0;">
                        @csrf
                        <button type="button" class="btn-trigger-logout" onclick="openLogoutModal()">
                            <span class="profile-menu-icon profile-menu-icon--logout" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 17l5-5-5-5M15 12H3"></path>
                                    <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"></path>
                                </svg>
                            </span>
                            <span class="profile-menu-label">Keluar</span><b aria-hidden="true">›</b>
                        </button>
                    </form>
                </aside>
            </section>
        </div>
    </main>
</div>
@endif
@endsection
