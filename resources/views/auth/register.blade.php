@extends('layouts.app')

@section('title', 'Daftar Akun PATEN SPACE')

@section('content')
<style>
    .account-page {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 34px;
        background: #eaf2f8;
    }

    .account-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(360px, .9fr);
        width: min(100%, 1040px);
        min-height: 650px;
        overflow: hidden;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 24px 60px rgba(21, 59, 91, .18);
    }

    .account-visual {
        display: flex;
        flex-direction: column;
        padding: 34px 42px 30px;
        position: relative;
        overflow: hidden;
        color: #164e7e;
        background-color: #e9f5ff;
        background-image: linear-gradient(180deg, rgba(233, 245, 255, .98) 0%, rgba(233, 245, 255, .96) 45%, rgba(233, 245, 255, .2) 68%, rgba(8, 108, 184, .2) 100%), url('/images/paten-building.jpg');
        background-position: center bottom;
        background-size: cover;
    }

    .account-institution {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #1260a8;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .08em;
    }

    .account-mark {
        display: grid;
        place-items: center;
        width: 34px;
        height: 38px;
        border: 3px solid rgba(255, 255, 255, .78);
        border-radius: 7px 7px 12px 12px;
        background: #f6d957;
        color: #1260a8;
        font-size: 1.1rem;
        font-weight: 900;
    }

    .account-copy {
        position: relative;
        z-index: 1;
        max-width: 310px;
        margin-top: 54px;
    }

    .account-copy h1 {
        margin: 0 0 8px;
        font-size: clamp(1.8rem, 3.2vw, 2.55rem);
        line-height: 1.04;
        text-shadow: 0 2px 10px rgba(255, 255, 255, .7);
    }

    .account-copy p {
        max-width: 330px;
        margin: 0;
        color: #426b87;
        font-size: .9rem;
        line-height: 1.6;
    }

    .account-features {
        position: relative;
        z-index: 1;
        display: grid;
        gap: 9px;
        max-width: 270px;
        margin-top: 26px;
        color: #2a638d;
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
        background: #087bd1;
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
        color: #164e7e;
        font-size: 1.5rem;
        text-align: center;
    }

    .account-subtitle {
        margin: 0 0 25px;
        color: #7890a3;
        font-size: .78rem;
        text-align: center;
    }

    .account-field {
        display: grid;
        gap: 6px;
        margin-bottom: 14px;
    }

    .account-field label {
        color: #607c92;
        font-size: .74rem;
        font-weight: 600;
    }

    .account-field input {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #c7d7e3;
        border-radius: 5px;
        outline: none;
        color: #28516e;
        font-size: .78rem;
        background: #fbfdff;
    }

    .account-field input:focus {
        border-color: #1680d0;
        box-shadow: 0 0 0 3px rgba(22, 128, 208, .12);
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
        background: #087bd1;
        color: #fff;
        cursor: pointer;
        font-size: .8rem;
        font-weight: 700;
    }

    .account-submit:hover { background: #0669b4; }

    .account-terms {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin: 2px 0 17px;
        color: #607c92;
        font-size: .72rem;
        line-height: 1.45;
    }

    .account-terms input { margin-top: 2px; accent-color: #087bd1; }

    .account-login {
        display: block;
        margin-top: 17px;
        color: #087bd1;
        font-size: .74rem;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
    }

    .account-login:hover { color: #0669b4; text-decoration: underline; }

    @media (max-width: 760px) {
        .account-page { padding: 16px; }
        .account-shell { grid-template-columns: 1fr; min-height: auto; }
        .account-visual { min-height: 285px; padding: 24px; }
        .account-copy { margin-top: 34px; }
        .account-form { padding: 32px 20px 38px; }
    }
</style>

<main class="account-page">
    <div class="account-shell">
        <section class="account-visual" aria-label="Informasi PATEN SPACE">
            <div class="account-institution">
                <div class="account-mark">P</div>
                <span>PEMERINTAH KABUPATEN<br>PELAYANAN TERPADU</span>
            </div>

            <div class="account-copy">
                <h1>Daftar Akun<br><strong>Operator</strong></h1>
                <p>Buat akun untuk mengakses pelayanan administrasi terpadu Kecamatan Jatisari.</p>
            </div>

            <div class="account-features">
                <span>Aman dan terpercaya</span>
                <span>Pelayanan lebih cepat</span>
                <span>Khusus petugas kecamatan</span>
            </div>
        </section>

        <section class="account-form" aria-labelledby="register-title">
            <h2 id="register-title">Daftar Akun Operator</h2>
            <p class="account-subtitle">Silakan isi data berikut untuk membuat akun operator.</p>

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
                    <label for="nik">NIK</label>
                    <input id="nik" name="nik" type="text" value="{{ old('nik') }}" inputmode="numeric" maxlength="16" placeholder="Masukkan NIK (16 digit)" required>
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

            <a class="account-login" href="{{ route('login') }}">Sudah punya akun? Login</a>
        </section>
    </div>
</main>
@endsection
