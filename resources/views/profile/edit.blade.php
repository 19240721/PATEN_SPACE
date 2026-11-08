@extends('layouts.app')

@section('title', 'Pengaturan Akun - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page account-profile-page">
    <aside class="sidebar dark-sidebar">
        <a href="{{ auth()->user()->role === 'masyarakat' ? route('masyarakat.dashboard') : route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang" class="brand-mark">
            <span class="brand-text"><span class="brand-title">PATEN SPACE</span><span class="brand-sub">Pelayanan Kecamatan</span></span>
        </a>
        <nav class="sidebar-nav" aria-label="Menu utama">
            <div class="nav-group-label">Menu Utama</div>
            @if (auth()->user()->role === 'masyarakat')
                <a href="{{ route('masyarakat.dashboard') }}" class="nav-item"><span aria-hidden="true">🏠</span> Beranda</a>
                <a href="{{ route('masyarakat.permohonan.create') }}" class="nav-item"><span aria-hidden="true">📝</span> Ajukan Permohonan</a>
                <a href="{{ route('masyarakat.riwayat') }}" class="nav-item"><span aria-hidden="true">📋</span> Riwayat Pelayanan</a>
            @elseif (auth()->user()->role === 'camat')
                <a href="{{ route('camat.dashboard') }}" class="nav-item"><span aria-hidden="true">◫</span> Dashboard</a>
                <a href="{{ route('camat.monitoring') }}" class="nav-item"><span aria-hidden="true">▣</span> Monitoring Pelayanan</a>
                <a href="{{ route('camat.statistik') }}" class="nav-item"><span aria-hidden="true">📈</span> Statistik</a>
                <a href="{{ route('camat.laporan') }}" class="nav-item"><span aria-hidden="true">▥</span> Laporan</a>
                <a href="{{ route('camat.aktivitas') }}" class="nav-item"><span aria-hidden="true">⏱</span> Aktivitas Pelayanan</a>
            @else
                <a href="{{ route('dashboard') }}" class="nav-item"><span aria-hidden="true">◫</span> Dashboard</a>
            @endif
        </nav>
        {{-- Identitas Pengguna di Bawah Sidebar (Akses ke Profil) --}}
        <a href="{{ route('profil') }}" class="sidebar-profile sidebar-profile-link" aria-label="Buka profil {{ $user->name }}">
            @if ($user->foto_profil_url)
                <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" class="profile-avatar" style="object-fit: cover;">
            @else
                <span class="profile-avatar">{{ $user->inisial }}</span>
            @endif
            <span>
                <strong>{{ $user->name }}</strong>
                <small>
                    @if ($user->role === 'camat')
                        Pimpinan / Camat
                    @elseif ($user->role === 'masyarakat')
                        Warga Pemohon
                    @elseif ($user->peran_layanan === 'Operator')
                        Operator Kecamatan
                    @else
                        Petugas Loket
                    @endif
                </small>
            </span>
        </a>
    </aside>

    <main class="main-panel">
        <div class="dashboard-content">
            <header class="profile-page-heading">
                <span class="profile-heading-avatar" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="m19.4 15 .1.1 1.1.9-1.5 2.6-1.4-.5a7.7 7.7 0 0 1-1.5.9l-.3 1.5h-3l-.3-1.5a7.7 7.7 0 0 1-1.5-.9l-1.4.5-1.5-2.6 1.1-.9a7 7 0 0 1 0-1.8l-1.1-.9 1.5-2.6 1.4.5a7.7 7.7 0 0 1 1.5-.9l.3-1.5h3l.3 1.5a7.7 7.7 0 0 1 1.5.9l1.4-.5 1.5 2.6-1.1.9a7 7 0 0 1 0 1.8Z"></path>
                    </svg>
                </span>
                <span><strong>Profil Saya</strong><small>Pengaturan akun, email, kontak, dan keamanan Anda</small></span>
                <a href="{{ route('profil') }}" class="profile-dashboard-link">Kembali ke Profil</a>
            </header>

            @if (session('success'))
                <div class="profile-success" role="status">{{ session('success') }}</div>
            @endif

            <section class="profile-overview-grid" aria-label="Ringkasan akun">
                <article class="dashboard-panel profile-identity-card">
                    @if ($user->foto_profil_url)
                        <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" class="profile-avatar-large" style="object-fit: cover; border: 3px solid #eee8d7;">
                    @else
                        <div class="profile-avatar-large" aria-hidden="true">{{ $user->inisial }}</div>
                    @endif
                    <div class="profile-identity-copy">
                        <h1>{{ $user->name }}</h1>
                        <p>
                            @if ($user->role === 'camat')
                                Pimpinan / Camat
                            @elseif ($user->role === 'masyarakat')
                                Masyarakat (Warga Pemohon)
                            @elseif ($user->peran_layanan === 'Operator')
                                Operator Kecamatan
                            @else
                                Petugas Loket
                            @endif
                        </p>
                        <span class="profile-active-badge"><span></span> Akun Aktif</span>
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

                    {{-- 1. Ubah Data Profil (Email & Kontak) --}}
                    <a href="{{ route('account.profile.data') }}">
                        <span class="profile-menu-icon profile-menu-icon--data" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                        </span>
                        <span class="profile-menu-label">Ubah Data Profil</span><b aria-hidden="true">›</b>
                    </a>

                    {{-- 2. Ubah Password --}}
                    <a href="{{ route('account.profile.password') }}">
                        <span class="profile-menu-icon profile-menu-icon--password" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="10" width="14" height="11" rx="2"></rect>
                                <path d="M8 10V7a4 4 0 1 1 8 0v3M12 14v3"></path>
                            </svg>
                        </span>
                        <span class="profile-menu-label">Ubah Password</span><b aria-hidden="true">›</b>
                    </a>

                    {{-- 3. Pengaturan Akun --}}
                    <a href="{{ route('account.profile.settings') }}">
                        <span class="profile-menu-icon profile-menu-icon--settings" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="m19.4 15 .1.1 1.1.9-1.5 2.6-1.4-.5a7.7 7.7 0 0 1-1.5.9l-.3 1.5h-3l-.3-1.5a7.7 7.7 0 0 1-1.5-.9l-1.4.5-1.5-2.6 1.1-.9a7 7 0 0 1 0-1.8l-1.1-.9 1.5-2.6 1.4.5a7.7 7.7 0 0 1 1.5-.9l.3-1.5h3l.3 1.5a7.7 7.7 0 0 1 1.5.9l1.4-.5 1.5 2.6-1.1.9a7 7 0 0 1 0 1.8Z"></path>
                            </svg>
                        </span>
                        <span class="profile-menu-label">Pengaturan Akun</span><b aria-hidden="true">›</b>
                    </a>

                    {{-- 4. Kembali ke Profil --}}
                    <a href="{{ route('profil') }}">
                        <span class="profile-menu-icon profile-menu-icon--dashboard" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="3.5"></circle>
                                <path d="M5 20a7 7 0 0 1 14 0"></path>
                            </svg>
                        </span>
                        <span class="profile-menu-label">Kembali ke Profil</span><b aria-hidden="true">›</b>
                    </a>

                    {{-- 5. Keluar (Logout dengan Modal Konfirmasi Kustom) --}}
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0;">
                        @csrf
                        <button type="button" class="btn-trigger-logout" onclick="openLogoutModal()">
                            <span class="profile-menu-icon profile-menu-icon--logout" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 17l5-5-5-5M15 12H3"></path>
                                    <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"></path>
                                </svg>
                            </span>
                            <span class="profile-menu-label">Keluar</span><b aria-hidden="true">›</b>
                        </button>
                    </form>
                </aside>
            </section>
        </div>
    </main>
</div>
@endsection
