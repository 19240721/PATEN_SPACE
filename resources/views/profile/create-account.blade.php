@extends('layouts.app')

@section('title', 'Buat Akun Baru - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page account-profile-page">
    <main class="main-panel">
        <div class="dashboard-content">
            <header class="profile-page-heading">
                <span class="profile-heading-avatar" aria-hidden="true">＋</span>
                <span><strong>Buat Akun Baru</strong><small>Tambahkan akun masyarakat atau petugas</small></span>
                <a href="{{ route('account.profile') }}" class="profile-dashboard-link">Kembali ke Profil</a>
            </header>

            @if (session('success'))
                <div class="profile-success" role="status">{{ session('success') }}</div>
            @endif

            <form action="{{ route('account.users.store') }}" method="POST" class="dashboard-panel account-profile-form">
                @csrf
                <section class="profile-form-section">
                    <h2>Informasi Akun</h2>
                    <div class="profile-fields">
                        <div class="field-group">
                            <label for="name">Nama Lengkap</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required>
                            @error('name')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-group">
                            <label for="role">Jenis Akun</label>
                            <select id="role" name="role" required>
                                <option value="">Pilih jenis akun</option>
                                <option value="masyarakat" {{ old('role') === 'masyarakat' ? 'selected' : '' }}>Masyarakat</option>
                                <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                            </select>
                            @error('role')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-group">
                            <label for="nik">NIK</label>
                            <input id="nik" name="nik" type="text" inputmode="numeric" maxlength="16" value="{{ old('nik') }}" required>
                            @error('nik')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-group">
                            <label for="phone">Nomor HP</label>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" required>
                            @error('phone')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                            @error('email')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </section>

                <section class="profile-form-section profile-password-section">
                    <h2>Kata Sandi Awal</h2>
                    <div class="profile-fields">
                        <div class="field-group">
                            <label for="password">Kata Sandi</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" required>
                            @error('password')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-group">
                            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                        </div>
                    </div>
                </section>

                <div class="profile-form-actions">
                    <a href="{{ route('account.profile') }}" class="profile-back-link">Batal</a>
                    <button type="submit" class="dashboard-primary-link">Buat Akun</button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
