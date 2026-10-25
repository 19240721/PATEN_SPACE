@extends('layouts.app')

@section('title', 'Daftar Akun PATEN SPACE')

@section('content')
<style>
    .account-page {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 34px;
        background: #f3f0e7;
    }

    .account-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(360px, .9fr);
        width: min(100%, 1040px);
        min-height: 650px;
        overflow: hidden;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 24px 60px rgba(69, 59, 39, .14);
    }

    .account-visual {
        display: flex;
        flex-direction: column;
        padding: 34px 42px 30px;
        position: relative;
        overflow: hidden;
        color: #171611;
        background-color: #e9e2d0;
        background-image: linear-gradient(180deg, rgba(247, 244, 234, .9) 0%, rgba(247, 244, 234, .83) 56%, rgba(230, 221, 197, .58) 100%), url('/images/paten-building.jpg');
        background-position: center 52%;
        background-size: cover;
    }

    .account-institution {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #171611;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .08em;
    }

    .account-mark {
        display: block;
        width: 42px;
        height: 46px;
        flex: 0 0 42px;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    .account-copy {
        position: relative;
        z-index: 1;
        max-width: 310px;
        margin-top: 54px;
    }

    .account-copy h1 {
        margin: 0 0 8px;
        color: #171611;
        font-size: clamp(1.8rem, 3.2vw, 2.55rem);
        line-height: 1.04;
        text-shadow: 0 1px 4px rgba(255, 255, 255, .9);
    }

    .account-copy p {
        max-width: 330px;
        margin: 0;
        color: #171611;
        font-size: .9rem;
        font-weight: 600;
        line-height: 1.6;
        text-shadow: 0 1px 3px rgba(255, 255, 255, .95);
    }

    .account-features {
        position: relative;
        z-index: 1;
        display: grid;
        gap: 9px;
        max-width: 270px;
        margin-top: 26px;
        color: #171611;
        font-size: .7rem;
        font-weight: 700;
    }

    .account-features span {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .account-features span::before {
        content: 'P';
        display: grid;
        place-items: center;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #796743;
        color: #fff;
        font-size: .6rem;
    }

    .account-form {
        align-self: center;
        width: min(100%, 365px);
        margin: auto;
        padding: 34px 20px;
    }

    .account-form h2 {
        margin: 0 0 7px;
        color: #171611;
        font-size: 1.5rem;
        text-align: center;
    }

    .account-subtitle {
        margin: 0 0 25px;
        color: #514b3d;
        font-size: .78rem;
        text-align: center;
    }

    .account-field {
        display: grid;
        gap: 6px;
        margin-bottom: 14px;
    }

    .account-field label {
        color: #171611;
        font-size: .74rem;
        font-weight: 600;
    }

    .account-field input {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #d8ceb6;
        border-radius: 5px;
        outline: none;
        color: #171611;
        font-size: .78rem;
        background: #fdfcf9;
    }

    .account-field input:focus {
        border-color: #9b875d;
        box-shadow: 0 0 0 3px rgba(155, 135, 93, .16);
    }

    .account-error {
        margin: -7px 0 14px;
        padding: 9px 11px;
        border-radius: 5px;
        background: #fff0ef;
        color: #b42318;
        font-size: .75rem;
    }

    .account-submit {
        width: 100%;
        padding: 12px 16px;
        border: 0;
        border-radius: 5px;
        background: #796743;
        color: #fff;
        cursor: pointer;
        font-size: .8rem;
        font-weight: 700;
    }

    .account-submit:hover { background: #635435; }

    .account-terms {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin: 2px 0 17px;
        color: #171611;
        font-size: .72rem;
        line-height: 1.45;
    }

    .account-terms input { margin-top: 2px; accent-color: #796743; }

    .account-login {
        display: block;
        margin-top: 17px;
        color: #796743;
        font-size: .74rem;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
    }

    .account-login:hover { color: #635435; text-decoration: underline; }

    .citizen-account-page {
        padding: 24px 14px;
        background: #f3f0e7;
    }

    .citizen-account-page .account-shell {
        display: block;
        width: min(100%, 430px);
        min-height: 0;
        border: 1px solid #e8e1d1;
        border-radius: 18px;
        box-shadow: 0 16px 40px rgba(69, 59, 39, .14);
    }

    .citizen-account-page .account-visual {
        display: flex;
        min-height: 230px;
        padding: 20px 24px;
        background-position: center 52%;
    }

    .citizen-account-page .account-institution { color: #171611; font-size: .68rem; }
    .citizen-account-page .account-mark { width: 36px; height: 40px; flex-basis: 36px; }
    .citizen-account-page .account-copy { margin-top: 24px; }
    .citizen-account-page .account-copy h1 { color: #171611; font-size: 1.7rem; }
    .citizen-account-page .account-copy p { color: #171611; font-size: .78rem; font-weight: 700; }
    .citizen-account-page .account-features { display: none; }

    .citizen-account-page .account-form {
        width: 100%;
        padding: 28px 24px 30px;
    }

    .citizen-account-page .account-form h2 { font-size: 1.35rem; }
    .citizen-account-page .account-subtitle { margin-bottom: 20px; }

    @media (max-width: 760px) {
        .citizen-account-page { display: block; padding: 0; }
        .citizen-account-page .account-shell { width: 100%; min-height: 100vh; border: 0; border-radius: 0; box-shadow: none; }
        .citizen-account-page .account-visual { min-height: 220px; padding: 20px 22px; }
        .citizen-account-page .account-form { padding: 30px 22px 36px; }
    }

    @media (max-width: 380px) {
        .citizen-account-page .account-form { padding-right: 18px; padding-left: 18px; }
        .citizen-account-page .account-field input { padding: 10px 11px; }
    }
</style>

<main class="account-page citizen-account-page">
    <div class="account-shell">
        <section class="account-visual" aria-label="Informasi PATEN SPACE">
            <div class="account-institution">
                <img class="account-mark" src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang">
                <span>PEMERINTAH KABUPATEN<br>PELAYANAN TERPADU</span>
            </div>

            <div class="account-copy">
                <h1>Daftar Akun<br><strong>Masyarakat</strong></h1>
                <p>Buat akun untuk mengajukan dan memantau permohonan administrasi Kecamatan Jatisari.</p>
            </div>

            <div class="account-features">
                <span>Aman dan terpercaya</span>
                <span>Pelayanan lebih cepat</span>
                <span>Akun khusus masyarakat</span>
            </div>
        </section>

        <section class="account-form" aria-labelledby="register-title">
            <h2 id="register-title">Daftar Akun Masyarakat</h2>
            <p class="account-subtitle">Lengkapi data berikut untuk mulai menggunakan layanan PATEN.</p>

            @if ($errors->any())
                <div class="account-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('account.register.store') }}" method="POST">
                @csrf

                <div class="account-field">
                    <label for="name">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" placeholder="Masukkan nama lengkap" required autofocus>
                </div>

                <div class="account-field">
                    <label for="phone">Nomor HP</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" placeholder="Masukkan nomor HP" required>
                </div>

                <div class="account-field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="Masukkan email" required>
                </div>

                <div class="account-field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Minimal 8 karakter" required>
                </div>

                <div class="account-field">
                    <label for="password_confirmation">Ulangi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi password" required>
                </div>

                <label class="account-terms">
                    <input type="checkbox" name="terms_accepted" value="1" {{ old('terms_accepted') ? 'checked' : '' }} required>
                    <span>Saya menyetujui Syarat &amp; Ketentuan penggunaan PATEN SPACE.</span>
                </label>

                <button class="account-submit" type="submit">Daftar Akun</button>
            </form>

            <a class="account-login" href="{{ route('login.citizen') }}">Sudah punya akun? Login</a>
        </section>
    </div>
</main>
@endsection
