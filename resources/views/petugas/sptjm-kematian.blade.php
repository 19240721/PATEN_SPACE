<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPTJM Kematian #{{ $pendaftaran->id }} - {{ $pendaftaran->nama_alm ?? $pendaftaran->nama_lengkap }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000000;
            background: {{ $isPdf ? '#ffffff' : '#e5e7eb' }};
            margin: 0;
            padding: 0;
        }

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
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .bar-left { display: flex; align-items: center; gap: 12px; }
        .bar-title { font-size: 14px; font-weight: 700; }
        .bar-sub { font-size: 12px; color: #9ca3af; }
        .bar-actions { display: flex; align-items: center; gap: 10px; }
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
        }
        .btn-print { background: #2563eb; color: #ffffff; }
        .btn-pdf { background: #059669; color: #ffffff; }
        .btn-back { background: #4b5563; color: #ffffff; }

        .sheet {
            width: 210mm;
            min-height: 297mm;
            margin: {{ $isPdf ? '0' : '24px auto' }};
            background: #ffffff;
            padding: {{ $isPdf ? '10mm 15mm' : '15mm 20mm' }};
            box-shadow: {{ $isPdf ? 'none' : '0 4px 20px rgba(0,0,0,0.15)' }};
        }

        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000000;
            margin-bottom: 18px;
            padding-bottom: 8px;
        }
        .kop-logo { width: 75px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 70px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; }
        .kop-instansi { font-size: 14pt; font-weight: 700; letter-spacing: 0.5px; margin: 0; text-transform: uppercase; }
        .kop-kecamatan { font-size: 15pt; font-weight: 800; letter-spacing: 1px; margin: 2px 0 0; text-transform: uppercase; }
        .kop-paten { font-size: 10.5pt; font-weight: 600; margin: 2px 0 0; }
        .kop-alamat { font-size: 9pt; margin: 3px 0 0; font-style: italic; }

        /* Judul */
        .doc-title-box { text-align: center; margin: 16px 0 20px; }
        .doc-title { font-size: 12.5pt; font-weight: 800; text-decoration: underline; text-transform: uppercase; margin: 0; }
        .doc-subtitle { font-size: 11pt; font-weight: 700; text-transform: uppercase; margin: 3px 0 0; letter-spacing: 0.5px; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0 12px;
            font-size: 10.5pt;
        }
        .data-table td { padding: 3px 2px; vertical-align: top; }
        .col-num { width: 22px; }
        .col-label { width: 200px; }
        .col-sep { width: 15px; text-align: center; }
        .col-val { font-weight: 600; }

        .statement-text {
            text-align: justify;
            text-indent: 32px;
            margin: 10px 0;
            font-size: 10.5pt;
            line-height: 1.55;
        }

        /* Tanda Tangan */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            font-size: 10.5pt;
        }
        .ttd-table td { vertical-align: top; }
        .ttd-left { width: 48%; }
        .ttd-right { width: 52%; text-align: center; }

        .materai-box {
            display: inline-block;
            width: 100px;
            height: 60px;
            border: 1px dashed #6b7280;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
            color: #4b5563;
            padding-top: 18px;
            margin: 8px 0;
            background: #fafafa;
        }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: #ffffff !important; }
            .sheet {
                width: 100% !important;
                margin: 0 !important;
                padding: 10mm 15mm !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

@unless($isPdf)
<div class="no-print-bar">
    <div class="bar-left">
        <a href="javascript:history.back()" class="btn-action btn-back">← Kembali</a>
        <div>
            <div class="bar-title">SPTJM Kebenaran Data Kematian</div>
            <div class="bar-sub">No. Registrasi: #{{ $pendaftaran->id }} &bull; NIK: {{ $pendaftaran->nik }}</div>
        </div>
    </div>
    <div class="bar-actions">
        <button type="button" onclick="window.print()" class="btn-action btn-print">🖨️ Cetak Dokumen</button>
        <a href="{{ request()->fullUrlWithQuery(['pdf' => 1]) }}" class="btn-action btn-pdf" target="_blank">📄 Unduh PDF</a>
    </div>
</div>
@endunless

<div class="sheet">
    {{-- Kop Surat --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo Karawang">
                @else
                    <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Karawang">
                @endif
            </td>
            <td class="kop-text">
                <div class="kop-instansi">Pemerintah Kabupaten Karawang</div>
                <div class="kop-kecamatan">Kecamatan Jatisari</div>
                <div class="kop-paten">Pelayanan Administrasi Terpadu Kecamatan (PATEN)</div>
                <div class="kop-alamat">Jl. Raya Jatisari No. 1, Jatisari, Kec. Jatisari, Kab. Karawang, Jawa Barat 41374</div>
            </td>
        </tr>
    </table>

    {{-- Judul --}}
    <div class="doc-title-box">
        <div class="doc-title">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK (SPTJM)</div>
        <div class="doc-subtitle">KEBENARAN DATA KEMATIAN</div>
    </div>

    <p style="margin: 0 0 6px; font-size: 10.5pt;">Yang bertanda tangan di bawah ini:</p>

    <table class="data-table">
        <tr>
            <td class="col-num">1.</td>
            <td class="col-label">Nama Lengkap</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->yang_menyatakan ?? $pendaftaran->nama_pelapor ?? $pendaftaran->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="col-num">2.</td>
            <td class="col-label">NIK</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nik_yang_menyatakan ?? $pendaftaran->nik_pelapor ?? $pendaftaran->nik }}</td>
        </tr>
        <tr>
            <td class="col-num">3.</td>
            <td class="col-label">Hubungan dengan Jenazah</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->hubungan_yang_menyatakan ?? 'Keluarga / Ahli Waris' }}</td>
        </tr>
        <tr>
            <td class="col-num">4.</td>
            <td class="col-label">Alamat</td>
            <td class="col-sep">:</td>
            <td class="col-val">
                {{ $pendaftaran->alamat ?? '-' }}
                @if($pendaftaran->jalan) Jl. {{ $pendaftaran->jalan }} @endif
                RT {{ $pendaftaran->rt ?? '00' }} / RW {{ $pendaftaran->rw ?? '00' }},
                Desa {{ $pendaftaran->desa ?? '-' }}, Kec. {{ $pendaftaran->kecamatan ?? 'Jatisari' }}
            </td>
        </tr>
    </table>

    <p class="statement-text">
        Menyatakan dengan sesungguhnya dan bertanggung jawab penuh bahwa data almarhum / almarhumah di bawah ini:
    </p>

    <table class="data-table">
        <tr>
            <td class="col-num">1.</td>
            <td class="col-label">Nama Almarhum / Jenazah</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nama_alm ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">2.</td>
            <td class="col-label">NIK Jenazah</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->nik_jenazah ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">3.</td>
            <td class="col-label">Tempat / Tanggal Lahir</td>
            <td class="col-sep">:</td>
            <td class="col-val">
                {{ $pendaftaran->tempat_lahir_jenazah ?? '-' }},
                {{ $pendaftaran->tanggal_lahir_jenazah ? \Illuminate\Support\Carbon::parse($pendaftaran->tanggal_lahir_jenazah)->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="col-num">4.</td>
            <td class="col-label">Alamat Terakhir</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->alamat_jenazah ?? $pendaftaran->alamat ?? 'Kecamatan Jatisari' }}</td>
        </tr>
        <tr>
            <td class="col-num">5.</td>
            <td class="col-label">Tanggal Kematian</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->tanggal_meninggal ? \Illuminate\Support\Carbon::parse($pendaftaran->tanggal_meninggal)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">6.</td>
            <td class="col-label">Tempat Kematian</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->tempat_meninggal ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">7.</td>
            <td class="col-label">Penyebab Kematian</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $pendaftaran->penyebab_kematian ?? $pendaftaran->keterangan_kematian ?? 'Sakit / Wajar' }}</td>
        </tr>
    </table>

    <p class="statement-text">
        Adalah benar telah meninggal dunia. Surat Pernyataan Tanggung Jawab Mutlak (SPTJM) ini saya buat dengan sadar, sebenar-benarnya tanpa paksaan dari pihak manapun sebagai kelengkapan persyaratan penerbitan Kutipan Akta Kematian dikarenakan <strong>KTP Jenazah tidak ada</strong>. Apabila di kemudian hari pernyataan ini terbukti tidak benar, saya bersedia dituntut di hadapan hukum sesuai peraturan perundang-undangan yang berlaku.
    </p>

    {{-- Tanda Tangan & Materai --}}
    <table class="ttd-table">
        <tr>
            <td class="ttd-left">
                <div style="font-weight: 700; margin-bottom: 8px;">Saksi-Saksi:</div>
                <div style="margin-bottom: 30px;">
                    <div>1. Saksi I</div>
                    <div style="margin-top: 40px; font-weight: 700; text-decoration: underline;">
                        {{ $pendaftaran->nama_saksi1 ?? '( .................................................... )' }}
                    </div>
                    <div style="font-size: 9.5pt;">NIK: {{ $pendaftaran->nik_saksi1 ?? '-' }}</div>
                </div>
                <div>
                    <div>2. Saksi II</div>
                    <div style="margin-top: 40px; font-weight: 700; text-decoration: underline;">
                        {{ $pendaftaran->nama_saksi2 ?? '( .................................................... )' }}
                    </div>
                    <div style="font-size: 9.5pt;">NIK: {{ $pendaftaran->nik_saksi2 ?? '-' }}</div>
                </div>
            </td>
            <td class="ttd-right">
                <div>Jatisari, {{ $pendaftaran->tanggal_daftar ? \Illuminate\Support\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</div>
                <div style="font-weight: 700; margin-top: 4px;">Yang Membuat Pernyataan,</div>

                {{-- Area Materai --}}
                <div style="margin: 8px 0;">
                    <div class="materai-box">
                        MATERAI<br>
                        Rp 10.000
                    </div>
                </div>

                <div style="font-weight: 700; text-decoration: underline; margin-top: 4px;">
                    {{ $pendaftaran->yang_menyatakan ?? $pendaftaran->nama_pelapor ?? $pendaftaran->nama_lengkap }}
                </div>
                <div style="font-size: 9.5pt;">
                    NIK: {{ $pendaftaran->nik_yang_menyatakan ?? $pendaftaran->nik_pelapor ?? $pendaftaran->nik }}
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
