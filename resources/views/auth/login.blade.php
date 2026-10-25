@extends('layouts.app')

@section('title', $citizenLogin ? 'Login Masyarakat - PATEN SPACE' : 'Login Petugas & Camat - PATEN SPACE')

@section('content')
<style>
    .login-page {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 34px 20px;
        background: #f3f0e7;
    }

    .login-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(360px, .95fr);
        width: min(100%, 1020px);
        min-height: 620px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e8e1d1;
        border-radius: 18px;
        box-shadow: 0 24px 60px rgba(69, 59, 39, .14);
    }

    .login-visual {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        overflow: hidden;
        padding: 38px 44px 34px;
        color: #171611;
        background-color: #e9e2d0;
        background-image: linear-gradient(180deg, rgba(247, 244, 234, .9) 0%, rgba(247, 244, 234, .83) 56%, rgba(230, 221, 197, .58) 100%), url('/images/paten-building.jpg');
        background-position: center 52%;
        background-size: cover;
    }

    .login-visual > * { position: relative; z-index: 1; }

    .institution {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #171611;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .02em;
        line-height: 1.35;
    }

    .institution-logo {
        width: 42px;
        height: 46px;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    .visual-copy {
        width: 100%;
        max-width: 400px;
        margin-top: 50px;
    }

    .visual-copy h1 {
        margin: 0 0 10px;
        color: #171611;
        font-size: clamp(1.8rem, 2.8vw, 2.4rem);
        line-height: 1.1;
        letter-spacing: -.01em;
        text-shadow: 0 1px 4px rgba(255, 255, 255, .9);
    }

    .visual-copy p {
        margin: 0;
        max-width: 360px;
        color: #171611;
        font-size: .88rem;
        font-weight: 600;
        line-height: 1.6;
        text-shadow: 0 1px 3px rgba(255, 255, 255, .95);
    }

    .visual-features {
        display: grid;
        gap: 10px;
        max-width: 340px;
        margin-top: 32px;
        color: #171611;
        font-size: .74rem;
        font-weight: 700;
    }

    .visual-features span {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .visual-features span::before {
        content: 'P';
        display: grid;
        place-items: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #796743;
        color: #fff;
        font-size: .65rem;
        flex-shrink: 0;
    }

    .login-card {
        align-self: center;
        width: min(100%, 375px);
        margin: auto;
        padding: 38px 26px;
        background: #fff;
    }

    .login-logo {
        display: block;
        width: 60px;
        height: 66px;
        margin: 0 auto 14px;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    .login-card h1 {
        margin: 0 0 7px;
        color: #171611;
        font-size: 1.45rem;
        text-align: center;
        font-weight: 800;
    }

    .login-card > p {
        margin: 0 0 24px;
        color: #514b3d;
        font-size: .78rem;
        text-align: center;
        line-height: 1.45;
    }

    .login-field {
        display: grid;
        gap: 6px;
        margin-bottom: 14px;
    }

    .login-field label {
        color: #171611;
        font-size: .74rem;
        font-weight: 600;
    }

    .login-field input {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #d8ceb6;
        border-radius: 5px;
        outline: none;
        color: #171611;
        font-size: .78rem;
        background: #fdfcf9;
    }

    .login-field input:focus {
        border-color: #9b875d;
        box-shadow: 0 0 0 3px rgba(155, 135, 93, .16);
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
        color: #796743;
        cursor: pointer;
    }

    .password-toggle:hover {
        background: #f7f4ec;
        color: #635435;
    }

    .password-toggle:focus-visible {
        outline: 2px solid #9b875d;
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
        color: #171611;
        font-size: .74rem;
        font-weight: 600;
    }

    .captcha-row {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
    }

    .captcha-image-wrap,
    .captcha-refresh {
        height: 54px;
        border: 1px solid #d8ceb6;
        border-radius: 8px;
        background: #fdfcf9;
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
        height: 46px;
    }

    .captcha-refresh {
        display: grid;
        flex: 0 0 46px;
        place-items: center;
        color: #796743;
        cursor: pointer;
        font-size: 1.35rem;
    }

    .captcha-refresh:hover {
        background: #f7f4ec;
        color: #635435;
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
        margin: 2px 0 18px;
        color: #514b3d;
        font-size: .72rem;
        accent-color: #796743;
    }

    .login-submit {
        width: 100%;
        padding: 12px 16px;
        border: 0;
        border-radius: 5px;
        background: #796743;
        color: #fff;
        cursor: pointer;
        font-size: .82rem;
        font-weight: 700;
        transition: background 0.15s;
    }

    .login-submit:hover { background: #635435; }

    .login-help {
        margin: 18px 0 0;
        padding: 11px 12px;
        border-radius: 6px;
        background: #f7f4ec;
        border: 1px solid #e8e1d1;
        color: #514b3d;
        font-size: .74rem;
        line-height: 1.5;
        text-align: center;
    }

    .login-help a {
        color: #796743;
        font-weight: 700;
        text-decoration: none;
    }

    .login-help a:hover {
        color: #635435;
        text-decoration: underline;
    }

    @media (max-width: 900px) {
        .login-page {
            padding: 24px 14px;
        }

        .login-shell {
            display: block;
            width: min(100%, 450px);
            min-height: 0;
            border-radius: 16px;
        }

        .login-visual {
            min-height: 200px;
            padding: 22px 24px 20px;
        }

        .visual-copy {
            margin-top: 18px;
            max-width: 100%;
        }

        .visual-copy h1 {
            font-size: 1.5rem;
        }

        .visual-features {
            display: none;
        }

        .login-card {
            width: 100%;
            padding: 28px 22px 30px;
        }
    }

    @media (max-width: 480px) {
        .login-page {
            padding: 0;
        }

        .login-shell {
            width: 100%;
            min-height: 100vh;
            border-radius: 0;
            box-shadow: none;
            border: 0;
        }
    }

    @if($citizenLogin)
    /* Khusus Halaman Login Masyarakat (Tampilan Mobile Card Khaki) */
    .citizen-login-page {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 24px 14px;
        background: #f3f0e7;
    }

    .citizen-login-page .login-shell {
        display: block;
        width: min(100%, 430px);
        min-height: 0;
        border: 1px solid #e8e1d1;
        border-radius: 18px;
        box-shadow: 0 16px 40px rgba(69, 59, 39, .14);
        background: #fff;
        overflow: hidden;
    }

    .citizen-login-page .login-visual {
        display: flex;
        flex-direction: column;
        min-height: 230px;
        padding: 20px 24px;
        position: relative;
        overflow: hidden;
        color: #171611;
        background-color: #e9e2d0;
        background-image: linear-gradient(180deg, rgba(247, 244, 234, .9) 0%, rgba(247, 244, 234, .83) 56%, rgba(230, 221, 197, .58) 100%), url('/images/paten-building.jpg');
        background-position: center 52%;
        background-size: cover;
    }

    .citizen-login-page .institution {
        color: #171611;
        font-size: .70rem;
        font-weight: 700;
        letter-spacing: .02em;
        line-height: 1.35;
    }

    .citizen-login-page .institution-logo {
        width: 36px;
        height: 40px;
        flex-basis: 36px;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    .citizen-login-page .visual-copy {
        margin-top: 24px;
        max-width: 310px;
    }

    .citizen-login-page .visual-copy h1 {
        margin: 0 0 6px;
        color: #171611;
        font-size: 1.7rem;
        line-height: 1.08;
        letter-spacing: normal;
        text-shadow: 0 1px 4px rgba(255, 255, 255, .9);
    }

    .citizen-login-page .visual-copy p {
        margin: 0;
        color: #171611;
        font-size: .78rem;
        font-weight: 700;
        line-height: 1.5;
        text-shadow: 0 1px 3px rgba(255, 255, 255, .95);
    }

    .citizen-login-page .visual-features {
        display: none;
    }

    .citizen-login-page .login-card {
        width: 100%;
        padding: 28px 24px 30px;
        background: #fff;
    }

    .citizen-login-page .login-card h1 {
        margin: 0 0 7px;
        color: #171611;
        font-size: 1.35rem;
        text-align: center;
        font-weight: 700;
    }

    .citizen-login-page .login-card > p {
        margin: 0 0 20px;
        color: #514b3d;
        font-size: .78rem;
        text-align: center;
    }

    @media (max-width: 760px) {
        .citizen-login-page { display: block; padding: 0; }
        .citizen-login-page .login-shell { width: 100%; min-height: 100vh; border: 0; border-radius: 0; box-shadow: none; }
        .citizen-login-page .login-visual { min-height: 220px; padding: 20px 22px; }
        .citizen-login-page .login-card { padding: 30px 22px 36px; }
    }

    @media (max-width: 380px) {
        .citizen-login-page .login-card { padding-right: 18px; padding-left: 18px; }
        .citizen-login-page .login-field input { padding: 10px 11px; }
    }
    @endif

    @if(!$citizenLogin)
    @media (max-width: 820px) {
        .login-page {
            display: block;
            padding: 16px;
        }

        .login-shell {
            grid-template-columns: 1fr;
            min-height: auto;
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
        }

        .login-visual {
            min-height: auto;
            padding: 24px 22px 20px;
        }

        .visual-copy {
            margin-top: 18px;
        }

        .visual-copy h1 {
            font-size: 1.5rem;
        }

        .visual-features {
            display: none;
        }

        .login-card {
            padding: 30px 22px 36px;
        }
    }
    @endif
</style>

<main class="login-page {{ $citizenLogin ? 'citizen-login-page' : '' }}">
    <div class="login-shell">
        <section class="login-visual" aria-label="Informasi PATEN SPACE">
            <div class="institution">
                <img class="institution-logo" src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang">
                <span>Pelayanan Administrasi Terpadu<br>Kecamatan Jatisari</span>
            </div>

            <div class="visual-copy">
                <h1>{{ $citizenLogin ? 'Login Akun' : 'Portal Pelayanan' }}<br><strong>{{ $citizenLogin ? 'Masyarakat' : 'Kecamatan Jatisari' }}</strong></h1>
                <p>{{ $citizenLogin ? 'Masuk untuk mengajukan dan memantau permohonan administrasi Kecamatan Jatisari.' : 'Sistem Informasi Pelayanan Administrasi Terpadu Kecamatan Jatisari Kabupaten Karawang.' }}</p>
            </div>

            @unless($citizenLogin)
            <div class="visual-features">
                <span>Verifikasi &amp; Pemrosesan Berkas</span>
                <span>Pelayanan Terpadu Satu Pintu</span>
                <span>Monitoring &amp; Pelaporan Real-time</span>
            </div>
            @endunless
        </section>

        <section class="login-card" aria-labelledby="login-title">
            @unless($citizenLogin)
            <img class="login-logo" src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang">
            @endunless
            <h1 id="login-title">{{ $citizenLogin ? 'Login Akun Masyarakat' : 'Login' }}</h1>
            <p>{{ $citizenLogin ? 'Masukkan email dan password Anda untuk masuk ke sistem.' : 'Masuk untuk mengelola pelayanan dan administrasi kecamatan.' }}</p>

            @if ($errors->any())
                <div class="login-error">{{ $errors->first() }}</div>
            @endif

            @if (session('status'))
                <div class="login-success">{{ session('status') }}</div>
            @endif

            <form action="{{ $citizenLogin ? route('login.citizen.store') : route('login.store') }}" method="POST" autocomplete="off">
                @csrf

                <div class="login-field">
                    <label for="email">Email / Username</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="off" required placeholder="Masukkan email anda">
                </div>

                <div class="login-field">
                    <label for="password">Password</label>
                    <div class="password-input-wrap">
                        <input id="password" name="password" type="password" autocomplete="new-password" required placeholder="Masukkan password">
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

            @if ($citizenLogin)
                <p class="login-help">Belum punya akun masyarakat? <a href="{{ route('account.register') }}">Daftar akun masyarakat</a></p>
                <div style="margin-top: 14px; text-align: center;">
                    <a href="{{ route('login') }}" style="color: #796743; font-size: 0.74rem; font-weight: 700; text-decoration: none;">Login Petugas / Camat &rarr;</a>
                </div>
            @else
                <p class="login-help">Login khusus operator dan camat. Untuk akun masyarakat, <a href="{{ route('login.citizen') }}">buka halaman login masyarakat</a>.</p>
            @endif
        </section>
    </div>
</main>

<script>
    function clearLoginAutofill() {
        @if(!$errors->any() && !old('email'))
        const emailInput = document.getElementById('email');
        if (emailInput && !emailInput.defaultValue) {
            emailInput.value = '';
        }
        @endif
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.value = '';
        }
    }

    window.addEventListener('DOMContentLoaded', clearLoginAutofill);
    window.addEventListener('pageshow', clearLoginAutofill);
    window.addEventListener('load', function () {
        clearLoginAutofill();
        setTimeout(clearLoginAutofill, 50);
        setTimeout(clearLoginAutofill, 250);
    });

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
</script>
@endsection
