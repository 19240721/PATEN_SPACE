@extends('layouts.camat')

@section('title', 'Monitoring Pelayanan - PATEN SPACE Camat Jatisari')

@section('camat_content')
<style>
    .camat-filter-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.4fr) auto;
        gap: 16px;
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

    .badge-kinerja {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
        line-height: 1.3;
    }

    .badge-kinerja.kinerja-belum-ada {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .badge-kinerja.kinerja-berjalan {
        background: #ffedd5;
        color: #c2410c;
        border: 1px solid #fed7aa;
    }

    .badge-kinerja.kinerja-sebagian {
        background: #fef9c3;
        color: #854d0e;
        border: 1px solid #fde047;
    }

    .badge-kinerja.kinerja-selesai {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    @media (max-width: 820px) {
        .camat-filter-grid {
            grid-template-columns: 1fr 1fr;
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

{{-- HEADING MONITORING PELAYANAN --}}
<section class="dashboard-heading">
    <div>
        <h1>Monitoring Pelayanan</h1>
        <p>Pantauan progres penyelesaian dan ringkasan aktivitas pelayanan Kecamatan Jatisari</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('camat.laporan') }}" class="dashboard-primary-link" style="background: #1e3a5f;">
            <span>▥</span> Lihat Laporan
        </a>
    </div>
</section>

{{-- KARTU RINGKASAN MONITORING --}}
<section class="dashboard-stats" style="grid-template-columns: repeat(4, minmax(0, 1fr)); margin-bottom: 22px;">
    <div class="dashboard-stat total-stat" style="border-left: 4px solid #0870c9;">
        <span class="stat-symbol">▤</span>
        <div>
            <span>Total Pelayanan</span>
            <strong>{{ number_format($totalPermohonan, 0, ',', '.') }}</strong>
            <small style="color: #64748b; font-size: 0.7rem;">Berkas Terdaftar</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #16a34a;">
        <span class="stat-symbol green">✓</span>
        <div>
            <span>Selesai</span>
            <strong style="color: #15803d;">{{ number_format($totalSelesai, 0, ',', '.') }}</strong>
            <small style="color: #16a34a; font-size: 0.7rem;">{{ $totalPermohonan > 0 ? round(($totalSelesai / $totalPermohonan) * 100, 1) : 0 }}% Penyelesaian</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #ea580c;">
        <span class="stat-symbol gold">⏳</span>
        <div>
            <span>Dalam Proses</span>
            <strong style="color: #c2410c;">{{ number_format($totalProses, 0, ',', '.') }}</strong>
            <small style="color: #ea580c; font-size: 0.7rem;">Sedang Ditangani</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #2563eb;">
        <span class="stat-symbol blue">⚡</span>
        <div>
            <span>Rata-rata Waktu</span>
            <strong>1 - 2 Hari</strong>
            <small style="color: #2563eb; font-size: 0.7rem;">SOP Terpenuhi</small>
        </div>
    </div>
</section>

{{-- PANEL UTAMA TABEL MONITORING DENGAN FILTER --}}
<section class="dashboard-panel recent-panel">
    <div class="panel-heading">
        <div>
            <h2>Tabel Monitoring Pelayanan</h2>
            <p>Rekapitulasi progres pelayanan administrasi Kecamatan Jatisari</p>
        </div>
        <span class="chart-year">Tahun {{ $year }}</span>
    </div>

    {{-- FILTER PERIODE & JENIS PELAYANAN --}}
    <form method="GET" action="{{ route('camat.monitoring') }}" class="camat-filter-form" style="background: #ffffff; padding: 18px 24px; border-bottom: 1px solid #e2e8f0; margin-bottom: 0;">
        <div class="camat-filter-grid">
            {{-- 1. PERIODE --}}
            <div class="camat-filter-group">
                <label for="filter-periode" class="camat-filter-label">Periode</label>
                <select id="filter-periode" name="periode" class="camat-filter-select" aria-label="Filter Periode">
                    <option value="semua" {{ $periode === 'semua' ? 'selected' : '' }}>Semua Periode</option>
                    <option value="bulan_ini" {{ $periode === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="triwulan_1" {{ $periode === 'triwulan_1' ? 'selected' : '' }}>Triwulan I (Jan - Mar)</option>
                    <option value="triwulan_2" {{ $periode === 'triwulan_2' ? 'selected' : '' }}>Triwulan II (Apr - Jun)</option>
                    <option value="triwulan_3" {{ $periode === 'triwulan_3' ? 'selected' : '' }}>Triwulan III (Jul - Sep)</option>
                    <option value="triwulan_4" {{ $periode === 'triwulan_4' ? 'selected' : '' }}>Triwulan IV (Okt - Des)</option>
                </select>
            </div>

            {{-- 2. JENIS PELAYANAN --}}
            <div class="camat-filter-group">
                <label for="filter-jenis" class="camat-filter-label">Jenis Pelayanan</label>
                <select id="filter-jenis" name="jenis_layanan" class="camat-filter-select" aria-label="Filter Jenis Layanan">
                    <option value="semua" {{ $selectedLayanan === 'semua' ? 'selected' : '' }}>Semua Jenis Pelayanan</option>
                    @foreach ($allServices as $s)
                        <option value="{{ $s['key'] }}" {{ $selectedLayanan === $s['key'] ? 'selected' : '' }}>{{ $s['label'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 3. TOMBOL TERAPKAN FILTER --}}
            <div class="camat-filter-group camat-filter-action-group">
                <span class="camat-filter-label-spacer" aria-hidden="true">&nbsp;</span>
                <div class="camat-btn-wrapper">
                    <button type="submit" class="report-filter-button">Terapkan Filter</button>
                    @if ($periode !== 'semua' || $selectedLayanan !== 'semua')
                        <a href="{{ route('camat.monitoring') }}" class="camat-filter-reset">Reset</a>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Jenis Pelayanan</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: center;">Selesai</th>
                    <th style="text-align: center;">Proses</th>
                    <th style="width: 170px;">Persentase Selesai</th>
                    <th style="text-align: center; width: 130px;">Status Kinerja</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $index => $item)
                    @php
                        $count = $item['count'];
                        $selesai = $item['selesai'];
                        $percentage = $count > 0 ? round(($selesai / $count) * 100, 1) : 0;

                        if ($count === 0) {
                            $statusKinerja = 'Belum Ada Data';
                            $statusBadgeClass = 'kinerja-belum-ada';
                        } elseif ($selesai === 0) {
                            $statusKinerja = 'Sedang Berjalan';
                            $statusBadgeClass = 'kinerja-berjalan';
                        } elseif ($selesai < $count) {
                            $statusKinerja = 'Sebagian Selesai';
                            $statusBadgeClass = 'kinerja-sebagian';
                        } else {
                            $statusKinerja = 'Selesai';
                            $statusBadgeClass = 'kinerja-selesai';
                        }
                    @endphp
                    <tr>
                        <td><strong>0{{ $loop->iteration }}</strong></td>
                        <td>
                            <div>
                                <strong style="color: #1e293b; font-size: 0.88rem; display: block; line-height: 1.3;">{{ $item['label'] }}</strong>
                                <span style="font-size: 0.72rem; color: #64748b;">Kecamatan Jatisari</span>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <strong style="font-size: 0.95rem; color: #0f172a;">{{ number_format($item['count'], 0, ',', '.') }}</strong>
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
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="flex: 1; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                    <div style="width: {{ $percentage }}%; height: 100%; background: #16a34a; border-radius: 4px;"></div>
                                </div>
                                <span style="font-size: 0.76rem; font-weight: 700; color: {{ $percentage > 0 ? '#15803d' : '#94a3b8' }}; min-width: 44px;">{{ $percentage }}%</span>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge-kinerja {{ $statusBadgeClass }}">{{ $statusKinerja }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">Tidak ada data pelayanan yang sesuai dengan filter.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background: #f8fafc; font-weight: 800; border-top: 2px solid #cbd5e1;">
                    <td colspan="2" style="text-align: right; padding-right: 16px;">TOTAL KESELURUHAN:</td>
                    <td style="text-align: center; color: #0870c9; font-size: 1.05rem;">{{ number_format($totalPermohonan, 0, ',', '.') }}</td>
                    <td style="text-align: center; color: #15803d; font-size: 1rem;">{{ number_format($totalSelesai, 0, ',', '.') }}</td>
                    <td style="text-align: center; color: #c2410c; font-size: 1rem;">{{ number_format($totalProses, 0, ',', '.') }}</td>
                    <td colspan="2">
                        <span style="color: #15803d; font-size: 0.8rem;">
                            Efektivitas Penyelesaian: <strong>{{ $totalPermohonan > 0 ? round(($totalSelesai / $totalPermohonan) * 100, 1) : 0 }}%</strong>
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
@endsection
