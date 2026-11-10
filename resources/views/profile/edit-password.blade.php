@extends('layouts.app')

@section('title', 'Ubah Password - PATEN SPACE')

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
                <h1>Ubah Password</h1>
                <p>Kecamatan Jatisari</p>
            </div>
        </div>
    </header>

    {{-- Scrollable Form --}}
    <main class="mob-prof-scroll">
        <form action="{{ route('account.profile.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mob-card">
                <div class="mob-card-title">
                    <span>🔒</span>
                    <span>Keamanan Kata Sandi</span>
                </div>
                <div class="mob-input-help" style="margin-bottom: 14px;">
                    Masukkan password Anda saat ini, lalu tentukan password baru minimal 8 karakter.
                </div>

                <div class="mob-input-group">
                    <label for="mob_current_password" class="mob-input-label">Password Saat Ini <span style="color:#dc2626;">*</span></label>
                    <input id="mob_current_password" name="current_password" type="password" required class="mob-input-text" autocomplete="current-password">
                    @error('current_password')<div class="mob-field-err">{{ $message }}</div>@enderror
                </div>

                <div class="mob-input-group">
                    <label for="mob_password" class="mob-input-label">Password Baru <span style="color:#dc2626;">*</span></label>
                    <input id="mob_password" name="password" type="password" required class="mob-input-text" autocomplete="new-password">
                    @error('password')<div class="mob-field-err">{{ $message }}</div>@enderror
                    <div class="mob-input-help">Minimal 8 karakter.</div>
                </div>

                <div class="mob-input-group" style="margin-bottom: 0;">
                    <label for="mob_password_confirmation" class="mob-input-label">Konfirmasi Password Baru <span style="color:#dc2626;">*</span></label>
                    <input id="mob_password_confirmation" name="password_confirmation" type="password" required class="mob-input-text" autocomplete="new-password">
                </div>
            </div>

            <div style="margin-top: 14px; margin-bottom: 8px;">
                <button type="submit" class="mob-submit-btn">
                    <span>💾</span>
                    <span>Perbarui Password</span>
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

@else
{{-- ========================================================
     TAMPILAN STANDAR DESKTOP (PETUGAS / OPERATOR / CAMAT)
     ======================================================== --}}
<div class="app-shell dashboard-page account-profile-page">
    <main class="main-panel" style="width: 100%; max-width: 860px; margin: 0 auto; padding: 25px 20px;">
        <div class="dashboard-content">
            <header class="profile-page-heading" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 22px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <span class="profile-heading-avatar" aria-hidden="true">🔒</span>
                    <div>
                        <strong style="font-size: 1.25rem; color: #1e3a5f; display: block;">Ubah Password</strong>
                        <small style="color: #64748b; font-size: 0.84rem;">Jaga keamanan akun Anda secara berkala</small>
                    </div>
                </div>
                <a href="{{ route('account.profile') }}" class="profile-dashboard-link" style="padding: 9px 15px; font-size: 0.82rem;">
                    Kembali ke Pengaturan Akun
                </a>
            </header>

            <form action="{{ route('account.profile.password.update') }}" method="POST" class="dashboard-panel account-profile-form" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                @csrf
                @method('PUT')
                <section class="profile-form-section" style="margin-bottom: 24px;">
                    <h2 style="font-size: 1.05rem; color: #1e3a5f; margin: 0 0 6px; font-weight: 750;">Keamanan Password Akun</h2>
                    <p class="profile-password-help" style="color: #64748b; font-size: 0.82rem; margin-bottom: 20px;">
                        Masukkan password akun Anda saat ini, lalu tentukan password baru minimal 8 karakter.
                    </p>

                    <div class="profile-fields" style="display: flex; flex-direction: column; gap: 16px; max-width: 520px;">
                        <div class="field-group">
                            <label for="current_password" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Password Saat Ini <span style="color: #dc2626;">*</span>
                            </label>
                            <input id="current_password"
                                   name="current_password"
                                   type="password"
                                   autocomplete="current-password"
                                   required
                                   style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                            @error('current_password')<span class="profile-field-error" style="color: #dc2626; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                        </div>

                        <div class="field-group">
                            <label for="password" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Password Baru <span style="color: #dc2626;">*</span>
                            </label>
                            <input id="password"
                                   name="password"
                                   type="password"
                                   autocomplete="new-password"
                                   required
                                   style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                            @error('password')<span class="profile-field-error" style="color: #dc2626; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                            <small style="color: #64748b; font-size: 0.72rem; margin-top: 4px; display: block;">Minimal 8 karakter.</small>
                        </div>

                        <div class="field-group">
                            <label for="password_confirmation" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Konfirmasi Password Baru <span style="color: #dc2626;">*</span>
                            </label>
                            <input id="password_confirmation"
                                   name="password_confirmation"
                                   type="password"
                                   autocomplete="new-password"
                                   required
                                   style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                        </div>
                    </div>
                </section>

                <div class="profile-form-actions" style="display: flex; gap: 12px; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 18px;">
                    <button type="submit" class="dashboard-primary-link" style="border: none; padding: 10px 22px; font-size: 0.86rem; font-weight: 700; cursor: pointer; border-radius: 8px;">
                        Perbarui Password
                    </button>
                    <a href="{{ route('account.profile') }}" class="profile-back-link" style="padding: 9px 18px; border: 1px solid #cbd5e1; border-radius: 8px; color: #64748b; text-decoration: none; font-size: 0.84rem; font-weight: 600;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
@endif
@endsection
