@extends('layouts.app')

@section('title', 'Ajukan Permohonan - PATEN SPACE')

@section('content')
@php
    $isCitizen = true;
    $pendaftaran = $pendaftaran ?? new \App\Models\Pendaftaran();
    $isEdit = isset($pendaftaran->id);
    $actionUrl = $isCitizen ? route('masyarakat.permohonan.store') : route('pendaftaran.store');
    $citizen = auth()->user();
    $fixedService = request('jenis_layanan');
    $hasFixedService = in_array($fixedService, ['PRR','KTP','KK','Akta Kematian','Akta Lahir','Kedatangan','Pindah'], true);
    $selectedService = old('jenis_layanan', $fixedService ?? '');
@endphp

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

    /* ── Mobile Container (Tampilan HP Khaki) ── */
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

    /* ── Khaki Header ── */
    .mob-header {
        background: linear-gradient(135deg, var(--khaki-dark) 0%, var(--khaki-primary) 55%, var(--khaki-light) 100%);
        color: #ffffff;
        padding: 16px 18px 20px;
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: 0 4px 18px rgba(92, 71, 40, 0.2);
    }
    .mob-header-inner {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .mob-back-btn {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.22);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 1.1rem;
        flex-shrink: 0;
        transition: background 0.15s;
    }
    .mob-back-btn:active { background: rgba(255, 255, 255, 0.35); }
    .mob-header-info { flex: 1; }
    .mob-header-title {
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: -0.01em;
        line-height: 1.2;
    }
    .mob-header-sub {
        font-size: 0.72rem;
        opacity: 0.92;
        margin-top: 2px;
    }

    /* ── Stepper Bar ── */
    .mob-steps-wrap {
        background: #ffffff;
        border-bottom: 1px solid var(--khaki-border);
        padding: 14px 16px 12px;
        box-shadow: 0 2px 8px rgba(92, 71, 40, 0.02);
    }
    .mob-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .mob-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        flex: 1;
    }
    .mob-step-dot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--khaki-soft);
        border: 2px solid var(--khaki-border);
        color: var(--khaki-darker);
        display: grid;
        place-items: center;
        font-size: 0.75rem;
        font-weight: 800;
        transition: all 0.2s ease;
    }
    .mob-step-dot.active {
        background: var(--khaki-primary);
        border-color: var(--khaki-primary);
        color: #ffffff;
        box-shadow: 0 2px 10px rgba(138, 108, 66, 0.35);
    }
    .mob-step-dot.done {
        background: #10b981;
        border-color: #10b981;
        color: #ffffff;
    }
    .mob-step-label {
        font-size: 0.65rem;
        color: #78716c;
        font-weight: 600;
        text-align: center;
    }
    .mob-step-label.active {
        color: var(--khaki-darker);
        font-weight: 800;
    }
    .mob-step-line {
        flex: 1;
        height: 2px;
        background: var(--khaki-border);
        margin-bottom: 16px;
    }
    .mob-step-line.done { background: #10b981; }

    /* ── Content Container ── */
    .mob-body {
        padding: 16px 16px 24px;
        flex: 1;
    }

    /* ── Card (Ukuran Lebih Ringkas & Kecil) ── */
    .mob-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 12px 12px;
        margin-bottom: 12px;
        border: 1px solid var(--khaki-border);
        box-shadow: 0 2px 10px rgba(92, 71, 40, 0.04);
    }
    .mob-card-head {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.84rem;
        font-weight: 800;
        color: #292524;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f5f1e8;
    }

    /* ── Service Pills Grid (Lebih Kecil) ── */
    .mob-services-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 6px;
    }
    .mob-service-pill {
        background: #fdfcf9;
        border: 1.5px solid var(--khaki-border);
        border-radius: 12px;
        padding: 10px 8px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        text-align: center;
        transition: all 0.18s ease;
        box-shadow: 0 1px 4px rgba(92, 71, 40, 0.02);
    }
    .mob-service-pill:hover {
        border-color: var(--khaki-light);
        background: var(--khaki-soft);
        transform: translateY(-1px);
    }
    .mob-service-pill.selected {
        border-color: var(--khaki-primary);
        background: var(--khaki-soft);
        box-shadow: 0 2px 10px rgba(138, 108, 66, 0.2);
    }
    .msp-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: var(--khaki-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--khaki-primary);
        transition: background 0.15s;
    }
    .mob-service-pill.selected .msp-icon-box {
        background: var(--khaki-primary);
        color: #ffffff;
    }
    .msp-title {
        font-size: 0.74rem;
        font-weight: 700;
        color: #292524;
        line-height: 1.25;
    }
    .mob-service-pill.selected .msp-title {
        color: var(--khaki-darker);
    }

    /* ── Form Inputs Ringkas ── */
    .mob-field { margin-bottom: 10px; }
    .mob-label {
        display: block;
        font-size: 0.74rem;
        font-weight: 700;
        color: #443521;
        margin-bottom: 4px;
    }
    .mob-label span { color: #ef4444; }
    .mob-input, .mob-select, .mob-textarea {
        width: 100%;
        padding: 9px 11px;
        border: 1.5px solid var(--khaki-border);
        border-radius: 9px;
        font-size: 0.82rem;
        color: #292524;
        background: #ffffff;
        transition: all 0.15s;
    }
    .mob-input:focus, .mob-select:focus, .mob-textarea:focus {
        outline: none;
        border-color: var(--khaki-primary);
        box-shadow: 0 0 0 3px rgba(138, 108, 66, 0.15);
    }
    .mob-input:read-only {
        background: var(--khaki-soft);
        color: #78716c;
        cursor: not-allowed;
    }
    .mob-input.error, .mob-select.error { border-color: #ef4444; }
    .mob-error { color: #dc2626; font-size: 0.7rem; margin-top: 3px; font-weight: 600; }
    .mob-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }

    /* ── Step 3 Service Detail Sections ── */
    .mob-service-section { display: none; }
    .mob-service-section.show { display: block; }

    /* ── Camera & Upload Area (Step 4 Ringkas) ── */
    .mob-scan-card {
        border: 2px dashed var(--khaki-primary);
        border-radius: 12px;
        padding: 14px 10px;
        text-align: center;
        background: var(--khaki-soft);
        margin-bottom: 10px;
        cursor: pointer;
        transition: background 0.15s;
    }
    .mob-scan-card:active { background: #e8deca; }
    .msc-icon {
        width: 40px;
        height: 40px;
        background: var(--khaki-primary);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 6px;
        box-shadow: 0 2px 8px rgba(138, 108, 66, 0.25);
    }
    .msc-title { font-weight: 800; font-size: 0.82rem; color: var(--khaki-darker); margin-bottom: 2px; }
    .msc-desc { font-size: 0.68rem; color: var(--khaki-mid); }
    #scan-camera-input { display: none; }

    .mob-upload-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 10px;
        border: 1.5px dashed var(--khaki-border);
        border-radius: 10px;
        background: #fdfcf9;
        color: var(--khaki-darker);
        font-size: 0.76rem;
        font-weight: 700;
        cursor: pointer;
    }
    #scan-file-input { display: none; }

    .mob-file-list { display: flex; flex-direction: column; gap: 6px; margin-top: 10px; }
    .mob-file-item {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--khaki-bg);
        border: 1px solid var(--khaki-border);
        border-radius: 8px;
        padding: 6px 10px;
    }
    .mob-file-thumb {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        background: var(--khaki-soft);
        display: grid;
        place-items: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .mob-file-thumb img { width: 36px; height: 36px; border-radius: 6px; object-fit: cover; }
    .mob-file-info { flex: 1; min-width: 0; }
    .mob-file-name { font-size: 0.74rem; font-weight: 700; color: #292524; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mob-file-size { font-size: 0.64rem; color: #78716c; margin-top: 1px; }
    .mob-file-del {
        background: #fee2e2;
        border: none;
        border-radius: 6px;
        color: #dc2626;
        cursor: pointer;
        padding: 3px 6px;
        font-size: 0.74rem;
        font-weight: 700;
    }

    /* ── Action Buttons ── */
    .btn-step-next {
        width: 100%;
        padding: 11px;
        background: linear-gradient(135deg, var(--khaki-dark) 0%, var(--khaki-primary) 100%);
        color: #ffffff;
        border: none;
        border-radius: 11px;
        font-size: 0.88rem;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 3px 12px rgba(138, 108, 66, 0.3);
        transition: transform 0.12s;
    }
    .btn-step-next:active { transform: scale(0.98); }

    .mob-submit {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff;
        border: none;
        border-radius: 11px;
        font-size: 0.92rem;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 3px 12px rgba(16, 185, 129, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 10px;
    }
    .mob-submit:active { transform: scale(0.98); }
    .mob-submit:disabled { opacity: 0.7; cursor: not-allowed; }

    /* ── Bottom Navigation Bar (Khaki) ── */
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

    /* ── Camera Modal ── */
    .cam-modal {
        display: none; position: fixed; inset: 0; z-index: 200;
        background: #000; flex-direction: column;
    }
    .cam-modal.open { display: flex; }
    .cam-topbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; background: rgba(0,0,0,.6);
        color: #fff; position: absolute; top: 0; left: 0; right: 0; z-index: 10;
    }
    .cam-close { background: none; border: none; color: #fff; font-size: 1.4rem; cursor: pointer; }
    #cam-video { width: 100%; height: 100%; object-fit: cover; }
    .cam-controls {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 24px 40px env(safe-area-inset-bottom, 24px);
        display: flex; align-items: center; justify-content: space-between;
        background: rgba(0,0,0,.5);
    }
    .cam-switch, .cam-flash {
        width: 44px; height: 44px; border-radius: 50%;
        background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.3);
        color: #fff; font-size: 1.3rem; cursor: pointer;
        display: grid; place-items: center;
    }
    .cam-shutter {
        width: 68px; height: 68px; border-radius: 50%;
        background: #fff; border: 4px solid rgba(255,255,255,.4);
        cursor: pointer;
    }
    #cam-canvas { display: none; }
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

        {{-- Khaki Header --}}
        <header class="mob-header">
        <div class="mob-header-inner">
            <a href="{{ route('masyarakat.dashboard') }}" class="mob-back-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <div class="mob-header-info">
                <h1 class="mob-header-title">Ajukan Permohonan</h1>
                <div class="mob-header-sub">PATEN SPACE · Kecamatan Jatisari</div>
            </div>
        </div>
    </header>

    {{-- Stepper Bar --}}
    <div class="mob-steps-wrap">
        <div class="mob-steps">
            <div class="mob-step">
                <div class="mob-step-dot active" id="step-dot-1">1</div>
                <div class="mob-step-label active" id="step-lbl-1">Layanan</div>
            </div>
            <div class="mob-step-line" id="step-line-1"></div>
            <div class="mob-step">
                <div class="mob-step-dot" id="step-dot-2">2</div>
                <div class="mob-step-label" id="step-lbl-2">Data Diri</div>
            </div>
            <div class="mob-step-line" id="step-line-2"></div>
            <div class="mob-step">
                <div class="mob-step-dot" id="step-dot-3">3</div>
                <div class="mob-step-label" id="step-lbl-3">Detail</div>
            </div>
            <div class="mob-step-line" id="step-line-3"></div>
            <div class="mob-step">
                <div class="mob-step-dot" id="step-dot-4">4</div>
                <div class="mob-step-label" id="step-lbl-4">Dokumen</div>
            </div>
        </div>
    </div>

    {{-- Form Body --}}
    <div class="mob-body">

        @if($errors->any())
            <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:12px;padding:12px 14px;font-size:0.78rem;margin-bottom:14px;">
                <strong>⚠️ Mohon periksa kembali isian:</strong>
                <ul style="margin:4px 0 0 16px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" id="mob-form">
            @csrf
            <input type="hidden" name="tanggal_daftar" value="{{ now()->format('Y-m-d') }}">
            <input type="hidden" id="hidden-jenis-layanan" name="jenis_layanan" value="{{ old('jenis_layanan', $selectedService) }}">

            {{-- STEP 1: Pilih Layanan --}}
            <div id="mob-step-1">
                <div class="mob-card">
                    <div class="mob-card-head">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--khaki-primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Pilih Jenis Layanan Administrasi
                    </div>

                    <div class="mob-services-grid">
                        {{-- KTP --}}
                        <button type="button"
                            class="mob-service-pill {{ old('jenis_layanan', $selectedService) === 'KTP' ? 'selected' : '' }}"
                            data-service="KTP" onclick="selectService('KTP')">
                            <div class="msp-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="16" rx="3"></rect>
                                    <circle cx="9" cy="10" r="2"></circle>
                                    <line x1="15" y1="8" x2="17" y2="8"></line>
                                    <line x1="15" y1="12" x2="17" y2="12"></line>
                                    <line x1="7" y1="16" x2="17" y2="16"></line>
                                </svg>
                            </div>
                            <div class="msp-title">KTP Pembaharuan</div>
                        </button>

                        {{-- PRR --}}
                        <button type="button"
                            class="mob-service-pill {{ old('jenis_layanan', $selectedService) === 'PRR' ? 'selected' : '' }}"
                            data-service="PRR" onclick="selectService('PRR')">
                            <div class="msp-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                    <path d="M9 14l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div class="msp-title">PRR / KTP Baru</div>
                        </button>

                        {{-- KK --}}
                        <button type="button"
                            class="mob-service-pill {{ old('jenis_layanan', $selectedService) === 'KK' ? 'selected' : '' }}"
                            data-service="KK" onclick="selectService('KK')">
                            <div class="msp-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <div class="msp-title">Kartu Keluarga</div>
                        </button>

                        {{-- Akta Lahir --}}
                        <button type="button"
                            class="mob-service-pill {{ old('jenis_layanan', $selectedService) === 'Akta Lahir' ? 'selected' : '' }}"
                            data-service="Akta Lahir" onclick="selectService('Akta Lahir')">
                            <div class="msp-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2a5 5 0 0 1 5 5c0 2-1 3.5-2.5 4.5l1.5 6.5-4-2-4 2 1.5-6.5C8 10.5 7 9 7 7a5 5 0 0 1 5-5z"></path>
                                </svg>
                            </div>
                            <div class="msp-title">Akta Kelahiran</div>
                        </button>

                        {{-- Akta Kematian --}}
                        <button type="button"
                            class="mob-service-pill {{ old('jenis_layanan', $selectedService) === 'Akta Kematian' ? 'selected' : '' }}"
                            data-service="Akta Kematian" onclick="selectService('Akta Kematian')">
                            <div class="msp-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                            </div>
                            <div class="msp-title">Akta Kematian</div>
                        </button>

                        {{-- Kedatangan --}}
                        <button type="button"
                            class="mob-service-pill {{ old('jenis_layanan', $selectedService) === 'Kedatangan' ? 'selected' : '' }}"
                            data-service="Kedatangan" onclick="selectService('Kedatangan')">
                            <div class="msp-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                            </div>
                            <div class="msp-title">Kedatangan</div>
                        </button>

                        {{-- Pindah --}}
                        <button type="button"
                            class="mob-service-pill {{ old('jenis_layanan', $selectedService) === 'Pindah' ? 'selected' : '' }}"
                            data-service="Pindah" onclick="selectService('Pindah')" style="grid-column: span 2;">
                            <div class="msp-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg>
                            </div>
                            <div class="msp-title">Surat Pindah (SKPWNI)</div>
                        </button>
                    </div>

                    @error('jenis_layanan')<p class="mob-error">{{ $message }}</p>@enderror
                </div>

                <button type="button" onclick="goStep(2)" class="btn-step-next">
                    Lanjut ke Data Diri →
                </button>
            </div>

            {{-- STEP 2: Data Diri --}}
            <div id="mob-step-2" style="display:none;">
                <div class="mob-card">
                    <div class="mob-card-head">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--khaki-primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Identitas Pemohon
                    </div>

                    <div class="mob-field">
                        <label class="mob-label">Nama Lengkap <span>*</span></label>
                        <input class="mob-input {{ $errors->has('nama_lengkap') ? 'error' : '' }}"
                            name="nama_lengkap" type="text"
                            placeholder="Nama sesuai KTP"
                            value="{{ old('nama_lengkap', $citizen->name ?? '') }}" required>
                        @error('nama_lengkap')<p class="mob-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="mob-field">
                        <label class="mob-label">Nomor Induk Kependudukan (NIK) <span>*</span></label>
                        <input class="mob-input" name="nik" type="text"
                            placeholder="16 digit NIK"
                            value="{{ old('nik', $citizen->nik ?? '') }}" readonly required>
                    </div>

                    <div class="mob-grid-2">
                        <div class="mob-field">
                            <label class="mob-label">Jenis Kelamin</label>
                            <select class="mob-select" name="jenis_kelamin">
                                <option value="">Pilih</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">No. Telepon / WA</label>
                            <input class="mob-input" name="no_telepon" type="tel"
                                placeholder="08xxxxxxxxxx"
                                value="{{ old('no_telepon', $citizen->no_telepon ?? '') }}">
                        </div>
                    </div>

                    <div class="mob-grid-2">
                        <div class="mob-field">
                            <label class="mob-label">Tempat Lahir</label>
                            <input class="mob-input" name="tempat_lahir" type="text"
                                placeholder="Kota Lahir"
                                value="{{ old('tempat_lahir') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Tanggal Lahir</label>
                            <input class="mob-input" name="tanggal_lahir" type="text"
                                placeholder="dd-mm-yyyy"
                                value="{{ old('tanggal_lahir') }}">
                        </div>
                    </div>

                    <div class="mob-field">
                        <label class="mob-label">Alamat / Nama Jalan</label>
                        <input class="mob-input" name="jalan" type="text"
                            placeholder="Dusun Krajan / Jl. Mawar" value="{{ old('jalan') }}">
                    </div>

                    <div class="mob-grid-2">
                        <div class="mob-field">
                            <label class="mob-label">RT</label>
                            <input class="mob-input" name="rt" type="text" placeholder="001" value="{{ old('rt') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">RW</label>
                            <input class="mob-input" name="rw" type="text" placeholder="002" value="{{ old('rw') }}">
                        </div>
                    </div>

                    <div class="mob-field">
                        <label class="mob-label">Desa / Kelurahan</label>
                        <select class="mob-select" name="desa">
                            <option value="">Pilih Desa di Jatisari</option>
                            @foreach(['Barugbug','Situdam','Balonggandu','Kalijati','Jatisari','Mekarsari','Cirejag','Cikalongsari','Jatiragas','Jatiwangi','Jatibaru','Telarsari','Sukamekar','Pacing'] as $desa)
                                <option value="{{ $desa }}" {{ old('desa') == $desa ? 'selected' : '' }}>{{ $desa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="button" onclick="goStep(1)"
                        style="flex:0 0 48px;padding:14px;background:var(--khaki-soft);color:var(--khaki-darker);border:1px solid var(--khaki-border);border-radius:14px;font-size:0.95rem;font-weight:700;cursor:pointer;">←</button>
                    <button type="button" onclick="goStep(3)" class="btn-step-next" style="flex:1;">
                        Lanjut ke Detail Layanan →
                    </button>
                </div>
            </div>

            {{-- STEP 3: Detail Layanan --}}
            <div id="mob-step-3" style="display:none;">

                {{-- KTP --}}
                <div class="mob-service-section" data-for="KTP">
                    <div class="mob-card">
                        <div class="mob-card-head">Detail KTP Pembaharuan</div>
                        <div class="mob-field">
                            <label class="mob-label">Alasan Pembaharuan</label>
                            <select class="mob-select" name="alasan_pembaruan">
                                <option value="">Pilih alasan</option>
                                <option value="Hilang" {{ old('alasan_pembaruan')=='Hilang'?'selected':'' }}>Hilang</option>
                                <option value="Rusak" {{ old('alasan_pembaruan')=='Rusak'?'selected':'' }}>Rusak</option>
                                <option value="Perubahan Data" {{ old('alasan_pembaruan')=='Perubahan Data'?'selected':'' }}>Perubahan Data</option>
                            </select>
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Keterangan Tambahan</label>
                            <textarea class="mob-textarea" name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- PRR --}}
                <div class="mob-service-section" data-for="PRR">
                    <div class="mob-card">
                        <div class="mob-card-head">Detail PRR / KTP Baru</div>
                        <div class="mob-grid-2">
                            <div class="mob-field">
                                <label class="mob-label">Bulan</label>
                                <input class="mob-input" name="bulan" type="text" placeholder="Contoh: JAN" value="{{ old('bulan') }}">
                            </div>
                            <div class="mob-field">
                                <label class="mob-label">Minggu Ke</label>
                                <input class="mob-input" name="minggu_ke" type="text" placeholder="1" value="{{ old('minggu_ke') }}">
                            </div>
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Tanggal Kedatangan</label>
                            <input class="mob-input" name="tanggal_kedatangan" type="date" value="{{ old('tanggal_kedatangan') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Alamat Asal</label>
                            <input class="mob-input" name="alamat_asal" type="text" placeholder="Alamat asal" value="{{ old('alamat_asal') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Alamat Tujuan</label>
                            <input class="mob-input" name="alamat_tujuan" type="text" placeholder="Alamat tujuan" value="{{ old('alamat_tujuan') }}">
                        </div>
                    </div>
                </div>

                {{-- KK --}}
                <div class="mob-service-section" data-for="KK">
                    <div class="mob-card">
                        <div class="mob-card-head">Detail Kartu Keluarga</div>
                        <div class="mob-field">
                            <label class="mob-label">Nomor KK</label>
                            <input class="mob-input" name="nomor_kk" type="text" placeholder="16 digit nomor KK" value="{{ old('nomor_kk') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Nama Kepala Keluarga</label>
                            <input class="mob-input" name="kepala_keluarga" type="text" placeholder="Nama Kepala Keluarga" value="{{ old('kepala_keluarga') }}">
                        </div>
                        <div class="mob-grid-2">
                            <div class="mob-field">
                                <label class="mob-label">Hubungan</label>
                                <input class="mob-input" name="hubungan" type="text" placeholder="Hubungan" value="{{ old('hubungan') }}">
                            </div>
                            <div class="mob-field">
                                <label class="mob-label">Jumlah Anggota</label>
                                <input class="mob-input" name="jumlah_anggota" type="text" placeholder="Contoh: 4" value="{{ old('jumlah_anggota') }}">
                            </div>
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Keterangan</label>
                            <textarea class="mob-textarea" name="keterangan_kk" rows="2" placeholder="Keterangan permohonan KK">{{ old('keterangan_kk') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Akta Lahir --}}
                <div class="mob-service-section" data-for="Akta Lahir">
                    <div class="mob-card">
                        <div class="mob-card-head">Detail Akta Kelahiran</div>
                        <div class="mob-field">
                            <label class="mob-label">Nama Anak</label>
                            <input class="mob-input" name="nama_anak_lahir" type="text" placeholder="Nama lengkap anak" value="{{ old('nama_anak_lahir') }}">
                        </div>
                        <div class="mob-grid-2">
                            <div class="mob-field">
                                <label class="mob-label">Nama Ayah</label>
                                <input class="mob-input" name="nama_ayah_lahir" type="text" placeholder="Nama ayah" value="{{ old('nama_ayah_lahir') }}">
                            </div>
                            <div class="mob-field">
                                <label class="mob-label">Nama Ibu</label>
                                <input class="mob-input" name="nama_ibu_lahir" type="text" placeholder="Nama ibu" value="{{ old('nama_ibu_lahir') }}">
                            </div>
                        </div>
                        <div class="mob-grid-2">
                            <div class="mob-field">
                                <label class="mob-label">Berat Badan (kg)</label>
                                <input class="mob-input" name="berat_badan" type="text" placeholder="Contoh: 3.2" value="{{ old('berat_badan') }}">
                            </div>
                            <div class="mob-field">
                                <label class="mob-label">Panjang (cm)</label>
                                <input class="mob-input" name="panjang_badan" type="text" placeholder="Contoh: 49" value="{{ old('panjang_badan') }}">
                            </div>
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Tempat Lahir Detail</label>
                            <input class="mob-input" name="tempat_lahir_detail" type="text" placeholder="Klinik / RS / Bidan" value="{{ old('tempat_lahir_detail') }}">
                        </div>
                    </div>
                </div>

                {{-- Akta Kematian --}}
                <div class="mob-service-section" data-for="Akta Kematian">
                    <div class="mob-card">
                        <div class="mob-card-head">Detail Akta Kematian</div>
                        <div class="mob-field">
                            <label class="mob-label">Nama Almarhum/ah</label>
                            <input class="mob-input" name="nama_alm" type="text" placeholder="Nama lengkap" value="{{ old('nama_alm') }}">
                        </div>
                        <div class="mob-grid-2">
                            <div class="mob-field">
                                <label class="mob-label">Nama Ayah</label>
                                <input class="mob-input" name="nama_ayah" type="text" placeholder="Nama ayah" value="{{ old('nama_ayah') }}">
                            </div>
                            <div class="mob-field">
                                <label class="mob-label">Nama Ibu</label>
                                <input class="mob-input" name="nama_ibu" type="text" placeholder="Nama ibu" value="{{ old('nama_ibu') }}">
                            </div>
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Tempat Meninggal</label>
                            <input class="mob-input" name="tempat_meninggal" type="text" placeholder="Tempat meninggal" value="{{ old('tempat_meninggal') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Tanggal Meninggal</label>
                            <input class="mob-input" name="tanggal_meninggal" type="date" value="{{ old('tanggal_meninggal') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Keterangan</label>
                            <textarea class="mob-textarea" name="keterangan_kematian" rows="2" placeholder="Penyebab / keterangan">{{ old('keterangan_kematian') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Kedatangan --}}
                <div class="mob-service-section" data-for="Kedatangan">
                    <div class="mob-card">
                        <div class="mob-card-head">Detail Kedatangan</div>
                        <div class="mob-field">
                            <label class="mob-label">Nama</label>
                            <input class="mob-input" name="nama_kedatangan" type="text" placeholder="Nama lengkap" value="{{ old('nama_kedatangan') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">NIK</label>
                            <input class="mob-input" name="nik_kedatangan" type="text" placeholder="NIK 16 digit" value="{{ old('nik_kedatangan') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Alamat Asal</label>
                            <input class="mob-input" name="alamat_asal_kedatangan" type="text" placeholder="Alamat lengkap asal" value="{{ old('alamat_asal_kedatangan') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Alamat Tujuan di Jatisari</label>
                            <input class="mob-input" name="alamat_tujuan_kedatangan" type="text" placeholder="Dusun / RT / RW / Desa tujuan" value="{{ old('alamat_tujuan_kedatangan') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Keterangan</label>
                            <textarea class="mob-textarea" name="keterangan_kedatangan" rows="2">{{ old('keterangan_kedatangan') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Pindah --}}
                <div class="mob-service-section" data-for="Pindah">
                    <div class="mob-card">
                        <div class="mob-card-head">Detail Surat Pindah</div>
                        <div class="mob-field">
                            <label class="mob-label">Nama</label>
                            <input class="mob-input" name="nama_pindah" type="text" placeholder="Nama lengkap" value="{{ old('nama_pindah') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">NIK</label>
                            <input class="mob-input" name="nik_pindah" type="text" placeholder="NIK 16 digit" value="{{ old('nik_pindah') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Alamat Asal di Jatisari</label>
                            <input class="mob-input" name="alamat_asal_pindah" type="text" placeholder="Alamat asal" value="{{ old('alamat_asal_pindah') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Alamat Tujuan Pindah</label>
                            <input class="mob-input" name="alamat_tujuan_pindah" type="text" placeholder="Kota / Kabupaten tujuan" value="{{ old('alamat_tujuan_pindah') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Alasan Pindah</label>
                            <textarea class="mob-textarea" name="alasan_pindah" rows="2" placeholder="Alasan kepindahan">{{ old('alasan_pindah') }}</textarea>
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="button" onclick="goStep(2)"
                        style="flex:0 0 48px;padding:14px;background:var(--khaki-soft);color:var(--khaki-darker);border:1px solid var(--khaki-border);border-radius:14px;font-size:0.95rem;font-weight:700;cursor:pointer;">←</button>
                    <button type="button" onclick="goStep(4)" class="btn-step-next" style="flex:1;">
                        Lanjut ke Dokumen →
                    </button>
                </div>
            </div>

            {{-- STEP 4: Upload Dokumen & Camera Scan --}}
            <div id="mob-step-4" style="display:none;">
                <div class="mob-card" id="dokumen">
                    <div class="mob-card-head">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--khaki-primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                        </svg>
                        Upload Dokumen Persyaratan
                    </div>
                    <p style="font-size:0.75rem;color:#78716c;margin-bottom:14px;line-height:1.45;">
                        Lampirkan foto atau scan dokumen pendukung (KTP asli, Kartu Keluarga, Akta, dll). Maks 5 file, 5 MB per file.
                    </p>

                    {{-- Camera Scan --}}
                    <div class="mob-scan-card" onclick="openCamera()">
                        <div class="msc-icon">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                        </div>
                        <div class="msc-title">Foto Dokumen dengan Kamera</div>
                        <div class="msc-desc">Buka kamera ponsel untuk memfoto berkas persyaratan secara instan</div>
                    </div>

                    {{-- File Picker --}}
                    <label class="mob-upload-btn" for="scan-file-input">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        Pilih dari Galeri Foto / File PDF
                    </label>
                    <input id="scan-file-input" type="file" accept="image/*,.pdf" multiple>

                    {{-- Hidden file inputs --}}
                    <div id="hidden-file-inputs"></div>

                    {{-- File previews --}}
                    <div id="mob-file-list" class="mob-file-list"></div>

                    @error('documents')  <p class="mob-error">{{ $message }}</p> @enderror
                    @error('documents.*')<p class="mob-error">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="mob-submit" id="mob-submit-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Kirim Permohonan
                </button>

                <div style="margin-top:10px;">
                    <button type="button" onclick="goStep(3)"
                        style="width:100%;padding:12px;background:var(--khaki-soft);color:var(--khaki-darker);border:1px solid var(--khaki-border);border-radius:12px;font-size:0.82rem;font-weight:700;cursor:pointer;">
                        ← Kembali ke Detail Layanan
                    </button>
                </div>
            </div>
        </form>

    </div>
    </div>

    {{-- Bottom Navigation Bar Aplikasi --}}
    <nav class="mob-bottom-nav">
        <a class="mbn-link" href="{{ route('masyarakat.dashboard') }}">
            <span class="mbn-icon">🏠</span>
            <span>Beranda</span>
        </a>
        <a class="mbn-link active" href="{{ route('masyarakat.permohonan.create') }}">
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

{{-- Camera Modal --}}
<div class="cam-modal" id="cam-modal">
    <div class="cam-topbar">
        <span style="font-weight:700;font-size:0.95rem;">📷 Pemindai Dokumen</span>
        <button class="cam-close" onclick="closeCamera()">✕</button>
    </div>
    <video id="cam-video" autoplay playsinline muted></video>
    <canvas id="cam-canvas"></canvas>
    <div class="cam-controls">
        <button type="button" class="cam-switch" onclick="switchCamera()" title="Ganti kamera">🔄</button>
        <button type="button" class="cam-shutter" onclick="capturePhoto()" title="Ambil foto"></button>
        <button type="button" class="cam-flash" id="cam-flash-btn" onclick="toggleFlash()" title="Flash">🔦</button>
    </div>
</div>

<script>
let currentStep = {{ $errors->any() ? 4 : 1 }};
const totalSteps = 4;

function goStep(n) {
    if (n === 2 && !validateStep1()) return;
    document.getElementById('mob-step-' + currentStep).style.display = 'none';
    currentStep = n;
    document.getElementById('mob-step-' + n).style.display = 'block';
    updateStepUI();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepUI() {
    for (let i = 1; i <= totalSteps; i++) {
        const dot = document.getElementById('step-dot-' + i);
        const lbl = document.getElementById('step-lbl-' + i);
        const line = document.getElementById('step-line-' + i);
        dot.className = 'mob-step-dot' + (i < currentStep ? ' done' : i === currentStep ? ' active' : '');
        dot.textContent = i < currentStep ? '✓' : i;
        lbl.className = 'mob-step-label' + (i === currentStep ? ' active' : '');
        if (line) line.className = 'mob-step-line' + (i < currentStep ? ' done' : '');
    }
}

function validateStep1() {
    const svc = document.getElementById('hidden-jenis-layanan').value;
    if (!svc) { alert('Pilih jenis layanan terlebih dahulu.'); return false; }
    return true;
}

function selectService(value) {
    document.getElementById('hidden-jenis-layanan').value = value;
    document.querySelectorAll('.mob-service-pill').forEach(p => {
        p.classList.toggle('selected', p.dataset.service === value);
    });
    document.querySelectorAll('.mob-service-section').forEach(s => {
        s.classList.toggle('show', s.dataset.for === value);
    });
}

(function() {
    const saved = '{{ old('jenis_layanan', $selectedService) }}';
    if (saved) selectService(saved);
    if ({{ $errors->any() ? 'true' : 'false' }}) {
        for (let i = 1; i <= totalSteps; i++) {
            document.getElementById('mob-step-' + i).style.display = 'none';
        }
        document.getElementById('mob-step-4').style.display = 'block';
        currentStep = 4;
        updateStepUI();
    }
})();

// File management
let fileStore = [];
const MAX_FILES = 5;

function formatBytes(b) {
    return b < 1024*1024 ? (b/1024).toFixed(1)+' KB' : (b/(1024*1024)).toFixed(1)+' MB';
}

function addFile(file) {
    if (fileStore.length >= MAX_FILES) { alert('Maksimal 5 dokumen.'); return; }
    if (file.size > 5 * 1024 * 1024) { alert(file.name + ' melebihi batas 5 MB.'); return; }

    const reader = new FileReader();
    reader.onload = function(e) {
        const item = { file, dataUrl: e.target.result };
        fileStore.push(item);
        renderFileList();
        syncHiddenInputs();
    };
    reader.readAsDataURL(file);
}

function removeFile(idx) {
    fileStore.splice(idx, 1);
    renderFileList();
    syncHiddenInputs();
}

function renderFileList() {
    const list = document.getElementById('mob-file-list');
    list.innerHTML = '';
    fileStore.forEach((item, idx) => {
        const isPdf = item.file.type === 'application/pdf';
        const thumb = isPdf
            ? '<div class="mob-file-thumb">📄</div>'
            : `<div class="mob-file-thumb"><img src="${item.dataUrl}" alt=""></div>`;
        const div = document.createElement('div');
        div.className = 'mob-file-item';
        div.innerHTML = `
            ${thumb}
            <div class="mob-file-info">
                <div class="mob-file-name">${item.file.name}</div>
                <div class="mob-file-size">${formatBytes(item.file.size)}</div>
            </div>
            <button type="button" class="mob-file-del" onclick="removeFile(${idx})">✕</button>
        `;
        list.appendChild(div);
    });
}

function syncHiddenInputs() {
    const container = document.getElementById('hidden-file-inputs');
    container.innerHTML = '';
    fileStore.forEach((item, idx) => {
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'documents[]';
        input.style.display = 'none';
        container.appendChild(input);
        const dt = new DataTransfer();
        dt.items.add(item.file);
        input.files = dt.files;
    });
}

document.getElementById('scan-file-input').addEventListener('change', function(e) {
    Array.from(e.target.files).forEach(addFile);
    this.value = '';
});

// Camera
let camStream = null;
let facingMode = 'environment';
let flashOn = false;
let flashTrack = null;

async function openCamera() {
    const modal = document.getElementById('cam-modal');
    modal.classList.add('open');
    await startStream();
}

function closeCamera() {
    document.getElementById('cam-modal').classList.remove('open');
    stopStream();
}

async function startStream() {
    stopStream();
    try {
        camStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode, width: { ideal: 1920 }, height: { ideal: 1080 } },
            audio: false
        });
        const video = document.getElementById('cam-video');
        video.srcObject = camStream;
        flashTrack = camStream.getVideoTracks()[0];
    } catch(err) {
        alert('Tidak dapat mengakses kamera: ' + err.message);
        closeCamera();
    }
}

function stopStream() {
    if (camStream) { camStream.getTracks().forEach(t => t.stop()); camStream = null; }
    flashOn = false;
}

async function switchCamera() {
    facingMode = facingMode === 'environment' ? 'user' : 'environment';
    await startStream();
}

async function toggleFlash() {
    if (!flashTrack) return;
    flashOn = !flashOn;
    try {
        await flashTrack.applyConstraints({ advanced: [{ torch: flashOn }] });
        document.getElementById('cam-flash-btn').textContent = flashOn ? '⚡' : '🔦';
    } catch(e) { flashOn = false; }
}

function capturePhoto() {
    const video  = document.getElementById('cam-video');
    const canvas = document.getElementById('cam-canvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    canvas.toBlob(blob => {
        const ts   = new Date().toISOString().replace(/[:.]/g,'').slice(0,15);
        const file = new File([blob], `scan-${ts}.jpg`, { type: 'image/jpeg' });
        closeCamera();
        addFile(file);
        goStep(4);
    }, 'image/jpeg', 0.92);
}

document.getElementById('mob-form').addEventListener('submit', function(e) {
    const btn = document.getElementById('mob-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span>⏳</span> Mengirim...';
});

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
