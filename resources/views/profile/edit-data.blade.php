@extends('layouts.app')

@section('title', 'Ubah Data Profil - PATEN SPACE')

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
    .mob-card-title {
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

    /* ── Form Inputs Mobile ── */
    .mob-input-group {
        margin-bottom: 14px;
    }
    .mob-input-label {
        display: block;
        font-size: 0.76rem;
        font-weight: 700;
        color: #44403c;
        margin-bottom: 5px;
    }
    .mob-input-text {
        width: 100%;
        padding: 10px 12px;
        border: 1.5px solid var(--khaki-border);
        border-radius: 12px;
        font-size: 0.84rem;
        font-family: inherit;
        background: #faf8f5;
        color: #1c1917;
        box-sizing: border-box;
        transition: border-color 0.15s;
    }
    .mob-input-text:focus {
        outline: none;
        border-color: var(--khaki-primary);
        background: #ffffff;
    }
    .mob-input-help {
        font-size: 0.7rem;
        color: #78716c;
        margin-top: 4px;
    }
    .mob-field-err {
        color: #dc2626;
        font-size: 0.72rem;
        margin-top: 4px;
        font-weight: 600;
    }

    /* ── Photo Upload Box ── */
    .mob-photo-box {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #faf8f5;
        border: 1px solid var(--khaki-border);
        border-radius: 14px;
        padding: 12px;
        margin-bottom: 14px;
    }
    .mob-avatar-preview {
        width: 60px;
        height: 60px;
        min-width: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--khaki-primary), var(--khaki-darker));
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        font-weight: 800;
        border: 2px solid #eee8d7;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .mob-avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* ── Readonly Tag ── */
    .mob-ro-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 14px;
    }
    .mob-ro-item {
        background: #f5f2ea;
        border: 1px solid var(--khaki-border);
        border-radius: 10px;
        padding: 8px 10px;
    }
    .mob-ro-label {
        font-size: 0.65rem;
        color: #78716c;
        font-weight: 600;
        display: block;
    }
    .mob-ro-val {
        font-size: 0.78rem;
        font-weight: 750;
        color: #292524;
        margin-top: 2px;
        word-break: break-word;
    }

    /* ── Actions ── */
    .mob-submit-btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--khaki-primary), var(--khaki-darker));
        color: #ffffff;
        padding: 12px;
        border-radius: 14px;
        font-size: 0.84rem;
        font-weight: 750;
        border: none;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(92, 71, 40, 0.25);
        transition: transform 0.1s;
        font-family: inherit;
    }
    .mob-submit-btn:active {
        transform: translateY(1px);
    }
    .mob-cancel-btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #ffffff;
        border: 1.5px solid var(--khaki-border);
        color: #78716c;
        padding: 10px;
        border-radius: 14px;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        text-align: center;
        margin-top: 8px;
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
                <h1>Ubah Data Profil</h1>
                <p>Kecamatan Jatisari</p>
            </div>
        </div>
    </header>

    {{-- Scrollable Form --}}
    <main class="mob-prof-scroll">
        <form action="{{ route('account.profile.data.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- 1. FOTO PROFIL --}}
            <div class="mob-card">
                <div class="mob-card-title">
                    <span>📷</span>
                    <span>Foto Profil Akun</span>
                </div>
                <div class="mob-photo-box">
                    <div class="mob-avatar-preview">
                        @if ($user->foto_profil_url)
                            <img id="mob-preview-avatar" src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}">
                        @else
                            <span id="mob-preview-placeholder">{{ $user->inisial }}</span>
                            <img id="mob-preview-avatar" src="" alt="Preview Foto" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <input type="file" name="foto_profil" id="mob_foto_profil" accept="image/png, image/jpeg, image/jpg" onchange="previewMobPhoto(event)" style="font-size: 0.74rem; width: 100%;">
                        @error('foto_profil')
                            <div class="mob-field-err">{{ $message }}</div>
                        @enderror
                        <div class="mob-input-help">JPG, JPEG, PNG (Maks 2 MB).</div>
                    </div>
                </div>
            </div>

            {{-- 2. IDENTITAS RESMI (READONLY) --}}
            <div class="mob-card" style="margin-top: 12px;">
                <div class="mob-card-title">
                    <span>🔒</span>
                    <span>Identitas Resmi Sistem</span>
                </div>
                <div class="mob-ro-grid">
                    <div class="mob-ro-item">
                        <span class="mob-ro-label">Nama Lengkap</span>
                        <span class="mob-ro-val">{{ $user->name }}</span>
                    </div>
                    <div class="mob-ro-item">
                        <span class="mob-ro-label">NIK Kependudukan</span>
                        <span class="mob-ro-val">{{ $user->nik ?: '—' }}</span>
                    </div>
                    <div class="mob-ro-item">
                        <span class="mob-ro-label">Peran</span>
                        <span class="mob-ro-val">Masyarakat</span>
                    </div>
                    <div class="mob-ro-item">
                        <span class="mob-ro-label">Wilayah</span>
                        <span class="mob-ro-val">Kecamatan Jatisari</span>
                    </div>
                </div>
            </div>

            {{-- 3. KONTAK BISA DIUBAH --}}
            <div class="mob-card" style="margin-top: 12px;">
                <div class="mob-card-title">
                    <span>✉️</span>
                    <span>Kontak yang Dapat Diperbarui</span>
                </div>

                <div class="mob-input-group">
                    <label for="mob_email" class="mob-input-label">Alamat Email <span style="color:#dc2626;">*</span></label>
                    <input id="mob_email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="mob-input-text">
                    @error('email')<div class="mob-field-err">{{ $message }}</div>@enderror
                </div>

                <div class="mob-input-group" style="margin-bottom: 0;">
                    <label for="mob_phone" class="mob-input-label">Nomor WhatsApp / HP <span style="color:#dc2626;">*</span></label>
                    <input id="mob_phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" required class="mob-input-text">
                    @error('phone')<div class="mob-field-err">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="margin-top: 14px; margin-bottom: 8px;">
                <button type="submit" class="mob-submit-btn">
                    <span>💾</span>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="{{ route('profil') }}" class="mob-cancel-btn">Batal</a>
            </div>
        </form>
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
function previewMobPhoto(event) {
    const input = event.target;
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran foto terlalu besar! Maksimal 2 MB.');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('mob-preview-avatar');
        const placeholder = document.getElementById('mob-preview-placeholder');
        if (preview) { preview.src = e.target.result; preview.style.display = 'block'; }
        if (placeholder) { placeholder.style.display = 'none'; }
    };
    reader.readAsDataURL(file);
}
</script>

@else
{{-- ========================================================
     TAMPILAN STANDAR DESKTOP (PETUGAS / OPERATOR / CAMAT)
     ======================================================== --}}
<div class="app-shell dashboard-page account-profile-page">
    <main class="main-panel" style="width: 100%; max-width: 860px; margin: 0 auto; padding: 25px 20px;">
        <div class="dashboard-content">
            <header class="profile-page-heading" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 22px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <span class="profile-heading-avatar" aria-hidden="true">✉</span>
                    <div>
                        <strong style="font-size: 1.25rem; color: #1e3a5f; display: block;">Ubah Data Profil</strong>
                        <small style="color: #64748b; font-size: 0.84rem;">Perbarui alamat email, nomor telepon, dan foto profil akun Anda</small>
                    </div>
                </div>
                <a href="{{ route('account.profile') }}" class="profile-dashboard-link" style="padding: 9px 15px; font-size: 0.82rem;">
                    Kembali ke Pengaturan Akun
                </a>
            </header>

            <form action="{{ route('account.profile.data.update') }}" method="POST" enctype="multipart/form-data" class="dashboard-panel account-profile-form" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                @csrf
                @method('PUT')

                {{-- 1. UNGGAH FOTO PROFIL --}}
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; margin-bottom: 24px; display: flex; align-items: center; gap: 18px; flex-wrap: wrap;">
                    <div>
                        @if ($user->foto_profil_url)
                            <img id="preview-avatar" src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" style="width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 3px solid #eee8d7; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                        @else
                            <div id="preview-placeholder" style="width: 76px; height: 76px; border-radius: 50%; background: linear-gradient(135deg, #796743, #5c4728); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 700; border: 3px solid #eee8d7; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                {{ $user->inisial }}
                            </div>
                            <img id="preview-avatar" src="" alt="Preview Foto" style="display: none; width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 3px solid #eee8d7; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                        @endif
                    </div>

                    <div style="flex: 1; min-width: 250px;">
                        <label for="foto_profil" style="display: block; font-size: 0.84rem; font-weight: 700; color: #1e3a5f; margin-bottom: 5px;">
                            Foto Profil Akun
                        </label>
                        <input type="file" name="foto_profil" id="foto_profil" accept="image/png, image/jpeg, image/jpg" onchange="previewNewPhoto(event)" style="font-size: 0.8rem; color: #475569;">
                        @error('foto_profil')
                            <div style="color: #dc2626; font-size: 0.74rem; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                        <div style="font-size: 0.72rem; color: #64748b; margin-top: 4px;">
                            Format gambar yang didukung: <strong>JPG, JPEG, PNG</strong>. Ukuran file maksimal: <strong>2 MB</strong>.
                        </div>
                    </div>
                </div>

                {{-- 2. DATA IDENTITAS RESMI (READ-ONLY) --}}
                <div style="background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px 18px; margin-bottom: 24px;">
                    <div style="font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                        <span>🔒</span> Data Identitas Resmi Sistem (Tidak Dapat Diubah Mandiri)
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                        <div>
                            <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: 600;">Nama Lengkap:</span>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #334155; margin-top: 2px;">{{ $user->name }}</div>
                            <small style="font-size: 0.68rem; color: #94a3b8;">Ditetapkan oleh sistem resmi</small>
                        </div>

                        <div>
                            <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: 600;">
                                @if ($user->role === 'camat')
                                    NIP:
                                @elseif ($user->role === 'masyarakat')
                                    NIK Kependudukan:
                                @else
                                    NIK / Identitas Pegawai:
                                @endif
                            </span>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #334155; margin-top: 2px;">
                                @if ($user->role === 'camat')
                                    {{ $user->nip ?: '—' }}
                                @else
                                    {{ $user->nik ?: ($user->nip ?: '—') }}
                                @endif
                            </div>
                            <small style="font-size: 0.68rem; color: #94a3b8;">
                                {{ $user->role === 'camat' ? 'Nomor Induk Pegawai resmi' : 'Nomor identitas terdaftar' }}
                            </small>
                        </div>

                        <div>
                            <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: 600;">
                                {{ $user->role === 'camat' ? 'Jabatan:' : 'Peran / Jabatan:' }}
                            </span>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #334155; margin-top: 2px;">
                                @if ($user->role === 'camat')
                                    {{ $user->jabatan }}
                                @elseif ($user->role === 'masyarakat')
                                    Masyarakat (Warga Pemohon)
                                @elseif ($user->peran_layanan === 'Operator')
                                    Operator Kecamatan
                                @else
                                    Petugas Loket
                                @endif
                            </div>
                            <small style="font-size: 0.68rem; color: #94a3b8;">Hak akses dalam sistem</small>
                        </div>

                        <div>
                            <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: 600;">Instansi / Wilayah:</span>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #334155; margin-top: 2px;">Kecamatan Jatisari, Karawang</div>
                            <small style="font-size: 0.68rem; color: #94a3b8;">Wilayah kerja / domisili</small>
                        </div>
                    </div>
                </div>

                {{-- 3. DATA KONTAK YANG DAPAT DIUBAH (EMAIL & NO HP) --}}
                <section class="profile-form-section" style="margin-bottom: 24px;">
                    <h2 style="font-size: 1.05rem; color: #1e3a5f; margin: 0 0 14px; font-weight: 750;">Informasi Kontak Akun</h2>

                    <div class="profile-fields" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
                        <div class="field-group">
                            <label for="email" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Alamat Email Akun <span style="color: #dc2626;">*</span>
                            </label>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   value="{{ old('email', $user->email) }}"
                                   autocomplete="email"
                                   required
                                   style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                            @error('email')<span class="profile-field-error" style="color: #dc2626; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                            <small style="color: #64748b; font-size: 0.72rem; margin-top: 4px; display: block;">Digunakan untuk login dan notifikasi sistem.</small>
                        </div>

                        <div class="field-group">
                            <label for="phone" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Nomor Telepon / WhatsApp <span style="color: #dc2626;">*</span>
                            </label>
                            <input id="phone"
                                   name="phone"
                                   type="tel"
                                   value="{{ old('phone', $user->phone) }}"
                                   autocomplete="tel"
                                   required
                                   style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                            @error('phone')<span class="profile-field-error" style="color: #dc2626; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                            <small style="color: #64748b; font-size: 0.72rem; margin-top: 4px; display: block;">Nomor kontak aktif yang dapat dihubungi.</small>
                        </div>
                    </div>
                </section>

                <div class="profile-form-actions" style="display: flex; gap: 12px; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 18px;">
                    <button type="submit" class="dashboard-primary-link" style="border: none; padding: 10px 22px; font-size: 0.86rem; font-weight: 700; cursor: pointer; border-radius: 8px;">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('account.profile') }}" class="profile-back-link" style="padding: 9px 18px; border: 1px solid #cbd5e1; border-radius: 8px; color: #64748b; text-decoration: none; font-size: 0.84rem; font-weight: 600;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
function previewNewPhoto(event) {
    const input = event.target;
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!validTypes.includes(file.type)) {
        alert('Format file tidak didukung! Harap unggah gambar JPG, JPEG, atau PNG.');
        input.value = '';
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran foto terlalu besar! Maksimal ukuran foto adalah 2 MB.');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('preview-avatar');
        const placeholder = document.getElementById('preview-placeholder');
        if (preview) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        if (placeholder) {
            placeholder.style.display = 'none';
        }
    };
    reader.readAsDataURL(file);
}
</script>
@endif
@endsection
