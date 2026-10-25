@extends('layouts.app')

@section('title', 'Permohonan Terkirim - PATEN SPACE')

@section('content')
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
        margin: 0;
        padding: 0;
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

    /* ── Status Bar Ponsel (Diam Permanen di Atas, Tidak Gerak Saat Scroll) ── */
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    .psb-clock {
        font-weight: 800;
        font-size: 0.82rem;
        letter-spacing: -0.02em;
        min-width: 44px;
    }
    .psb-notch {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        top: 6px;
        width: 96px;
        height: 22px;
        background: #17130e;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.6);
    }
    .psb-speaker {
        width: 32px;
        height: 3.5px;
        background: #2b251d;
        border-radius: 3px;
    }
    .psb-camera-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #0d1a2d;
        border: 1.5px solid #201a14;
        box-shadow: inset 0 0 2px #3b82f6;
    }
    .psb-indicators {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .psb-signal {
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }
    .psb-net-type {
        font-size: 0.65rem;
        font-weight: 900;
        line-height: 1;
    }
    .psb-wifi {
        display: inline-flex;
        align-items: center;
    }
    .psb-battery-unit {
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .psb-battery-text {
        font-size: 0.68rem;
        font-weight: 800;
    }
    .psb-battery-outline {
        width: 22px;
        height: 11px;
        border: 1.5px solid #ffffff;
        border-radius: 3.5px;
        padding: 1px;
        display: flex;
        align-items: center;
    }
    .psb-battery-level {
        width: 85%;
        height: 100%;
        background: #22c55e;
        border-radius: 1.5px;
    }
    .psb-battery-cap {
        width: 2px;
        height: 5px;
        background: #ffffff;
        border-radius: 0 1.5px 1.5px 0;
    }

    /* ── Area Konten yang Bisa di-Scroll di Bawah Status Bar ── */
    .mob-scroll-area {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        display: flex;
        flex-direction: column;
    }
    .mob-scroll-area::-webkit-scrollbar {
        width: 4px;
    }
    .mob-scroll-area::-webkit-scrollbar-thumb {
        background: var(--khaki-light);
        border-radius: 4px;
    }

    /* ── Vibrant Khaki/Gold Header ── */
    .mob-header-success {
        background: linear-gradient(135deg, var(--khaki-dark) 0%, var(--khaki-primary) 50%, var(--khaki-light) 100%);
        color: #ffffff;
        padding: 36px 20px 48px;
        text-align: center;
        position: relative;
        box-shadow: 0 4px 18px rgba(92, 71, 40, 0.25);
    }
    .success-check-badge {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.22);
        border: 3px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin: 0 auto 12px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
    }
    .success-title {
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.01em;
        margin-bottom: 4px;
    }
    .success-sub {
        font-size: 0.82rem;
        opacity: 0.92;
        line-height: 1.45;
    }

    /* ── Body Card ── */
    .mob-body {
        padding: 0 16px 24px;
        margin-top: -24px;
        position: relative;
        z-index: 2;
        flex: 1;
    }
    .receipt-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 20px 18px;
        border: 1px solid var(--khaki-border);
        box-shadow: 0 8px 24px rgba(92, 71, 40, 0.06);
    }

    /* Registration Number Box */
    .reg-number-box {
        text-align: center;
        padding: 16px;
        background: var(--khaki-soft);
        border: 1.5px solid var(--khaki-border);
        border-radius: 14px;
        margin-bottom: 18px;
    }
    .rnb-label {
        font-size: 0.68rem;
        color: var(--khaki-darker);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .rnb-value {
        font-size: 2rem;
        font-weight: 900;
        color: var(--khaki-primary);
        font-family: monospace;
    }

    /* Detail Rows */
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f5f1e8;
        font-size: 0.82rem;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-key { color: #78716c; font-weight: 600; }
    .detail-val { font-weight: 700; color: #292524; text-align: right; }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 999px;
        background: var(--khaki-soft);
        color: var(--khaki-darker);
        border: 1px solid var(--khaki-border);
        font-size: 0.72rem;
        font-weight: 800;
    }

    .notice-box {
        background: #fffbeb;
        border: 1px solid #fed7aa;
        border-radius: 12px;
        padding: 12px 14px;
        margin: 16px 0;
        font-size: 0.76rem;
        color: #92400e;
        line-height: 1.45;
    }
    .notice-box strong { display: block; margin-bottom: 2px; }

    /* Action Buttons */
    .btn-primary-khaki {
        width: 100%;
        padding: 14px;
        text-align: center;
        background: linear-gradient(135deg, var(--khaki-dark) 0%, var(--khaki-primary) 100%);
        color: #fff;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 800;
        font-size: 0.88rem;
        box-shadow: 0 4px 14px rgba(138, 108, 66, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-bottom: 8px;
    }
    .btn-secondary-light {
        width: 100%;
        padding: 12px;
        text-align: center;
        background: var(--khaki-soft);
        color: var(--khaki-darker);
        border: 1px solid var(--khaki-border);
        border-radius: 14px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.82rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    /* ── Bottom Navigation Bar ── */
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
        font-size: 0.65rem;
        font-weight: 700;
    }
    .mbn-link.active { color: var(--khaki-primary); }
    .mbn-icon { font-size: 1.25rem; line-height: 1; }

    /* ── Tombol Navigasi Virtual Ponsel (Android 3-Button Bar) ── */
    .phone-nav-bar {
        flex-shrink: 0;
        width: 100%;
        height: 42px;
        min-height: 42px;
        background: #17130e;
        display: flex;
        align-items: center;
        justify-content: space-around;
        z-index: 100;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        position: relative;
    }
    @media (min-width: 481px) {
        .phone-nav-bar {
            border-bottom-left-radius: 35px;
            border-bottom-right-radius: 35px;
        }
    }
    .pnb-key {
        background: none;
        border: none;
        color: #d1c7bc;
        padding: 8px 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: background 0.15s, color 0.15s;
        text-decoration: none;
    }
    .pnb-key:hover, .pnb-key:active {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
    }
    .pnb-home-circle {
        width: 13px;
        height: 13px;
        border-radius: 50%;
        border: 2px solid currentColor;
    }
    .pnb-recent-square {
        width: 12px;
        height: 12px;
        border-radius: 2.5px;
        border: 2px solid currentColor;
    }
</style>

<div class="mob-shell">

    {{-- Status Bar Ponsel (Diam Permanen di Atas, Tidak Gerak Saat Scroll) --}}
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

    {{-- Area Konten yang Bisa di-Scroll di Bawah Status Bar --}}
    <div class="mob-scroll-area">

        {{-- Vibrant Khaki Header --}}
        <header class="mob-header-success">
        <div class="success-check-badge">✓</div>
        <h1 class="success-title">Permohonan Terkirim!</h1>
        <div class="success-sub">Berkas Anda telah masuk ke sistem PATEN Kecamatan Jatisari dan sedang menunggu proses verifikasi petugas.</div>
    </header>

    {{-- Receipt Body --}}
    <div class="mob-body">
        <div class="receipt-card">

            {{-- Big Registration Number --}}
            <div class="reg-number-box">
                <div class="rnb-label">Nomor Registrasi Permohonan</div>
                <div class="rnb-value">#{{ str_pad($pendaftaran->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>

            <div style="font-size:0.75rem;font-weight:800;color:#78716c;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">
                Rincian Pengajuan
            </div>

            <div class="detail-row">
                <span class="detail-key">Jenis Layanan</span>
                <span class="detail-val" style="color:var(--khaki-primary);">{{ $pendaftaran->jenis_layanan }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-key">Nama Pemohon</span>
                <span class="detail-val">{{ $pendaftaran->nama_lengkap }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-key">Tanggal Pengajuan</span>
                <span class="detail-val">{{ $pendaftaran->tanggal_daftar?->translatedFormat('d F Y') ?? '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-key">Jumlah Dokumen</span>
                <span class="detail-val">{{ count($pendaftaran->documents ?? []) }} berkas terlampir</span>
            </div>
            <div class="detail-row">
                <span class="detail-key">Status Saat Ini</span>
                <span class="detail-val">
                    <span class="status-pill">🔵 {{ $pendaftaran->status ?? 'Baru' }}</span>
                </span>
            </div>

            <div class="notice-box">
                <strong>ℹ️ Pelacakan Status & Pengambilan Berkas:</strong>
                Pantau perkembangan permohonan melalui menu <strong>Beranda</strong>. Jika status sudah <strong>Selesai</strong>, silakan datang ke kantor Kecamatan Jatisari dengan menunjukkan nomor registrasi ini.
            </div>

            <a href="{{ route('masyarakat.dashboard') }}" class="btn-primary-khaki">
                🏠 Pantau Status di Beranda
            </a>
            <a href="{{ route('masyarakat.permohonan.create') }}" class="btn-secondary-light">
                📝 Ajukan Permohonan Lain
            </a>

        </div>
    </div>
    </div>

    {{-- Bottom Navigation Bar Aplikasi --}}
    <nav class="mob-bottom-nav">
        <a class="mbn-link active" href="{{ route('masyarakat.dashboard') }}">
            <span class="mbn-icon">🏠</span>
            <span>Beranda</span>
        </a>
        <a class="mbn-link" href="{{ route('masyarakat.permohonan.create') }}">
            <span class="mbn-icon">📝</span>
            <span>Ajukan</span>
        </a>
        <a class="mbn-link" href="{{ route('masyarakat.dashboard') }}">
            <span class="mbn-icon">📋</span>
            <span>Riwayat</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="flex:1;display:flex;">
            @csrf
            <button type="submit" class="mbn-link" style="background:none;border:none;cursor:pointer;width:100%;font-family:inherit;">
                <span class="mbn-icon">🚪</span>
                <span>Keluar</span>
            </button>
        </form>
    </nav>

    {{-- Tombol Navigasi Virtual Ponsel (Android 3-Button Navigation) --}}
    <div class="phone-nav-bar">
        <button type="button" class="pnb-key" onclick="if(window.history.length > 1){ window.history.back(); } else { window.location.href='{{ route('masyarakat.dashboard') }}'; }" title="Kembali (Back)" aria-label="Kembali">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <a href="{{ route('masyarakat.dashboard') }}" class="pnb-key" title="Beranda (Home)" aria-label="Beranda">
            <span class="pnb-home-circle"></span>
        </a>
        <button type="button" class="pnb-key" onclick="alert('PATEN SPACE Mobile - Sistem online terhubung ke Kecamatan Jatisari.')" title="Recent Apps" aria-label="Aplikasi Terakhir">
            <span class="pnb-recent-square"></span>
        </button>
    </div>

</div>

<script>
    function updatePhoneClock() {
        const el = document.getElementById('phone-clock');
        if (!el) return;
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        el.textContent = `${hours}:${minutes}`;
    }
    setInterval(updatePhoneClock, 30000);
    updatePhoneClock();
</script>
@endsection
