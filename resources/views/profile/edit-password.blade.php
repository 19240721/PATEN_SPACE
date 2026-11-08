@extends('layouts.app')

@section('title', 'Ubah Password - PATEN SPACE')

@section('content')
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
@endsection
