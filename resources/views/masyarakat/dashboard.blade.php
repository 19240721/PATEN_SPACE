@extends('layouts.app')

@section('title', 'Dashboard - PATEN SPACE')

@section('content')
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f0f4f8; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }

    /* ── Mobile shell ── */
    .masy-shell {
        max-width: 480px;
        margin: 0 auto;
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        background: #f0f4f8;
        position: relative;
    }

    /* ── Top header ── */
    .masy-header {
        background: linear-gradient(135deg, #1a56db 0%, #0e9f6e 100%);
        padding: 20px 20px 60px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .masy-header::before {
        content: '';
        position: absolute; top: -40px; right: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.08);
    }
    .masy-header::after {
        content: '';
        position: absolute; bottom: -20px; left: -20px;
        width: 140px; height: 140px; border-radius: 50%;
        background: rgba(255,255,255,.06);
    }
    .masy-header-top {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 18px; position: relative; z-index: 1;
    }
    .masy-logo { display: flex; align-items: center; gap: 10px; }
    .masy-logo-mark {
        width: 36px; height: 36px;
        background: rgba(255,255,255,.2); border-radius: 10px;
        display: grid; place-items: center;
        font-weight: 900; font-size: 1rem;
        border: 1.5px solid rgba(255,255,255,.3);
    }
    .masy-logo-text { font-size: .75rem; font-weight: 700; opacity: .9; line-height: 1.3; }
    .masy-logo-sub  { font-size: .65rem; opacity: .75; }
    .masy-avatar {
        width: 38px; height: 38px;
        background: rgba(255,255,255,.25);
        border: 2px solid rgba(255,255,255,.5);
        border-radius: 50%;
        display: grid; place-items: center;
        font-size: 1.1rem;
    }
    .masy-greeting { position: relative; z-index: 1; }
    .masy-greeting-sub  { font-size: .78rem; opacity: .8; margin-bottom: 4px; }
    .masy-greeting-name { font-size: 1.4rem; font-weight: 700; }
    .masy-greeting-date { font-size: .72rem; opacity: .75; margin-top: 3px; }

    /* ── Stat cards floating ── */
    .masy-stats-wrap {
        padding: 0 16px;
        margin-top: -38px;
        position: relative; z-index: 2;
        margin-bottom: 20px;
    }
    .masy-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .masy-stat {
        background: #fff; border-radius: 14px; padding: 14px 8px;
        text-align: center; box-shadow: 0 4px 16px rgba(0,0,0,.08);
    }
    .masy-stat-num { font-size: 1.5rem; font-weight: 800; line-height: 1; }
    .masy-stat-lbl { font-size: .6rem; color: #6b7280; margin-top: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: .3px; }
    .masy-stat-num.blue   { color: #1a56db; }
    .masy-stat-num.yellow { color: #d97706; }
    .masy-stat-num.green  { color: #059669; }
    .masy-stat-num.red    { color: #dc2626; }

    /* ── Section title ── */
    .masy-section-title {
        padding: 0 16px; font-size: .8rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px;
        color: #6b7280; margin-bottom: 10px;
        display: flex; align-items: center; justify-content: space-between;
    }

    /* ── Quick actions ── */
    .masy-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 0 16px; margin-bottom: 24px; }
    .masy-action {
        background: #fff; border-radius: 16px; padding: 18px 14px;
        text-decoration: none; color: inherit;
        box-shadow: 0 2px 10px rgba(0,0,0,.06);
        display: flex; flex-direction: column; gap: 8px;
        transition: transform .15s ease;
    }
    .masy-action:active { transform: scale(.97); }
    .masy-action.primary { background: linear-gradient(135deg, #1a56db, #2563eb); color: #fff; }
    .masy-action.camera  { background: linear-gradient(135deg, #0e9f6e, #059669); color: #fff; }
    .masy-action-icon  { font-size: 1.8rem; }
    .masy-action-label { font-weight: 700; font-size: .88rem; }
    .masy-action-desc  { font-size: .73rem; opacity: .75; line-height: 1.3; }

    /* ── Permohonan cards ── */
    .masy-cards { padding: 0 16px; display: flex; flex-direction: column; gap: 12px; margin-bottom: 100px; }
    .masy-card {
        background: #fff; border-radius: 16px; padding: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,.06);
        border-left: 4px solid #e5e7eb;
    }
    .masy-card.baru    { border-left-color: #3b82f6; }
    .masy-card.proses  { border-left-color: #f59e0b; }
    .masy-card.selesai { border-left-color: #10b981; }
    .masy-card.ditolak { border-left-color: #ef4444; }
    .masy-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
    .masy-card-service { font-weight: 700; font-size: .92rem; color: #111827; }
    .masy-card-id { font-size: .7rem; color: #9ca3af; }
    .masy-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 999px;
        font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .3px;
        white-space: nowrap;
    }
    .masy-badge.baru    { background: #dbeafe; color: #1d4ed8; }
    .masy-badge.proses  { background: #fef3c7; color: #92400e; }
    .masy-badge.selesai { background: #d1fae5; color: #065f46; }
    .masy-badge.ditolak { background: #fee2e2; color: #991b1b; }
    .masy-card-meta { font-size: .75rem; color: #6b7280; margin-bottom: 8px; }
    .masy-card-source { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: .65rem; font-weight: 600; margin-left: 6px; }
    .masy-card-source.mandiri { background: #e0f2fe; color: #0369a1; }
    .masy-card-source.petugas-src { background: #f3e8ff; color: #6b21a8; }
    .masy-card-note {
        margin-top: 8px; padding: 8px 10px;
        border-radius: 8px; font-size: .73rem; line-height: 1.5;
    }
    .masy-card-note.tolak   { background: #fef2f2; color: #991b1b; border-left: 3px solid #ef4444; }
    .masy-card-note.catatan { background: #eff6ff; color: #1e40af; border-left: 3px solid #3b82f6; }
    .masy-card-docs { margin-top: 10px; padding-top: 10px; border-top: 1px solid #f3f4f6; }
    .masy-card-docs a {
        display: inline-flex; align-items: center; gap: 5px;
        color: #2563eb; font-size: .73rem; text-decoration: none;
        background: #eff6ff; padding: 4px 10px; border-radius: 6px; margin: 2px 2px 2px 0;
    }
    .masy-empty {
        text-align: center; padding: 40px 20px;
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }
    .masy-empty-icon { font-size: 2.5rem; margin-bottom: 10px; }
    .masy-empty-text { color: #6b7280; font-size: .88rem; }
    .masy-empty-action {
        display: inline-block; margin-top: 14px;
        background: #1a56db; color: #fff;
        padding: 10px 20px; border-radius: 10px;
        text-decoration: none; font-weight: 600; font-size: .85rem;
    }

    /* ── Bottom nav ── */
    .masy-bottom-nav {
        position: fixed; bottom: 0; left: 50%;
        transform: translateX(-50%);
        width: 100%; max-width: 480px;
        background: #fff; border-top: 1px solid #e5e7eb;
        display: flex; box-shadow: 0 -4px 20px rgba(0,0,0,.08);
        z-index: 100;
        padding-bottom: env(safe-area-inset-bottom, 0);
    }
    .masy-nav-item {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; gap: 3px;
        padding: 10px 4px 8px;
        text-decoration: none; color: #9ca3af;
        font-size: .6rem; font-weight: 600;
        transition: color .15s ease;
    }
    .masy-nav-item.active { color: #1a56db; }
    .masy-nav-item-icon { font-size: 1.3rem; line-height: 1; }
    .masy-nav-center {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        text-decoration: none;
    }
    .masy-nav-fab {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, #1a56db, #2563eb);
        border-radius: 50%;
        display: grid; place-items: center;
        font-size: 1.4rem;
        box-shadow: 0 4px 14px rgba(26,86,219,.4);
        margin-top: -20px;
        border: 3px solid #fff;
    }

    /* ── Toast ── */
    .masy-toast {
        margin: 0 16px 12px; padding: 12px 14px;
        background: #d1fae5; border: 1px solid #6ee7b7;
        color: #065f46; border-radius: 12px;
        font-size: .83rem; font-weight: 600;
    }

    @media (min-width: 481px) {
        .masy-shell { box-shadow: 0 0 0 1px #e5e7eb, 0 20px 60px rgba(0,0,0,.12); }
        body { padding: 20px 0; }
    }
</style>

<div class="masy-shell">
    {{-- Header --}}
    <div class="masy-header">
        <div class="masy-header-top">
            <div class="masy-logo">
                <div class="masy-logo-mark">P</div>
                <div>
                    <div class="masy-logo-text">PATEN SPACE</div>
                    <div class="masy-logo-sub">Kecamatan Jatisari</div>
                </div>
            </div>
            <div class="masy-avatar">👤</div>
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
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="masy-toast">✅ {{ session('success') }}</div>
    @endif

    {{-- Quick actions --}}
    <div class="masy-section-title">Layanan Cepat</div>
    <div class="masy-actions">
        <a href="{{ route('masyarakat.permohonan.create') }}" class="masy-action primary">
            <div class="masy-action-icon">📝</div>
            <div class="masy-action-label">Ajukan Permohonan</div>
            <div class="masy-action-desc">Isi formulir layanan online</div>
        </a>
        <a href="{{ route('masyarakat.permohonan.create') }}#dokumen" class="masy-action camera">
            <div class="masy-action-icon">📷</div>
            <div class="masy-action-label">Scan Dokumen</div>
            <div class="masy-action-desc">Foto & upload persyaratan</div>
        </a>
    </div>

    {{-- Riwayat --}}
    <div class="masy-section-title">
        Riwayat Permohonan
        <span style="background:#e5e7eb;color:#374151;padding:2px 8px;border-radius:999px;font-size:.65rem;font-weight:700;">{{ $pendaftarans->count() }}</span>
    </div>

    <div class="masy-cards">
        @forelse ($pendaftarans as $pendaftaran)
            @php $statusLower = strtolower($pendaftaran->status ?? 'baru'); @endphp
            <div class="masy-card {{ $statusLower }}">
                <div class="masy-card-top">
                    <div>
                        <div class="masy-card-service">{{ $pendaftaran->jenis_layanan }}</div>
                        <div class="masy-card-id">
                            #{{ $pendaftaran->id }}
                            @if($pendaftaran->intake_source === 'masyarakat')
                                <span class="masy-card-source mandiri">Mandiri</span>
                            @else
                                <span class="masy-card-source petugas-src">Via Petugas</span>
                            @endif
                        </div>
                    </div>
                    <span class="masy-badge {{ $statusLower }}">
                        @if($statusLower==='baru') 🔵 @elseif($statusLower==='proses') 🟡 @elseif($statusLower==='selesai') ✅ @else ❌ @endif
                        {{ $pendaftaran->status ?? 'Baru' }}
                    </span>
                </div>
                <div class="masy-card-meta">
                    📅 {{ $pendaftaran->tanggal_daftar?->format('d/m/Y') ?? '-' }}
                    &nbsp;·&nbsp; {{ $pendaftaran->nama_lengkap }}
                </div>

                @if($pendaftaran->status === 'Ditolak' && !empty($pendaftaran->alasan_penolakan))
                    <div class="masy-card-note tolak">
                        <strong>Alasan Penolakan:</strong> {{ $pendaftaran->alasan_penolakan }}
                    </div>
                @endif
                @if(!empty($pendaftaran->catatan_petugas))
                    <div class="masy-card-note catatan">
                        <strong>Catatan Petugas:</strong> {{ $pendaftaran->catatan_petugas }}
                    </div>
                @endif

                @if(!empty($pendaftaran->documents))
                    <div class="masy-card-docs">
                        @foreach($pendaftaran->documents as $index => $document)
                            <a href="{{ route('masyarakat.dokumen', [$pendaftaran, $index]) }}">
                                📎 {{ $document['original_name'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="masy-empty">
                <div class="masy-empty-icon">📋</div>
                <div class="masy-empty-text">Belum ada permohonan yang diajukan.</div>
                <a href="{{ route('masyarakat.permohonan.create') }}" class="masy-empty-action">+ Ajukan Permohonan</a>
            </div>
        @endforelse
    </div>

    {{-- Bottom navigation --}}
    <nav class="masy-bottom-nav">
        <a class="masy-nav-item active" href="{{ route('masyarakat.dashboard') }}">
            <span class="masy-nav-item-icon">🏠</span>
            Beranda
        </a>
        <a class="masy-nav-item" href="{{ route('masyarakat.permohonan.create') }}">
            <span class="masy-nav-item-icon">📝</span>
            Ajukan
        </a>
        <a class="masy-nav-center" href="{{ route('masyarakat.permohonan.create') }}#dokumen">
            <div class="masy-nav-fab" title="Scan Dokumen">📷</div>
        </a>
        <a class="masy-nav-item" href="{{ route('masyarakat.dashboard') }}">
            <span class="masy-nav-item-icon">📋</span>
            Status
        </a>
        <form method="POST" action="{{ route('logout') }}" style="flex:1;display:flex;">
            @csrf
            <button type="submit" class="masy-nav-item" style="background:none;border:none;cursor:pointer;width:100%;font-family:inherit;">
                <span class="masy-nav-item-icon">🚪</span>
                Keluar
            </button>
        </form>
    </nav>
</div>
@endsection
