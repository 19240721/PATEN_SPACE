@extends('layouts.app')

@section('title', 'Pengaturan Akun - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page account-profile-page">
    <main class="main-panel" style="width: 100%; max-width: 860px; margin: 0 auto; padding: 25px 20px;">
        <div class="dashboard-content">
            <header class="profile-page-heading" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 22px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <span class="profile-heading-avatar" aria-hidden="true">⚙</span>
                    <div>
                        <strong style="font-size: 1.25rem; color: #1e3a5f; display: block;">Pengaturan Akun</strong>
                        <small style="color: #64748b; font-size: 0.84rem;">Preferensi dan keamanan akun PATEN SPACE</small>
                    </div>
                </div>
                <a href="{{ route('account.profile') }}" class="profile-dashboard-link" style="padding: 9px 15px; font-size: 0.82rem;">
                    Kembali ke Pengaturan Akun
                </a>
            </header>

            <section class="dashboard-panel account-profile-form profile-settings-page" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div class="profile-form-section" style="margin-bottom: 24px; padding-bottom: 18px; border-bottom: 1px solid #f1f5f9;">
                    <h2 style="font-size: 1.05rem; color: #1e3a5f; margin: 0 0 14px; font-weight: 750;">Preferensi Tampilan</h2>
                    <div class="profile-preference-row" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; background: #f8fafc; border-radius: 8px; margin-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span class="profile-preference-icon" aria-hidden="true">◐</span>
                            <span><strong>Tema PATEN SPACE</strong><br><small style="color: #64748b;">Tampilan khaki dan biru tua digunakan agar konsisten dengan aplikasi.</small></span>
                        </div>
                        <span class="profile-preference-value" style="font-weight: 700; color: #796743; background: #eee8d7; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem;">Khaki</span>
                    </div>
                    <div class="profile-preference-row" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; background: #f8fafc; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span class="profile-preference-icon" aria-hidden="true">文</span>
                            <span><strong>Bahasa Sistem</strong><br><small style="color: #64748b;">Bahasa resmi yang digunakan pada seluruh formulir.</small></span>
                        </div>
                        <span class="profile-preference-value" style="font-weight: 700; color: #1e3a5f; background: #eff6ff; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem;">Indonesia</span>
                    </div>
                </div>

                <div class="profile-form-section profile-password-section">
                    <h2 style="font-size: 1.05rem; color: #1e3a5f; margin: 0 0 14px; font-weight: 750;">Keamanan Akun</h2>
                    <a href="{{ route('account.profile.password') }}" class="profile-preference-link" style="display: flex; align-items: center; justify-content: space-between; padding: 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; text-decoration: none; color: inherit; margin-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span class="profile-preference-icon" aria-hidden="true">🔒</span>
                            <span><strong>Ubah Password</strong><br><small style="color: #64748b;">Perbarui password untuk menjaga keamanan akun Anda.</small></span>
                        </div>
                        <b aria-hidden="true" style="color: #94a3b8;">›</b>
                    </a>
                    <a href="{{ route('account.profile.data') }}" class="profile-preference-link" style="display: flex; align-items: center; justify-content: space-between; padding: 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; text-decoration: none; color: inherit;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span class="profile-preference-icon" aria-hidden="true">✉</span>
                            <span><strong>Ubah Data Profil</strong><br><small style="color: #64748b;">Pastikan alamat email dan nomor telepon Anda selalu aktif.</small></span>
                        </div>
                        <b aria-hidden="true" style="color: #94a3b8;">›</b>
                    </a>
                </div>
            </section>
        </div>
    </main>
</div>
@endsection
