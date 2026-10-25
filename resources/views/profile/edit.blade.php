@extends('layouts.app')

@section('title', 'Profil Akun - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page account-profile-page">
    <aside class="sidebar dark-sidebar">
        <a href="{{ route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang" class="brand-mark">
            <span class="brand-text"><span class="brand-title">PATEN SPACE</span><span class="brand-sub">Pelayanan Kecamatan</span></span>
        </a>
        <nav class="sidebar-nav" aria-label="Menu utama">
            <div class="nav-group-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item"><span aria-hidden="true">◫</span> Dashboard</a>
            @if ($user->role === 'petugas')
                <a href="{{ route('pendaftaran.create') }}" class="nav-item"><span aria-hidden="true">＋</span> Pendaftaran Baru</a>
                <a href="{{ route('petugas.permohonan.index') }}" class="nav-item"><span aria-hidden="true">▣</span> Daftar Permohonan</a>
                <a href="{{ route('pendaftaran.index') }}" class="nav-item"><span aria-hidden="true">▥</span> Semua Data</a>
                <a href="{{ route('laporan') }}" class="nav-item"><span aria-hidden="true">▤</span> Data dan Laporan</a>
            @endif
        </nav>
        <a href="{{ route('account.profile') }}" class="sidebar-profile sidebar-profile-link is-active" aria-current="page" aria-label="Profil {{ $user->name }}">
            <span class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            <span><strong>{{ $user->name }}</strong><small>{{ ucfirst($user->role) }} PATEN</small></span>
        </a>
    </aside>

    <main class="main-panel">
        <div class="dashboard-content">
            <header class="profile-page-heading">
                <span class="profile-heading-avatar" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="3.5"></circle>
                        <path d="M5 20a7 7 0 0 1 14 0"></path>
                    </svg>
                </span>
                <span><strong>Profil Saya</strong><small>Kelola informasi akun Anda</small></span>
                <a href="{{ route('dashboard') }}" class="profile-dashboard-link">Kembali ke Dashboard</a>
            </header>

            @if (session('success'))
                <div class="profile-success" role="status">{{ session('success') }}</div>
            @endif

            <section class="profile-overview-grid" aria-label="Ringkasan akun">
                <article class="dashboard-panel profile-identity-card">
                    <div class="profile-avatar-large" aria-hidden="true">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="profile-identity-copy">
                        <h1>{{ $user->name }}</h1>
                        <p>{{ ucfirst($user->role) }} PATEN</p>
                        <span class="profile-active-badge"><span></span> Aktif</span>
                        <div class="profile-contact-list">
                            <span><b aria-hidden="true">✉</b>{{ $user->email }}</span>
                            <span><b aria-hidden="true">☎</b>{{ $user->phone ?: 'Nomor telepon belum diisi' }}</span>
                            <span><b aria-hidden="true">⌖</b>Kecamatan Jatisari, Karawang</span>
                        </div>
                    </div>
                </article>

                <aside class="dashboard-panel profile-settings-card" aria-label="Pengaturan akun">
                    <h2>
                        <span class="profile-settings-heading-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="m19.4 15 .1.1 1.1.9-1.5 2.6-1.4-.5a7.7 7.7 0 0 1-1.5.9l-.3 1.5h-3l-.3-1.5a7.7 7.7 0 0 1-1.5-.9l-1.4.5-1.5-2.6 1.1-.9a7 7 0 0 1 0-1.8l-1.1-.9 1.5-2.6 1.4.5a7.7 7.7 0 0 1 1.5-.9l.3-1.5h3l.3 1.5a7.7 7.7 0 0 1 1.5.9l1.4-.5 1.5 2.6-1.1.9a7 7 0 0 1 0 1.8Z"></path>
                            </svg>
                        </span>
                        Pengaturan Akun
                    </h2>
                    <a href="{{ route('account.profile.data') }}">
                        <span class="profile-menu-icon profile-menu-icon--data" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 20a7 7 0 0 1 14 0M19 4v5M16.5 6.5h5"></path></svg></span>
                        <span class="profile-menu-label">Ubah Data Profil</span><b aria-hidden="true">›</b>
                    </a>
                    <a href="{{ route('account.profile.password') }}">
                        <span class="profile-menu-icon profile-menu-icon--password" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="10" width="14" height="11" rx="2"></rect><path d="M8 10V7a4 4 0 1 1 8 0v3M12 14v3"></path></svg></span>
                        <span class="profile-menu-label">Ubah Password</span><b aria-hidden="true">›</b>
                    </a>
                    <a href="{{ route('account.profile.settings') }}">
                        <span class="profile-menu-icon profile-menu-icon--settings" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="m19.4 15 .1.1 1.1.9-1.5 2.6-1.4-.5a7.7 7.7 0 0 1-1.5.9l-.3 1.5h-3l-.3-1.5a7.7 7.7 0 0 1-1.5-.9l-1.4.5-1.5-2.6 1.1-.9a7 7 0 0 1 0-1.8l-1.1-.9 1.5-2.6 1.4.5a7.7 7.7 0 0 1 1.5-.9l.3-1.5h3l.3 1.5a7.7 7.7 0 0 1 1.5.9l1.4-.5 1.5 2.6-1.1.9a7 7 0 0 1 0 1.8Z"></path></svg></span>
                        <span class="profile-menu-label">Pengaturan</span><b aria-hidden="true">›</b>
                    </a>
                    @if ($user->role === 'petugas')
                        <a href="{{ route('account.users.create') }}">
                            <span class="profile-menu-icon profile-menu-icon--create" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0M19 8v8M15 12h8"></path></svg></span>
                            <span class="profile-menu-label">Buat Akun Baru</span><b aria-hidden="true">›</b>
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}">
                        <span class="profile-menu-icon profile-menu-icon--dashboard" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7h-6v7H4a1 1 0 0 1-1-1z"></path></svg></span>
                        <span class="profile-menu-label">Kembali ke Dashboard</span><b aria-hidden="true">›</b>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">
                            <span class="profile-menu-icon profile-menu-icon--logout" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5M15 12H3"></path><path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"></path></svg></span>
                            <span class="profile-menu-label">Keluar</span><b aria-hidden="true">›</b>
                        </button>
                    </form>
                </aside>
            </section>
        </div>
    </main>
</div>
@endsection
