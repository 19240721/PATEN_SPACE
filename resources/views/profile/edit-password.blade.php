@extends('layouts.app')

@section('title', 'Ubah Password - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page account-profile-page">
    <main class="main-panel">
        <div class="dashboard-content">
            <header class="profile-page-heading">
                <span class="profile-heading-avatar" aria-hidden="true">▣</span>
                <span><strong>Ubah Password</strong><small>Jaga keamanan akun Anda</small></span>
                <a href="{{ route('account.profile') }}" class="profile-dashboard-link">Kembali ke Profil</a>
            </header>

            <form action="{{ route('account.profile.password.update') }}" method="POST" class="dashboard-panel account-profile-form">
                @csrf
                @method('PUT')
                <section class="profile-form-section">
                    <h2>Password Akun</h2>
                    <p class="profile-password-help">Masukkan password saat ini, lalu pilih password baru minimal 8 karakter.</p>
                    <div class="profile-fields">
                        <div class="field-group">
                            <label for="current_password">Password Saat Ini</label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                            @error('current_password')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-group">
                            <label for="password">Password Baru</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" required>
                            @error('password')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                        </div>
                    </div>
                </section>
                <div class="profile-form-actions">
                    <a href="{{ route('account.profile') }}" class="profile-back-link">Batal</a>
                    <button type="submit" class="dashboard-primary-link">Perbarui Password</button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
