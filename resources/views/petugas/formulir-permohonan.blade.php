<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Permohonan #{{ $pendaftaran->id }} - {{ $pendaftaran->nama_lengkap }} - PATEN SPACE</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, 'Segoe UI', Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.45;
            color: #111827;
            background: {{ $isPdf ? '#ffffff' : '#e5e7eb' }};
            margin: 0;
            padding: 0;
        }

        /* Floating action bar (screen only) */
        .no-print-bar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #1f2937;
            color: #f9fafb;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.25);
        }

        .bar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .bar-title {
            font-size: 14px;
            font-weight: 700;
        }

        .bar-sub {
            font-size: 12px;
            color: #9ca3af;
        }

        .bar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: background 0.15s ease;
        }

        .btn-print {
            background: #2563eb;
            color: #ffffff;
        }
        .btn-print:hover { background: #1d4ed8; }

        .btn-pdf {
            background: #059669;
            color: #ffffff;
        }
        .btn-pdf:hover { background: #047857; }

        .btn-back {
            background: #4b5563;
            color: #ffffff;
        }
        .btn-back:hover { background: #374151; }

        /* Sheet Container */
        .sheet {
            width: 210mm;
            min-height: 297mm;
            margin: {{ $isPdf ? '0' : '24px auto' }};
            background: #ffffff;
            padding: {{ $isPdf ? '8mm 12mm 10mm 12mm' : '16mm 20mm 18mm 20mm' }};
            box-shadow: {{ $isPdf ? 'none' : '0 4px 20px rgba(0,0,0,0.15)' }};
            border-radius: {{ $isPdf ? '0' : '4px' }};
        }

        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .kop-logo {
            width: 68px;
            text-align: center;
        }

        .kop-logo img {
            width: 58px;
            height: auto;
            max-height: 70px;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text .instansi-1 {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #000000;
        }

        .kop-text .instansi-2 {
            font-size: 13.5pt;
            font-weight: 800;
            letter-spacing: 1.2px;
            color: #000000;
            margin: 1px 0;
        }

        .kop-text .instansi-paten {
            font-size: 10pt;
            font-weight: 700;
            color: #1e3a8a;
            letter-spacing: 0.5px;
        }

        .kop-text .instansi-alamat {
            font-size: 8pt;
            color: #374151;
            margin-top: 3px;
        }

        .kop-text .instansi-kontak {
            font-size: 7.5pt;
            color: #4b5563;
        }

        /* Judul Formulir */
        .title-block {
            text-align: center;
            margin: 10px 0 12px;
        }

        .title-block h1 {
            margin: 0 0 3px;
            font-size: 11.5pt;
            font-weight: 800;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }

        .title-block .reg-number {
            font-size: 9pt;
            font-weight: 600;
            color: #374151;
        }

        /* Meta Box */
        .meta-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 8.5pt;
            background: #f9fafb;
            border: 1px solid #d1d5db;
        }

        .meta-box td {
            padding: 5px 8px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        /* Section Header */
        .section-header {
            font-size: 9pt;
            font-weight: 800;
            background: #e5e7eb;
            color: #111827;
            padding: 3px 8px;
            margin: 8px 0 5px;
            border-left: 4px solid #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 6px;
        }

        .data-table td {
            padding: 3px 6px;
            vertical-align: top;
            border: none;
        }

        .col-num {
            width: 22px;
            color: #374151;
        }

        .col-label {
            width: 170px;
            color: #374151;
        }

        .col-sep {
            width: 10px;
            text-align: center;
        }

        .col-val {
            color: #111827;
            font-weight: 500;
        }

        .val-bold {
            font-weight: 700;
            color: #000000;
        }

        /* Pernyataan Box */
        .pernyataan-box {
            font-size: 7.8pt;
            text-align: justify;
            line-height: 1.4;
            padding: 6px 10px;
            background: #fafafa;
            border: 1px solid #d1d5db;
            margin: 6px 0 12px;
            color: #374151;
        }

        /* Signature Table */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            page-break-inside: avoid;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 4px 10px;
            border: none;
        }

        .sig-role {
            font-size: 8.5pt;
            color: #374151;
        }

        .sig-instansi {
            font-size: 9pt;
            font-weight: 700;
            margin-bottom: 4px;
        }

        /* Kotak Materai Manual */
        .materai-box {
            width: 110px;
            height: 64px;
            border: 1.5px dashed #4b5563;
            background: #ffffff;
            margin: 6px auto 8px;
            text-align: center;
            padding: 6px 4px;
            box-sizing: border-box;
            display: inline-block;
        }

        .materai-title {
            font-size: 6.5pt;
            font-weight: 700;
            color: #6b7280;
            letter-spacing: 0.5px;
        }

        .materai-value {
            font-size: 8.5pt;
            font-weight: 800;
            color: #1f2937;
            margin: 3px 0 2px;
        }

        .materai-hint {
            font-size: 6pt;
            color: #9ca3af;
            line-height: 1.15;
        }

        .sig-space-petugas {
            height: 70px;
        }

        .sig-line {
            font-size: 9pt;
            color: #111827;
        }

        .sig-name {
            font-size: 9pt;
            font-weight: 700;
            color: #000000;
            margin-top: 3px;
        }

        .sig-sub {
            font-size: 8pt;
            color: #4b5563;
        }

        /* Footer Nota */
        .form-footer {
            margin-top: 10px;
            padding-top: 6px;
            border-top: 1px dashed #d1d5db;
            display: flex;
            justify-content: space-between;
            font-size: 7pt;
            color: #6b7280;
        }

        /* Print Specific CSS */
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 10.5pt;
            }

            .no-print-bar {
                display: none !important;
            }

            .sheet {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            @page {
                size: A4 portrait;
                margin: 12mm 15mm 12mm 15mm;
            }
        }
    </style>
</head>
<body>

@if(!$isPdf)
<div class="no-print-bar">
    <div class="bar-left">
        <span class="bar-title">📄 Formulir Permohonan #{{ $pendaftaran->id }}</span>
        <span class="bar-sub">{{ $pendaftaran->nama_lengkap }} ({{ $pendaftaran->jenis_layanan }})</span>
    </div>
    <div class="bar-actions">
        <a href="{{ route('petugas.permohonan.index') }}" class="btn-action btn-back">
            <span>←</span> Kembali
        </a>
        <a href="{{ route('petugas.permohonan.formulir', [$pendaftaran, 'pdf' => 1]) }}" class="btn-action btn-pdf" target="_blank">
            <span>📥</span> Unduh PDF
        </a>
        <button type="button" class="btn-action btn-print" onclick="window.print()">
            <span>🖨️</span> Cetak Formulir (Print)
        </button>
    </div>
</div>
@endif

<div class="sheet">
    {{-- KOP SURAT RESMI --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo Kabupaten Karawang">
                @else
                    <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang">
                @endif
            </td>
            <td class="kop-text">
                <div class="instansi-1">PEMERINTAH KABUPATEN KARAWANG</div>
                <div class="instansi-2">KECAMATAN JATISARI</div>
                <div class="instansi-paten">PELAYANAN ADMINISTRASI TERPADU KECAMATAN (PATEN)</div>
                <div class="instansi-alamat">Jl. Raya Jatisari No. 1, Jatisari, Kec. Jatisari, Kab. Karawang, Jawa Barat 41374</div>
                <div class="instansi-kontak">Email: kecamatan.jatisari@karawangkab.go.id &bull; Laman: https://paten.jatisari.go.id</div>
            </td>
            <td style="width: 68px;"></td>
        </tr>
    </table>

    {{-- JUDUL FORMULIR --}}
    <div class="title-block">
        <h1>FORMULIR PERMOHONAN PELAYANAN ADMINISTRASI</h1>
        <div class="reg-number">
            Nomor Registrasi: <strong>PATEN/JTS/{{ date('Y', strtotime($pendaftaran->tanggal_daftar ?? now())) }}/{{ str_pad($pendaftaran->id, 5, '0', STR_PAD_LEFT) }}</strong>
        </div>
    </div>

    {{-- META PERMOHONAN --}}
    <table class="meta-box">
        <tr>
            <td style="width: 22%;"><strong>Jenis Pelayanan</strong></td>
            <td style="width: 38%;"><strong style="color: #1e3a8a;">{{ $pendaftaran->jenis_layanan }}</strong></td>
            <td style="width: 20%;"><strong>Tanggal Permohonan</strong></td>
            <td style="width: 20%;">{{ $pendaftaran->tanggal_daftar ? \Illuminate\Support\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Status Berkas</strong></td>
            <td><strong>{{ $pendaftaran->status ?? 'Baru' }}</strong></td>
            <td><strong>Saluran Pengajuan</strong></td>
            <td>{{ $pendaftaran->intake_source === 'masyarakat' ? 'Mandiri Online (Warga)' : 'Loket Pelayanan (Petugas)' }}</td>
        </tr>
    </table>

    {{-- I. DATA PEMOHON --}}
    <div class="section-header">I. Data Identitas Pemohon</div>
    <table class="data-table">
        <tr>
            <td class="col-num">1.</td>
            <td class="col-label">Nama Lengkap</td>
            <td class="col-sep">:</td>
            <td class="col-val val-bold">{{ $pendaftaran->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="col-num">2.</td>
            <td class="col-label">Nomor Induk Kependudukan (NIK)</td>
            <td class="col-sep">:</td>
            <td class="col-val val-bold">{{ $pendaftaran->nik }}</td>
        </tr>
        @if($pendaftaran->nomor_kk)
        <tr>
            <td class="col-num">3.</td>
            <td class="col-label">Nomor Kartu Keluarga (KK)</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nomor_kk }}</td>
        </tr>
        @endif
        <tr>
            <td class="col-num">{{ $pendaftaran->nomor_kk ? '4.' : '3.' }}</td>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->jenis_kelamin ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">{{ $pendaftaran->nomor_kk ? '5.' : '4.' }}</td>
            <td class="col-label">Tempat / Tanggal Lahir</td>
            <td class="col-sep">:</td>
            <td class="col-val">
                {{ $pendaftaran->tempat_lahir ?? '-' }}, {{ $pendaftaran->tanggal_lahir ? \Illuminate\Support\Carbon::parse($pendaftaran->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="col-num">{{ $pendaftaran->nomor_kk ? '6.' : '5.' }}</td>
            <td class="col-label">Alamat Domisili</td>
            <td class="col-sep">:</td>
            <td class="col-val">
                {{ $pendaftaran->alamat ?? '-' }}
                @if($pendaftaran->jalan) &bull; Jl. {{ $pendaftaran->jalan }} @endif
                &bull; RT {{ $pendaftaran->rt ?? '00' }} / RW {{ $pendaftaran->rw ?? '00' }}
                @if($pendaftaran->dusun) &bull; Dusun: {{ $pendaftaran->dusun }} @endif
                &bull; Desa: {{ $pendaftaran->desa ?? '-' }}
                &bull; Kec. {{ $pendaftaran->kecamatan ?? 'Jatisari' }}
            </td>
        </tr>
        <tr>
            <td class="col-num">{{ $pendaftaran->nomor_kk ? '7.' : '6.' }}</td>
            <td class="col-label">Nomor Telepon / WhatsApp</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->no_telepon ?? '-' }}</td>
        </tr>
    </table>

    {{-- II. RINCIAN LAYANAN ADMINISTRASI --}}
    <div class="section-header">II. Rincian Pelayanan Administrasi ({{ $pendaftaran->jenis_layanan }})</div>
    <table class="data-table">
        @if($pendaftaran->nama_kepala_keluarga || $pendaftaran->kepala_keluarga)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Nama Kepala Keluarga</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nama_kepala_keluarga ?? $pendaftaran->kepala_keluarga }}</td>
        </tr>
        @endif

        @if($pendaftaran->hubungan)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Hubungan dalam Keluarga</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->hubungan }}</td>
        </tr>
        @endif

        @if($pendaftaran->jumlah_anggota)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Jumlah Anggota Keluarga</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->jumlah_anggota }} orang</td>
        </tr>
        @endif

        @if($pendaftaran->keterangan_kk)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Keterangan KK</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->keterangan_kk }}</td>
        </tr>
        @endif

        @if($pendaftaran->jenis_layanan === 'KK' && !empty($pendaftaran->anggota_kk))
        <tr>
            <td colspan="4" style="padding-top: 12px;">
                <strong style="display:block; margin-bottom: 6px;">Daftar Anggota Keluarga</strong>
                <table style="width:100%; border-collapse:collapse; font-size: 11px;">
                    <thead>
                        <tr style="background:#f5f2eb;">
                            <th style="border:1px solid #ccc; padding:4px 6px; text-align:center; width:30px;">No</th>
                            <th style="border:1px solid #ccc; padding:4px 6px; text-align:left;">Nama Lengkap</th>
                            <th style="border:1px solid #ccc; padding:4px 6px; text-align:left;">NIK</th>
                            <th style="border:1px solid #ccc; padding:4px 6px; text-align:left;">SHDK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendaftaran->anggota_kk as $i => $anggota)
                        <tr>
                            <td style="border:1px solid #ccc; padding:4px 6px; text-align:center;">{{ $i + 1 }}</td>
                            <td style="border:1px solid #ccc; padding:4px 6px;">{{ $anggota['nama'] ?? '-' }}</td>
                            <td style="border:1px solid #ccc; padding:4px 6px;">{{ $anggota['nik'] ?? '-' }}</td>
                            <td style="border:1px solid #ccc; padding:4px 6px;">{{ $anggota['shdk'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        @endif

        @if($pendaftaran->jenis_layanan === 'KK' && !empty($pendaftaran->perubahan_kk))
        <tr>
            <td colspan="4" style="padding-top: 12px;">
                <strong style="display:block; margin-bottom: 6px;">Perubahan Data (Pendidikan, Pekerjaan, Agama, dll)</strong>
                <table style="width:100%; border-collapse:collapse; font-size: 11px;">
                    <thead>
                        <tr style="background:#f5f2eb;">
                            <th style="border:1px solid #ccc; padding:4px 6px; text-align:center; width:30px;">No</th>
                            <th style="border:1px solid #ccc; padding:4px 6px; text-align:left;">Semula</th>
                            <th style="border:1px solid #ccc; padding:4px 6px; text-align:left;">Menjadi</th>
                            <th style="border:1px solid #ccc; padding:4px 6px; text-align:left;">Dasar Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendaftaran->perubahan_kk as $i => $perubahan)
                        <tr>
                            <td style="border:1px solid #ccc; padding:4px 6px; text-align:center;">{{ $i + 1 }}</td>
                            <td style="border:1px solid #ccc; padding:4px 6px;">{{ $perubahan['semula'] ?? '-' }}</td>
                            <td style="border:1px solid #ccc; padding:4px 6px;">{{ $perubahan['menjadi'] ?? '-' }}</td>
                            <td style="border:1px solid #ccc; padding:4px 6px;">{{ $perubahan['dasar'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        @endif

        @if($pendaftaran->nama_anak_lahir || $pendaftaran->nama_anak)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Nama Anak (Lahir)</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nama_anak_lahir ?? $pendaftaran->nama_anak }}</td>
        </tr>
        @endif

        @if($pendaftaran->tempat_lahir_detail)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Tempat Lahir Detail</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->tempat_lahir_detail }}</td>
        </tr>
        @endif

        @if($pendaftaran->berat_badan || $pendaftaran->panjang_badan)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Berat / Panjang Badan</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->berat_badan ? $pendaftaran->berat_badan.' kg' : '-' }} / {{ $pendaftaran->panjang_badan ? $pendaftaran->panjang_badan.' cm' : '-' }}</td>
        </tr>
        @endif

        @if($pendaftaran->nama_ayah_lahir || $pendaftaran->nama_ayah)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Nama Ayah</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nama_ayah_lahir ?? $pendaftaran->nama_ayah }}{{ $pendaftaran->nik_ayah ? ' (NIK: '.$pendaftaran->nik_ayah.')' : '' }}</td>
        </tr>
        @endif

        @if($pendaftaran->nama_ibu_lahir || $pendaftaran->nama_ibu)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Nama Ibu</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nama_ibu_lahir ?? $pendaftaran->nama_ibu }}{{ $pendaftaran->nik_ibu ? ' (NIK: '.$pendaftaran->nik_ibu.')' : '' }}</td>
        </tr>
        @endif

        @if($pendaftaran->nama_pelapor || $pendaftaran->nik_pelapor)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Identitas Pelapor</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nama_pelapor ?? '-' }}{{ $pendaftaran->nik_pelapor ? ' (NIK: '.$pendaftaran->nik_pelapor.')' : '' }}</td>
        </tr>
        @endif

        @if($pendaftaran->nama_saksi1 || $pendaftaran->nik_saksi1)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Saksi I</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nama_saksi1 ?? '-' }}{{ $pendaftaran->nik_saksi1 ? ' (NIK: '.$pendaftaran->nik_saksi1.')' : '' }}</td>
        </tr>
        @endif

        @if($pendaftaran->nama_saksi2 || $pendaftaran->nik_saksi2)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Saksi II</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nama_saksi2 ?? '-' }}{{ $pendaftaran->nik_saksi2 ? ' (NIK: '.$pendaftaran->nik_saksi2.')' : '' }}</td>
        </tr>
        @endif

        @if($pendaftaran->nama_alm)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Nama Almarhum/Almarhumah</td>
            <td class="col-sep">:</td>
            <td class="col-val val-bold">{{ $pendaftaran->nama_alm }}</td>
        </tr>
        @endif

        @if($pendaftaran->tempat_meninggal || $pendaftaran->tanggal_meninggal)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Meninggal di / Tanggal</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->tempat_meninggal ?? '-' }}, {{ $pendaftaran->tanggal_meninggal ? \Illuminate\Support\Carbon::parse($pendaftaran->tanggal_meninggal)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        @endif

        @if($pendaftaran->keterangan_kematian)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Keterangan Kematian</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->keterangan_kematian }}</td>
        </tr>
        @endif

        @if($pendaftaran->sptjm_kematian)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Dokumen SPTJM</td>
            <td class="col-sep">:</td>
            <td class="col-val">
                <strong>Ya, Menggunakan SPTJM Kebenaran Data Kematian (KTP Jenazah Tidak Ada)</strong>
                <br><span style="font-size:10px;color:#4b5563;">NIK Jenazah: {{ $pendaftaran->nik_jenazah ?? '-' }} &bull; TTL: {{ $pendaftaran->tempat_lahir_jenazah ?? '-' }}, {{ $pendaftaran->tanggal_lahir_jenazah ?? '-' }} &bull; Bermaterai Rp 10.000 &bull; TTD Yang Menyatakan & 2 Saksi</span>
            </td>
        </tr>
        @endif

        @if($pendaftaran->nama_kedatangan || $pendaftaran->nik_kedatangan)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Warga Kedatangan</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nama_kedatangan ?? '-' }} (NIK: {{ $pendaftaran->nik_kedatangan ?? '-' }})</td>
        </tr>
        @endif

        @if($pendaftaran->alamat_asal_kedatangan || $pendaftaran->alamat_asal)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Alamat Asal</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->alamat_asal_kedatangan ?? $pendaftaran->alamat_asal }}</td>
        </tr>
        @endif

        @if($pendaftaran->alamat_tujuan_kedatangan || $pendaftaran->alamat_tujuan)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Alamat Tujuan</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->alamat_tujuan_kedatangan ?? $pendaftaran->alamat_tujuan }}</td>
        </tr>
        @endif

        @if($pendaftaran->tanggal_kedatangan)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Tanggal Kedatangan</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ \Illuminate\Support\Carbon::parse($pendaftaran->tanggal_kedatangan)->translatedFormat('d F Y') }}</td>
        </tr>
        @endif

        @if($pendaftaran->nama_pindah || $pendaftaran->nik_pindah)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Warga Pindah</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nama_pindah ?? '-' }} (NIK: {{ $pendaftaran->nik_pindah ?? '-' }})</td>
        </tr>
        @endif

        @if($pendaftaran->alamat_asal_pindah)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Alamat Asal Pindah</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->alamat_asal_pindah }}</td>
        </tr>
        @endif

        @if($pendaftaran->alamat_tujuan_pindah)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Alamat Tujuan Pindah</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->alamat_tujuan_pindah }}</td>
        </tr>
        @endif

        @if($pendaftaran->alasan_pindah)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Alasan Pindah</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->alasan_pindah }}</td>
        </tr>
        @endif

        @if($pendaftaran->alasan_permohonan)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Alasan Permohonan</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->alasan_permohonan }}</td>
        </tr>
        @endif

        @if($pendaftaran->alasan_pembaruan)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Alasan Pembaruan</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->alasan_pembaruan }}</td>
        </tr>
        @endif

        @if($pendaftaran->keterangan)
        <tr>
            <td class="col-num">&bull;</td>
            <td class="col-label">Catatan / Keterangan</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->keterangan }}</td>
        </tr>
        @endif
    </table>

    {{-- III. PERNYATAAN PEMOHON --}}
    <div class="section-header">III. Pernyataan Pemohon</div>
    <div class="pernyataan-box">
        Dengan ini saya menyatakan bahwa data, keterangan, dan berkas yang saya berikan dalam formulir permohonan ini adalah benar, sah, dan dibuat dengan sesungguhnya tanpa paksaan dari pihak manapun. Apabila di kemudian hari terbukti keterangan yang saya sampaikan tidak benar/palsu, saya bersedia bertanggung jawab sepenuhnya dan dituntut sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.
    </div>

    {{-- IV. TANDA TANGAN & MATERAI (MANUAL) --}}
    <table class="signature-table">
        <tr>
            <td>
                <div class="sig-role">Mengetahui / Menerima,</div>
                <div class="sig-instansi">Petugas Pelayanan PATEN Jatisari</div>
                <div class="sig-space-petugas"></div>
                <div class="sig-line">( ............................................................ )</div>
                <div class="sig-name">{{ $pendaftaran->petugas ?? auth()->user()->name ?? 'Petugas Loket PATEN' }}</div>
                <div class="sig-sub">Petugas Administrasi Kecamatan</div>
            </td>

            <td>
                <div class="sig-role">
                    Jatisari, {{ $pendaftaran->tanggal_daftar ? \Illuminate\Support\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d F Y') : \Illuminate\Support\Carbon::now()->translatedFormat('d F Y') }}
                </div>
                <div class="sig-instansi">Pemohon,</div>

                @php
                    $needsMaterai = in_array($pendaftaran->jenis_layanan, ['KK', 'Akta Kematian', 'Akta Lahir'], true);
                @endphp

                @if($needsMaterai)
                    {{-- Kotak Materai Tempel Fisik Manual (Hanya untuk KK, Akta Kematian, dan Akta Kelahiran) --}}
                    <div class="materai-box">
                        <div class="materai-title">MATERAI TEMPEL</div>
                        <div class="materai-value">Rp 10.000,-</div>
                        <div class="materai-hint">(Tanda tangan manual<br>di atas materai)</div>
                    </div>
                @else
                    <div class="sig-space-petugas"></div>
                @endif

                <div class="sig-line">( ............................................................ )</div>
                <div class="sig-name">{{ $pendaftaran->nama_lengkap }}</div>
                <div class="sig-sub">NIK. {{ $pendaftaran->nik }}</div>
            </td>
        </tr>
    </table>

    {{-- FOOTER / CATATAN SISTEM --}}
    <div class="form-footer">
        <span>Sistem Pelayanan Administrasi Terpadu Kecamatan (PATEN SPACE) - Kecamatan Jatisari</span>
        <span>Dicetak pada: {{ now()->translatedFormat('d-m-Y H:i:s') }} WIB</span>
    </div>
</div>

</body>
</html>

