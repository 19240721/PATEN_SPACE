@extends('layouts.app')

@section('title', 'Permohonan Terkirim - PATEN SPACE')

@section('content')
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f0f4f8; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }

    .success-shell {
        max-width: 480px; margin: 0 auto;
        min-height: 100dvh; display: flex; flex-direction: column;
        background: #f0f4f8;
    }

    /* Header */
    .success-header {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        padding: 48px 20px 80px;
        color: #fff; text-align: center;
        position: relative; overflow: hidden;
    }
    .success-header::before {
        content: ''; position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.08);
    }
    .success-header::after {
        content: ''; position: absolute;
        bottom: -60px; left: -30px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(255,255,255,.06);
    }
    .success-checkmark {
        width: 80px; height: 80px;
        background: rgba(255,255,255,.25);
        border: 3px solid rgba(255,255,255,.5);
        border-radius: 50%;
        display: grid; place-items: center;
        font-size: 2.2rem;
        margin: 0 auto 16px;
        position: relative; z-index: 1;
        animation: popIn .4s cubic-bezier(.36,.07,.19,.97) both;
    }
    @keyframes popIn {
        0%   { transform: scale(0.3); opacity: 0; }
        70%  { transform: scale(1.1); }
        100% { transform: scale(1);   opacity: 1; }
    }
    .success-title { font-size: 1.4rem; font-weight: 800; margin-bottom: 8px; position: relative; z-index: 1; }
    .success-sub   { font-size: .83rem; opacity: .85; line-height: 1.5; position: relative; z-index: 1; }

    /* Detail card */
    .success-card {
        background: #fff; border-radius: 20px 20px 0 0;
        margin-top: -32px;
        padding: 24px 20px;
        box-shadow: 0 -4px 20px rgba(0,0,0,.06);
        flex: 1;
    }
    .success-card-title {
        font-size: .75rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .5px; color: #6b7280; margin-bottom: 14px;
    }
    .success-detail-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 0; border-bottom: 1px solid #f3f4f6;
        font-size: .85rem;
    }
    .success-detail-row:last-child { border-bottom: none; }
    .success-detail-key { color: #6b7280; }
    .success-detail-val { font-weight: 700; color: #111827; text-align: right; }
    .success-status-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 12px; border-radius: 999px;
        background: #dbeafe; color: #1d4ed8;
        font-size: .75rem; font-weight: 700;
    }

    /* Info box */
    .success-info {
        background: #fefce8; border: 1px solid #fde68a;
        border-radius: 12px; padding: 14px 16px;
        margin: 16px 0; font-size: .8rem; color: #92400e; line-height: 1.5;
    }
    .success-info strong { display: block; margin-bottom: 4px; }

    /* Nomor permohonan besar */
    .success-number {
        text-align: center; padding: 20px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-radius: 14px; margin-bottom: 16px;
    }
    .success-number-label { font-size: .72rem; color: #3b82f6; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
    .success-number-val   { font-size: 2rem; font-weight: 900; color: #1d4ed8; }

    /* CTA buttons */
    .success-cta { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
    .btn-primary-mob {
        width: 100%; padding: 16px; text-align: center;
        background: linear-gradient(135deg, #1a56db, #2563eb);
        color: #fff; border-radius: 14px; text-decoration: none;
        font-weight: 700; font-size: .95rem;
        box-shadow: 0 4px 14px rgba(26,86,219,.3);
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-secondary-mob {
        width: 100%; padding: 14px; text-align: center;
        background: #f3f4f6; color: #374151;
        border-radius: 14px; text-decoration: none;
        font-weight: 600; font-size: .9rem;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }

    @media (min-width: 481px) {
        .success-shell { box-shadow: 0 0 0 1px #e5e7eb, 0 20px 60px rgba(0,0,0,.12); }
        body { padding: 20px 0; }
    }
</style>

<div class="success-shell">
    <div class="success-header">
        <div class="success-checkmark">✅</div>
        <div class="success-title">Permohonan Terkirim!</div>
        <div class="success-sub">Permohonan Anda telah masuk ke sistem<br>dan menunggu diproses oleh petugas.</div>
    </div>

    <div class="success-card">
        {{-- Nomor permohonan --}}
        <div class="success-number">
            <div class="success-number-label">Nomor Permohonan</div>
            <div class="success-number-val">#{{ str_pad($pendaftaran->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="success-card-title">Detail Permohonan</div>

        <div class="success-detail-row">
            <span class="success-detail-key">Jenis Layanan</span>
            <span class="success-detail-val">{{ $pendaftaran->jenis_layanan }}</span>
        </div>
        <div class="success-detail-row">
            <span class="success-detail-key">Nama Pemohon</span>
            <span class="success-detail-val">{{ $pendaftaran->nama_lengkap }}</span>
        </div>
        <div class="success-detail-row">
            <span class="success-detail-key">Tanggal Pengajuan</span>
            <span class="success-detail-val">{{ $pendaftaran->tanggal_daftar?->format('d F Y') ?? '-' }}</span>
        </div>
        <div class="success-detail-row">
            <span class="success-detail-key">Jumlah Dokumen</span>
            <span class="success-detail-val">{{ count($pendaftaran->documents ?? []) }} file</span>
        </div>
        <div class="success-detail-row">
            <span class="success-detail-key">Status</span>
            <span class="success-detail-val">
                <span class="success-status-pill">🔵 {{ $pendaftaran->status }}</span>
            </span>
        </div>

        <div class="success-info">
            <strong>ℹ️ Cara Tracking Status</strong>
            Pantau perkembangan permohonan Anda secara real-time melalui menu <strong>Beranda</strong>.
            Petugas akan menghubungi Anda jika ada kekurangan dokumen.
        </div>

        <div class="success-cta">
            <a href="{{ route('masyarakat.dashboard') }}" class="btn-primary-mob">
                🏠 Lihat Status Permohonan
            </a>
            <a href="{{ route('masyarakat.permohonan.create') }}" class="btn-secondary-mob">
                📝 Ajukan Permohonan Lain
            </a>
        </div>
    </div>
</div>
@endsection
