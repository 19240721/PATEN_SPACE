<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelayanan PATEN - Kecamatan Jatisari</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-karawang.png') }}">
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #1a1a1a;
            background: #e2e8f0;
            margin: 0;
            padding: 24px 0;
        }

        /* Top control bar for browser viewing */
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-family: system-ui, -apple-system, sans-serif;
        }

        .print-control-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .print-control-left strong {
            font-size: 13px;
            color: #f8fafc;
        }

        .print-control-left span {
            font-size: 11px;
            color: #94a3b8;
        }

        .print-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-print {
            background: #796743;
            color: #ffffff;
            border: 0;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background 0.15s;
        }

        .btn-print:hover {
            background: #635435;
        }

        .btn-back {
            background: #334155;
            color: #e2e8f0;
            border: 0;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back:hover {
            background: #475569;
            color: #fff;
        }

        /* Document paper sheet */
        .sheet {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px 48px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }

        /* KOP SURAT */
        .kop-table {
            width: 100%;
            border: none;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .kop-table td {
            border: none;
            padding: 0;
        }

        .kop-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 68px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 80px;
        }

        .kop-text h3 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #000;
            text-transform: uppercase;
        }

        .kop-text h2 {
            margin: 3px 0;
            font-size: 19px;
            font-weight: 900;
            letter-spacing: 1.5px;
            color: #000;
            text-transform: uppercase;
        }

        .kop-text .kop-paten {
            font-size: 13px;
            font-weight: bold;
            color: #222;
            margin-top: 2px;
        }

        .kop-text .kop-address {
            font-size: 10px;
            color: #444;
            margin-top: 3px;
        }

        /* Title */
        .doc-title {
            text-align: center;
            margin-bottom: 18px;
        }

        .doc-title h1 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .doc-title p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #555;
        }

        /* Filter Info Badge */
        .filter-badge-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 11px;
        }

        .filter-badge-box strong {
            color: #1e293b;
        }

        .filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 6px;
        }

        .filter-tag {
            background: #e2e8f0;
            color: #334155;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }

        /* Summary Total & Rekap */
        .summary-wrap {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }

        .total-box {
            flex: 1;
            border: 1px solid #796743;
            background: #fdfbf7;
            border-radius: 6px;
            padding: 14px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .total-box .total-number {
            font-size: 28px;
            font-weight: 900;
            color: #796743;
            line-height: 1;
        }

        .total-box .total-label {
            font-size: 11px;
            font-weight: bold;
            color: #555;
            margin-top: 6px;
            text-transform: uppercase;
        }

        .rekap-box {
            flex: 2;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 0 0 8px;
            text-transform: uppercase;
            color: #222;
        }

        /* Tables */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10.5px;
        }

        table.data-table th {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            text-align: center;
            border: 1px solid #94a3b8;
            padding: 7px 6px;
        }

        table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }

        table.data-table tr:nth-child(even) td {
            background: #fbfcfe;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-badge.baru { background: #e0f2fe; color: #0369a1; }
        .status-badge.proses { background: #fef3c7; color: #b45309; }
        .status-badge.selesai { background: #dcfce7; color: #15803d; }
        .status-badge.ditolak { background: #ffe4e6; color: #be123c; }

        /* Signature block */
        .signature-wrap {
            margin-top: 32px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 250px;
            text-align: center;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Print styles */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }

            .print-control-bar {
                display: none !important;
            }

            .sheet {
                max-width: 100% !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            table.data-table {
                page-break-inside: auto;
            }

            table.data-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .summary-wrap {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    {{-- Screen Only Action Bar --}}
    @if(!($isPdf ?? false))
    <div class="print-control-bar">
        <div class="print-control-left">
            <strong>Pratinjau Cetak Laporan</strong>
            <span>| {{ $totalPelayanan }} data terdaftar &bull; Dicetak: {{ now()->translatedFormat('d F Y') }}</span>
        </div>
        <div class="print-actions">
            <button type="button" class="btn-print" onclick="window.print()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Dokumen</span>
            </button>
            <a href="{{ route('laporan') }}" class="btn-back">
                &larr; Kembali ke Data dan Laporan
            </a>
        </div>
    </div>
    @endif

    <div class="sheet">
        {{-- KOP SURAT --}}
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
                    <h3>Pemerintah Kabupaten Karawang</h3>
                    <h2>Kecamatan Jatisari</h2>
                    <div class="kop-paten">Pelayanan Administrasi Terpadu Kecamatan (PATEN)</div>
                    <div class="kop-address">Jl. Raya Jatisari No. 1, Kecamatan Jatisari, Kabupaten Karawang, Jawa Barat - 41374</div>
                </td>
            </tr>
        </table>

        {{-- JUDUL LAPORAN --}}
        <div class="doc-title">
            <h1>Laporan Data Pelayanan PATEN</h1>
            <p>Kecamatan Jatisari &bull; Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}</p>
        </div>

        {{-- INFORMASI FILTER JIKA ADA --}}
        @php
            $hasFilter = !empty($filters['q']) || !empty($filters['jenis_layanan']) || !empty($filters['status']) || !empty($filters['tanggal_awal']) || !empty($filters['tanggal_akhir']);
        @endphp

        @if($hasFilter)
        <div class="filter-badge-box">
            <strong>Kriteria Filter yang Diterapkan:</strong>
            <div class="filter-tags">
                @if(!empty($filters['q']))
                    <span class="filter-tag">Pencarian: "{{ $filters['q'] }}"</span>
                @endif
                @if(!empty($filters['jenis_layanan']))
                    <span class="filter-tag">Layanan: {{ $filters['jenis_layanan'] }}</span>
                @endif
                @if(!empty($filters['status']))
                    <span class="filter-tag">Status: {{ $filters['status'] }}</span>
                @endif
                @if(!empty($filters['tanggal_awal']) || !empty($filters['tanggal_akhir']))
                    <span class="filter-tag">
                        Periode: {{ $filters['tanggal_awal'] ?? 'Awal' }} s/d {{ $filters['tanggal_akhir'] ?? 'Sekarang' }}
                    </span>
                @endif
            </div>
        </div>
        @endif

        {{-- RINGKASAN & REKAP --}}
        <div class="summary-wrap">
            <div class="total-box">
                <div class="total-number">{{ number_format($totalPelayanan, 0, ',', '.') }}</div>
                <div class="total-label">Total Pelayanan</div>
            </div>

            <div class="rekap-box">
                <div class="section-title">Rekapitulasi Berdasarkan Jenis Layanan</div>
                <table class="data-table" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th width="8%">No</th>
                            <th>Jenis Pelayanan</th>
                            <th width="20%">Jumlah</th>
                            <th width="20%">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapLayanan as $jenis => $jumlah)
                            @php
                                $persentase = $totalPelayanan > 0 ? round(($jumlah / $totalPelayanan) * 100) : 0;
                            @endphp
                            <tr>
                                <td class="center">{{ $loop->iteration }}</td>
                                <td>{{ $jenis === 'KK' ? 'Kartu Keluarga (KK)' : $jenis }}</td>
                                <td class="right"><strong>{{ number_format($jumlah, 0, ',', '.') }}</strong></td>
                                <td class="right">{{ $persentase }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="center" style="color: #94a3b8; padding: 12px;">
                                    Belum ada data pelayanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TABEL RINCIAN DATA --}}
        <div class="section-title">Daftar Rincian Pendaftaran ({{ $pendaftarans->count() }} Data)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="16%">NIK</th>
                    <th>Nama Pemohon</th>
                    <th width="16%">Jenis Layanan</th>
                    <th width="15%">Petugas</th>
                    <th width="13%">Tanggal</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftarans as $data)
                    <tr>
                        <td class="center">{{ $loop->iteration }}</td>
                        <td class="center">{{ $data->nik ?? '-' }}</td>
                        <td><strong>{{ $data->nama_lengkap ?? '-' }}</strong></td>
                        <td>{{ $data->jenis_layanan === 'KK' ? 'Kartu Keluarga' : ($data->jenis_layanan ?? '-') }}</td>
                        <td>{{ $data->petugas ?? '-' }}</td>
                        <td class="center">
                            {{ $data->tanggal_daftar ? \Illuminate\Support\Facades\Date::parse($data->tanggal_daftar)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="center">
                            <span class="status-badge {{ strtolower($data->status ?? 'baru') }}">
                                {{ $data->status ?? 'Baru' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="center" style="color: #94a3b8; padding: 24px;">
                            Tidak ada data pendaftaran yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- TANDA TANGAN RESMI --}}
        <div class="signature-wrap">
            <div class="signature-box">
                <div>Jatisari, {{ now()->translatedFormat('d F Y') }}</div>
                <div>Petugas Pelayanan PATEN,</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ auth()->user()->name ?? 'Petugas Kecamatan' }}</div>
                <div style="font-size: 10px; color: #555;">{{ auth()->user()->peran_layanan ?? 'Operator PATEN' }}</div>
            </div>
        </div>
    </div>

</body>
</html>
