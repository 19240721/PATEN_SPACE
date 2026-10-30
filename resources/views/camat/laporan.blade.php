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
</style>

{{-- KOP LAPORAN UNTUK CETAK --}}
<div class="print-header" style="text-align: center; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; justify-content: center; gap: 18px;">
        <img src="{{ asset('image/logo-kecamatan.png') }}" alt="Logo Karawang" style="width: 70px; height: auto;">
        <div>
            <h3 style="margin: 0; font-size: 1.15rem; text-transform: uppercase; letter-spacing: 0.05em;">PEMERINTAH KABUPATEN KARAWANG</h3>
            <h2 style="margin: 3px 0; font-size: 1.4rem; text-transform: uppercase;">KECAMATAN JATISARI</h2>
            <p style="margin: 0; font-size: 0.8rem; color: #333;">Jl. Raya Jatisari No. 1, Jatisari, Kec. Jatisari, Kabupaten Karawang, Jawa Barat 41374</p>
        </div>
    </div>
    <hr style="border: 0; border-top: 2px solid #000; margin-top: 12px; margin-bottom: 3px;">
    <hr style="border: 0; border-top: 1px solid #000; margin-top: 0; margin-bottom: 16px;">
    <h3 style="margin: 0 0 4px; font-size: 1.1rem; text-decoration: underline;">REKAPITULASI LAPORAN PELAYANAN PATEN</h3>
    <p style="margin: 0; font-size: 0.82rem;">
        Periode: {{ $bulan !== 'semua' ? ucfirst($bulan) . ' ' : 'Semua Bulan ' }}Tahun {{ $tahun }}
        @if ($jenisLayanan !== 'semua')
            &bull; Layanan: {{ $selectedLayananLabel }}
        @endif
        @if ($status !== 'semua')
            &bull; Status: {{ $status === 'selesai' ? 'Selesai' : 'Dalam Proses' }}
        @endif
    </p>
</div>

{{-- HEADING LAPORAN PELAYANAN --}}
<section class="dashboard-heading no-print">
    <div>
        <h1>Laporan Pelayanan</h1>
        <p>Rekapitulasi dan laporan kinerja pelayanan administrasi Kecamatan Jatisari</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <button type="button" onclick="window.print()" class="dashboard-primary-link" style="background: #1e3a5f; cursor: pointer; border: 0;">
            <span>🖨</span> Cetak Laporan
        </button>
        <button type="button" onclick="window.print()" class="dashboard-primary-link" style="background: #0870c9; cursor: pointer; border: 0;" title="Cetak atau Simpan sebagai PDF">
            <span>📥</span> Export PDF
        </button>
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
            <strong style="color: #0f766e;">98.4%</strong>
            <small style="color: #0f766e; font-size: 0.7rem;">Sesuai Standar Layanan</small>
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

    {{-- KOLOM TANDA TANGAN PENGESAHAN CAMAT --}}
    <div style="margin-top: 36px; padding: 20px 24px; display: flex; justify-content: flex-end;">
        <div style="text-align: center; width: 280px; font-size: 0.85rem; line-height: 1.6;">
            <div>Karawang, {{ now()->translatedFormat('d F Y') }}</div>
            <div style="font-weight: 700; margin-bottom: 60px;">Camat Kecamatan Jatisari,</div>
            <div style="font-weight: 800; text-decoration: underline;">CAMAT JATISARI</div>
            <div style="color: #475569; font-size: 0.78rem;">Pimpinan / Camat Kecamatan Jatisari</div>
            <div style="color: #475569; font-size: 0.78rem;">NIP. 19740512 199803 1 004</div>
        </div>
    </div>
</section>
@endsection
