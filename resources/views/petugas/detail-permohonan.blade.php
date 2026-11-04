@extends('layouts.app')

@section('title', 'Detail Permohonan - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page">
    <aside class="sidebar dark-sidebar">
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

                {{-- Panel Kiri: Detail Data --}}
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    {{-- Info Umum --}}
                    <section class="dashboard-panel" style="padding: 24px;">
                        <div class="panel-heading" style="margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <h2 style="margin: 0; font-size: 1.15rem; color: #17334c;">Informasi Permohonan</h2>
                                <p style="margin: 3px 0 0; font-size: 0.8rem; color: #6b7c93;">Rincian data permohonan yang diajukan</p>
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
                                'Sumber'          => $pendaftaran->intake_source === 'masyarakat' ? 'Mandiri (Masyarakat)' : 'Input Petugas',
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
                                'Nama Ibu'        => $pendaftaran->nama_ibu,
                                'Tempat Meninggal'=> $pendaftaran->tempat_meninggal,
                                'Tgl Meninggal'   => $pendaftaran->tanggal_meninggal ? \Illuminate\Support\Facades\Date::parse($pendaftaran->tanggal_meninggal)->format('d-m-Y') : null,
                                'Keterangan Kematian' => $pendaftaran->keterangan_kematian,
                                'Nama Anak Lahir' => $pendaftaran->nama_anak_lahir,
                                'Nama Ayah Lahir' => $pendaftaran->nama_ayah_lahir,
                                'Nama Ibu Lahir'  => $pendaftaran->nama_ibu_lahir,
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

                    {{-- Dokumen --}}
                    @if(!empty($pendaftaran->documents))
                        <section class="dashboard-panel" style="padding: 24px;">
                            <div class="panel-heading" style="margin-bottom: 16px;">
                                <h2 style="margin: 0; font-size: 1.15rem; color: #17334c;">Dokumen Persyaratan</h2>
                                <p style="margin: 3px 0 0; font-size: 0.8rem; color: #6b7c93;">{{ count($pendaftaran->documents) }} file terlampir</p>
                            </div>
                            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px;">
                                @foreach($pendaftaran->documents as $idx => $doc)
                                    <li style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: #faf9f6; border-radius: 8px; border: 1px solid #e8e2d2;">
                                        <span style="font-size: 22px;">📄</span>
                                        <span style="flex: 1; font-size: 14px; font-weight: 500; color: #263f55;">{{ $doc['original_name'] ?? 'Dokumen '.($idx+1) }}</span>
                                        <a href="{{ route('pendaftaran.document', [$pendaftaran, $idx]) }}"
                                           style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #e1d8c2; color: #3f3523; border-radius: 6px; font-size: 13px; text-decoration: none; font-weight: 700; transition: background .15s ease;"
                                           target="_blank">
                                            ⬇ Unduh
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    {{-- Alasan Penolakan --}}
                    @if($pendaftaran->status === 'Ditolak' && $pendaftaran->alasan_penolakan)
                        <section class="dashboard-panel" style="padding: 20px; background: #fef2f2; border: 1px solid #fecaca;">
                            <div style="font-weight: 700; color: #991b1b; margin-bottom: 6px; font-size: 0.95rem; display: flex; align-items: center; gap: 6px;">
                                <span>❌</span> Alasan Penolakan
                            </div>
                            <p style="margin: 0; color: #7f1d1d; font-size: 14px; line-height: 1.5;">{{ $pendaftaran->alasan_penolakan }}</p>
                        </section>
                    @endif

                    {{-- Catatan Petugas --}}
                    @if($pendaftaran->catatan_petugas)
                        <section class="dashboard-panel" style="padding: 20px; background: #f5f2eb; border: 1px solid #dfd7c5;">
                            <div style="font-weight: 700; color: #5b4b2d; margin-bottom: 6px; font-size: 0.95rem; display: flex; align-items: center; gap: 6px;">
                                <span>📝</span> Catatan Petugas
                            </div>
                            <p style="margin: 0; color: #44371f; font-size: 14px; line-height: 1.5;">{{ $pendaftaran->catatan_petugas }}</p>
                        </section>
                    @endif
                </div>

                {{-- Panel Kanan: Form Update Status --}}
                <div>
                    <section class="dashboard-panel" style="padding: 24px; position: sticky; top: 84px;">
                        <div class="panel-heading" style="margin-bottom: 18px;">
                            <h2 style="margin: 0; font-size: 1.15rem; color: #17334c;">Update Status</h2>
                            <p style="margin: 3px 0 0; font-size: 0.8rem; color: #6b7c93;">Perbarui progres pengerjaan berkas</p>
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
                                    <option value="Proses"  {{ old('status', $pendaftaran->status) === 'Proses'  ? 'selected' : '' }}>Proses</option>
                                    <option value="Selesai" {{ old('status', $pendaftaran->status) === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Ditolak" {{ old('status', $pendaftaran->status) === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>

                            <div id="alasan-section" style="margin-bottom: 16px; {{ old('status', $pendaftaran->status) === 'Ditolak' ? '' : 'display:none;' }}">
                                <label for="alasan_penolakan" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px; color: #263f55;">
                                    Alasan Penolakan <span style="color: #ef4444;">*</span>
                                </label>
                                <textarea id="alasan_penolakan" name="alasan_penolakan" rows="4"
                                          style="width: 100%; padding: 10px 12px; border: 1px solid #d1c9b6; border-radius: 6px; font-size: 14px; resize: vertical; box-sizing: border-box;"
                                          placeholder="Jelaskan alasan penolakan permohonan ini...">{{ old('alasan_penolakan', $pendaftaran->alasan_penolakan) }}</textarea>
                                @error('alasan_penolakan')
                                    <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div style="margin-bottom: 18px;">
                                <label for="catatan_petugas" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px; color: #263f55;">
                                    Catatan Petugas
                                </label>
                                <textarea id="catatan_petugas" name="catatan_petugas" rows="3"
                                          style="width: 100%; padding: 10px 12px; border: 1px solid #d1c9b6; border-radius: 6px; font-size: 14px; resize: vertical; box-sizing: border-box;"
                                          placeholder="Catatan atau komentar tambahan (opsional)...">{{ old('catatan_petugas', $pendaftaran->catatan_petugas) }}</textarea>
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
    </script>
</div>
@endsection
