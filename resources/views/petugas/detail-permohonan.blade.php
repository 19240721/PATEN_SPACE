@extends('layouts.app')

@section('title', 'Detail Permohonan - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page">
    <aside class="sidebar dark-sidebar">
        <button class="sidebar-toggle" type="button" aria-label="Buka atau tutup sidebar" aria-expanded="true">
            <span></span><span></span><span></span>
        </button>
        <a href="{{ route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang" class="brand-mark">
            <span class="brand-text"><span class="brand-title">PATEN SPACE</span><span class="brand-sub">Pelayanan Kecamatan</span></span>
        </a>
        <nav class="sidebar-nav">
            <div class="nav-group-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item"><span>◫</span> Dashboard</a>
            <a href="{{ route('pendaftaran.create') }}" class="nav-item"><span>＋</span> Pendaftaran Baru</a>
            <a href="{{ route('petugas.permohonan.index') }}" class="nav-item active">
                <span>▣</span> Daftar Permohonan
                @if(($newCitizenCount ?? 0) > 0)
                    <span class="nav-counter">{{ $newCitizenCount }}</span>
                @endif
            </a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item"><span>▥</span> Semua Data</a>
            <a href="{{ route('laporan') }}" class="nav-item"><span>▥</span> Data dan Laporan</a>
            <details class="service-menu" open>
                <summary class="nav-item service-menu-toggle"><span>▤</span> Jenis Layanan <span class="menu-chevron">⌄</span></summary>
                <div class="service-submenu">
                    @foreach ($services as $service)
                        <a href="{{ route('pendaftaran.create', ['jenis_layanan' => $service['type']]) }}" class="nav-item service-nav-item">{{ $service['title'] }}</a>
                    @endforeach
                </div>
            </details>
        </nav>
        <a href="{{ route('account.profile') }}" class="sidebar-profile sidebar-profile-link" aria-label="Buka profil {{ auth()->user()->name }}">
            <span class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <span><strong>{{ auth()->user()->name }}</strong><small>Operator Kecamatan</small></span>
        </a>
    </aside>

    <main class="main-panel">
        <header class="dashboard-topbar">
            <div><strong>Kecamatan Jatisari</strong><span>Kabupaten Karawang</span></div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="report-filter-button">Keluar</button>
            </form>
        </header>

        <div class="dashboard-content">
            <section class="dashboard-heading" style="margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <a href="{{ route('petugas.permohonan.index') }}" aria-label="Kembali" title="Kembali ke Daftar Permohonan"
                       style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; background: #e1d8c2; color: #3f3523; font-size: 18px; font-weight: bold; border: 1px solid #c7b991; transition: all .15s ease;">
                        ←
                    </a>
                    <div>
                        <h1 style="font-size: 1.75rem; margin: 0 0 4px; color: #17334c;">Detail Permohonan</h1>
                        <p style="margin: 0; color: #5b6f84; font-size: 0.88rem;">ID #{{ $pendaftaran->id }} — Pelayanan {{ $pendaftaran->jenis_layanan }}</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <a href="{{ route('petugas.permohonan.formulir', $pendaftaran) }}" target="_blank"
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; background: #796743; color: #ffffff; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 700; box-shadow: 0 2px 4px rgba(121, 103, 67, 0.2); transition: background .15s ease;">
                        <span>🖨️</span> Cetak Formulir
                    </a>
                    <div style="padding: 9px 14px; border: 1px solid #d9e4ee; border-radius: 8px; background: #fff; color: #53677e; font-size: 13px; font-weight: 600;">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </section>

            @if(session('success'))
                <div style="background: #e8f5e9; border: 1px solid #a5d6a7; color: #1b5e20; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start;">

                {{-- Panel Kiri: Detail Data & Lampiran Dokumen --}}
                <div style="display: flex; flex-direction: column; gap: 20px;">

                    {{-- Lampiran Berkas & Foto Dokumen Persyaratan (Khusus untuk memeriksa kelengkapan online) --}}
                    <section class="dashboard-panel" style="padding: 24px;">
                        <div class="panel-heading" style="margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                            <div>
                                <h2 style="margin: 0; font-size: 1.15rem; color: #17334c; display: flex; align-items: center; gap: 8px;">
                                    <span>📸</span> Lampiran Berkas & Foto Dokumen
                                </h2>
                                <p style="margin: 3px 0 0; font-size: 0.8rem; color: #6b7c93;">
                                    Foto berkas persyaratan yang diunggah pemohon secara online (KTP, KK, Surat Pengantar, dsb.)
                                </p>
                            </div>
                            @if(!empty($pendaftaran->documents))
                                <span style="background: #e8e2d2; color: #5b4b2d; padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">
                                    {{ count($pendaftaran->documents) }} Berkas Diunggah
                                </span>
                            @endif
                        </div>

                        @if(!empty($pendaftaran->documents))
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 16px;">
                                @foreach($pendaftaran->documents as $idx => $doc)
                                    @php
                                        $ext = strtolower(pathinfo($doc['original_name'] ?? $doc['path'], PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp'], true);
                                        $previewUrl = route('pendaftaran.document', [$pendaftaran, $idx, 'preview' => 1]);
                                        $downloadUrl = route('pendaftaran.document', [$pendaftaran, $idx]);
                                        $fileName = $doc['original_name'] ?? ('Dokumen '.($idx+1));
                                    @endphp

                                    <div class="doc-card" style="border: 1px solid #e2dac7; border-radius: 10px; overflow: hidden; background: #faf9f6; display: flex; flex-direction: column; transition: all .15s ease;">
                                        {{-- Area Gambar / Dokumen --}}
                                        <div style="height: 160px; background: #1c1917; position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; cursor: pointer;"
                                             onclick="{{ $isImage ? "openImageModal('{$previewUrl}', '{$fileName}')" : "window.open('{$previewUrl}', '_blank')" }}"
                                             title="{{ $isImage ? 'Klik untuk memperbesar foto' : 'Klik untuk membuka dokumen PDF' }}">
                                            @if($isImage)
                                                <img src="{{ $previewUrl }}"
                                                     alt="{{ $fileName }}"
                                                     style="width: 100%; height: 100%; object-fit: cover; transition: transform .25s ease;"
                                                     loading="lazy"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div style="display: none; position: absolute; inset: 0; align-items: center; justify-content: center; color: #d6d3d1; font-size: 12px;">
                                                    Foto gagal dimuat
                                                </div>
                                                <div class="hover-overlay" style="position: absolute; inset: 0; background: rgba(0,0,0,0.45); opacity: 0; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 13px; font-weight: 700; gap: 6px; transition: opacity .18s ease;">
                                                    <span>🔍</span> Perbesar Foto
                                                </div>
                                            @else
                                                <div style="text-align: center; color: #fff; padding: 12px;">
                                                    <div style="font-size: 38px; line-height: 1; margin-bottom: 6px;">📄</div>
                                                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; background: #e11d48; padding: 2px 8px; border-radius: 4px; display: inline-block;">
                                                        Berkas PDF
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Informasi & Tombol --}}
                                        <div style="padding: 12px; display: flex; flex-direction: column; gap: 8px; flex: 1; justify-content: space-between;">
                                            <div>
                                                <div style="font-size: 13px; font-weight: 700; color: #17334c; word-break: break-word; line-height: 1.3;" title="{{ $fileName }}">
                                                    {{ $fileName }}
                                                </div>
                                                <div style="font-size: 11px; color: #6b7c93; margin-top: 3px;">
                                                    Berkas #{{ $idx + 1 }} • {{ strtoupper($ext) }}
                                                </div>
                                            </div>

                                            <div style="display: flex; gap: 6px; margin-top: 4px;">
                                                @if($isImage)
                                                    <button type="button"
                                                            onclick="openImageModal('{{ $previewUrl }}', '{{ $fileName }}')"
                                                            style="flex: 1; padding: 6px 8px; background: #796743; color: #fff; border: none; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                                                        <span>🔍</span> Lihat
                                                    </button>
                                                @else
                                                    <a href="{{ $previewUrl }}" target="_blank"
                                                       style="flex: 1; padding: 6px 8px; background: #796743; color: #fff; border: none; border-radius: 6px; font-size: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                                                        <span>↗</span> Buka
                                                    </a>
                                                @endif
                                                <a href="{{ $downloadUrl }}"
                                                   style="padding: 6px 10px; background: #e1d8c2; color: #3f3523; border-radius: 6px; font-size: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;"
                                                   title="Unduh berkas asli">
                                                    ⬇
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @if($pendaftaran->intake_source === 'masyarakat')
                                <div style="background: #fffbeb; border: 1px dashed #f59e0b; border-radius: 8px; padding: 22px; text-align: center;">
                                    <div style="font-size: 2.2rem; margin-bottom: 6px;">⚠️</div>
                                    <h3 style="margin: 0 0 4px; font-size: 1rem; color: #92400e; font-weight: 700;">Tidak Ada Lampiran Foto / Dokumen</h3>
                                    <p style="margin: 0 auto; max-width: 520px; font-size: 0.85rem; color: #b45309; line-height: 1.4;">
                                        Pemohon mendaftar secara daring (online) namun tidak melampirkan foto dokumen persyaratan. Petugas dapat menolak permohonan atau meminta pemohon melengkapi berkas jika diperlukan.
                                    </p>
                                </div>
                            @else
                                <div style="background: #faf9f6; border: 1px dashed #d1c9b6; border-radius: 8px; padding: 18px; text-align: center; color: #78716c;">
                                    <span style="font-size: 1.5rem; display: block; margin-bottom: 4px;">📋</span>
                                    <p style="margin: 0; font-size: 0.85rem;">
                                        Pendaftaran langsung di loket (tatap muka / berkas fisik diperiksa manual oleh petugas loket).
                                    </p>
                                </div>
                            @endif
                        @endif
                    </section>

                    {{-- Informasi Permohonan --}}
                    <section class="dashboard-panel" style="padding: 24px;">
                        <div class="panel-heading" style="margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <h2 style="margin: 0; font-size: 1.15rem; color: #17334c;">Informasi Pemohon & Data Formulir</h2>
                                <p style="margin: 3px 0 0; font-size: 0.8rem; color: #6b7c93;">Rincian data permohonan yang tercatat di sistem</p>
                            </div>
                            @php $status = $pendaftaran->status ?? 'Baru'; @endphp
                            @if($status === 'Selesai')
                                <span class="status-pill selesai" style="font-size: 0.82rem; padding: 6px 14px;">{{ $status }}</span>
                            @elseif($status === 'Proses')
                                <span class="status-pill proses" style="font-size: 0.82rem; padding: 6px 14px;">{{ $status }}</span>
                            @elseif($status === 'Ditolak')
                                <span class="status-pill" style="font-size: 0.82rem; padding: 6px 14px; background: #fee2e2; color: #991b1b;">{{ $status }}</span>
                            @else
                                <span class="status-pill" style="font-size: 0.82rem; padding: 6px 14px; background: #e8e2d2; color: #5b4b2d;">{{ $status }}</span>
                            @endif
                        </div>

                        @php
                            $fields = [
                                'Jenis Layanan'   => $pendaftaran->jenis_layanan,
                                'Tanggal Daftar'  => $pendaftaran->tanggal_daftar ? \Illuminate\Support\Facades\Date::parse($pendaftaran->tanggal_daftar)->format('d-m-Y') : null,
                                'Sumber Pendaftaran' => $pendaftaran->intake_source === 'masyarakat' ? 'Online / Mandiri (Masyarakat)' : 'Input Langsung Petugas Loket',
                                'Nama Lengkap'    => $pendaftaran->nama_lengkap,
                                'NIK'             => $pendaftaran->nik,
                                'Jenis Kelamin'   => $pendaftaran->jenis_kelamin,
                                'Tempat Lahir'    => $pendaftaran->tempat_lahir,
                                'Tanggal Lahir'   => $pendaftaran->tanggal_lahir ? \Illuminate\Support\Facades\Date::parse($pendaftaran->tanggal_lahir)->format('d-m-Y') : null,
                                'Alamat'          => $pendaftaran->alamat,
                                'Jalan'           => $pendaftaran->jalan,
                                'RT'              => $pendaftaran->rt,
                                'RW'              => $pendaftaran->rw,
                                'Desa'            => $pendaftaran->desa,
                                'Kecamatan'       => $pendaftaran->kecamatan,
                                'Dusun'           => $pendaftaran->dusun,
                                'No. Telepon'     => $pendaftaran->no_telepon,
                                'Nama KK'         => $pendaftaran->nama_kepala_keluarga,
                                'Nomor KK'        => $pendaftaran->nomor_kk,
                                'Nama Kepala Keluarga' => $pendaftaran->kepala_keluarga,
                                'Hubungan'        => $pendaftaran->hubungan,
                                'Jumlah Anggota'  => $pendaftaran->jumlah_anggota,
                                'Nama Anak'       => $pendaftaran->nama_anak,
                                'Nik Anak'        => $pendaftaran->nik_anak,
                                'Keterangan KK'   => $pendaftaran->keterangan_kk,
                                'Nama Almarhum'   => $pendaftaran->nama_alm,
                                'Nama Ayah'       => $pendaftaran->nama_ayah,
                                'NIK Ayah'        => $pendaftaran->nik_ayah,
                                'Nama Ibu'        => $pendaftaran->nama_ibu,
                                'NIK Ibu'         => $pendaftaran->nik_ibu,
                                'Tempat Meninggal'=> $pendaftaran->tempat_meninggal,
                                'Tgl Meninggal'   => $pendaftaran->tanggal_meninggal ? \Illuminate\Support\Facades\Date::parse($pendaftaran->tanggal_meninggal)->format('d-m-Y') : null,
                                'Keterangan Kematian' => $pendaftaran->keterangan_kematian,
                                'Nama Anak Lahir' => $pendaftaran->nama_anak_lahir,
                                'Nama Ayah Lahir' => $pendaftaran->nama_ayah_lahir,
                                'Nama Ibu Lahir'  => $pendaftaran->nama_ibu_lahir,
                                'Nama Pelapor'    => $pendaftaran->nama_pelapor,
                                'NIK Pelapor'     => $pendaftaran->nik_pelapor,
                                'Nama Saksi 1'    => $pendaftaran->nama_saksi1,
                                'NIK Saksi 1'     => $pendaftaran->nik_saksi1,
                                'Nama Saksi 2'    => $pendaftaran->nama_saksi2,
                                'NIK Saksi 2'     => $pendaftaran->nik_saksi2,
                                'Berat Badan'     => $pendaftaran->berat_badan,
                                'Panjang Badan'   => $pendaftaran->panjang_badan,
                                'Tempat Lahir Detail' => $pendaftaran->tempat_lahir_detail,
                                'Nama Kedatangan' => $pendaftaran->nama_kedatangan,
                                'NIK Kedatangan'  => $pendaftaran->nik_kedatangan,
                                'Alamat Asal Kedatangan'  => $pendaftaran->alamat_asal_kedatangan,
                                'Alamat Tujuan Kedatangan'=> $pendaftaran->alamat_tujuan_kedatangan,
                                'Ket. Kedatangan' => $pendaftaran->keterangan_kedatangan,
                                'Nama Pindah'     => $pendaftaran->nama_pindah,
                                'NIK Pindah'      => $pendaftaran->nik_pindah,
                                'Alamat Asal Pindah'  => $pendaftaran->alamat_asal_pindah,
                                'Alamat Tujuan Pindah'=> $pendaftaran->alamat_tujuan_pindah,
                                'Alasan Pindah'   => $pendaftaran->alasan_pindah,
                                'Alasan Permohonan' => $pendaftaran->alasan_permohonan,
                                'Alasan Pembaruan'  => $pendaftaran->alasan_pembaruan,
                                'Bulan'           => $pendaftaran->bulan,
                                'Minggu Ke'       => $pendaftaran->minggu_ke,
                                'Tanggal Kedatangan' => $pendaftaran->tanggal_kedatangan ? \Illuminate\Support\Facades\Date::parse($pendaftaran->tanggal_kedatangan)->format('d-m-Y') : null,
                                'Keterangan'      => $pendaftaran->keterangan,
                                'Petugas Input'   => $pendaftaran->petugas,
                            ];
                        @endphp

                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; min-width: 0;">
                                <tbody>
                                    @foreach($fields as $label => $value)
                                        @if(!is_null($value) && $value !== '')
                                            <tr style="border-bottom: 1px solid #f0ede4;">
                                                <td style="padding: 10px 6px; color: #6b7c93; font-size: 13px; width: 38%; vertical-align: top; font-weight: 500;">
                                                    {{ $label }}
                                                </td>
                                                <td style="padding: 10px 6px; font-size: 14px; font-weight: 600; color: #17334c;">
                                                    {{ $value }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {{-- Daftar Anggota Keluarga (Khusus KK) --}}
                    @if($pendaftaran->jenis_layanan === 'KK' && !empty($pendaftaran->anggota_kk))
                        <section class="dashboard-panel" style="padding: 24px;">
                            <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 1.1rem;">👨‍👩‍👧‍👦</span>
                                <div>
                                    <h2 style="margin: 0; font-size: 1.1rem; color: #17334c;">Daftar Anggota Keluarga</h2>
                                    <p style="margin: 2px 0 0; font-size: 0.8rem; color: #6b7c93;">{{ count($pendaftaran->anggota_kk) }} anggota tercatat</p>
                                </div>
                            </div>
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
                                    <thead>
                                        <tr style="background: #f5f2eb;">
                                            <th style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #5b4b2d; font-weight: 700; text-align: center; width: 44px;">No</th>
                                            <th style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #5b4b2d; font-weight: 700; text-align: left;">Nama Lengkap</th>
                                            <th style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #5b4b2d; font-weight: 700; text-align: left;">NIK</th>
                                            <th style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #5b4b2d; font-weight: 700; text-align: left;">SHDK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendaftaran->anggota_kk as $i => $anggota)
                                        <tr style="border-bottom: 1px solid #f0ede4;">
                                            <td style="padding: 9px 12px; border: 1px solid #e1d8c2; text-align: center; color: #8898aa; font-weight: 600;">{{ $i + 1 }}</td>
                                            <td style="padding: 9px 12px; border: 1px solid #e1d8c2; font-weight: 600; color: #17334c;">{{ $anggota['nama'] ?? '-' }}</td>
                                            <td style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #2d3748; font-family: monospace;">{{ $anggota['nik'] ?? '-' }}</td>
                                            <td style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #2d3748;">{{ $anggota['shdk'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif

                    {{-- Perubahan Data KK (Pendidikan, Pekerjaan, Agama, dll) --}}
                    @if($pendaftaran->jenis_layanan === 'KK' && !empty($pendaftaran->perubahan_kk))
                        <section class="dashboard-panel" style="padding: 24px;">
                            <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 1.1rem;">📝</span>
                                <div>
                                    <h2 style="margin: 0; font-size: 1.1rem; color: #17334c;">Perubahan Data</h2>
                                    <p style="margin: 2px 0 0; font-size: 0.8rem; color: #6b7c93;">Pendidikan, pekerjaan, agama, status perkawinan, dll</p>
                                </div>
                            </div>
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
                                    <thead>
                                        <tr style="background: #f5f2eb;">
                                            <th style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #5b4b2d; font-weight: 700; text-align: center; width: 44px;">No</th>
                                            <th style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #5b4b2d; font-weight: 700; text-align: left;">Semula</th>
                                            <th style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #5b4b2d; font-weight: 700; text-align: left;">Menjadi</th>
                                            <th style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #5b4b2d; font-weight: 700; text-align: left;">Dasar Perubahan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendaftaran->perubahan_kk as $i => $perubahan)
                                        <tr style="border-bottom: 1px solid #f0ede4;">
                                            <td style="padding: 9px 12px; border: 1px solid #e1d8c2; text-align: center; color: #8898aa; font-weight: 600;">{{ $i + 1 }}</td>
                                            <td style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #17334c;">{{ $perubahan['semula'] ?? '-' }}</td>
                                            <td style="padding: 9px 12px; border: 1px solid #e1d8c2; font-weight: 600; color: #17334c;">{{ $perubahan['menjadi'] ?? '-' }}</td>
                                            <td style="padding: 9px 12px; border: 1px solid #e1d8c2; color: #2d3748;">{{ $perubahan['dasar'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif

                    {{-- Alasan Penolakan (Jika ada) --}}
                    @if($pendaftaran->status === 'Ditolak' && $pendaftaran->alasan_penolakan)
                        <section class="dashboard-panel" style="padding: 20px; background: #fef2f2; border: 1px solid #fecaca;">
                            <div style="font-weight: 700; color: #991b1b; margin-bottom: 6px; font-size: 0.95rem; display: flex; align-items: center; gap: 6px;">
                                <span>❌</span> Alasan Penolakan
                            </div>
                            <p style="margin: 0; color: #7f1d1d; font-size: 14px; line-height: 1.5;">{{ $pendaftaran->alasan_penolakan }}</p>
                        </section>
                    @endif

                    {{-- Catatan Petugas (Jika ada) --}}
                    @if($pendaftaran->catatan_petugas)
                        <section class="dashboard-panel" style="padding: 20px; background: #f5f2eb; border: 1px solid #dfd7c5;">
                            <div style="font-weight: 700; color: #5b4b2d; margin-bottom: 6px; font-size: 0.95rem; display: flex; align-items: center; gap: 6px;">
                                <span>📝</span> Catatan Petugas
                            </div>
                            <p style="margin: 0; color: #44371f; font-size: 14px; line-height: 1.5;">{{ $pendaftaran->catatan_petugas }}</p>
                        </section>
                    @endif
                </div>

                {{-- Panel Kanan: Form Update Status & Verifikasi Berkas --}}
                <div>
                    <section class="dashboard-panel" style="padding: 24px; position: sticky; top: 84px;">
                        <div class="panel-heading" style="margin-bottom: 18px;">
                            <h2 style="margin: 0; font-size: 1.15rem; color: #17334c;">Update & Tindak Lanjut</h2>
                            <p style="margin: 3px 0 0; font-size: 0.8rem; color: #6b7c93;">Verifikasi kelengkapan foto/berkas pemohon</p>
                        </div>

                        <form action="{{ route('petugas.permohonan.status', $pendaftaran) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div style="margin-bottom: 16px;">
                                <label for="status" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px; color: #263f55;">
                                    Status Permohonan
                                </label>
                                <select id="status" name="status" required
                                        style="width: 100%; padding: 10px 12px; border: 1px solid #d1c9b6; border-radius: 6px; font-size: 14px; background: #fff; color: #17334c;"
                                        onchange="toggleAlasan(this.value)">
                                    <option value="Baru"    {{ old('status', $pendaftaran->status) === 'Baru'    ? 'selected' : '' }}>Baru</option>
                                    <option value="Proses"  {{ old('status', $pendaftaran->status) === 'Proses'  ? 'selected' : '' }}>Proses (Berkas Lengkap & Valid)</option>
                                    <option value="Selesai" {{ old('status', $pendaftaran->status) === 'Selesai' ? 'selected' : '' }}>Selesai (Sudah Diterbitkan)</option>
                                    <option value="Ditolak" {{ old('status', $pendaftaran->status) === 'Ditolak' ? 'selected' : '' }}>Ditolak (Berkas Kurang / Tidak Sesuai)</option>
                                </select>
                            </div>

                            <div id="alasan-section" style="margin-bottom: 16px; {{ old('status', $pendaftaran->status) === 'Ditolak' ? '' : 'display:none;' }}">
                                <label for="alasan_penolakan" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px; color: #263f55;">
                                    Alasan Penolakan <span style="color: #ef4444;">*</span>
                                </label>

                                {{-- Preset tombol cepat untuk alasan penolakan berkas online --}}
                                <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 8px;">
                                    <button type="button" class="preset-alasan-btn" onclick="pilihAlasan('Foto dokumen buram / tidak terbaca dengan jelas')">
                                        📷 Foto Buram
                                    </button>
                                    <button type="button" class="preset-alasan-btn" onclick="pilihAlasan('Dokumen persyaratan belum lengkap (kurang KTP / Kartu Keluarga)')">
                                        📄 Berkas Kurang
                                    </button>
                                    <button type="button" class="preset-alasan-btn" onclick="pilihAlasan('NIK atau data pemohon tidak sesuai dengan lampiran')">
                                        🆔 Data Tidak Sesuai
                                    </button>
                                    <button type="button" class="preset-alasan-btn" onclick="pilihAlasan('Surat Pengantar dari Desa / RT / RW belum dilampirkan')">
                                        🏠 Kurang Surat Desa
                                    </button>
                                </div>

                                <textarea id="alasan_penolakan" name="alasan_penolakan" rows="4"
                                          style="width: 100%; padding: 10px 12px; border: 1px solid #d1c9b6; border-radius: 6px; font-size: 14px; resize: vertical; box-sizing: border-box;"
                                          placeholder="Tuliskan kekurangan berkas atau alasan penolakan...">{{ old('alasan_penolakan', $pendaftaran->alasan_penolakan) }}</textarea>
                                @error('alasan_penolakan')
                                    <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div style="margin-bottom: 18px;">
                                <label for="catatan_petugas" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px; color: #263f55;">
                                    Catatan Tambahan Petugas
                                </label>
                                <textarea id="catatan_petugas" name="catatan_petugas" rows="3"
                                          style="width: 100%; padding: 10px 12px; border: 1px solid #d1c9b6; border-radius: 6px; font-size: 14px; resize: vertical; box-sizing: border-box;"
                                          placeholder="Catatan tambahan untuk pemohon atau arsip (opsional)...">{{ old('catatan_petugas', $pendaftaran->catatan_petugas) }}</textarea>
                                @error('catatan_petugas')
                                    <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0;">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    style="width: 100%; padding: 12px; background: #796743; color: #fff; border: none; border-radius: 6px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background .15s ease;">
                                Simpan Perubahan Status
                            </button>
                        </form>

                        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e8e2d2;">

                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <a href="{{ route('petugas.permohonan.formulir', $pendaftaran) }}" target="_blank"
                               style="display: block; text-align: center; padding: 10px; background: #b45309; color: #fff; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 700;">
                                🖨️ Cetak Formulir Permohonan
                            </a>
                            <a href="{{ route('petugas.permohonan.index') }}"
                               style="display: block; text-align: center; padding: 10px; background: #e8e2d2; color: #3f3523; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                ← Kembali ke Daftar Permohonan
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    {{-- Lightbox Modal untuk Preview Foto Dokumen --}}
    <div id="image-modal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;" onclick="closeModalOnBackdrop(event)">
        <div style="position: relative; max-width: 90vw; max-height: 90vh; display: flex; flex-direction: column; align-items: center;">
            <div style="width: 100%; display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; gap: 10px;">
                <span id="modal-caption" style="color: #f5f5f5; font-size: 14px; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <a id="modal-newtab-btn" href="#" target="_blank" style="color: #fff; background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 6px; font-size: 12px; text-decoration: none; font-weight: 600;">
                        ↗ Buka di Tab Baru
                    </a>
                    <button type="button" onclick="closeImageModal()" style="background: #e11d48; color: #fff; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 16px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        ✕
                    </button>
                </div>
            </div>
            <img id="modal-img" src="" alt="Preview Dokumen" style="max-width: 90vw; max-height: 80vh; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); object-fit: contain; background: #111;">
        </div>
    </div>

    <style>
        .doc-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .doc-card:hover .hover-overlay {
            opacity: 1 !important;
        }
        .preset-alasan-btn {
            background: #f5f2eb;
            border: 1px solid #c7b991;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 600;
            color: #5b4b2d;
            cursor: pointer;
            transition: all .15s ease;
        }
        .preset-alasan-btn:hover {
            background: #e1d8c2;
            color: #3f3523;
        }
    </style>

    <script>
        function toggleAlasan(value) {
            const section = document.getElementById('alasan-section');
            const textarea = document.getElementById('alasan_penolakan');
            if (value === 'Ditolak') {
                section.style.display = 'block';
                textarea.required = true;
            } else {
                section.style.display = 'none';
                textarea.required = false;
            }
        }

        function pilihAlasan(teks) {
            const textarea = document.getElementById('alasan_penolakan');
            if (textarea.value.trim() === '') {
                textarea.value = teks;
            } else {
                textarea.value = textarea.value + '; ' + teks;
            }
            textarea.focus();
        }

        function openImageModal(url, caption) {
            const modal = document.getElementById('image-modal');
            const img = document.getElementById('modal-img');
            const cap = document.getElementById('modal-caption');
            const newTabBtn = document.getElementById('modal-newtab-btn');

            img.src = url;
            cap.textContent = caption;
            newTabBtn.href = url;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('image-modal');
            const img = document.getElementById('modal-img');
            img.src = '';
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        function closeModalOnBackdrop(e) {
            if (e.target.id === 'image-modal') {
                closeImageModal();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</div>
@endsection
