@extends('layouts.camat')

@section('title', 'Laporan Pelayanan - PATEN SPACE Camat Jatisari')

@section('camat_content')
<style>
    .camat-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr)) auto;
        gap: 14px;
        align-items: flex-end;
    }

    .camat-filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .camat-filter-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #334155;
        letter-spacing: 0.01em;
        line-height: 1.2;
    }

    .camat-filter-label-spacer {
        font-size: 0.82rem;
        line-height: 1.2;
        user-select: none;
        visibility: hidden;
    }

    .camat-filter-select {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background-color: #ffffff;
        color: #1e293b;
        font-size: 0.84rem;
        font-weight: 500;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        cursor: pointer;
        box-sizing: border-box;
    }

    .camat-filter-select:focus {
        border-color: #796743;
        box-shadow: 0 0 0 3px rgba(121, 103, 67, 0.15);
    }

    .camat-btn-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        height: 42px;
    }

    .report-filter-button {
        height: 42px;
        padding: 0 22px;
        border: 0;
        border-radius: 6px;
        background: #796743;
        color: #ffffff;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(121, 103, 67, 0.2);
        transition: background-color 0.15s ease, transform 0.05s ease;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .report-filter-button:hover {
        background: #635435;
    }

    .report-filter-button:active {
        transform: translateY(1px);
    }

    .camat-filter-reset {
        display: inline-flex;
        align-items: center;
        padding: 0 8px;
        height: 42px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #796743;
        text-decoration: none;
        border-radius: 6px;
        transition: color 0.15s, background-color 0.15s;
        white-space: nowrap;
    }

    .camat-filter-reset:hover {
        color: #514328;
        background-color: #f5efe3;
    }

    @media (max-width: 960px) {
        .camat-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }
        .camat-filter-action-group {
            grid-column: span 2;
        }
        .camat-filter-label-spacer {
            display: none;
        }
    }

    @media (max-width: 540px) {
        .camat-filter-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .camat-filter-action-group {
            grid-column: span 1;
        }
        .camat-btn-wrapper {
            flex-direction: column;
            height: auto;
        }
        .camat-btn-wrapper .report-filter-button,
        .camat-filter-reset {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
    }

    /* CSS KHUSUS PRINT DOKUMEN RESMI (GAYA MICROSOFT WORD) */
    @media print {
        html, body {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            min-height: auto !important;
            height: auto !important;
            font-family: Arial, Helvetica, sans-serif !important;
            color: #000000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .app-shell, .main-panel, .dashboard-content {
            display: block !important;
            position: static !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
            overflow: visible !important;
            min-height: auto !important;
            height: auto !important;
        }

        .sidebar,
        .sidebar-toggle,
        .dashboard-topbar,
        .dashboard-heading,
        .dashboard-stats,
        .report-panel,
        .recent-panel,
        .camat-filter-form,
        .print-header,
        #printSettingsModal,
        .no-print {
            display: none !important;
        }

        .official-print-document {
            display: block !important;
            font-family: Arial, Helvetica, sans-serif !important;
            color: #000000 !important;
            background: #ffffff !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        thead {
            display: table-header-group !important;
        }

        tr {
            page-break-inside: avoid !important;
        }

        .signature-block {
            page-break-inside: avoid !important;
        }
    }

    .official-print-document {
        display: none;
    }
</style>

{{-- HEADING LAPORAN PELAYANAN --}}
<section class="dashboard-heading no-print">
    <div>
        <h1>Laporan Pelayanan</h1>
        <p>Rekapitulasi dan laporan kinerja pelayanan administrasi Kecamatan Jatisari</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <button type="button" onclick="openPrintModal('print')" class="dashboard-primary-link" style="background: #1e3a5f; cursor: pointer; border: 0;">
            <span>🖨</span> Cetak Laporan
        </button>
        <button type="button" onclick="openPrintModal('pdf')" class="dashboard-primary-link" style="background: #0870c9; cursor: pointer; border: 0;" title="Cetak atau Simpan sebagai PDF">
            <span>📥</span> Export PDF
        </button>
        <a href="{{ route('camat.laporan.cetak', ['tahun' => $tahun, 'bulan' => $bulan, 'jenis_layanan' => $jenisLayanan, 'status' => $status]) }}" target="_blank" class="dashboard-primary-link" style="background: #796743; text-decoration: none;" title="Buka Pratinjau Dokumen Cetak Halaman Penuh">
            <span>👁</span> Pratinjau
        </a>
    </div>
</section>

{{-- KARTU TOTAL PELAYANAN & SUMMARY --}}
<section class="dashboard-stats" style="grid-template-columns: repeat(4, minmax(0, 1fr)); margin-bottom: 22px;">
    <div class="dashboard-stat total-stat" style="border-left: 4px solid #0870c9;">
        <span class="stat-symbol">▥</span>
        <div>
            <span>Total Pelayanan</span>
            <strong>{{ number_format($total, 0, ',', '.') }}</strong>
            <small style="color: #64748b; font-size: 0.7rem;">Periode {{ $tahun }}</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #16a34a;">
        <span class="stat-symbol green">✓</span>
        <div>
            <span>Pelayanan Selesai</span>
            <strong style="color: #15803d;">{{ number_format($totalSelesai, 0, ',', '.') }}</strong>
            <small style="color: #16a34a; font-size: 0.7rem;">{{ $total > 0 ? round(($totalSelesai / $total) * 100, 1) : 0 }}% Tuntas</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #ea580c;">
        <span class="stat-symbol gold">⏳</span>
        <div>
            <span>Dalam Proses</span>
            <strong style="color: #c2410c;">{{ number_format($totalProses, 0, ',', '.') }}</strong>
            <small style="color: #ea580c; font-size: 0.7rem;">Sedang Berjalan</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #00a997;">
        <span class="stat-symbol" style="background: #ccfbf1; color: #0f766e;">🎯</span>
        <div>
            <span>Ketepatan Waktu</span>
            <strong>—</strong>
            <small style="color: #64748b; font-size: 0.7rem;">Belum Ada Data</small>
        </div>
    </div>
</section>

{{-- FILTER TAHUN, BULAN, JENIS PELAYANAN, DAN STATUS --}}
<section class="dashboard-panel report-panel no-print" style="margin-bottom: 22px; padding: 20px 24px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);">
    <form method="GET" action="{{ route('camat.laporan') }}" class="camat-filter-form" style="padding: 0; margin: 0; border: 0;">
        <div class="camat-filter-grid">
            {{-- 1. TAHUN --}}
            <div class="camat-filter-group">
                <label for="filter-tahun" class="camat-filter-label">Tahun</label>
                <select id="filter-tahun" name="tahun" class="camat-filter-select" aria-label="Filter Tahun">
                    <option value="2026" {{ $tahun === '2026' ? 'selected' : '' }}>2026</option>
                    <option value="2025" {{ $tahun === '2025' ? 'selected' : '' }}>2025</option>
                </select>
            </div>

            {{-- 2. BULAN --}}
            <div class="camat-filter-group">
                <label for="filter-bulan" class="camat-filter-label">Bulan</label>
                <select id="filter-bulan" name="bulan" class="camat-filter-select" aria-label="Filter Bulan">
                    <option value="semua" {{ $bulan === 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="januari" {{ strtolower($bulan) === 'januari' ? 'selected' : '' }}>Januari</option>
                    <option value="februari" {{ strtolower($bulan) === 'februari' ? 'selected' : '' }}>Februari</option>
                    <option value="maret" {{ strtolower($bulan) === 'maret' ? 'selected' : '' }}>Maret</option>
                    <option value="april" {{ strtolower($bulan) === 'april' ? 'selected' : '' }}>April</option>
                    <option value="mei" {{ strtolower($bulan) === 'mei' ? 'selected' : '' }}>Mei</option>
                    <option value="juni" {{ strtolower($bulan) === 'juni' ? 'selected' : '' }}>Juni</option>
                    <option value="juli" {{ strtolower($bulan) === 'juli' ? 'selected' : '' }}>Juli</option>
                    <option value="agustus" {{ strtolower($bulan) === 'agustus' ? 'selected' : '' }}>Agustus</option>
                    <option value="september" {{ strtolower($bulan) === 'september' ? 'selected' : '' }}>September</option>
                    <option value="oktober" {{ strtolower($bulan) === 'oktober' ? 'selected' : '' }}>Oktober</option>
                    <option value="november" {{ strtolower($bulan) === 'november' ? 'selected' : '' }}>November</option>
                    <option value="desember" {{ strtolower($bulan) === 'desember' ? 'selected' : '' }}>Desember</option>
                </select>
            </div>

            {{-- 3. JENIS PELAYANAN --}}
            <div class="camat-filter-group">
                <label for="filter-jenis" class="camat-filter-label">Jenis Pelayanan</label>
                <select id="filter-jenis" name="jenis_layanan" class="camat-filter-select" aria-label="Filter Jenis Layanan">
                    <option value="semua" {{ $jenisLayanan === 'semua' ? 'selected' : '' }}>Semua</option>
                    @foreach ($allServices as $s)
                        <option value="{{ $s['key'] }}" {{ $jenisLayanan === $s['key'] ? 'selected' : '' }}>{{ $s['label'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 4. STATUS --}}
            <div class="camat-filter-group">
                <label for="filter-status" class="camat-filter-label">Status</label>
                <select id="filter-status" name="status" class="camat-filter-select" aria-label="Filter Status">
                    <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="selesai" {{ strtolower($status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dalam_proses" {{ in_array(strtolower($status), ['dalam_proses', 'dalam proses', 'proses']) ? 'selected' : '' }}>Dalam Proses</option>
                </select>
            </div>

            {{-- 5. TOMBOL TERAPKAN (SEJAJAR HORIZONTAL DI SEBELAH KANAN STATUS) --}}
            <div class="camat-filter-group camat-filter-action-group">
                <span class="camat-filter-label-spacer" aria-hidden="true">&nbsp;</span>
                <div class="camat-btn-wrapper">
                    <button type="submit" class="report-filter-button">Terapkan</button>
                    @if ($tahun !== '2026' || $bulan !== 'semua' || $jenisLayanan !== 'semua' || $status !== 'semua')
                        <a href="{{ route('camat.laporan') }}" class="camat-filter-reset">Reset</a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</section>

{{-- TABEL REKAP PELAYANAN --}}
<section class="dashboard-panel recent-panel">
    <div class="panel-heading no-print">
        <div>
            <h2>Tabel Rekap Pelayanan</h2>
            <p>Rekapitulasi berkas pelayanan administrasi PATEN Kecamatan Jatisari</p>
        </div>
        <span class="report-date">{{ $tanggalCetak }}</span>
    </div>

    <div class="table-scroll">
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Jenis Pelayanan</th>
                    <th style="text-align: center;">Total Berkas</th>
                    <th style="text-align: center;">Selesai</th>
                    <th style="text-align: center;">Proses</th>
                    <th style="text-align: center;">Persentase</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $index => $item)
                    @php
                        $pct = $item['count'] > 0 ? round(($item['selesai'] / $item['count']) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td><strong>0{{ $loop->iteration }}</strong></td>
                        <td>
                            <strong style="color: #1e3a5f;">{{ $item['label'] }}</strong>
                            <div style="font-size: 0.72rem; color: #64748b;">Kecamatan Jatisari</div>
                        </td>
                        <td style="text-align: center;">
                            <strong style="color: #0870c9; font-size: 0.95rem;">{{ number_format($item['count'], 0, ',', '.') }}</strong>
                        </td>
                        <td style="text-align: center;">
                            <span class="status-pill selesai">{{ number_format($item['selesai'], 0, ',', '.') }}</span>
                        </td>
                        <td style="text-align: center;">
                            @if ($item['proses'] > 0)
                                <span class="status-pill proses">{{ $item['proses'] }}</span>
                            @else
                                <span style="color: #94a3b8;">0</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <strong style="color: #15803d; font-size: 0.85rem;">{{ $pct }}%</strong>
                        </td>
                        <td>
                            <span style="font-size: 0.75rem; color: #475569;">
                                @if ($item['count'] === 0)
                                    Belum Ada Data
                                @elseif ($item['proses'] === 0 && $item['selesai'] > 0)
                                    Pelayanan Selesai 100%
                                @elseif ($item['selesai'] === 0 && $item['proses'] > 0)
                                    Sedang Diproses Operator
                                @else
                                    Pelayanan Berjalan Optimal
                                @endif
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state" style="text-align: center; padding: 36px 20px; color: #64748b; font-size: 0.88rem;">
                            Belum Ada Data
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background: #f8fafc; font-weight: 800; border-top: 2px solid #cbd5e1;">
                    <td colspan="2" style="text-align: right; padding-right: 14px;">TOTAL REKAPITULASI:</td>
                    <td style="text-align: center; color: #0870c9; font-size: 1.05rem;">{{ number_format($total, 0, ',', '.') }}</td>
                    <td style="text-align: center; color: #15803d; font-size: 1rem;">{{ number_format($totalSelesai, 0, ',', '.') }}</td>
                    <td style="text-align: center; color: #ea580c; font-size: 1rem;">{{ number_format($totalProses, 0, ',', '.') }}</td>
                    <td style="text-align: center; color: #15803d; font-size: 0.95rem;">
                        {{ $total > 0 ? round(($totalSelesai / $total) * 100, 1) : 0 }}%
                    </td>
                    <td>
                        <span class="badge-camat success">Terverifikasi</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</section>

{{-- DOKUMEN CETAK & PDF RESMI (HANYA MUNCUL KETIKA DICETAK / EXPORT PDF, GAYA FORMAL MICROSOFT WORD) --}}
<div class="official-print-document">
    @include('camat.partials.laporan-dokumen')
</div>

{{-- MODAL PENGATURAN CETAK & EXPORT PDF --}}
<div id="printSettingsModal" class="no-print" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); z-index: 99999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: #ffffff; width: 92%; max-width: 480px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); overflow: hidden;">
        <div style="background: #1e3a5f; color: #ffffff; padding: 16px 22px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span id="modalIcon" style="font-size: 1.3rem;">🖨</span>
                <h3 id="modalTitle" style="margin: 0; font-size: 1.05rem; font-weight: 700;">Pengaturan Cetak Dokumen</h3>
            </div>
            <button type="button" onclick="closePrintModal()" style="background: transparent; border: 0; color: #ffffff; font-size: 1.4rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <div style="padding: 20px 22px;">
            <div id="modalDescription" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; margin-bottom: 18px; font-size: 0.82rem; color: #475569; line-height: 1.45;">
                Format dokumen resmi standar administrasi Kecamatan Jatisari (Font Arial, Tinta Hitam, Kop Kabupaten Karawang, Tanpa Blok Warna).
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
                <div>
                    <label for="modal-kertas" style="display: block; font-size: 0.82rem; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Ukuran Kertas</label>
                    <select id="modal-kertas" style="width: 100%; height: 40px; padding: 0 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.84rem; font-weight: 600; outline: none; background: #fff;">
                        <option value="A4" selected>A4 (210 x 297 mm)</option>
                        <option value="F4">F4 / Folio (215 x 330 mm)</option>
                        <option value="Letter">Letter (215.9 x 279.4 mm)</option>
                        <option value="Legal">Legal (215.9 x 355.6 mm)</option>
                    </select>
                </div>
                <div>
                    <label for="modal-orientasi" style="display: block; font-size: 0.82rem; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Orientasi</label>
                    <select id="modal-orientasi" style="width: 100%; height: 40px; padding: 0 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.84rem; font-weight: 600; outline: none; background: #fff;">
                        <option value="portrait" selected>Portrait (Tegak)</option>
                        <option value="landscape">Landscape (Mendatar)</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <button type="button" id="modalActionButton" onclick="executePrintOrExport()" class="report-filter-button" style="width: 100%; height: 42px; background: #796743; justify-content: center; font-size: 0.88rem; border-radius: 6px;">
                    <span>🖨</span> Cetak Dokumen Sekarang
                </button>
                <a id="modalPreviewLink" href="{{ route('camat.laporan.cetak', ['tahun' => $tahun, 'bulan' => $bulan, 'jenis_layanan' => $jenisLayanan, 'status' => $status]) }}" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; height: 38px; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; color: #334155; font-size: 0.82rem; font-weight: 600; gap: 6px;">
                    <span>👁</span> Buka Dokumen Penuh di Tab Baru
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPrintMode = 'print';

    function openPrintModal(mode) {
        currentPrintMode = mode;
        const modal = document.getElementById('printSettingsModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalIcon = document.getElementById('modalIcon');
        const actionBtn = document.getElementById('modalActionButton');
        const modalDesc = document.getElementById('modalDescription');

        if (mode === 'pdf') {
            modalTitle.innerText = 'Pengaturan Export PDF Laporan';
            modalIcon.innerText = '📥';
            actionBtn.innerHTML = '<span>📥</span> Export / Simpan sebagai PDF';
            actionBtn.style.background = '#0870c9';
            if (modalDesc) {
                modalDesc.innerHTML = 'Format dokumen resmi standar administrasi Kecamatan Jatisari. Pada jendela cetak browser yang terbuka, pilih tujuan (Destination): <strong>Simpan sebagai PDF (Save as PDF)</strong>.';
            }
        } else {
            modalTitle.innerText = 'Pengaturan Cetak Dokumen Laporan';
            modalIcon.innerText = '🖨';
            actionBtn.innerHTML = '<span>🖨</span> Cetak Dokumen Sekarang';
            actionBtn.style.background = '#796743';
            if (modalDesc) {
                modalDesc.innerHTML = 'Format dokumen resmi standar administrasi Kecamatan Jatisari (Font Arial, Tinta Hitam, Kop Kabupaten Karawang, Tanpa Blok Warna).';
            }
        }

        updatePreviewLink();
        modal.style.display = 'flex';
    }

    function closePrintModal() {
        document.getElementById('printSettingsModal').style.display = 'none';
    }

    function updatePreviewLink() {
        const kertas = document.getElementById('modal-kertas').value;
        const orientasi = document.getElementById('modal-orientasi').value;
        const baseUrl = "{{ route('camat.laporan.cetak') }}";
        const params = new URLSearchParams(window.location.search);
        params.set('kertas', kertas);
        params.set('orientasi', orientasi);
        document.getElementById('modalPreviewLink').href = baseUrl + '?' + params.toString();
    }

    document.getElementById('modal-kertas').addEventListener('change', updatePreviewLink);
    document.getElementById('modal-orientasi').addEventListener('change', updatePreviewLink);

    function applyDynamicPaperStyle(kertas, orientasi) {
        let styleEl = document.getElementById('dynamic-paper-style');
        if (!styleEl) {
            styleEl = document.createElement('style');
            styleEl.id = 'dynamic-paper-style';
            document.head.appendChild(styleEl);
        }

        let pageSize = 'A4 ' + orientasi;
        if (kertas === 'F4') {
            pageSize = orientasi === 'landscape' ? '330mm 215mm' : '215mm 330mm';
        } else if (kertas === 'Letter') {
            pageSize = 'letter ' + orientasi;
        } else if (kertas === 'Legal') {
            pageSize = 'legal ' + orientasi;
        }

        styleEl.innerHTML = `@page { size: ${pageSize}; margin: 15mm 18mm 15mm 18mm; }`;
    }

    function executePrintOrExport() {
        const kertas = document.getElementById('modal-kertas').value;
        const orientasi = document.getElementById('modal-orientasi').value;

        applyDynamicPaperStyle(kertas, orientasi);
        closePrintModal();

        const baseUrl = "{{ route('camat.laporan.cetak') }}";
        const params = new URLSearchParams(window.location.search);
        params.set('kertas', kertas);
        params.set('orientasi', orientasi);
        params.set('auto_print', '1');
        params.set('_t', Date.now());
        const printUrl = baseUrl + '?' + params.toString();

        printDocumentViaFrame(printUrl);
    }

    function printDocumentViaFrame(url) {
        let iframe = document.getElementById('officialPrintFrame');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'officialPrintFrame';
            iframe.name = 'officialPrintFrame';
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '10px';
            iframe.style.height = '10px';
            iframe.style.opacity = '0.01';
            iframe.style.zIndex = '-9999';
            iframe.style.border = '0';
            document.body.appendChild(iframe);
        }

        iframe.src = url;
    }
</script>
@endsection
