@extends('layouts.app')

@section('title', 'Registrasi PATEN')

@section('content')
@php
    $isCitizen = $isCitizen ?? false;
@endphp
<div class="app-shell form-page-shell">
    <aside class="sidebar">
        <div class="brand-wrap">
            <div class="brand-mark">P</div>
            <div class="brand-text">
                <div class="brand-title">PATEN SPACE</div>
                <div class="brand-sub">Kecamatan Jatisari</div>
            </div>
        </div>

        <button class="sidebar-toggle" type="button" aria-label="Tutup menu samping" aria-expanded="true">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="sidebar-nav">
            <div class="nav-group-label">Dashboard</div>
            <a href="{{ $isCitizen ? route('masyarakat.dashboard') : route('dashboard') }}" class="nav-item">Dashboard</a>

            <div class="nav-group-label">Layanan PATEN</div>
            <a href="{{ $isCitizen ? route('masyarakat.permohonan.create') : route('pendaftaran.create') }}" class="nav-item active">{{ $isCitizen ? 'Ajukan Permohonan' : 'Registrasi / Pendaftaran' }}</a>
            @unless($isCitizen)<a href="{{ route('pendaftaran.index') }}" class="nav-item">Daftar Permohonan</a>@endunless

            <div class="nav-group-label">Pengelolaan</div>
            <a href="#" class="nav-item">Data Pengguna</a>
            <a href="#" class="nav-item">Laporan</a>
            <a href="#" class="nav-item">Pengaturan</a>
        </nav>

        <a href="{{ route('account.profile') }}" class="sidebar-profile sidebar-profile-link" aria-label="Buka profil {{ auth()->user()->name }}">
            <span class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <span><strong>{{ auth()->user()->name }}</strong><small>{{ ucfirst(auth()->user()->role) }} PATEN</small></span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn" type="submit">Keluar</button>
        </form>
    </aside>

    <main class="main-panel form-page-main">
        <header class="topbar form-topbar">
            <div>
                <h1>{{ $isCitizen ? 'Ajukan Permohonan' : 'Registrasi / Pendaftaran' }}</h1>
                <p>{{ $isCitizen ? 'Isi formulir layanan dan unggah dokumen persyaratan.' : 'Form ini juga dapat digunakan untuk membantu warga yang belum memiliki ponsel atau belum terbiasa menggunakan gawai.' }}</p>
            </div>
            <div class="date-pill">{{ now()->translatedFormat('d F Y') }}</div>
        </header>

        @php
            $pendaftaran = $pendaftaran ?? new \App\Models\Pendaftaran();
            $isEdit = isset($pendaftaran->id);
            $actionUrl = $isEdit
                ? route('pendaftaran.update', $pendaftaran)
                : ($isCitizen ? route('masyarakat.permohonan.store') : route('pendaftaran.store'));
            $fixedService = $isEdit ? $pendaftaran->jenis_layanan : request('jenis_layanan');
            $hasFixedService = in_array($fixedService, ['PRR', 'KTP', 'KK', 'Akta Kematian', 'Akta Lahir', 'Kedatangan', 'Pindah'], true);
            $selectedService = old('jenis_layanan', $fixedService ?? 'KTP');
        @endphp

        <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" class="form-shell">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <section class="form-card form-head-card" data-role="informasi-pendaftar" {{ $selectedService == 'PRR' ? 'hidden' : '' }}>
                <div class="section-header form-section-header">
                    <h2>{{ $isEdit ? 'Edit Pendaftar' : 'Informasi Pendaftar' }}</h2>
                </div>

                <div class="form-grid">
                    @if ($hasFixedService)
                        <input type="hidden" name="jenis_layanan" value="{{ $selectedService }}">
                    @else
                        <div class="field-group">
                            <label for="jenis_layanan">Jenis Layanan</label>
                            <select id="jenis_layanan" name="jenis_layanan" required>
                                <option value="">Pilih layanan</option>
                                <option value="PRR" {{ $selectedService == 'PRR' ? 'selected' : '' }}>PRR / Pembuatan KTP Baru</option>
                                <option value="KTP" {{ $selectedService == 'KTP' ? 'selected' : '' }}>KTP / Pembaharuan (Hilang/Rusak)</option>
                                <option value="KK" {{ $selectedService == 'KK' ? 'selected' : '' }}>KK</option>
                                <option value="Akta Kematian" {{ $selectedService == 'Akta Kematian' ? 'selected' : '' }}>Akta Kematian</option>
                                <option value="Akta Lahir" {{ $selectedService == 'Akta Lahir' ? 'selected' : '' }}>Akta Lahir</option>
                                <option value="Kedatangan" {{ $selectedService == 'Kedatangan' ? 'selected' : '' }}>Kedatangan</option>
                                <option value="Pindah" {{ $selectedService == 'Pindah' ? 'selected' : '' }}>Pindah</option>
                            </select>
                        </div>
                    @endif
                    <div class="field-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input id="nama_lengkap" name="nama_lengkap" type="text" placeholder="Masukkan nama lengkap" value="{{ old('nama_lengkap', $pendaftaran->nama_lengkap ?? '') }}" required>
                    </div>
                    <div class="field-group">
                        <label for="nik">NIK</label>
                        <input id="nik" name="nik" type="text" placeholder="Masukkan NIK" value="{{ old('nik', $isCitizen ? $citizen->nik : ($pendaftaran->nik ?? '')) }}" {{ $isCitizen ? 'readonly' : '' }} required>
                    </div>
                    <div class="field-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input id="tempat_lahir" name="tempat_lahir" type="text" placeholder="Masukkan tempat lahir" value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir ?? '') }}">
                    </div>
                    <div class="field-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input id="tanggal_lahir" name="tanggal_lahir" type="text" placeholder="Contoh: 10-05-1998" value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir ?? '') }}">
                    </div>
                    <div class="field-group">
                        <label for="jalan">Jalan</label>
                        <input id="jalan" name="jalan" type="text" placeholder="Masukkan nama jalan" value="{{ old('jalan', $pendaftaran->jalan ?? '') }}">
                    </div>
                    <div class="field-group">
                        <label for="rt">RT</label>
                        <input id="rt" name="rt" type="text" placeholder="RT" value="{{ old('rt', $pendaftaran->rt ?? '') }}">
                    </div>
                    <div class="field-group">
                        <label for="rw">RW</label>
                        <input id="rw" name="rw" type="text" placeholder="RW" value="{{ old('rw', $pendaftaran->rw ?? '') }}">
                    </div>
                    <div class="field-group">
                        <label for="desa">Desa</label>
                        <select id="desa" name="desa">
                            <option value="">Pilih desa</option>
                            <option value="Barugbug" {{ old('desa', $pendaftaran->desa ?? '') == 'Barugbug' ? 'selected' : '' }}>Barugbug</option>
                            <option value="Situdam" {{ old('desa', $pendaftaran->desa ?? '') == 'Situdam' ? 'selected' : '' }}>Situdam</option>
                            <option value="Balonggandu" {{ old('desa', $pendaftaran->desa ?? '') == 'Balonggandu' ? 'selected' : '' }}>Balonggandu</option>
                            <option value="Kalijati" {{ old('desa', $pendaftaran->desa ?? '') == 'Kalijati' ? 'selected' : '' }}>Kalijati</option>
                            <option value="Jatisari" {{ old('desa', $pendaftaran->desa ?? '') == 'Jatisari' ? 'selected' : '' }}>Jatisari</option>
                            <option value="Mekarsari" {{ old('desa', $pendaftaran->desa ?? '') == 'Mekarsari' ? 'selected' : '' }}>Mekarsari</option>
                            <option value="Cirejag" {{ old('desa', $pendaftaran->desa ?? '') == 'Cirejag' ? 'selected' : '' }}>Cirejag</option>
                            <option value="Cikalongsari" {{ old('desa', $pendaftaran->desa ?? '') == 'Cikalongsari' ? 'selected' : '' }}>Cikalongsari</option>
                            <option value="Jatiragas" {{ old('desa', $pendaftaran->desa ?? '') == 'Jatiragas' ? 'selected' : '' }}>Jatiragas</option>
                            <option value="Jatiwangi" {{ old('desa', $pendaftaran->desa ?? '') == 'Jatiwangi' ? 'selected' : '' }}>Jatiwangi</option>
                            <option value="Jatibaru" {{ old('desa', $pendaftaran->desa ?? '') == 'Jatibaru' ? 'selected' : '' }}>Jatibaru</option>
                            <option value="Telarsari" {{ old('desa', $pendaftaran->desa ?? '') == 'Telarsari' ? 'selected' : '' }}>Telarsari</option>
                            <option value="Sukamekar" {{ old('desa', $pendaftaran->desa ?? '') == 'Sukamekar' ? 'selected' : '' }}>Sukamekar</option>
                            <option value="Pacing" {{ old('desa', $pendaftaran->desa ?? '') == 'Pacing' ? 'selected' : '' }}>Pacing</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label for="kecamatan">Kecamatan</label>
                        <input id="kecamatan" name="kecamatan" type="text" placeholder="Masukkan kecamatan" value="{{ old('kecamatan', $pendaftaran->kecamatan ?? '') }}">
                    </div>
                    <div class="field-group">
                        <label for="no_telepon">Nomor Telepon</label>
                        <input id="no_telepon" name="no_telepon" type="tel" placeholder="Masukkan nomor telepon" value="{{ old('no_telepon', $pendaftaran->no_telepon ?? '') }}">
                    </div>
                    @unless($isCitizen)
                    <div class="field-group">
                        <label for="petugas">Petugas</label>
                        <select id="petugas" name="petugas">
                            <option value="">Pilih petugas</option>
                            <option value="Andi Saputra" {{ old('petugas', $pendaftaran->petugas ?? '') == 'Andi Saputra' ? 'selected' : '' }}>Andi Saputra</option>
                            <option value="Siti Nurhaliza" {{ old('petugas', $pendaftaran->petugas ?? '') == 'Siti Nurhaliza' ? 'selected' : '' }}>Siti Nurhaliza</option>
                            <option value="Budi Kurniawan" {{ old('petugas', $pendaftaran->petugas ?? '') == 'Budi Kurniawan' ? 'selected' : '' }}>Budi Kurniawan</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label for="tanggal_daftar">Tanggal Pendaftaran</label>
                        <input id="tanggal_daftar" name="tanggal_daftar" type="date" value="{{ old('tanggal_daftar', $pendaftaran->tanggal_daftar ?? now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="field-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Baru" {{ old('status', $pendaftaran->status ?? 'Baru') == 'Baru' ? 'selected' : '' }}>Baru</option>
                            <option value="Proses" {{ old('status', $pendaftaran->status ?? 'Baru') == 'Proses' ? 'selected' : '' }}>Proses</option>
                            <option value="Selesai" {{ old('status', $pendaftaran->status ?? 'Baru') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Ditolak" {{ old('status', $pendaftaran->status ?? 'Baru') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="tanggal_daftar" value="{{ now()->format('Y-m-d') }}">
                    @endunless
                </div>
            </section>

            <section class="form-card form-service-card">
                <div class="section-header form-section-header">
                    <h2>Detail Layanan</h2>
                </div>

                <div class="service-section" data-service="PRR" {{ $selectedService == 'PRR' ? '' : 'hidden' }}>
                    <div class="form-grid compact-grid">
                        <div class="field-group"><label>Bulan</label><input type="text" name="bulan" value="{{ old('bulan', $pendaftaran->bulan ?? '') }}" placeholder="Contoh: JAN"></div>
                        <div class="field-group"><label>Minggu Ke</label><input type="text" name="minggu_ke" value="{{ old('minggu_ke', $pendaftaran->minggu_ke ?? '') }}" placeholder="Contoh: 1"></div>
                        <div class="field-group"><label>Tanggal Kedatangan</label><input type="date" name="tanggal_kedatangan" value="{{ old('tanggal_kedatangan', $pendaftaran->tanggal_kedatangan ?? '') }}"></div>
                        <div class="field-group"><label>Nama</label><input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftaran->nama_lengkap ?? '') }}" placeholder="Nama lengkap"></div>
                        <div class="field-group"><label>NIK</label><input type="text" name="nik" value="{{ old('nik', $isCitizen ? $citizen->nik : ($pendaftaran->nik ?? '')) }}" placeholder="NIK" {{ $isCitizen ? 'readonly' : '' }}></div>
                        <div class="field-group"><label>Tempat Lahir</label><input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir ?? '') }}" placeholder="Tempat lahir"></div>
                        <div class="field-group"><label>Tanggal Lahir</label><input type="text" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir ? \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->format('d-m-Y') : '') }}" placeholder="Contoh: 10-05-1998"></div>
                        <div class="field-group"><label>Jalan</label><input type="text" name="jalan" value="{{ old('jalan', $pendaftaran->jalan ?? '') }}" placeholder="Jalan"></div>
                        <div class="field-group"><label>RT</label><input type="text" name="rt" value="{{ old('rt', $pendaftaran->rt ?? '') }}" placeholder="RT"></div>
                        <div class="field-group"><label>RW</label><input type="text" name="rw" value="{{ old('rw', $pendaftaran->rw ?? '') }}" placeholder="RW"></div>
                        <div class="field-group">
                            <label>Desa</label>
                            <select name="desa">
                                <option value="">Pilih desa</option>
                                <option value="Barugbug" {{ old('desa', $pendaftaran->desa ?? '') == 'Barugbug' ? 'selected' : '' }}>Barugbug</option>
                                <option value="Situdam" {{ old('desa', $pendaftaran->desa ?? '') == 'Situdam' ? 'selected' : '' }}>Situdam</option>
                                <option value="Balonggandu" {{ old('desa', $pendaftaran->desa ?? '') == 'Balonggandu' ? 'selected' : '' }}>Balonggandu</option>
                                <option value="Kalijati" {{ old('desa', $pendaftaran->desa ?? '') == 'Kalijati' ? 'selected' : '' }}>Kalijati</option>
                                <option value="Jatisari" {{ old('desa', $pendaftaran->desa ?? '') == 'Jatisari' ? 'selected' : '' }}>Jatisari</option>
                                <option value="Mekarsari" {{ old('desa', $pendaftaran->desa ?? '') == 'Mekarsari' ? 'selected' : '' }}>Mekarsari</option>
                                <option value="Cirejag" {{ old('desa', $pendaftaran->desa ?? '') == 'Cirejag' ? 'selected' : '' }}>Cirejag</option>
                                <option value="Cikalongsari" {{ old('desa', $pendaftaran->desa ?? '') == 'Cikalongsari' ? 'selected' : '' }}>Cikalongsari</option>
                                <option value="Jatiragas" {{ old('desa', $pendaftaran->desa ?? '') == 'Jatiragas' ? 'selected' : '' }}>Jatiragas</option>
                                <option value="Jatiwangi" {{ old('desa', $pendaftaran->desa ?? '') == 'Jatiwangi' ? 'selected' : '' }}>Jatiwangi</option>
                                <option value="Jatibaru" {{ old('desa', $pendaftaran->desa ?? '') == 'Jatibaru' ? 'selected' : '' }}>Jatibaru</option>
                                <option value="Telarsari" {{ old('desa', $pendaftaran->desa ?? '') == 'Telarsari' ? 'selected' : '' }}>Telarsari</option>
                                <option value="Sukamekar" {{ old('desa', $pendaftaran->desa ?? '') == 'Sukamekar' ? 'selected' : '' }}>Sukamekar</option>
                                <option value="Pacing" {{ old('desa', $pendaftaran->desa ?? '') == 'Pacing' ? 'selected' : '' }}>Pacing</option>
                            </select>
                        </div>
                        @unless($isCitizen)
                        <div class="field-group"><label>Status</label>
                            <select name="status">
                                <option value="Baru" {{ old('status', $pendaftaran->status ?? 'Baru') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Proses" {{ old('status', $pendaftaran->status ?? 'Baru') == 'Proses' ? 'selected' : '' }}>Proses</option>
                                <option value="Selesai" {{ old('status', $pendaftaran->status ?? 'Baru') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Ditolak" {{ old('status', $pendaftaran->status ?? 'Baru') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        @endunless
                        <div class="field-group"><label>No HP</label><input type="text" name="no_telepon" value="{{ old('no_telepon', $pendaftaran->no_telepon ?? '') }}" placeholder="Nomor HP"></div>
                    </div>
                </div>

                <div class="service-section" data-service="KTP" {{ $selectedService == 'KTP' ? '' : 'hidden' }}>
                    <div class="form-grid compact-grid">
                        <div class="field-group"><label>Nomor KK</label><input type="text" name="nomor_kk" value="{{ old('nomor_kk', $pendaftaran->nomor_kk ?? '') }}" placeholder="Nomor KK"></div>
                        <div class="field-group"><label>Jenis Kelamin</label>
                            <select name="jenis_kelamin">
                                <option value="">Pilih</option>
                                <option value="L" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="field-group"><label>Tempat Lahir</label><input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir ?? '') }}" placeholder="Tempat lahir"></div>
                        <div class="field-group"><label>Tanggal Lahir</label><input type="text" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir ? \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->format('d-m-Y') : '') }}" placeholder="Contoh: 10-05-1998"></div>
                        <div class="field-group"><label>Jalan</label><input type="text" name="jalan" value="{{ old('jalan', $pendaftaran->jalan ?? '') }}" placeholder="Jalan"></div>
                        <div class="field-group"><label>RT</label><input type="text" name="rt" value="{{ old('rt', $pendaftaran->rt ?? '') }}" placeholder="RT"></div>
                        <div class="field-group"><label>RW</label><input type="text" name="rw" value="{{ old('rw', $pendaftaran->rw ?? '') }}" placeholder="RW"></div>
                        <div class="field-group"><label>Desa</label><input type="text" name="desa" value="{{ old('desa', $pendaftaran->desa ?? '') }}" placeholder="Desa"></div>
                        <div class="field-group"><label>Alasan Pembaruan</label>
                            <select name="alasan_pembaruan">
                                <option value="">Pilih alasan</option>
                                @foreach (['Hilang', 'Rusak', 'Update Data'] as $reason)
                                    <option value="{{ $reason }}" {{ old('alasan_pembaruan', $pendaftaran->alasan_pembaruan ?? '') === $reason ? 'selected' : '' }}>{{ $reason }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group"><label>No HP</label><input type="text" name="no_telepon" value="{{ old('no_telepon', $pendaftaran->no_telepon ?? '') }}" placeholder="Nomor HP"></div>
                    </div>
                </div>

                <div class="service-section" data-service="KK" {{ $selectedService == 'KK' ? '' : 'hidden' }}>
                    <div class="form-grid compact-grid">
                        <div class="field-group"><label>Bulan</label><input type="text" name="bulan" value="{{ old('bulan', $pendaftaran->bulan ?? '') }}" placeholder="Contoh: JAN"></div>
                        <div class="field-group"><label>Minggu Ke</label><input type="text" name="minggu_ke" value="{{ old('minggu_ke', $pendaftaran->minggu_ke ?? '') }}" placeholder="Contoh: 1"></div>
                        <div class="field-group"><label>Nama</label><input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftaran->nama_lengkap ?? '') }}" placeholder="Nama lengkap"></div>
                        <div class="field-group"><label>NIK</label><input type="text" name="nik" value="{{ old('nik', $pendaftaran->nik ?? '') }}" placeholder="NIK"></div>
                        <div class="field-group"><label>Jalan</label><input type="text" name="jalan" value="{{ old('jalan', $pendaftaran->jalan ?? '') }}" placeholder="Jalan"></div>
                        <div class="field-group"><label>RT</label><input type="text" name="rt" value="{{ old('rt', $pendaftaran->rt ?? '') }}" placeholder="RT"></div>
                        <div class="field-group"><label>RW</label><input type="text" name="rw" value="{{ old('rw', $pendaftaran->rw ?? '') }}" placeholder="RW"></div>
                        <div class="field-group"><label>Desa</label><input type="text" name="desa" value="{{ old('desa', $pendaftaran->desa ?? '') }}" placeholder="Desa"></div>
                        <div class="field-group"><label>Alasan</label><input type="text" name="keterangan_kk" value="{{ old('keterangan_kk', $pendaftaran->keterangan_kk ?? '') }}" placeholder="Alasan"></div>
                    </div>
                </div>

                <div class="service-section" data-service="Akta Kematian" {{ $selectedService == 'Akta Kematian' ? '' : 'hidden' }}>
                    <div class="form-grid compact-grid">
                        <div class="field-group"><label>Bulan</label><input type="text" name="bulan" value="{{ old('bulan', $pendaftaran->bulan ?? '') }}" placeholder="Contoh: JAN"></div>
                        <div class="field-group"><label>Minggu Ke</label><input type="text" name="minggu_ke" value="{{ old('minggu_ke', $pendaftaran->minggu_ke ?? '') }}" placeholder="Contoh: 1"></div>
                        <div class="field-group"><label>Nama Almarhum</label><input type="text" name="nama_alm" value="{{ old('nama_alm', $pendaftaran->nama_alm ?? '') }}" placeholder="Nama almarhum"></div>
                        <div class="field-group"><label>Nama Ayah</label><input type="text" name="nama_ayah" value="{{ old('nama_ayah', $pendaftaran->nama_ayah ?? '') }}" placeholder="Nama ayah"></div>
                        <div class="field-group"><label>Nama Ibu</label><input type="text" name="nama_ibu" value="{{ old('nama_ibu', $pendaftaran->nama_ibu ?? '') }}" placeholder="Nama ibu"></div>
                        <div class="field-group"><label>Tempat Meninggal</label><input type="text" name="tempat_meninggal" value="{{ old('tempat_meninggal', $pendaftaran->tempat_meninggal ?? '') }}" placeholder="Tempat meninggal"></div>
                        <div class="field-group"><label>Tanggal Meninggal</label><input type="date" name="tanggal_meninggal" value="{{ old('tanggal_meninggal', $pendaftaran->tanggal_meninggal ?? '') }}"></div>
                        <div class="field-group"><label>Alamat</label><input type="text" name="alamat" value="{{ old('alamat', $pendaftaran->alamat ?? '') }}" placeholder="Alamat lengkap"></div>
                        <div class="field-group"><label>Keterangan</label><input type="text" name="keterangan_kematian" value="{{ old('keterangan_kematian', $pendaftaran->keterangan_kematian ?? '') }}" placeholder="Keterangan"></div>
                    </div>
                </div>

                <div class="service-section" data-service="Akta Lahir" {{ $selectedService == 'Akta Lahir' ? '' : 'hidden' }}>
                    <div class="form-grid compact-grid">
                        <div class="field-group"><label>Bulan</label><input type="text" name="bulan" value="{{ old('bulan', $pendaftaran->bulan ?? '') }}" placeholder="Contoh: JAN"></div>
                        <div class="field-group"><label>Minggu Ke</label><input type="text" name="minggu_ke" value="{{ old('minggu_ke', $pendaftaran->minggu_ke ?? '') }}" placeholder="Contoh: 1"></div>
                        <div class="field-group"><label>Nama Anak</label><input type="text" name="nama_anak_lahir" value="{{ old('nama_anak_lahir', $pendaftaran->nama_anak_lahir ?? '') }}" placeholder="Nama anak"></div>
                        <div class="field-group"><label>Nama Ayah</label><input type="text" name="nama_ayah_lahir" value="{{ old('nama_ayah_lahir', $pendaftaran->nama_ayah_lahir ?? '') }}" placeholder="Nama ayah"></div>
                        <div class="field-group"><label>Nama Ibu</label><input type="text" name="nama_ibu_lahir" value="{{ old('nama_ibu_lahir', $pendaftaran->nama_ibu_lahir ?? '') }}" placeholder="Nama ibu"></div>
                        <div class="field-group"><label>Berat Badan</label><input type="text" name="berat_badan" value="{{ old('berat_badan', $pendaftaran->berat_badan ?? '') }}" placeholder="Contoh: 3kg"></div>
                        <div class="field-group"><label>Panjang Badan</label><input type="text" name="panjang_badan" value="{{ old('panjang_badan', $pendaftaran->panjang_badan ?? '') }}" placeholder="Contoh: 49cm"></div>
                        <div class="field-group"><label>Tempat Lahir</label><input type="text" name="tempat_lahir_detail" value="{{ old('tempat_lahir_detail', $pendaftaran->tempat_lahir_detail ?? '') }}" placeholder="Tempat lahir"></div>
                        <div class="field-group"><label>Alamat</label><input type="text" name="alamat" value="{{ old('alamat', $pendaftaran->alamat ?? '') }}" placeholder="Alamat lengkap"></div>
                    </div>
                </div>

                <div class="service-section" data-service="Kedatangan" {{ $selectedService == 'Kedatangan' ? '' : 'hidden' }}>
                    <div class="form-grid compact-grid">
                        <div class="field-group"><label>Bulan</label><input type="text" name="bulan" value="{{ old('bulan', $pendaftaran->bulan ?? '') }}" placeholder="Contoh: JAN"></div>
                        <div class="field-group"><label>Minggu Ke</label><input type="text" name="minggu_ke" value="{{ old('minggu_ke', $pendaftaran->minggu_ke ?? '') }}" placeholder="Contoh: 1"></div>
                        <div class="field-group"><label>Tanggal Kedatangan</label><input type="date" name="tanggal_kedatangan" value="{{ old('tanggal_kedatangan', $pendaftaran->tanggal_kedatangan ?? '') }}"></div>
                        <div class="field-group"><label>Nama</label><input type="text" name="nama_kedatangan" value="{{ old('nama_kedatangan', $pendaftaran->nama_kedatangan ?? '') }}" placeholder="Nama pendatang"></div>
                        <div class="field-group"><label>NIK</label><input type="text" name="nik_kedatangan" value="{{ old('nik_kedatangan', $pendaftaran->nik_kedatangan ?? '') }}" placeholder="NIK"></div>
                        <div class="field-group"><label>Alamat Asal</label><input type="text" name="alamat_asal_kedatangan" value="{{ old('alamat_asal_kedatangan', $pendaftaran->alamat_asal_kedatangan ?? '') }}" placeholder="Alamat asal"></div>
                        <div class="field-group"><label>Alamat Tujuan</label><input type="text" name="alamat_tujuan_kedatangan" value="{{ old('alamat_tujuan_kedatangan', $pendaftaran->alamat_tujuan_kedatangan ?? '') }}" placeholder="Alamat tujuan"></div>
                    </div>
                </div>

                <div class="service-section" data-service="Pindah" {{ $selectedService == 'Pindah' ? '' : 'hidden' }}>
                    <div class="form-grid compact-grid">
                        <div class="field-group"><label>Bulan</label><input type="text" name="bulan" value="{{ old('bulan', $pendaftaran->bulan ?? '') }}" placeholder="Contoh: JAN"></div>
                        <div class="field-group"><label>Minggu Ke</label><input type="text" name="minggu_ke" value="{{ old('minggu_ke', $pendaftaran->minggu_ke ?? '') }}" placeholder="Contoh: 1"></div>
                        <div class="field-group"><label>Nama</label><input type="text" name="nama_pindah" value="{{ old('nama_pindah', $pendaftaran->nama_pindah ?? '') }}" placeholder="Nama pemohon"></div>
                        <div class="field-group"><label>NIK</label><input type="text" name="nik_pindah" value="{{ old('nik_pindah', $pendaftaran->nik_pindah ?? '') }}" placeholder="NIK"></div>
                        <div class="field-group"><label>Alamat Asal</label><input type="text" name="alamat_asal_pindah" value="{{ old('alamat_asal_pindah', $pendaftaran->alamat_asal_pindah ?? '') }}" placeholder="Alamat asal"></div>
                        <div class="field-group"><label>Alamat Tujuan</label><input type="text" name="alamat_tujuan_pindah" value="{{ old('alamat_tujuan_pindah', $pendaftaran->alamat_tujuan_pindah ?? '') }}" placeholder="Alamat tujuan"></div>
                        <div class="field-group"><label>Alasan</label><input type="text" name="alasan_pindah" value="{{ old('alasan_pindah', $pendaftaran->alasan_pindah ?? '') }}" placeholder="Alasan pindah"></div>
                    </div>
                </div>
            </section>

            <section class="form-card">
                <div class="section-header form-section-header">
                    <h2>Dokumen Persyaratan</h2>
                </div>
                <p>Unggah hingga 5 berkas gambar atau PDF, maksimal 5 MB per berkas. Di ponsel, tombol kamera dapat digunakan untuk memotret dokumen.</p>
                <div class="form-grid compact-grid">
                    <div class="field-group">
                        <label for="documents-camera">Foto dengan kamera</label>
                        <input id="documents-camera" type="file" name="documents[]" accept="image/*" capture="environment" multiple>
                    </div>
                    <div class="field-group">
                        <label for="documents-scan">Pilih hasil scan / PDF</label>
                        <input id="documents-scan" type="file" name="documents[]" accept="image/*,.pdf" multiple>
                    </div>
                </div>
                @error('documents')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                @error('documents.*')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </section>

            <div class="action-row">
                <button type="button" class="btn btn-secondary" onclick="window.location='{{ $isCitizen ? route('masyarakat.dashboard') : route('dashboard') }}'">Batal</button>
                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Data' : ($isCitizen ? 'Kirim Permohonan' : 'Simpan Data') }}</button>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const serviceSelect = document.getElementById('jenis_layanan');
                const serviceSections = document.querySelectorAll('.service-section');
                const sidebar = document.querySelector('.form-page-shell .sidebar');
                const sidebarToggle = document.querySelector('.form-page-shell .sidebar-toggle');

                if (sidebar && sidebarToggle) {
                    sidebarToggle.addEventListener('click', function () {
                        const isCollapsed = sidebar.classList.toggle('collapsed');
                        sidebarToggle.setAttribute('aria-expanded', String(!isCollapsed));
                        sidebarToggle.setAttribute('aria-label', isCollapsed ? 'Buka menu samping' : 'Tutup menu samping');
                    });
                }

                function updateServiceSections() {
                    const selected = serviceSelect.value || 'KTP';
                    const infoCard = document.querySelector('[data-role="informasi-pendaftar"]');

                    serviceSections.forEach(function (section) {
                        const show = section.dataset.service === selected;
                        section.hidden = !show;
                        section.querySelectorAll('input, select, textarea').forEach(function (field) {
                            field.disabled = !show;
                        });
                    });

                    if (infoCard) {
                        infoCard.hidden = selected === 'PRR';
                        infoCard.querySelectorAll('input, select, textarea').forEach(function (field) {
                            field.disabled = selected === 'PRR'
                                && field.name !== 'jenis_layanan'
                                && field.name !== 'tanggal_daftar';
                        });
                    }
                }

                if (serviceSelect) {
                    serviceSelect.addEventListener('change', updateServiceSections);
                    updateServiceSections();
                }
            });
        </script>
    </main>
</div>
@endsection
