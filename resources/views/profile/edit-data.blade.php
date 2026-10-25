@extends('layouts.app')

@section('title', 'Ubah Data Profil - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page account-profile-page">
    <main class="main-panel">
        <div class="dashboard-content">
            <header class="profile-page-heading">
                <span class="profile-heading-avatar" aria-hidden="true">▤</span>
                <span><strong>Ubah Data Profil</strong><small>Perbarui informasi akun Anda</small></span>
                <a href="{{ route('account.profile') }}" class="profile-dashboard-link">Kembali ke Profil</a>
            </header>

            <form action="{{ route('account.profile.data.update') }}" method="POST" class="dashboard-panel account-profile-form">
                @csrf
                @method('PUT')
                <section class="profile-form-section">
                    <h2>Data Profil</h2>
                    <div class="profile-fields">
                        <div class="field-group">
                            <label for="name">Nama Lengkap</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" autocomplete="name" required>
                            @error('name')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-group">
                            <label for="nik">NIK</label>
                            <input id="nik" name="nik" type="text" inputmode="numeric" maxlength="16" value="{{ old('nik', $user->nik) }}" required>
                            @error('nik')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" autocomplete="email" required>
                            @error('email')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-group">
                            <label for="phone">Nomor HP</label>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" autocomplete="tel" required>
                            @error('phone')<span class="profile-field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </section>
                <div class="profile-form-actions">
                    <a href="{{ route('account.profile') }}" class="profile-back-link">Batal</a>
                    <button type="submit" class="dashboard-primary-link">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
