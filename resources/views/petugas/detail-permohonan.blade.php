@extends('layouts.app')

@section('title', 'Detail Permohonan - PATEN SPACE')

@section('content')
<div class="app-shell">
    <aside class="sidebar dark-sidebar">
        <button class="sidebar-toggle" type="button" aria-label="Buka atau tutup sidebar" aria-expanded="true">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="brand-wrap">
            <div class="brand-mark">P</div>
            <div class="brand-text">
                <div class="brand-title">PATEN SPACE</div>
                <div class="brand-sub">Kecamatan Jatisari</div>
            </div>
        </div>

        <div class="sidebar-nav">
            <div class="nav-group-label">Dashboard</div>
            <a href="{{ route('petugas.dashboard') }}" class="nav-item">Dashboard</a>

            <div class="nav-group-label">Permohonan</div>
            <a href="{{ route('petugas.permohonan.index') }}" class="nav-item active">Permohonan Masuk</a>
            <a href="{{ route('pendaftaran.create') }}" class="nav-item">Input Manual</a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item">Semua Data</a>

            <div class="nav-group-label">Laporan</div>
            <a href="{{ route('laporan') }}" class="nav-item">Laporan</a>
        </div>

        <form method="POST" action="{{ route('logout') }}" style="padding: 16px;">
            @csrf
            <button class="logout-btn" type="submit">Keluar</button>
        </form>
    </aside>

    <main class="main-panel">
        <header class="topbar form-topbar">
            <button type="button" class="back-button" aria-label="Kembali" title="Kembali"
                onclick="window.location.href='{{ route('petugas.permohonan.index') }}'">←</button>
            <div>
                <h1>Detail Permohonan</h1>
                <p>ID #{{ $pendaftaran->id }} — {{ $pendaftaran->jenis_layanan }}</p>
            </div>
            <div class="date-pill">{{ now()->translatedFormat('l, d F Y') }}</div>
        </header>

        @if(session('success'))
            <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:12px 16px;border-radius:8px;margin:16px 24px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 16px;border-radius:8px;margin:16px 24px;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display:grid;grid-template-columns:1fr 380px;gap:24px;padding:24px;">

            {{-- Panel Kiri: Detail Data --}}
            <div>
                {{-- Info Umum --}}
                <section class="form-card" style="margin-bottom:20px;">
                    <div class="section-header form-section-header" style="margin-bottom:16px;">
                        <h2>Informasi Umum</h2>
                        @php $status = $pendaftaran->status ?? 'Baru'; @endphp
                        @if($status === 'Selesai')
                            <span class="badge selected">{{ $status }}</span>
                        @elseif($status === 'Proses')
                            <span class="badge processing">{{ $status }}</span>
                        @elseif($status === 'Ditolak')
                            <span class="badge" style="background:#fee2e2;color:#991b1b;">{{ $status }}</span>
                        @else
                            <span class="badge" style="background:#f3f4f6;color:#374151;">{{ $status }}</span>
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

                    <table style="width:100%;border-collapse:collapse;">
                        @foreach($fields as $label => $value)
                            @if(!is_null($value) && $value !== '')
                                <tr style="border-bottom:1px solid #f3f4f6;">
                                    <td style="padding:8px 4px;color:#6b7280;font-size:13px;width:40%;vertical-align:top;">{{ $label }}</td>
                                    <td style="padding:8px 4px;font-size:14px;font-weight:500;color:#111827;">{{ $value }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </section>

                {{-- Dokumen --}}
                @if(!empty($pendaftaran->documents))
                    <section class="form-card" style="margin-bottom:20px;">
                        <div class="section-header form-section-header" style="margin-bottom:12px;">
                            <h2>Dokumen Persyaratan ({{ count($pendaftaran->documents) }} file)</h2>
                        </div>
                        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                            @foreach($pendaftaran->documents as $idx => $doc)
                                <li style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f9fafb;border-radius:6px;border:1px solid #e5e7eb;">
                                    <span style="font-size:20px;">📄</span>
                                    <span style="flex:1;font-size:14px;color:#374151;">{{ $doc['original_name'] ?? 'Dokumen '.($idx+1) }}</span>
                                    <a href="{{ route('pendaftaran.document', [$pendaftaran, $idx]) }}"
                                       style="color:#2563eb;font-size:13px;text-decoration:none;font-weight:600;"
                                       target="_blank">⬇ Unduh</a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                {{-- Alasan Penolakan --}}
                @if($pendaftaran->status === 'Ditolak' && $pendaftaran->alasan_penolakan)
                    <section style="margin-bottom:20px;background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:16px;">
                        <div style="font-weight:700;color:#991b1b;margin-bottom:6px;">❌ Alasan Penolakan</div>
                        <p style="margin:0;color:#7f1d1d;font-size:14px;">{{ $pendaftaran->alasan_penolakan }}</p>
                    </section>
                @endif

                {{-- Catatan Petugas --}}
                @if($pendaftaran->catatan_petugas)
                    <section style="margin-bottom:20px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:16px;">
                        <div style="font-weight:700;color:#1e40af;margin-bottom:6px;">📝 Catatan Petugas</div>
                        <p style="margin:0;color:#1e3a8a;font-size:14px;">{{ $pendaftaran->catatan_petugas }}</p>
                    </section>
                @endif
            </div>

            {{-- Panel Kanan: Form Update Status --}}
            <div>
                <section class="form-card" style="position:sticky;top:20px;">
                    <div class="section-header form-section-header" style="margin-bottom:16px;">
                        <h2>Update Status</h2>
                    </div>

                    <form action="{{ route('petugas.permohonan.status', $pendaftaran) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="field-group" style="margin-bottom:14px;">
                            <label for="status" style="font-weight:600;font-size:14px;display:block;margin-bottom:6px;">Status Permohonan</label>
                            <select id="status" name="status" required
                                    style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;"
                                    onchange="toggleAlasan(this.value)">
                                <option value="Baru"    {{ old('status', $pendaftaran->status) === 'Baru'    ? 'selected' : '' }}>Baru</option>
                                <option value="Proses"  {{ old('status', $pendaftaran->status) === 'Proses'  ? 'selected' : '' }}>Proses</option>
                                <option value="Selesai" {{ old('status', $pendaftaran->status) === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Ditolak" {{ old('status', $pendaftaran->status) === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div id="alasan-section" style="margin-bottom:14px;{{ old('status', $pendaftaran->status) === 'Ditolak' ? '' : 'display:none;' }}">
                            <label for="alasan_penolakan" style="font-weight:600;font-size:14px;display:block;margin-bottom:6px;">
                                Alasan Penolakan <span style="color:#ef4444;">*</span>
                            </label>
                            <textarea id="alasan_penolakan" name="alasan_penolakan" rows="4"
                                      style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;resize:vertical;box-sizing:border-box;"
                                      placeholder="Jelaskan alasan penolakan permohonan ini...">{{ old('alasan_penolakan', $pendaftaran->alasan_penolakan) }}</textarea>
                            @error('alasan_penolakan')
                                <p style="color:#ef4444;font-size:12px;margin:4px 0 0;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field-group" style="margin-bottom:16px;">
                            <label for="catatan_petugas" style="font-weight:600;font-size:14px;display:block;margin-bottom:6px;">Catatan Petugas</label>
                            <textarea id="catatan_petugas" name="catatan_petugas" rows="3"
                                      style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;resize:vertical;box-sizing:border-box;"
                                      placeholder="Catatan atau komentar tambahan (opsional)...">{{ old('catatan_petugas', $pendaftaran->catatan_petugas) }}</textarea>
                            @error('catatan_petugas')
                                <p style="color:#ef4444;font-size:12px;margin:4px 0 0;">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                style="width:100%;padding:12px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:15px;font-weight:600;cursor:pointer;">
                            Update Status
                        </button>
                    </form>
                </section>

                <div style="margin-top:12px;">
                    <a href="{{ route('petugas.permohonan.index') }}"
                       style="display:block;text-align:center;padding:10px;background:#f3f4f6;color:#374151;border-radius:6px;text-decoration:none;font-size:14px;">
                        ← Kembali ke Daftar Permohonan
                    </a>
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

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            if (!sidebar || !toggle) return;
            toggle.addEventListener('click', function () {
                const isCollapsed = sidebar.classList.toggle('collapsed');
                toggle.setAttribute('aria-expanded', String(!isCollapsed));
            });
        });
    </script>
</div>
@endsection
