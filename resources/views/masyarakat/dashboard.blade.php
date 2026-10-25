@extends('layouts.app')

@section('title', 'Dashboard Masyarakat - PATEN SPACE')

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

    /* ── Warm Khaki Canvas Background ── */
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

    /* ── Mobile Container Shell (Tampilan HP Khaki) ── */
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

    /* ── Header Khaki (Fixed, Tidak Ikut Scroll) ── */
    .mob-header {
        flex-shrink: 0;
        background: linear-gradient(145deg, var(--khaki-dark) 0%, var(--khaki-primary) 55%, var(--khaki-light) 100%);
        color: #ffffff;
        padding: 16px 18px 28px;
        position: relative;
        overflow: hidden;
        border-bottom-left-radius: 28px;
        border-bottom-right-radius: 28px;
        box-shadow: 0 6px 20px rgba(92, 71, 40, 0.22);
        z-index: 10;
    }

    /* ── Area Konten yang Bisa di-Scroll (Di Bawah Header Tetap) ── */
    .mob-scroll-area {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        display: flex;
        flex-direction: column;
        background: var(--khaki-bg);
        /* Overlap sedikit agar tidak ada celah antara rounded header dan konten */
        margin-top: -16px;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
        padding-top: 0;
    }
    .mob-scroll-area::-webkit-scrollbar {
        width: 4px;
    }
    .mob-scroll-area::-webkit-scrollbar-thumb {
        background: var(--khaki-light);
        border-radius: 4px;
    }
    .mob-header::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }
    .mob-header::after {
        content: '';
        position: absolute;
        bottom: -30px;
        left: -20px;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }
    .mob-header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        position: relative;
        z-index: 1;
    }
    .mob-brand {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .mob-brand-mark {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.22);
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        border-radius: 12px;
        display: grid;
        place-items: center;
        font-weight: 900;
        font-size: 1.25rem;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    }
    .mob-brand-text h1 {
        font-size: 1rem;
        font-weight: 800;
        letter-spacing: 0.02em;
        line-height: 1.2;
    }
    .mob-brand-text p {
        font-size: 0.68rem;
        opacity: 0.92;
    }
    .mob-user-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.35);
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #ffffff;
    }

    /* Greeting Section in Header */
    .mob-greeting {
        position: relative;
        z-index: 1;
    }
    .mob-greeting-sub {
        font-size: 0.8rem;
        opacity: 0.9;
        margin-bottom: 2px;
    }
    .mob-greeting-name {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.01em;
        line-height: 1.25;
    }
    .mob-header-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    .mob-date-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.72rem;
        opacity: 0.92;
        font-weight: 600;
    }
    .mob-status-pill-head {
        background: rgba(255, 255, 255, 0.2);
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    /* ── Main Content Area ── */
    .mob-content {
        padding: 20px 16px 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
        background: var(--khaki-bg);
    }

    /* ── Section Title ── */
    .section-title {
        font-size: 0.88rem;
        font-weight: 800;
        color: #443521;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .section-title-tag {
        background: var(--khaki-soft);
        color: var(--khaki-darker);
        font-size: 0.68rem;
        padding: 3px 8px;
        border-radius: 999px;
        font-weight: 700;
        border: 1px solid var(--khaki-border);
    }

    /* ── Quick Actions (Tombol Cepat Khaki Ringkas) ── */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .qa-card {
        border-radius: 12px;
        padding: 10px 10px;
        text-decoration: none;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        gap: 4px;
        box-shadow: 0 2px 8px rgba(92, 71, 40, 0.1);
        transition: transform 0.12s;
    }
    .qa-card:active { transform: scale(0.97); }
    .qa-card.primary {
        background: linear-gradient(135deg, var(--khaki-dark) 0%, var(--khaki-primary) 100%);
    }
    .qa-card.secondary {
        background: linear-gradient(135deg, #5c6e4e 0%, #6e845e 100%);
    }
    .qa-icon {
        width: 30px;
        height: 30px;
        background: rgba(255, 255, 255, 0.22);
        border-radius: 8px;
        display: grid;
        place-items: center;
        font-size: 1rem;
    }
    .qa-title {
        font-weight: 800;
        font-size: 0.8rem;
        line-height: 1.2;
    }
    .qa-desc {
        font-size: 0.65rem;
        opacity: 0.92;
        line-height: 1.25;
    }

    /* ── Service Catalog Panel Ringkas ── */
    .services-panel {
        background: #ffffff;
        border-radius: 14px;
        padding: 12px;
        border: 1px solid var(--khaki-border);
        box-shadow: 0 2px 10px rgba(92, 71, 40, 0.04);
    }
    .services-list-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
    .service-item-btn {
        background: #fdfcf9;
        border: 1.5px solid var(--khaki-border);
        border-radius: 10px;
        padding: 8px 6px;
        text-decoration: none;
        color: #292524;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 4px;
        transition: all 0.15s;
    }
    .service-item-btn:hover, .service-item-btn:active {
        background: var(--khaki-soft);
        border-color: var(--khaki-primary);
        transform: translateY(-1px);
    }
    .sib-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--khaki-soft);
        color: var(--khaki-primary);
        display: grid;
        place-items: center;
    }
    .sib-title {
        font-size: 0.72rem;
        font-weight: 700;
        line-height: 1.25;
    }
    .sib-badge {
        font-size: 0.58rem;
        font-weight: 700;
        background: #ecfdf5;
        color: #059669;
        padding: 1px 5px;
        border-radius: 4px;
    }

    /* ── Permohonan Cards List (Ukuran Lebih Kecil & Padat) ── */
    .app-cards-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .app-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 10px 12px;
        border: 1px solid var(--khaki-border);
        box-shadow: 0 1px 6px rgba(92, 71, 40, 0.04);
        border-left: 4px solid #cbd5e1;
    }
    .app-card.baru    { border-left-color: #3b82f6; }
    .app-card.proses  { border-left-color: var(--khaki-primary); }
    .app-card.selesai { border-left-color: #10b981; }
    .app-card.ditolak { border-left-color: #ef4444; }

    .ac-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }
    .ac-service {
        font-size: 0.82rem;
        font-weight: 800;
        color: #292524;
    }
    .ac-id {
        font-size: 0.64rem;
        color: #78716c;
        font-family: monospace;
    }
    .ac-source-pill {
        display: inline-block;
        padding: 1px 5px;
        border-radius: 4px;
        font-size: 0.6rem;
        font-weight: 700;
        background: var(--khaki-soft);
        color: var(--khaki-darker);
        margin-left: 4px;
    }
    .ac-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 2px 7px;
        border-radius: 999px;
        font-size: 0.64rem;
        font-weight: 800;
        text-transform: uppercase;
    }
    .ac-badge.baru    { background: #dbeafe; color: #1e40af; }
    .ac-badge.proses  { background: #fef3c7; color: #92400e; }
    .ac-badge.selesai { background: #d1fae5; color: #065f46; }
    .ac-badge.ditolak { background: #fee2e2; color: #991b1b; }

    .ac-meta {
        font-size: 0.68rem;
        color: #78716c;
        margin-bottom: 4px;
    }

    .ac-note {
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 0.68rem;
        line-height: 1.35;
        margin-top: 4px;
    }
    .ac-note.reject { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .ac-note.info   { background: var(--khaki-soft); border: 1px solid var(--khaki-border); color: var(--khaki-darker); }

    .ac-docs {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 4px;
        padding-top: 4px;
        border-top: 1px solid #f5f1e8;
    }
    .ac-doc-pill {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: var(--khaki-bg);
        border: 1px solid var(--khaki-border);
        padding: 2px 6px;
        border-radius: 5px;
        font-size: 0.62rem;
        color: var(--khaki-primary);
        font-weight: 600;
        text-decoration: none;
    }

    .ac-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 5px;
        padding-top: 5px;
        border-top: 1px solid #f5f1e8;
    }
    .btn-view-receipt {
        background: var(--khaki-soft);
        color: var(--khaki-darker);
        border: 1px solid var(--khaki-border);
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 0.66rem;
        font-weight: 700;
        text-decoration: none;
    }

    /* ── Ringkasan Status Layanan (Sleek Glass Chips, Bukan Card) ── */
    .mob-status-chips {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    .msc-chip {
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.26);
        border-radius: 8px;
        padding: 5px 2px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        text-align: center;
        backdrop-filter: blur(4px);
    }
    .msc-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
    }
    .msc-chip.diterima .msc-dot { background: #60a5fa; box-shadow: 0 0 5px #60a5fa; }
    .msc-chip.proses   .msc-dot { background: #fbbf24; box-shadow: 0 0 5px #fbbf24; }
    .msc-chip.selesai  .msc-dot { background: #34d399; box-shadow: 0 0 5px #34d399; }
    .msc-chip.ditolak  .msc-dot { background: #f87171; box-shadow: 0 0 5px #f87171; }

    .msc-lbl {
        font-size: 0.63rem;
        font-weight: 700;
        color: #ffffff;
        opacity: 0.95;
        line-height: 1;
    }
    .msc-val {
        font-size: 0.92rem;
        font-weight: 900;
        color: #ffffff;
        line-height: 1.1;
    }

    /* ── Fixed Bottom Navigation Bar (Khaki Elegan) ── */
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
        transition: color 0.15s;
    }
    .mbn-link.active { color: var(--khaki-primary); }
    .mbn-icon { font-size: 1.25rem; line-height: 1; }

    .mbn-fab-wrap {
        flex: 1;
        display: flex;
        justify-content: center;
        text-decoration: none;
    }
    .mbn-fab {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--khaki-primary), var(--khaki-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.35rem;
        margin-top: -20px;
        box-shadow: 0 4px 14px rgba(138, 108, 66, 0.4);
        border: 3px solid #ffffff;
    }

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

    {{-- Khaki Gradient Header (FIXED — Tidak Ikut Scroll) --}}
    <header class="mob-header">
    <div class="mob-header-top">
            <div class="mob-brand">
                <div class="mob-brand-mark">P</div>
                <div class="mob-brand-text">
                    <h1>PATEN SPACE</h1>
                    <p>Kecamatan Jatisari</p>
                </div>
            </div>
<<<<<<< Updated upstream
            <a href="{{ route('account.profile') }}" class="masy-avatar" aria-label="Buka profil akun" style="color:inherit;text-decoration:none;">👤</a>
        </div>
        <div class="masy-greeting">
            <div class="masy-greeting-sub">Selamat datang,</div>
            <div class="masy-greeting-name">{{ auth()->user()->name }}</div>
            <div class="masy-greeting-date">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="masy-stats-wrap">
        <div class="masy-stats">
            <div class="masy-stat">
                <div class="masy-stat-num blue">{{ $stats['total'] }}</div>
                <div class="masy-stat-lbl">Total</div>
            </div>
            <div class="masy-stat">
                <div class="masy-stat-num yellow">{{ $stats['proses'] }}</div>
                <div class="masy-stat-lbl">Proses</div>
            </div>
            <div class="masy-stat">
                <div class="masy-stat-num green">{{ $stats['selesai'] }}</div>
                <div class="masy-stat-lbl">Selesai</div>
            </div>
            <div class="masy-stat">
                <div class="masy-stat-num red">{{ $stats['ditolak'] }}</div>
                <div class="masy-stat-lbl">Ditolak</div>
=======
            <div class="mob-user-badge">
                <span>👤</span>
                <span>Warga</span>
>>>>>>> Stashed changes
            </div>
        </div>

        <div class="mob-greeting">
            <div class="mob-greeting-sub">Selamat datang,</div>
            <div class="mob-greeting-name">{{ auth()->user()->name }}</div>
            <div class="mob-header-footer">
                <div class="mob-date-badge">
                    <span>📅</span>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="mob-status-pill-head">
                    {{ $stats['total'] }} Pengajuan Berkas
                </div>
            </div>

            {{-- Ringkasan Status Layanan (Sleek Glass Chips, Bukan Card) --}}
            <div class="mob-status-chips">
                <div class="msc-chip diterima" title="Permohonan Diterima / Menunggu">
                    <span class="msc-dot"></span>
                    <span class="msc-lbl">Diterima</span>
                    <strong class="msc-val">{{ $stats['baru'] ?? 0 }}</strong>
                </div>
                <div class="msc-chip proses" title="Permohonan Sedang Diproses">
                    <span class="msc-dot"></span>
                    <span class="msc-lbl">Diproses</span>
                    <strong class="msc-val">{{ $stats['proses'] }}</strong>
                </div>
                <div class="msc-chip selesai" title="Permohonan Selesai">
                    <span class="msc-dot"></span>
                    <span class="msc-lbl">Selesai</span>
                    <strong class="msc-val">{{ $stats['selesai'] }}</strong>
                </div>
                <div class="msc-chip ditolak" title="Permohonan Ditolak">
                    <span class="msc-dot"></span>
                    <span class="msc-lbl">Ditolak</span>
                    <strong class="msc-val">{{ $stats['ditolak'] }}</strong>
                </div>
            </div>
        </div>
    </header>

    {{-- Area Konten yang Bisa di-Scroll di Bawah Header --}}
    <div class="mob-scroll-area">
    <main class="mob-content">

        {{-- Aksi Cepat --}}
        <div>
            <div class="section-title">
                <span>Layanan Cepat</span>
            </div>
            <div class="quick-actions-grid">
                <a href="{{ route('masyarakat.permohonan.create') }}" class="qa-card primary">
                    <div class="qa-icon">📝</div>
                    <div class="qa-title">Ajukan Permohonan</div>
                    <div class="qa-desc">Isi formulir online mandiri</div>
                </a>
                <a href="{{ route('masyarakat.permohonan.create') }}#dokumen" class="qa-card secondary">
                    <div class="qa-icon">📷</div>
                    <div class="qa-title">Scan Dokumen</div>
                    <div class="qa-desc">Foto berkas dengan HP</div>
                </a>
            </div>
        </div>

        {{-- Service Catalog Selector --}}
        <div class="services-panel">
            <div class="section-title">
                <span>Pilih Jenis Layanan</span>
                <span class="section-title-tag">Gratis</span>
            </div>
            <div class="services-list-grid">
                <a href="{{ route('masyarakat.permohonan.create', ['jenis_layanan' => 'KTP']) }}" class="service-item-btn">
                    <div class="sib-icon-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="16" rx="3"></rect>
                            <circle cx="9" cy="10" r="2"></circle>
                            <line x1="15" y1="8" x2="17" y2="8"></line>
                            <line x1="15" y1="12" x2="17" y2="12"></line>
                            <line x1="7" y1="16" x2="17" y2="16"></line>
                        </svg>
                    </div>
                    <div class="sib-title">KTP Pembaharuan</div>
                    <span class="sib-badge">1-2 Hari</span>
                </a>

                <a href="{{ route('masyarakat.permohonan.create', ['jenis_layanan' => 'PRR']) }}" class="service-item-btn">
                    <div class="sib-icon-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            <path d="M9 14l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div class="sib-title">PRR / KTP Baru</div>
                    <span class="sib-badge">2-3 Hari</span>
                </a>

                <a href="{{ route('masyarakat.permohonan.create', ['jenis_layanan' => 'KK']) }}" class="service-item-btn">
                    <div class="sib-icon-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="sib-title">Kartu Keluarga</div>
                    <span class="sib-badge">2-3 Hari</span>
                </a>

                <a href="{{ route('masyarakat.permohonan.create', ['jenis_layanan' => 'Akta Lahir']) }}" class="service-item-btn">
                    <div class="sib-icon-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a5 5 0 0 1 5 5c0 2-1 3.5-2.5 4.5l1.5 6.5-4-2-4 2 1.5-6.5C8 10.5 7 9 7 7a5 5 0 0 1 5-5z"></path>
                        </svg>
                    </div>
                    <div class="sib-title">Akta Kelahiran</div>
                    <span class="sib-badge">2-3 Hari</span>
                </a>

                <a href="{{ route('masyarakat.permohonan.create', ['jenis_layanan' => 'Akta Kematian']) }}" class="service-item-btn">
                    <div class="sib-icon-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                        </svg>
                    </div>
                    <div class="sib-title">Akta Kematian</div>
                    <span class="sib-badge">1-2 Hari</span>
                </a>

                <a href="{{ route('masyarakat.permohonan.create', ['jenis_layanan' => 'Pindah']) }}" class="service-item-btn">
                    <div class="sib-icon-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                    </div>
                    <div class="sib-title">Surat Pindah</div>
                    <span class="sib-badge">1-2 Hari</span>
                </a>
            </div>
        </div>

        {{-- Riwayat Permohonan --}}
        <div>
            <div class="section-title">
                <span>Riwayat Permohonan Anda</span>
                <span class="section-title-tag">{{ $pendaftarans->count() }} Permohonan</span>
            </div>

            <div class="app-cards-list">
                @forelse ($pendaftarans as $pendaftaran)
                    @php $statusLower = strtolower($pendaftaran->status ?? 'baru'); @endphp
                    <div class="app-card {{ $statusLower }}">
                        <div class="ac-top">
                            <div>
                                <div class="ac-service">{{ $pendaftaran->jenis_layanan }}</div>
                                <div class="ac-id">
                                    #{{ str_pad($pendaftaran->id, 5, '0', STR_PAD_LEFT) }}
                                    <span class="ac-source-pill">{{ $pendaftaran->intake_source === 'masyarakat' ? 'Mandiri' : 'Petugas' }}</span>
                                </div>
                            </div>
                            <span class="ac-badge {{ $statusLower }}">
                                @if($statusLower === 'baru') 🔵 @elseif($statusLower === 'proses') 🟡 @elseif($statusLower === 'selesai') ✅ @else ❌ @endif
                                {{ $pendaftaran->status ?? 'Baru' }}
                            </span>
                        </div>

                        <div class="ac-meta">
                            📅 {{ $pendaftaran->tanggal_daftar?->translatedFormat('d M Y') ?? '-' }}
                            &nbsp;·&nbsp; {{ $pendaftaran->nama_lengkap }}
                        </div>

                        @if($pendaftaran->status === 'Ditolak' && !empty($pendaftaran->alasan_penolakan))
                            <div class="ac-note reject">
                                <strong>Alasan:</strong> {{ $pendaftaran->alasan_penolakan }}
                            </div>
                        @endif

                        @if(!empty($pendaftaran->catatan_petugas))
                            <div class="ac-note info">
                                <strong>Catatan:</strong> {{ $pendaftaran->catatan_petugas }}
                            </div>
                        @endif

                        @if(!empty($pendaftaran->documents))
                            <div class="ac-docs">
                                @foreach($pendaftaran->documents as $idx => $doc)
                                    <a href="{{ route('masyarakat.dokumen', [$pendaftaran, $idx]) }}" class="ac-doc-pill">
                                        📎 {{ Str::limit($doc['original_name'], 15) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <div class="ac-footer">
                            <span style="font-size:0.64rem;color:#78716c;">NIK: {{ $pendaftaran->nik }}</span>
                            <a href="{{ route('masyarakat.permohonan.success', $pendaftaran) }}" class="btn-view-receipt">
                                Bukti Registrasi →
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="background:#fff;border-radius:16px;padding:30px 16px;text-align:center;border:1px solid var(--khaki-border);color:#78716c;">
                        <div style="font-size:2rem;margin-bottom:8px;">📋</div>
                        <div style="font-size:0.88rem;font-weight:700;color:#292524;">Belum Ada Permohonan</div>
                        <p style="font-size:0.75rem;margin:4px 0 14px;">Silakan pilih jenis layanan di atas untuk mengajukan berkas kependudukan Anda.</p>
                        <a href="{{ route('masyarakat.permohonan.create') }}" style="display:inline-block;background:var(--khaki-primary);color:#fff;padding:10px 18px;border-radius:10px;text-decoration:none;font-size:0.82rem;font-weight:700;">
                            + Ajukan Permohonan Sekarang
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

    </main>
    </div>{{-- /.mob-scroll-area --}}

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
        <a class="mbn-fab-wrap" href="{{ route('masyarakat.permohonan.create') }}#dokumen">
            <div class="mbn-fab" title="Scan Dokumen">📷</div>
        </a>
        <a class="mbn-link" href="{{ route('masyarakat.riwayat') }}">
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
