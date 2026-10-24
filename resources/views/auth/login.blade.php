@extends('layouts.app')

@section('title', 'Login PATEN SPACE')

@section('content')
<style>
    .login-page {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 34px;
        background: #eaf2f8;
    }

    .login-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(360px, .9fr);
        width: min(100%, 1040px);
        min-height: 650px;
        overflow: hidden;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 24px 60px rgba(21, 59, 91, .18);
    }

    .login-visual {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        overflow: hidden;
        padding: 34px 42px 30px;
        color: #fff;
        background-image: linear-gradient(180deg, rgba(8, 103, 178, .78) 0%, rgba(10, 115, 187, .24) 38%, rgba(8, 80, 137, .10) 72%, rgba(8, 80, 137, .25) 100%), url('{{ asset('images/paten-building-login.png') }}');
        background-position: center center;
        background-color: #0a73bb;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .login-visual::before,
    .login-visual::after {
        display: none;
    }

    .login-visual > * { position: relative; z-index: 1; }

    .institution {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .08em;
    }

    .institution-mark,
    .login-mark {
        display: grid;
        place-items: center;
        color: #1260a8;
        background: #f6d957;
        border: 3px solid rgba(255, 255, 255, .78);
        border-radius: 7px 7px 12px 12px;
        font-weight: 900;
    }

    .institution-mark {
        width: 34px;
        height: 38px;
        font-size: 1.1rem;
    }

    .institution-logo {
        width: 38px;
        height: 42px;
        object-fit: contain;
    }

    .visual-copy {
        width: 100%;
        max-width: 500px;
        margin-top: 92px;
    }

    .visual-copy h1 {
        margin: 0 0 8px;
        font-size: clamp(1.8rem, 3.2vw, 2.55rem);
        line-height: 1.04;
        letter-spacing: -.02em;
        text-shadow: 0 2px 10px rgba(0, 49, 91, .2);
    }

    .visual-copy p {
        margin: 0;
        max-width: 330px;
        color: rgba(255, 255, 255, .86);
        font-size: .9rem;
        line-height: 1.6;
    }

    .building {
        display: none;
    }

    .building::before {
        content: '';
        position: absolute;
        left: -22px;
        bottom: 0;
        width: 280px;
        height: 34px;
        background: #427b9f;
        clip-path: polygon(0 100%, 8% 35%, 23% 65%, 40% 25%, 55% 62%, 73% 20%, 100% 68%, 100% 100%);
    }

    .building::after {
        content: 'PATEN';
        position: absolute;
        left: 76px;
        top: 44px;
        padding: 5px 10px;
        color: #fff;
        background: #2783bd;
        font-size: .63rem;
        font-weight: 800;
        letter-spacing: .1em;
    }

    .visual-footer {
        position: absolute;
        left: 42px;
        bottom: 30px;
        display: flex;
        gap: 22px;
        color: #155b91;
        font-size: .68rem;
        font-weight: 700;
    }

    .visual-footer span::before {
        content: 'o';
        display: inline-grid;
        place-items: center;
        width: 17px;
        height: 17px;
        margin-right: 5px;
        border: 1px solid currentColor;
        border-radius: 50%;
        font-size: .55rem;
    }

    .login-card {
        align-self: center;
        width: min(100%, 365px);
        margin: auto;
        padding: 34px 20px;
        background: #fff;
    }

    .login-mark {
        width: 54px;
        height: 58px;
        margin: 0 auto 14px;
        font-size: 1.45rem;
    }

    .login-logo {
        display: block;
        width: 72px;
        height: 78px;
        margin: 0 auto 14px;
        object-fit: contain;
    }

    .login-card h1 {
        margin: 0 0 7px;
        color: #164e7e;
        font-size: 1.5rem;
        text-align: center;
    }

    .login-card > p {
        margin: 0 0 25px;
        color: #7890a3;
        font-size: .78rem;
        text-align: center;
    }

    .login-field {
        display: grid;
        gap: 6px;
        margin-bottom: 14px;
    }

    .login-field label {
        color: #607c92;
        font-size: .74rem;
        font-weight: 600;
    }

    .login-field input {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #c7d7e3;
        border-radius: 5px;
        outline: none;
        color: #28516e;
        font-size: .78rem;
        background: #fbfdff;
    }

    .login-field input:focus {
        border-color: #1680d0;
        box-shadow: 0 0 0 3px rgba(22, 128, 208, .12);
    }

    .password-input-wrap {
        position: relative;
    }

    .password-input-wrap input {
        padding-right: 44px;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 8px;
        display: grid;
        width: 30px;
        height: 30px;
        padding: 5px;
        place-items: center;
        transform: translateY(-50%);
        border: 0;
        border-radius: 4px;
        background: transparent;
        color: #607c92;
        cursor: pointer;
    }

    .password-toggle:hover {
        background: #eaf2f8;
        color: #164e7e;
    }

    .password-toggle:focus-visible {
        outline: 2px solid #1680d0;
        outline-offset: 2px;
    }

    .password-toggle svg {
        width: 20px;
        height: 20px;
        fill: none;
        stroke: currentColor;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-width: 1.8;
    }

    .password-toggle [hidden] {
        display: none;
    }

    .captcha-field {
        margin-bottom: 14px;
    }

    .captcha-field > label {
        display: block;
        margin-bottom: 7px;
        color: #607c92;
        font-size: .68rem;
        font-weight: 700;
    }

    .captcha-row {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
    }

    .captcha-image-wrap,
    .captcha-refresh {
        height: 56px;
        border: 1px solid #d6d0ff;
        border-radius: 9px;
        background: #fbfaff;
    }

    .captcha-image-wrap {
        display: grid;
        flex: 1;
        min-width: 0;
        place-items: center;
    }

    .captcha-image {
        display: block;
        width: 150px;
        max-width: 100%;
        height: 48px;
    }

    .captcha-refresh {
        display: grid;
        flex: 0 0 46px;
        place-items: center;
        color: #713df2;
        cursor: pointer;
        font-size: 1.35rem;
    }

    .captcha-refresh:hover {
        background: #f1eeff;
    }

    .captcha-field input {
        text-transform: uppercase;
    }

    .captcha-field input::placeholder {
        color: #9aa8b7;
        text-transform: none;
    }

    .login-error {
        margin: -7px 0 14px;
        padding: 9px 11px;
        border-radius: 5px;
        background: #fff0ef;
        color: #b42318;
        font-size: .75rem;
    }

    .login-success {
        margin: -7px 0 14px;
        padding: 9px 11px;
        border-radius: 5px;
        background: #edf9f1;
        color: #237443;
        font-size: .75rem;
    }

    .login-remember {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 3px 0 19px;
        color: #7890a3;
        font-size: .72rem;
    }

    .login-submit {
        width: 100%;
        padding: 12px 16px;
        border: 0;
        border-radius: 5px;
        background: #087bd1;
        color: #fff;
        cursor: pointer;
        font-size: .8rem;
        font-weight: 700;
    }

    .login-submit:hover { background: #0669b4; }

    .login-help {
        margin: 18px 0 0;
        padding: 10px 12px;
        border-radius: 5px;
        background: #eaf5fc;
        color: #54758e;
        font-size: .68rem;
        line-height: 1.5;
        text-align: center;
    }

    @media (max-width: 760px) {
        .login-page { padding: 16px; }
        .login-shell { grid-template-columns: 1fr; min-height: auto; }
        .login-visual { min-height: 285px; padding: 24px; }
        .visual-copy { margin-top: 34px; }
        .visual-copy h1 { font-size: 2rem; }
        .visual-footer { display: none; }
        .login-card { padding: 32px 20px 38px; }
    }

    /* ==========================================================================
       SPLASH SCREEN (INTRO ANIMASI LOGO PATEN SPACE)
       Modern Government Digital Service Intro
       ========================================================================== */
    .paten-splash-screen {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f5f9fd;
        opacity: 1;
        visibility: visible;
        transition: opacity 0.65s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.65s ease;
    }

    .splash-glow {
        position: absolute;
        top: 44%;
        left: 50%;
        width: 480px;
        height: 480px;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        background: radial-gradient(circle, rgba(147, 197, 253, 0.28) 0%, rgba(219, 234, 254, 0.14) 42%, rgba(245, 249, 253, 0) 70%);
        filter: blur(28px);
        animation: splashGlowPulse 3.2s ease-in-out infinite alternate;
        pointer-events: none;
    }

    .splash-ring {
        position: absolute;
        top: 44%;
        left: 50%;
        border-radius: 50%;
        border: 1px solid rgba(59, 130, 246, 0.16);
        transform: translate(-50%, -50%) scale(0.6);
        pointer-events: none;
    }

    .splash-ring.r-1 {
        width: 320px;
        height: 320px;
        animation: splashRingExpand 2.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .splash-ring.r-2 {
        width: 440px;
        height: 440px;
        border-style: dashed;
        border-color: rgba(59, 130, 246, 0.12);
        animation: splashRingExpand 3.2s cubic-bezier(0.16, 1, 0.3, 1) 0.3s forwards;
    }

    .splash-particle {
        position: absolute;
        border-radius: 50%;
        background: rgba(59, 130, 246, 0.35);
        box-shadow: 0 0 8px rgba(59, 130, 246, 0.4);
        pointer-events: none;
        animation: splashParticleFloat 4s ease-in-out infinite alternate;
    }

    .splash-particle.p-1 { width: 5px; height: 5px; top: 20%; left: 22%; animation-delay: 0.2s; }
    .splash-particle.p-2 { width: 7px; height: 7px; top: 26%; right: 24%; animation-delay: 0.6s; }
    .splash-particle.p-3 { width: 4px; height: 4px; bottom: 28%; left: 26%; animation-delay: 1.0s; }
    .splash-particle.p-4 { width: 6px; height: 6px; bottom: 22%; right: 25%; animation-delay: 0.4s; }
    .splash-particle.p-5 { width: 5px; height: 5px; top: 48%; left: 16%; animation-delay: 0.8s; }
    .splash-particle.p-6 { width: 6px; height: 6px; top: 52%; right: 18%; animation-delay: 1.2s; }

    .splash-container {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 20px;
        max-width: 580px;
        width: 100%;
        user-select: none;
    }

    .splash-logo-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        animation: splashLogoEnter 1.1s cubic-bezier(0.16, 1, 0.3, 1) forwards, splashLogoFloat 3.4s ease-in-out infinite 1.1s;
    }

    .splash-logo-img {
        display: block;
        width: clamp(280px, 48vw, 460px);
        height: auto;
        aspect-ratio: 1000 / 782;
        object-fit: contain;
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        filter: none !important;
        transform: translateZ(0);
        backface-visibility: hidden;
    }

    .splash-tagline {
        margin: 0;
        color: #4a7fa8;
        font-size: clamp(0.78rem, 1.6vw, 0.92rem);
        font-weight: 600;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        animation: splashTaglineEnter 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.8s both;
    }

    @keyframes splashGlowPulse {
        0% { transform: translate(-50%, -50%) scale(0.88); opacity: 0.6; }
        100% { transform: translate(-50%, -50%) scale(1.12); opacity: 1; }
    }

    @keyframes splashRingExpand {
        0% { transform: translate(-50%, -50%) scale(0.4); opacity: 0; }
        40% { opacity: 0.8; }
        100% { transform: translate(-50%, -50%) scale(1.05); opacity: 0.4; }
    }

    @keyframes splashParticleFloat {
        0% { transform: translateY(0) translateX(0); opacity: 0.25; }
        50% { transform: translateY(-12px) translateX(6px); opacity: 0.55; }
        100% { transform: translateY(-24px) translateX(-4px); opacity: 0.2; }
    }

    @keyframes splashLogoEnter {
        0% {
            opacity: 0;
            transform: scale(0.86) translateY(12px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes splashLogoFloat {
        0% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
        100% { transform: translateY(0); }
    }

    @keyframes splashTaglineEnter {
        0% {
            opacity: 0;
            transform: translateY(12px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .splash-fade-out {
        opacity: 0 !important;
        visibility: hidden !important;
        transition: opacity 0.65s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.65s ease !important;
        pointer-events: none !important;
    }

    @media (prefers-reduced-motion: reduce) {
        .paten-splash-screen {
            display: none !important;
        }
    }
</style>

<noscript>
    <style>
        .paten-splash-screen { display: none !important; }
    </style>
</noscript>

<div id="paten-splash" class="paten-splash-screen" role="dialog" aria-modal="true" aria-label="Intro PATEN SPACE">
    <div class="splash-glow" aria-hidden="true"></div>
    <div class="splash-ring r-1" aria-hidden="true"></div>
    <div class="splash-ring r-2" aria-hidden="true"></div>

    <div class="splash-particle p-1" aria-hidden="true"></div>
    <div class="splash-particle p-2" aria-hidden="true"></div>
    <div class="splash-particle p-3" aria-hidden="true"></div>
    <div class="splash-particle p-4" aria-hidden="true"></div>
    <div class="splash-particle p-5" aria-hidden="true"></div>
    <div class="splash-particle p-6" aria-hidden="true"></div>

    <div class="splash-container">
        <div class="splash-logo-wrap">
            <img class="splash-logo-img" src="{{ asset('images/logo-paten-space.png') }}" alt="Logo PATEN SPACE KECAMATAN JATISARI">
        </div>

        <p class="splash-tagline">Pelayanan Administrasi Terpadu</p>
    </div>
</div>

<main class="login-page">
    <div class="login-shell">
        <section class="login-visual" aria-label="Informasi PATEN SPACE">
            <div class="institution">
                <img class="institution-logo" src="{{ asset('images/logo-karawang-transparent.png') }}" alt="Logo Kabupaten Karawang">
                <span>PEMERINTAH KABUPATEN<br>PELAYANAN TERPADU</span>
            </div>

            <div class="visual-copy">
                <h1>Selamat Datang di<br><strong>PATEN SPACE</strong></h1>
                <p>Pelayanan Administrasi Terpadu Kecamatan Jatisari yang mudah, cepat, dan transparan.</p>
            </div>

            <div class="building" aria-hidden="true"></div>

            <div class="visual-footer">
                <span>Mudah diakses</span>
                <span>Cepat dan transparan</span>
                <span>Pelayanan terpadu</span>
            </div>
        </section>

        <section class="login-card" aria-labelledby="login-title">
            <img class="login-logo" src="{{ asset('images/logo-karawang-transparent.png') }}" alt="Logo Kabupaten Karawang">
            <h1 id="login-title">PATEN SPACE</h1>
            <p>Pelayanan Administrasi Terpadu Kecamatan Jatisari</p>

            @if ($errors->any())
                <div class="login-error">{{ $errors->first() }}</div>
            @endif

            @if (session('status'))
                <div class="login-success">{{ session('status') }}</div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="login-field">
                    <label for="email">Email / Username</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus placeholder="Masukkan email anda">
                </div>

                <div class="login-field">
                    <label for="password">Password</label>
                    <div class="password-input-wrap">
                        <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Masukkan password">
                        <button
                            class="password-toggle"
                            id="toggle-password"
                            type="button"
                            aria-label="Tampilkan password"
                            aria-pressed="false"
                            aria-controls="password"
                        >
                            <svg class="password-eye" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="password-eye-off" aria-hidden="true" viewBox="0 0 24 24" hidden>
                                <path d="m3 3 18 18"></path>
                                <path d="M10.6 5.1A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a15.8 15.8 0 0 1-3 3.8"></path>
                                <path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7c1.3 0 2.5-.3 3.5-.8"></path>
                                <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="captcha-field">
                    <label for="captcha">KODE KEAMANAN</label>
                    <div class="captcha-row">
                        <div class="captcha-image-wrap">
                            <img
                                class="captcha-image"
                                id="login-captcha-image"
                                src="{{ route('login.captcha') }}"
                                alt="Gambar kode keamanan"
                            >
                        </div>
                        <button class="captcha-refresh" id="refresh-captcha" type="button" aria-label="Muat ulang kode keamanan" title="Muat ulang kode keamanan">
                            <span aria-hidden="true">↻</span>
                        </button>
                    </div>
                    <input id="captcha" name="captcha" type="text" maxlength="3" minlength="3" autocomplete="off" autocapitalize="characters" required placeholder="Masukkan 3 karakter di atas">
                </div>

                <label class="login-remember">
                    <input type="checkbox" name="remember" value="1">
                    Ingat saya
                </label>

                <button class="login-submit" type="submit">Login</button>
            </form>

            <p class="login-help">Belum punya akun? Silakan hubungi administrator sistem untuk mendapatkan akses.</p>
        </section>
    </div>
</main>

<script>
    document.getElementById('toggle-password').addEventListener('click', function () {
        const password = document.getElementById('password');
        const isVisible = password.type === 'text';

        password.type = isVisible ? 'password' : 'text';
        this.setAttribute('aria-pressed', String(!isVisible));
        this.setAttribute('aria-label', isVisible ? 'Tampilkan password' : 'Sembunyikan password');
        this.querySelector('.password-eye').hidden = !isVisible;
        this.querySelector('.password-eye-off').hidden = isVisible;
    });

    document.getElementById('refresh-captcha').addEventListener('click', function () {
        const image = document.getElementById('login-captcha-image');
        image.src = `{{ route('login.captcha') }}?refresh=${Date.now()}`;
    });

    (function () {
        const splash = document.getElementById('paten-splash');
        if (!splash) return;

        @if ((isset($errors) && $errors->any()) || session('status') || old('email'))
            splash.style.display = 'none';
            return;
        @endif

        const urlParams = new URLSearchParams(window.location.search);
        const forceSplash = urlParams.has('splash') || urlParams.has('intro');
        const splashSeen = sessionStorage.getItem('paten_space_splash_seen');

        if (splashSeen && !forceSplash) {
            splash.style.display = 'none';
            return;
        }

        sessionStorage.setItem('paten_space_splash_seen', 'true');

        function dismissSplash() {
            if (!splash || splash.classList.contains('splash-fade-out')) return;
            splash.classList.add('splash-fade-out');
            setTimeout(function () {
                splash.style.display = 'none';
            }, 700);
        }

        const autoDismiss = setTimeout(dismissSplash, 3200);

        splash.addEventListener('click', function () {
            clearTimeout(autoDismiss);
            dismissSplash();
        });
    })();
</script>
@endsection