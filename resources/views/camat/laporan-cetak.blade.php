<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Pelayanan PATEN - Kecamatan Jatisari</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-karawang.png') }}">
    <style>
        /* ========================================================
           CSS HALAMAN CETAK & PDF RESMI (GAYA MICROSOFT WORD)
           FONT: ARLAL, WARNA: HITAM (#000000)
           ======================================================== */
        @php
            $kertasLower = strtolower($kertas ?? 'a4');
            $orientasiLower = strtolower($orientasi ?? 'portrait');

            // Dimensi Kertas Resmi
            if ($kertasLower === 'f4') {
                $pageSize = ($orientasiLower === 'landscape') ? '330mm 215mm' : '215mm 330mm';
            } elseif ($kertasLower === 'letter') {
                $pageSize = 'letter ' . $orientasiLower;
            } elseif ($kertasLower === 'legal') {
                $pageSize = 'legal ' . $orientasiLower;
            } else {
                $pageSize = 'A4 ' . $orientasiLower;
            }
        @endphp

        @page {
            size: {{ $pageSize }};
            margin: 15mm 18mm 15mm 18mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.35;
            color: #000000;
            background-color: #e2e8f0;
            margin: 0;
            padding: 20px 0;
        }

        /* TOOLBAR ATAS (HANYA MUNCUL DI LAYAR, HILANG SAAT DICETAK) */
        .print-control-bar {
            max-width: 900px;
            margin: 0 auto 16px;
            background: #1e293b;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .print-control-bar select {
            height: 36px;
            padding: 0 10px;
            border-radius: 4px;
            border: 1px solid #475569;
            background: #ffffff;
            color: #0f172a;
            font-size: 9pt;
            font-weight: 600;
        }

        .print-control-bar button,
        .print-control-bar a {
            height: 36px;
            padding: 0 16px;
            border-radius: 4px;
            border: 0;
            font-size: 9pt;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            transition: opacity 0.15s;
        }

        .btn-print {
            background: #796743;
            color: #ffffff;
        }
        .btn-print:hover { opacity: 0.9; }

        .btn-back {
            background: #334155;
            color: #f1f5f9;
        }
        .btn-back:hover { background: #475569; }

        /* LEMBAR KERTAS DOKUMEN */
        .sheet {
            background: #ffffff;
            color: #000000;
            margin: 0 auto;
            padding: 20mm 20mm;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
            @if ($orientasiLower === 'landscape')
                width: {{ $kertasLower === 'f4' ? '330mm' : ($kertasLower === 'legal' ? '355.6mm' : ($kertasLower === 'letter' ? '279.4mm' : '297mm')) }};
                min-height: {{ $kertasLower === 'f4' ? '215mm' : ($kertasLower === 'legal' ? '215.9mm' : ($kertasLower === 'letter' ? '215.9mm' : '210mm')) }};
            @else
                width: {{ $kertasLower === 'f4' ? '215mm' : ($kertasLower === 'legal' ? '215.9mm' : ($kertasLower === 'letter' ? '215.9mm' : '210mm')) }};
                min-height: {{ $kertasLower === 'f4' ? '330mm' : ($kertasLower === 'legal' ? '355.6mm' : ($kertasLower === 'letter' ? '279.4mm' : '297mm')) }};
            @endif
        }

        /* KOP SURAT RESMI */
        .kop-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            margin-bottom: 6px;
        }

        .kop-logo {
            width: 75px;
            height: auto;
            max-height: 90px;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
        }

        .kop-instansi {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            color: #000000;
        }

        .kop-kecamatan {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 2px 0;
            color: #000000;
        }

        .kop-alamat {
            font-size: 9.5pt;
            margin: 0;
            color: #000000;
            line-height: 1.3;
        }

        .kop-line-thick {
            border: 0;
            border-top: 2.5px solid #000000;
            margin-top: 10px;
            margin-bottom: 2px;
        }

        .kop-line-thin {
            border: 0;
            border-top: 1px solid #000000;
            margin-top: 0;
            margin-bottom: 16px;
        }

        /* JUDUL LAPORAN */
        .report-title-block {
            text-align: center;
            margin-bottom: 14px;
        }

        .report-title {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 5px;
            color: #000000;
            letter-spacing: 0.5px;
        }

        .report-periode-info {
            font-size: 10pt;
            color: #000000;
            margin: 0;
        }

        .report-date-right {
            text-align: right;
            font-size: 10pt;
            color: #000000;
            margin-bottom: 12px;
        }

        /* BAGIAN / SECTION HEADING (FORMAL WORD STYLE, NO COLOR HIGHLIGHT) */
        .section-heading {
            font-size: 11pt;
            font-weight: bold;
            color: #000000;
            margin-top: 14px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        /* TABEL FORMAL RESMI */
        table.formal-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000000;
            font-size: 9.5pt;
            color: #000000;
        }

        table.formal-table th,
        table.formal-table td {
            border: 1px solid #000000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        table.formal-table th {
            font-weight: bold;
            background-color: #f2f2f2;
            text-align: center;
        }

        table.formal-table tfoot td {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        /* BAGIAN A: TWO-COLUMN SIDE-BY-SIDE */
        .summary-side-by-side {
            width: 100%;
            border-collapse: collapse;
            border: 0;
            margin-bottom: 16px;
        }

        .summary-side-by-side td {
            border: 0;
            padding: 0;
            vertical-align: top;
        }

        .summary-left-col {
            width: 54%;
            padding-right: 12px !important;
        }

        .summary-right-col {
            width: 46%;
            padding-left: 12px !important;
        }

        .summary-brief-box {
            border: 1px solid #000000;
            padding: 10px 14px;
            height: 100%;
            min-height: 120px;
            background: #ffffff;
            font-size: 9.5pt;
            line-height: 1.5;
        }

        .summary-brief-box ul {
            margin: 4px 0 8px;
            padding-left: 18px;
        }

        /* TANDA TANGAN CAMAT */
        .signature-block {
            display: flex;
            justify-content: flex-end;
            margin-top: 24px;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            width: 270px;
            font-size: 10pt;
            line-height: 1.4;
            color: #000000;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* KETENTUAN KHUSUS SAAT PRINT (WINDOW.PRINT()) */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .print-control-bar, .no-print {
                display: none !important;
            }

            .sheet {
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                min-height: auto !important;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
            }

            .signature-block {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    {{-- TOOLBAR KONTROL CETAK (HANYA DITAMPILKAN DI BROWSER, OTOMATIS HILANG SAAT PRINT/PDF) --}}
    <div class="print-control-bar no-print">
        <form method="GET" action="{{ route('camat.laporan.cetak') }}" style="display: flex; align-items: center; gap: 10px; margin: 0;">
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <input type="hidden" name="bulan" value="{{ $bulan }}">
            <input type="hidden" name="jenis_layanan" value="{{ $jenisLayanan }}">
            <input type="hidden" name="status" value="{{ $status }}">

            <label for="select-kertas" style="font-size: 8.5pt; font-weight: 700; text-transform: uppercase;">Kertas:</label>
            <select id="select-kertas" name="kertas" onchange="this.form.submit()">
                <option value="A4" {{ ($kertasLower ?? 'a4') === 'a4' ? 'selected' : '' }}>A4 (210 x 297 mm)</option>
                <option value="F4" {{ ($kertasLower ?? '') === 'f4' ? 'selected' : '' }}>F4 / Folio (215 x 330 mm)</option>
                <option value="Letter" {{ ($kertasLower ?? '') === 'letter' ? 'selected' : '' }}>Letter (215.9 x 279.4 mm)</option>
                <option value="Legal" {{ ($kertasLower ?? '') === 'legal' ? 'selected' : '' }}>Legal (215.9 x 355.6 mm)</option>
            </select>

            <label for="select-orientasi" style="font-size: 8.5pt; font-weight: 700; text-transform: uppercase; margin-left: 6px;">Orientasi:</label>
            <select id="select-orientasi" name="orientasi" onchange="this.form.submit()">
                <option value="portrait" {{ ($orientasiLower ?? 'portrait') === 'portrait' ? 'selected' : '' }}>Portrait (Tegak)</option>
                <option value="landscape" {{ ($orientasiLower ?? '') === 'landscape' ? 'selected' : '' }}>Landscape (Mendatar)</option>
            </select>
        </form>

        <div style="display: flex; gap: 8px;">
            <button type="button" onclick="window.print()" class="btn-print" title="Cetak langsung atau simpan sebagai PDF">
                <span>🖨</span> Cetak / Simpan PDF
            </button>
            <a href="{{ route('camat.laporan', ['tahun' => $tahun, 'bulan' => $bulan, 'jenis_layanan' => $jenisLayanan, 'status' => $status]) }}" class="btn-back">
                <span>←</span> Kembali
            </a>
        </div>
    </div>

    {{-- LEMBAR DOKUMEN LAPORAN RESMI KECAMATAN JATISARI --}}
    <div class="sheet">

        {{-- 1. KOP SURAT RESMI DENGAN LOGO KABUPATEN KARAWANG --}}
        <div class="kop-wrapper">
            @php
                $logoUrl = isset($isPdf) && $isPdf && !empty($logoBase64)
                    ? $logoBase64
                    : asset('images/logo-karawang.png');
            @endphp
            <img src="{{ $logoUrl }}" alt="Logo Kabupaten Karawang" class="kop-logo">
            <div class="kop-text">
                <div class="kop-instansi">PEMERINTAH KABUPATEN KARAWANG</div>
                <div class="kop-kecamatan">KECAMATAN JATISARI</div>
                <div class="kop-alamat">Jl. Raya Jatisari No. 1, Jatisari, Kec. Jatisari, Kabupaten Karawang, Jawa Barat 41374</div>
            </div>
        </div>
        <hr class="kop-line-thick">
        <hr class="kop-line-thin">

        {{-- TANGGAL CETAK DI BAGIAN ATAS POJOK KANAN (SEBELUM JUDUL LAPORAN) --}}
        <div class="report-date-right">
            Karawang, {{ $tanggalCetak }}
        </div>

        {{-- 2. JUDUL DAN INFORMASI LAPORAN --}}
        <div class="report-title-block">
            <div class="report-title">REKAPITULASI LAPORAN PELAYANAN PATEN</div>
            <div class="report-periode-info">
                @php
                    $periodeText = 'Januari – Desember ' . $tahun;
                    if ($bulan !== 'semua' && !empty($bulan)) {
                        $periodeText = 'Bulan ' . ucfirst($bulan) . ' Tahun ' . $tahun;
                    }
                @endphp
                <strong>Periode:</strong> {{ $periodeText }}
                @if ($jenisLayanan !== 'semua')
                    &bull; <strong>Jenis Pelayanan:</strong> {{ $selectedLayananLabel }}
                @endif
                @if ($status !== 'semua')
                    &bull; <strong>Status:</strong> {{ $status === 'selesai' ? 'Selesai' : 'Dalam Proses' }}
                @endif
            </div>
        </div>

        {{-- 3. BAGIAN A — RINGKASAN HASIL PELAYANAN (DUA KOLOM BERDAMPINGAN) --}}
        <div class="section-heading">A. RINGKASAN HASIL PELAYANAN</div>

        @php
            $persenTuntas = $total > 0 ? round(($totalSelesai / $total) * 100, 1) : 0;
        @endphp

        <table class="summary-side-by-side">
            <tr>
                {{-- KOLOM KIRI: TABEL URAIAN DAN JUMLAH --}}
                <td class="summary-left-col">
                    <table class="formal-table">
                        <thead>
                            <tr>
                                <th style="text-align: left; width: 62%;">Uraian</th>
                                <th style="text-align: right; width: 38%;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total Berkas Pelayanan</td>
                                <td style="text-align: right; font-weight: bold;">{{ number_format($total, 0, ',', '.') }} Berkas</td>
                            </tr>
                            <tr>
                                <td>Berkas Selesai</td>
                                <td style="text-align: right; font-weight: bold;">{{ number_format($totalSelesai, 0, ',', '.') }} Berkas</td>
                            </tr>
                            <tr>
                                <td>Berkas Dalam Proses</td>
                                <td style="text-align: right; font-weight: bold;">{{ number_format($totalProses, 0, ',', '.') }} Berkas</td>
                            </tr>
                            <tr style="background-color: #f9f9f9;">
                                <td style="font-weight: bold;">Persentase Penyelesaian</td>
                                <td style="text-align: right; font-weight: bold;">{{ $persenTuntas }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                {{-- KOLOM KANAN: KETERANGAN RINGKAS SEJAJAR HORIZONTAL --}}
                <td class="summary-right-col">
                    <div class="summary-brief-box">
                        <div style="font-weight: bold; margin-bottom: 4px; text-decoration: underline;">Keterangan Ringkasan:</div>
                        <ul>
                            <li>Total pelayanan: <strong>{{ number_format($total, 0, ',', '.') }}</strong> berkas</li>
                            <li>Berkas selesai: <strong>{{ number_format($totalSelesai, 0, ',', '.') }}</strong> berkas</li>
                            <li>Dalam proses: <strong>{{ number_format($totalProses, 0, ',', '.') }}</strong> berkas</li>
                            <li>Penyelesaian: <strong>{{ $persenTuntas }}%</strong></li>
                        </ul>
                        <div style="font-size: 8.8pt; color: #333333; line-height: 1.35; margin-top: 4px;">
                            @if ($total === 0)
                                <em>Belum ada pengajuan berkas pelayanan yang terdaftar untuk filter yang dipilih.</em>
                            @elseif ($totalSelesai === $total)
                                <em>Seluruh permohonan pelayanan administrasi telah diselesaikan 100% oleh petugas.</em>
                            @elseif ($totalProses > 0)
                                <em>Sebagian permohonan saat ini masih dalam proses penanganan oleh petugas operator.</em>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- 4. BAGIAN B — RINCIAN REKAPITULASI PELAYANAN (TABEL 7 JENIS PELAYANAN) --}}
        <div class="section-heading">B. RINCIAN REKAPITULASI PELAYANAN</div>

        <table class="formal-table" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="width: 36px;">No.</th>
                    <th style="text-align: left;">Jenis Pelayanan</th>
                    <th style="width: 90px;">Total Berkas</th>
                    <th style="width: 75px;">Selesai</th>
                    <th style="width: 95px;">Dalam Proses</th>
                    <th style="width: 85px;">Persentase</th>
                    <th style="text-align: left; width: 145px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $index => $item)
                    @php
                        $pct = $item['count'] > 0 ? round(($item['selesai'] / $item['count']) * 100, 1) : 0;
                        if ($item['count'] === 0) {
                            $keterangan = 'Belum Ada Data';
                        } elseif ($item['proses'] === 0 && $item['selesai'] > 0) {
                            $keterangan = 'Pelayanan Selesai 100%';
                        } elseif ($item['selesai'] === 0 && $item['proses'] > 0) {
                            $keterangan = 'Sedang Diproses Operator';
                        } else {
                            $keterangan = 'Pelayanan Berjalan Optimal';
                        }
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td style="text-align: left; font-weight: 500;">{{ $item['label'] }}</td>
                        <td style="text-align: center;">{{ number_format($item['count'], 0, ',', '.') }}</td>
                        <td style="text-align: center;">{{ number_format($item['selesai'], 0, ',', '.') }}</td>
                        <td style="text-align: center;">{{ number_format($item['proses'], 0, ',', '.') }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $pct }}%</td>
                        <td style="text-align: left;">{{ $keterangan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 16px;">Belum Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right; padding-right: 12px;">TOTAL:</td>
                    <td style="text-align: center;">{{ number_format($total, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ number_format($totalSelesai, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ number_format($totalProses, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $persenTuntas }}%</td>
                    <td style="text-align: left;">Terverifikasi</td>
                </tr>
            </tfoot>
        </table>

        {{-- 5. BAGIAN TANDA TANGAN CAMAT (DI KANAN BAWAH) --}}
        <div class="signature-block">
            <div class="signature-box">
                <div style="font-weight: bold; margin-bottom: 65px; letter-spacing: 0.5px;">CAMAT JATISARI</div>
                <div class="signature-name">H. SURISNO.SKM.MAP</div>
                <div style="font-size: 9.5pt; margin-top: 2px;">NIP. 19690516 1989903 1 003</div>
            </div>
        </div>

    </div>

</body>
</html>
