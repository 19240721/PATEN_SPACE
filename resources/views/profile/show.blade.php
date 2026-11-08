@extends('layouts.app')

@section('title', 'Profil Pengguna - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page account-profile-page">
    {{-- SIDEBAR --}}
    <aside class="sidebar dark-sidebar">
        <a href="{{ auth()->user()->role === 'masyarakat' ? route('masyarakat.dashboard') : route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang" class="brand-mark">
            <span class="brand-text">
                <span class="brand-title">PATEN SPACE</span>
                <span class="brand-sub">Pelayanan Kecamatan</span>
            </span>
        </a>

        <nav class="sidebar-nav" aria-label="Menu utama">
            <div class="nav-group-label">Menu Utama</div>
            @if (auth()->user()->role === 'masyarakat')
                <a href="{{ route('masyarakat.dashboard') }}" class="nav-item"><span aria-hidden="true">🏠</span> Beranda</a>
                <a href="{{ route('masyarakat.permohonan.create') }}" class="nav-item"><span aria-hidden="true">📝</span> Ajukan Permohonan</a>
                <a href="{{ route('masyarakat.riwayat') }}" class="nav-item"><span aria-hidden="true">📋</span> Riwayat Pelayanan</a>
            @else
                <a href="{{ route('dashboard') }}" class="nav-item"><span aria-hidden="true">◫</span> Dashboard</a>
                <a href="{{ route('pendaftaran.create') }}" class="nav-item"><span aria-hidden="true">＋</span> Pendaftaran Baru</a>
                <a href="{{ route('petugas.permohonan.index') }}" class="nav-item"><span aria-hidden="true">▣</span> Daftar Permohonan</a>
                <a href="{{ route('pendaftaran.index') }}" class="nav-item"><span aria-hidden="true">▥</span> Semua Data</a>
                <a href="{{ route('laporan') }}" class="nav-item"><span aria-hidden="true">▤</span> Data dan Laporan</a>
            @endif
        </nav>

        {{-- Identitas Pengguna di Bagian Bawah Sidebar (Dapat Diklik ke Profil) --}}
        <a href="{{ route('profil') }}" class="sidebar-profile sidebar-profile-link is-active" aria-current="page" aria-label="Profil {{ $user->name }}">
            @if ($user->foto_profil_url)
                <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" class="profile-avatar" style="object-fit: cover;">
            @else
                <span class="profile-avatar">{{ $user->inisial }}</span>
            @endif
            <span>
                <strong>{{ $user->name }}</strong>
                <small>
                    @if ($user->role === 'masyarakat')
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

    {{-- MAIN CONTENT --}}
    <main class="main-panel">
        <div class="dashboard-content" style="max-width: 980px; margin: 0 auto;">
            {{-- HEADER HALAMAN PROFIL --}}
            <header class="profile-page-heading" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 22px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <span class="profile-heading-avatar" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="3.5"></circle>
                            <path d="M5 20a7 7 0 0 1 14 0"></path>
                        </svg>
                    </span>
                    <div>
                        <strong style="font-size: 1.25rem; color: #1e3a5f; display: block;">Profil Pengguna</strong>
                        <small style="color: #64748b; font-size: 0.84rem;">Informasi identitas resmi akun dalam sistem PATEN SPACE</small>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <a href="{{ route('account.profile') }}" class="dashboard-primary-link" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; font-size: 0.82rem; font-weight: 700; border-radius: 8px;">
                        <span>⚙</span> Pengaturan Akun
                    </a>
                    <a href="{{ auth()->user()->role === 'masyarakat' ? route('masyarakat.dashboard') : route('dashboard') }}" class="profile-dashboard-link" style="padding: 9px 15px; font-size: 0.82rem;">
                        Kembali ke {{ auth()->user()->role === 'masyarakat' ? 'Beranda' : 'Dashboard' }}
                    </a>
                </div>
            </header>

            @if (session('success'))
                <div class="profile-success" role="status" style="margin-bottom: 20px;">{{ session('success') }}</div>
            @endif

            {{-- KARTU RINGKASAN IDENTITAS --}}
            <section class="dashboard-panel" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; margin-bottom: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 18px;">
                        @if ($user->foto_profil_url)
                            <img src="{{ $user->foto_profil_url }}"
                                 alt="{{ $user->name }}"
                                 style="width: 82px; height: 82px; min-width: 82px; border-radius: 50%; object-fit: cover; border: 3px solid #eee8d7; box-shadow: 0 4px 14px rgba(121, 103, 67, 0.18);">
                        @else
                            <div style="width: 82px; height: 82px; min-width: 82px; border-radius: 50%; background: linear-gradient(135deg, #796743, #5c4728); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.7rem; font-weight: 800; box-shadow: 0 4px 14px rgba(121, 103, 67, 0.18);">
                                {{ $user->inisial }}
                            </div>
                        @endif

                        <div>
                            <h1 style="margin: 0 0 4px; font-size: 1.35rem; color: #1e3a5f; font-weight: 750;">{{ $user->name }}</h1>
                            <div style="font-size: 0.86rem; color: #64748b; font-weight: 600; margin-bottom: 8px;">
                                @if ($user->role === 'masyarakat')
                                    Masyarakat (Warga Pemohon Layanan)
                                @elseif ($user->peran_layanan === 'Operator')
                                    Operator Kecamatan (Pengelola Pelayanan Utama)
                                @else
                                    Petugas Loket (Pendamping Pelayanan)
                                @endif
                            </div>
                            <span class="profile-active-badge" style="display: inline-flex; align-items: center; gap: 5px; background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; padding: 3px 10px; border-radius: 20px; font-size: 0.74rem; font-weight: 700;">
                                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #22c55e;"></span>
                                Akun Aktif
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('account.profile.data') }}" class="report-filter-button" style="text-decoration: none; padding: 9px 15px; font-size: 0.8rem;">
                            ✎ Ubah Email & Kontak
                        </a>
                    </div>
                </div>
            </section>

            {{-- KARTU RINCIAN DATA PROFIL LENGKAP --}}
            <section class="dashboard-panel" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; margin-bottom: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <h2 style="margin: 0 0 4px; font-size: 1.08rem; color: #1e3a5f; font-weight: 750;">Rincian Informasi Akun</h2>
                    <p style="margin: 0; font-size: 0.82rem; color: #64748b;">Informasi kepegawaian, kontak, dan penempatan resmi pengguna</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                    {{-- Nama Lengkap --}}
                    <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                        <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Nama Lengkap</div>
                        <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">{{ $user->name }}</div>
                    </div>

                    {{-- Peran / Jabatan --}}
                    <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                        <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Peran / Jabatan Sistem</div>
                        <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">
                            @if ($user->role === 'masyarakat')
                                Masyarakat
                            @elseif ($user->peran_layanan === 'Operator')
                                Operator Kecamatan
                            @else
                                Petugas Loket
                            @endif
                        </div>
                    </div>

                    @if ($user->role === 'masyarakat')
                        {{-- NIK Masyarakat --}}
                        <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                            <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Nomor Induk Kependudukan (NIK)</div>
                            <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">{{ $user->nik ?: '—' }}</div>
                        </div>

                        {{-- Wilayah --}}
                        <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                            <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Wilayah Pelayanan</div>
                            <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">Kecamatan Jatisari, Kabupaten Karawang</div>
                        </div>
                    @else
                        {{-- Instansi --}}
                        <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                            <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Instansi</div>
                            <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">Pemerintah Kabupaten Karawang</div>
                        </div>

                        {{-- Wilayah Kerja --}}
                        <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                            <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Kecamatan / Wilayah Penugasan</div>
                            <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">Kecamatan Jatisari</div>
                        </div>
                    @endif

                    {{-- Email Akun --}}
                    <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                        <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Alamat Email</div>
                        <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">{{ $user->email }}</div>
                    </div>

                    {{-- Nomor Telepon --}}
                    <div style="padding: 13px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                        <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">Nomor Telepon / WhatsApp</div>
                        <div style="font-size: 0.92rem; font-weight: 700; color: #1e3a5f;">{{ $user->phone ?: 'Belum diisi' }}</div>
                    </div>
                </div>

                {{-- Catatan Keamanan Identitas --}}
                <div style="margin-top: 18px; padding: 12px 16px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; font-size: 0.78rem; color: #64748b; line-height: 1.5;">
                    <span style="font-weight: 700; color: #334155;">🔒 Catatan Perlindungan Data:</span> Data nama lengkap, NIK, jabatan, instansi, dan wilayah penugasan merupakan data resmi sistem yang tidak dapat diubah mandiri. Jika Anda perlu memperbarui alamat email, nomor telepon, foto profil, atau password login, silakan gunakan menu <strong>Pengaturan Akun</strong>.
                </div>
            </section>
        </div>
    </main>
</div>
@endsection
