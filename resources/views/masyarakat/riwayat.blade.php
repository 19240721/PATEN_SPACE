@extends('layouts.app')

@section('title', 'Riwayat Permohonan - PATEN SPACE')

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

    /* ── Status Bar ── */
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
    .psb-net-type { font-size: 0.65rem; font-weight: 900; line-height: 1; }
    .psb-battery-unit { display: inline-flex; align-items: center; gap: 3px; }
    .psb-battery-text { font-size: 0.68rem; font-weight: 800; }
    .psb-battery-outline {
        width: 22px; height: 11px; border: 1.5px solid #fff;
        border-radius: 3.5px; padding: 1px; display: flex; align-items: center;
    }
    .psb-battery-level { width: 85%; height: 100%; background: #22c55e; border-radius: 1.5px; }
    .psb-battery-cap { width: 2px; height: 5px; background: #fff; border-radius: 0 1.5px 1.5px 0; }

    /* ── Page Header (Fixed, Rounded Bottom) ── */
    .page-header {
        flex-shrink: 0;
        background: linear-gradient(145deg, var(--khaki-dark) 0%, var(--khaki-primary) 55%, var(--khaki-light) 100%);
        color: #ffffff;
        padding: 14px 18px 26px;
        position: relative;
        overflow: hidden;
        border-bottom-left-radius: 28px;
        border-bottom-right-radius: 28px;
        box-shadow: 0 6px 20px rgba(92, 71, 40, 0.22);
        z-index: 10;
    }
    .page-header::before {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 180px; height: 180px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .ph-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        position: relative;
        z-index: 1;
    }
    .ph-back {
        display: flex; align-items: center; gap: 6px;
        text-decoration: none; color: rgba(255,255,255,0.9);
        font-size: 0.82rem; font-weight: 700;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        padding: 6px 12px;
        border-radius: 999px;
        transition: background 0.15s;
    }
    .ph-back:active { background: rgba(255,255,255,0.25); }
    .ph-title {
        font-size: 1.05rem; font-weight: 800; color: #fff;
        letter-spacing: 0.01em;
        position: relative; z-index: 1;
    }
    .ph-subtitle {
        font-size: 0.72rem; opacity: 0.85; margin-top: 1px;
        position: relative; z-index: 1;
    }

    /* ── Filter Chips Row ── */
    .filter-strip {
        position: relative; z-index: 1;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 12px;
        overflow-x: auto;
        padding-bottom: 2px;
        scrollbar-width: none;
    }
    .filter-strip::-webkit-scrollbar { display: none; }
    .filter-chip {
        flex-shrink: 0;
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 12px;
        border-radius: 999px;
        border: 1.5px solid rgba(255,255,255,0.35);
        background: rgba(255,255,255,0.15);
        color: #fff;
        font-size: 0.72rem; font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .filter-chip.active,
    .filter-chip:active {
        background: rgba(255,255,255,0.92);
        color: var(--khaki-dark);
        border-color: transparent;
    }

    /* ── Search Bar ── */
    .search-wrap {
        padding: 12px 16px 6px;
        flex-shrink: 0;
    }
    .search-inner {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1.5px solid var(--khaki-border);
        border-radius: 12px;
        padding: 8px 12px;
        gap: 8px;
        box-shadow: 0 1px 6px rgba(92,71,40,0.06);
    }
    .search-inner svg { color: var(--khaki-light); flex-shrink: 0; }
    .search-inner input {
        flex: 1; border: none; outline: none;
        font-size: 0.82rem; font-family: inherit;
        color: #292524; background: transparent;
    }
    .search-inner input::placeholder { color: #a8a29e; }
    .search-btn {
        background: var(--khaki-primary); color: #fff;
        border: none; border-radius: 8px;
        padding: 4px 10px; font-size: 0.72rem; font-weight: 700;
        cursor: pointer; white-space: nowrap;
    }

    /* ── Summary Chips Row ── */
    .summary-row {
        display: flex;
        gap: 6px;
        padding: 8px 16px 4px;
        overflow-x: auto;
        scrollbar-width: none;
        flex-shrink: 0;
    }
    .summary-row::-webkit-scrollbar { display: none; }
    .sum-chip {
        flex-shrink: 0;
        display: flex; align-items: center; gap: 5px;
        background: #fff;
        border: 1px solid var(--khaki-border);
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 0.68rem; font-weight: 700; color: #292524;
        white-space: nowrap;
    }
    .sum-dot { width: 6px; height: 6px; border-radius: 50%; }
    .sum-dot.baru    { background: #3b82f6; }
    .sum-dot.proses  { background: var(--khaki-primary); }
    .sum-dot.selesai { background: #10b981; }
    .sum-dot.ditolak { background: #ef4444; }

    /* ── Scrollable List ── */
    .mob-scroll-area {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        display: flex;
        flex-direction: column;
        background: var(--khaki-bg);
        margin-top: -14px;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
        padding-top: 0;
    }
    .mob-scroll-area::-webkit-scrollbar { width: 4px; }
    .mob-scroll-area::-webkit-scrollbar-thumb { background: var(--khaki-light); border-radius: 4px; }

    .list-container {
        padding: 14px 14px 24px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* ── Riwayat Cards ── */
    .riwayat-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 12px 14px;
        border: 1px solid var(--khaki-border);
        box-shadow: 0 1px 6px rgba(92,71,40,0.05);
        border-left: 4px solid #cbd5e1;
        text-decoration: none;
        color: inherit;
        display: block;
        transition: box-shadow 0.15s, transform 0.12s;
    }
    .riwayat-card:active { transform: scale(0.98); }
    .riwayat-card.baru    { border-left-color: #3b82f6; }
    .riwayat-card.proses  { border-left-color: var(--khaki-primary); }
    .riwayat-card.selesai { border-left-color: #10b981; }
    .riwayat-card.ditolak { border-left-color: #ef4444; }

    .rc-top {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 6px; gap: 8px;
    }
    .rc-service {
        font-size: 0.88rem; font-weight: 800; color: #292524;
        line-height: 1.2;
    }
    .rc-badge {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 3px 8px; border-radius: 999px;
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
        flex-shrink: 0;
    }
    .rc-badge.baru    { background: #dbeafe; color: #1e40af; }
    .rc-badge.proses  { background: #fef3c7; color: #92400e; }
    .rc-badge.selesai { background: #d1fae5; color: #065f46; }
    .rc-badge.ditolak { background: #fee2e2; color: #991b1b; }

    .rc-meta {
        font-size: 0.70rem; color: #78716c;
        display: flex; align-items: center; gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 6px;
    }
    .rc-meta-sep { color: #d1ccc6; }

    .rc-footer {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 6px; padding-top: 6px;
        border-top: 1px solid #f5f1e8;
    }
    .rc-nik {
        font-size: 0.65rem; color: #a8a29e; font-family: monospace;
    }
    .rc-action {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.68rem; font-weight: 700;
        color: var(--khaki-primary);
        text-decoration: none;
        background: var(--khaki-soft);
        border: 1px solid var(--khaki-border);
        padding: 3px 10px; border-radius: 6px;
    }

    .rc-note {
        font-size: 0.70rem; line-height: 1.35;
        padding: 5px 8px; border-radius: 6px;
        margin-bottom: 6px;
    }
    .rc-note.reject { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .rc-note.info   { background: var(--khaki-soft); border: 1px solid var(--khaki-border); color: var(--khaki-darker); }

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #78716c;
    }
    .empty-state .es-icon { font-size: 3rem; margin-bottom: 12px; }
    .empty-state .es-title { font-size: 0.95rem; font-weight: 800; color: #292524; margin-bottom: 6px; }
    .empty-state .es-desc { font-size: 0.78rem; margin-bottom: 18px; line-height: 1.5; }
    .btn-ajukan {
        display: inline-block;
        background: var(--khaki-primary); color: #fff;
        padding: 10px 20px; border-radius: 10px;
        text-decoration: none; font-size: 0.82rem; font-weight: 700;
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
        box-shadow: 0 -4px 16px rgba(92,71,40,0.08);
        z-index: 90;
    }
    .mbn-link {
        flex: 1; display: flex; flex-direction: column; align-items: center;
        gap: 3px; text-decoration: none; color: #78716c;
        font-size: 0.65rem; font-weight: 700; transition: color 0.15s;
    }
    .mbn-link.active { color: var(--khaki-primary); }
    .mbn-icon { font-size: 1.25rem; line-height: 1; }
    .mbn-fab-wrap { flex: 1; display: flex; justify-content: center; text-decoration: none; }
    .mbn-fab {
        width: 48px; height: 48px;
        background: linear-gradient(135deg, var(--khaki-primary), var(--khaki-dark));
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.35rem; margin-top: -20px;
        box-shadow: 0 4px 14px rgba(138,108,66,0.4); border: 3px solid #fff;
    }

    /* ── Virtual Navigation Bar ── */
    .phone-nav-bar {
        flex-shrink: 0; width: 100%; height: 42px; min-height: 42px;
        background: #17130e; display: flex; align-items: center;
        justify-content: space-around; z-index: 100;
        border-top: 1px solid rgba(255,255,255,0.08); position: relative;
    }
    @media (min-width: 481px) {
        .phone-nav-bar { border-bottom-left-radius: 35px; border-bottom-right-radius: 35px; }
    }
    .pnb-key {
        background: none; border: none; color: #d1c7bc;
        padding: 8px 24px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        border-radius: 8px; transition: background 0.15s, color 0.15s;
        text-decoration: none;
    }
    .pnb-key:hover, .pnb-key:active { background: rgba(255,255,255,0.15); color: #fff; }
    .pnb-home-circle { width: 13px; height: 13px; border-radius: 50%; border: 2px solid currentColor; }
    .pnb-recent-square { width: 12px; height: 12px; border-radius: 2.5px; border: 2px solid currentColor; }
</style>

<div class="mob-shell">

    {{-- Status Bar --}}
    <div class="phone-status-bar">
        <div class="psb-clock" id="phone-clock">{{ now()->format('H:i') }}</div>
        <div class="psb-notch">
            <span class="psb-speaker"></span>
            <span class="psb-camera-dot"></span>
        </div>
        <div class="psb-indicators">
            <span style="display:inline-flex;align-items:center;gap:2px;" title="Sinyal 5G">
                <svg width="15" height="11" viewBox="0 0 15 11" fill="currentColor">
                    <rect x="0" y="8" width="2" height="3" rx="0.5"/>
                    <rect x="3.2" y="6" width="2" height="5" rx="0.5"/>
                    <rect x="6.4" y="4" width="2" height="7" rx="0.5"/>
                    <rect x="9.6" y="2" width="2" height="9" rx="0.5"/>
                    <rect x="12.8" y="0" width="2" height="11" rx="0.5"/>
                </svg>
                <span class="psb-net-type">5G</span>
            </span>
            <span style="display:inline-flex;align-items:center;" title="Wi-Fi">
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

    {{-- Page Header (Fixed, Tidak Scroll) --}}
    <div class="page-header">
        <div class="ph-topbar">
            <a href="{{ route('masyarakat.dashboard') }}" class="ph-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Beranda
            </a>
            <a href="{{ route('masyarakat.permohonan.create') }}" style="background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.3);color:#fff;font-size:0.72rem;font-weight:700;padding:5px 12px;border-radius:999px;text-decoration:none;">
                + Ajukan
            </a>
        </div>

        <div class="ph-title">📋 Riwayat Permohonan</div>
        <div class="ph-subtitle">{{ auth()->user()->name }} · Total {{ $stats['total'] }} permohonan</div>

        {{-- Filter Status Chips --}}
        <div class="filter-strip">
            @php $currentStatus = request('status'); $currentLayanan = request('layanan'); @endphp
            <a href="{{ route('masyarakat.riwayat', array_filter(['layanan' => $currentLayanan, 'q' => request('q')])) }}"
               class="filter-chip {{ !$currentStatus ? 'active' : '' }}">
                Semua ({{ $stats['total'] }})
            </a>
            <a href="{{ route('masyarakat.riwayat', array_filter(['status' => 'Baru', 'layanan' => $currentLayanan, 'q' => request('q')])) }}"
               class="filter-chip {{ $currentStatus === 'Baru' ? 'active' : '' }}">
                🔵 Diterima ({{ $stats['baru'] }})
            </a>
            <a href="{{ route('masyarakat.riwayat', array_filter(['status' => 'Proses', 'layanan' => $currentLayanan, 'q' => request('q')])) }}"
               class="filter-chip {{ $currentStatus === 'Proses' ? 'active' : '' }}">
                🟡 Proses ({{ $stats['proses'] }})
            </a>
            <a href="{{ route('masyarakat.riwayat', array_filter(['status' => 'Selesai', 'layanan' => $currentLayanan, 'q' => request('q')])) }}"
               class="filter-chip {{ $currentStatus === 'Selesai' ? 'active' : '' }}">
                🟢 Selesai ({{ $stats['selesai'] }})
            </a>
            <a href="{{ route('masyarakat.riwayat', array_filter(['status' => 'Ditolak', 'layanan' => $currentLayanan, 'q' => request('q')])) }}"
               class="filter-chip {{ $currentStatus === 'Ditolak' ? 'active' : '' }}">
                🔴 Ditolak ({{ $stats['ditolak'] }})
            </a>
        </div>
    </div>

    {{-- Scrollable Content --}}
    <div class="mob-scroll-area">

        {{-- Search Bar --}}
        <div class="search-wrap">
            <form method="GET" action="{{ route('masyarakat.riwayat') }}" class="search-inner">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('layanan'))
                    <input type="hidden" name="layanan" value="{{ request('layanan') }}">
                @endif
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="q" placeholder="Cari NIK, nama, atau jenis layanan..." value="{{ request('q') }}">
                <button type="submit" class="search-btn">Cari</button>
            </form>
        </div>

        {{-- Filter Layanan ── dropdown mini --}}
        <div style="padding: 0 14px 8px; display:flex; align-items:center; gap:6px;">
            <span style="font-size:0.72rem;color:#78716c;font-weight:700;">Layanan:</span>
            <div style="display:flex;gap:4px;overflow-x:auto;scrollbar-width:none;">
                @foreach(['KTP','PRR','KK','Akta Lahir','Akta Kematian','Kedatangan','Pindah'] as $jl)
                    <a href="{{ route('masyarakat.riwayat', array_filter(['status' => $currentStatus, 'layanan' => $jl !== ($currentLayanan ?? '') ? $jl : null, 'q' => request('q')])) }}"
                       style="flex-shrink:0;font-size:0.65rem;font-weight:700;padding:3px 8px;border-radius:6px;text-decoration:none;white-space:nowrap;
                              {{ $currentLayanan === $jl ? 'background:var(--khaki-primary);color:#fff;border:1px solid var(--khaki-primary);' : 'background:#fff;color:#78716c;border:1px solid var(--khaki-border);' }}">
                        {{ $jl }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Results Count --}}
        <div style="padding: 0 16px 6px; font-size:0.72rem; color:#78716c; font-weight:600;">
            Menampilkan <strong style="color:#292524;">{{ $pendaftarans->count() }}</strong> permohonan
            @if($currentStatus) · <span style="color:var(--khaki-primary);">{{ $currentStatus }}</span> @endif
            @if($currentLayanan) · <span style="color:var(--khaki-primary);">{{ $currentLayanan }}</span> @endif
        </div>

        {{-- List Riwayat --}}
        <div class="list-container">

            @forelse($pendaftarans as $item)
                @php
                    $statusClass = match(strtolower($item->status ?? '')) {
                        'baru'    => 'baru',
                        'proses'  => 'proses',
                        'selesai' => 'selesai',
                        'ditolak' => 'ditolak',
                        default   => '',
                    };
                @endphp

                <div class="riwayat-card {{ $statusClass }}">

                    <div class="rc-top">
                        <div class="rc-service">{{ $item->jenis_layanan }}</div>
                        <span class="rc-badge {{ $statusClass }}">
                            {{ $item->status ?? 'Baru' }}
                        </span>
                    </div>

                    <div class="rc-meta">
                        <span>{{ $item->nama_lengkap ?: ($item->nama_kedatangan ?: ($item->nama_pindah ?: '—')) }}</span>
                        <span class="rc-meta-sep">·</span>
                        <span>{{ $item->tanggal_daftar ? $item->tanggal_daftar->format('d M Y') : '—' }}</span>
                        @if($item->desa)
                            <span class="rc-meta-sep">·</span>
                            <span>{{ $item->desa }}</span>
                        @endif
                    </div>

                    {{-- Catatan / Alasan --}}
                    @if($item->status === 'Ditolak' && !empty($item->alasan_penolakan))
                        <div class="rc-note reject">
                            <strong>Alasan Ditolak:</strong> {{ $item->alasan_penolakan }}
                        </div>
                    @endif
                    @if(!empty($item->catatan_petugas))
                        <div class="rc-note info">
                            <strong>Catatan:</strong> {{ $item->catatan_petugas }}
                        </div>
                    @endif

                    <div class="rc-footer">
                        <span class="rc-nik">NIK: {{ $item->nik ?: ($item->nik_kedatangan ?: ($item->nik_pindah ?: '—')) }}</span>
                        <a href="{{ route('masyarakat.permohonan.success', $item) }}" class="rc-action">
                            Bukti →
                        </a>
                    </div>
                </div>

            @empty
                <div class="empty-state">
                    <div class="es-icon">📋</div>
                    <div class="es-title">
                        @if($currentStatus || $currentLayanan || request('q'))
                            Tidak Ada Hasil
                        @else
                            Belum Ada Permohonan
                        @endif
                    </div>
                    <p class="es-desc">
                        @if($currentStatus || $currentLayanan || request('q'))
                            Tidak ditemukan permohonan yang sesuai filter. Coba ubah kriteria pencarian.
                        @else
                            Anda belum pernah mengajukan permohonan layanan kependudukan.
                        @endif
                    </p>
                    @if($currentStatus || $currentLayanan || request('q'))
                        <a href="{{ route('masyarakat.riwayat') }}" class="btn-ajukan" style="background:var(--khaki-soft);color:var(--khaki-darker);border:1px solid var(--khaki-border);">
                            Reset Filter
                        </a>
                    @else
                        <a href="{{ route('masyarakat.permohonan.create') }}" class="btn-ajukan">
                            + Ajukan Sekarang
                        </a>
                    @endif
                </div>
            @endforelse

        </div>
    </div>{{-- /.mob-scroll-area --}}

    {{-- Bottom Navigation Bar --}}
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
        <a class="mbn-link active" href="{{ route('masyarakat.riwayat') }}">
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

    {{-- Virtual Android Navigation Bar --}}
    <div class="phone-nav-bar">
        <button type="button" class="pnb-key" onclick="if(window.history.length > 1){ window.history.back(); } else { window.location.href='{{ route('masyarakat.dashboard') }}'; }" title="Kembali" aria-label="Kembali">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <a href="{{ route('masyarakat.dashboard') }}" class="pnb-key" title="Beranda" aria-label="Beranda">
            <span class="pnb-home-circle"></span>
        </a>
        <button type="button" class="pnb-key" onclick="alert('PATEN SPACE Mobile')" title="Recent Apps" aria-label="Aplikasi Terakhir">
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

