@extends('layouts.camat')

@section('title', 'Monitoring Pelayanan - PATEN SPACE Camat Jatisari')

@section('camat_content')
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
    <form method="GET" action="{{ route('camat.monitoring') }}" class="report-filters" style="background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; margin-bottom: 0; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <label style="font-size: 0.8rem; font-weight: 700; color: #475569;">Periode:</label>
            <select name="periode" aria-label="Filter Periode" onchange="this.form.submit()">
                <option value="semua" {{ $periode === 'semua' ? 'selected' : '' }}>Semua Periode (2026)</option>
                <option value="bulan_ini" {{ $periode === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini (September 2026)</option>
                <option value="triwulan_3" {{ $periode === 'triwulan_3' ? 'selected' : '' }}>Triwulan III (Jul - Sep)</option>
                <option value="triwulan_2" {{ $periode === 'triwulan_2' ? 'selected' : '' }}>Triwulan II (Apr - Jun)</option>
                <option value="triwulan_1" {{ $periode === 'triwulan_1' ? 'selected' : '' }}>Triwulan I (Jan - Mar)</option>
            </select>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            <label style="font-size: 0.8rem; font-weight: 700; color: #475569;">Jenis Pelayanan:</label>
            <select name="jenis_layanan" aria-label="Filter Jenis Pelayanan" onchange="this.form.submit()">
                <option value="semua">Semua Jenis Pelayanan</option>
                @foreach ($allServices as $s)
                    <option value="{{ $s['key'] }}" {{ $selectedLayanan === $s['key'] ? 'selected' : '' }}>{{ $s['label'] }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="report-filter-button">Terapkan Filter</button>

        @if ($periode !== 'semua' || $selectedLayanan !== 'semua')
            <a href="{{ route('camat.monitoring') }}" class="report-reset" style="margin-left: 8px;">Reset</a>
        @endif
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
                    <th style="text-align: center; width: 120px;">Status Kinerja</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $index => $item)
                    @php
                        $percentage = $item['count'] > 0 ? round(($item['selesai'] / $item['count']) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td><strong>0{{ $loop->iteration }}</strong></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 9px;">
                                <span class="stat-symbol" style="width: 28px; height: 28px; font-size: 0.9rem; background: #e0f2fe; color: {{ $item['color'] }};">{{ $item['icon'] }}</span>
                                <div>
                                    <strong style="color: #1e293b; font-size: 0.85rem;">{{ $item['label'] }}</strong>
                                    <div style="font-size: 0.72rem; color: #64748b;">Kecamatan Jatisari</div>
                                </div>
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
                                <span style="font-size: 0.76rem; font-weight: 700; color: #15803d; min-width: 44px;">{{ $percentage }}%</span>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge-camat success">Sangat Baik</span>
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
