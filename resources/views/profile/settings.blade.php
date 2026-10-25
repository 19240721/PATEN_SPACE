@extends('layouts.app')

@section('title', 'Pengaturan Akun - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page account-profile-page">
    <main class="main-panel">
        <div class="dashboard-content">
            <header class="profile-page-heading">
                <span class="profile-heading-avatar" aria-hidden="true">⚙</span>
                <span><strong>Pengaturan</strong><small>Preferensi dan keamanan akun</small></span>
                <a href="{{ route('account.profile') }}" class="profile-dashboard-link">Kembali ke Profil</a>
            </header>

            <section class="dashboard-panel account-profile-form profile-settings-page">
                <div class="profile-form-section">
                    <h2>Preferensi Tampilan</h2>
                    <div class="profile-preference-row">
                        <span class="profile-preference-icon" aria-hidden="true">◐</span>
                        <span><strong>Tema PATEN SPACE</strong><small>Tampilan khaki digunakan agar sesuai dengan dashboard.</small></span>
                        <span class="profile-preference-value">Khaki</span>
                    </div>
                    <div class="profile-preference-row">
                        <span class="profile-preference-icon" aria-hidden="true">文</span>
                        <span><strong>Bahasa</strong><small>Bahasa yang digunakan pada halaman akun.</small></span>
                        <span class="profile-preference-value">Indonesia</span>
                    </div>
                </div>

                <div class="profile-form-section profile-password-section">
                    <h2>Keamanan Akun</h2>
                    <a href="{{ route('account.profile.password') }}" class="profile-preference-link">
                        <span class="profile-preference-icon" aria-hidden="true">▣</span>
                        <span><strong>Ubah Password</strong><small>Perbarui password untuk menjaga keamanan akun.</small></span>
                        <b aria-hidden="true">›</b>
                    </a>
                    <a href="{{ route('account.profile.data') }}" class="profile-preference-link">
                        <span class="profile-preference-icon" aria-hidden="true">▤</span>
                        <span><strong>Perbarui Data Profil</strong><small>Pastikan email dan nomor telepon Anda masih aktif.</small></span>
                        <b aria-hidden="true">›</b>
                    </a>
                </div>
            </section>
        </div>
    </main>
</div>
@endsection
