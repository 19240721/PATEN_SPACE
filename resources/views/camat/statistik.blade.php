@extends('layouts.camat')

@section('title', 'Statistik Pelayanan - PATEN SPACE Camat Jatisari')

@section('camat_content')
{{-- HEADING STATISTIK PELAYANAN --}}
<section class="dashboard-heading">
    <div>
        <h1>Statistik Pelayanan</h1>
        <p>Analisis data statistik, tren bulanan, dan status pelayanan administrasi Kecamatan Jatisari tahun {{ $year }}</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('camat.laporan') }}" class="dashboard-primary-link">
            <span>📊</span> Lihat Laporan
        </a>
    </div>
</section>

{{-- CARD TOTAL PELAYANAN & RINGKASAN METRIK --}}
<section class="dashboard-stats" style="grid-template-columns: repeat(4, minmax(0, 1fr)); margin-bottom: 22px;">
    <div class="dashboard-stat total-stat" style="border-left: 4px solid #0870c9;">
        <span class="stat-symbol">📈</span>
        <div>
            <span>Total Pelayanan</span>
            <strong>{{ number_format($totalPelayanan, 0, ',', '.') }}</strong>
            <small style="color: #64748b; font-size: 0.7rem;">Januari - September {{ $year }}</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #2563eb;">
        <span class="stat-symbol blue">⚖</span>
        <div>
            <span>Rata-rata per Bulan</span>
            <strong>{{ $rataBulanan }} Pelayanan</strong>
            <small style="color: #2563eb; font-size: 0.7rem;">Tingkat Aktivitas Tinggi</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #16a34a;">
        <span class="stat-symbol green">🎯</span>
        <div>
            <span>Tingkat Penyelesaian</span>
            <strong style="color: #15803d;">{{ $persenSelesai }}%</strong>
            <small style="color: #16a34a; font-size: 0.7rem;">Target Minimal 95%</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #8b5cf6;">
        <span class="stat-symbol" style="background: #ede9fe; color: #7c3aed;">🏆</span>
        <div>
            <span>Layanan Tertinggi</span>
            <strong style="font-size: 1.15rem; color: #6d28d9;">Kartu Keluarga</strong>
            <small style="color: #7c3aed; font-size: 0.7rem;">276 Berkas Permohonan</small>
        </div>
    </div>
</section>

{{-- FILTER TAHUN & PERIODE --}}
<section class="dashboard-panel" style="padding: 14px 20px; margin-bottom: 22px; background: #ffffff;">
    <form method="GET" action="{{ route('camat.statistik') }}" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <label style="font-size: 0.82rem; font-weight: 700; color: #334155;">Tahun:</label>
                <select name="tahun" style="height: 38px; padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 600;" onchange="this.form.submit()">
                    <option value="2026" selected>2026 (Tahun Berjalan)</option>
                    <option value="2025">2025</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <label style="font-size: 0.82rem; font-weight: 700; color: #334155;">Periode:</label>
                <select name="periode" style="height: 38px; padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 600;" onchange="this.form.submit()">
                    <option value="2026" {{ $periode === '2026' ? 'selected' : '' }}>Semua Periode (Jan - Sep)</option>
                    <option value="tw3" {{ $periode === 'tw3' ? 'selected' : '' }}>Triwulan III (Jul - Sep)</option>
                    <option value="tw2" {{ $periode === 'tw2' ? 'selected' : '' }}>Triwulan II (Apr - Jun)</option>
                    <option value="tw1" {{ $periode === 'tw1' ? 'selected' : '' }}>Triwulan I (Jan - Mar)</option>
                </select>
            </div>

            <button type="submit" class="report-filter-button" style="height: 38px;">Terapkan Filter</button>
        </div>

        <span class="badge-camat info" style="font-size: 0.78rem;">Data Statistik SIAK PATEN 2026</span>
    </form>
</section>

{{-- GRAFIK PELAYANAN BERDASARKAN JENIS & TREN PELAYANAN BULANAN --}}
<section class="dashboard-charts">
    {{-- GRAFIK PELAYANAN BERDASARKAN JENIS --}}
    <article class="dashboard-panel chart-panel">
        <div class="panel-heading">
            <div>
                <h2>Pelayanan Berdasarkan Jenis</h2>
                <p>Rekap jumlah pelayanan yang tercatat</p>
            </div>
            <span class="chart-year">{{ $year }}</span>
        </div>
        <div class="service-chart" role="img" aria-label="Grafik volume pelayanan per jenis">
            @foreach ($services as $service)
                <div class="service-bar-column">
                    <span class="bar-value">{{ $service['count'] }}</span>
                    <div class="service-bar" style="height: {{ max(6, ($service['count'] / $chartMax) * 100) }}%; background: {{ $service['color'] }};" title="{{ $service['label'] }}: {{ $service['count'] }} berkas"></div>
                    <span class="bar-label" title="{{ $service['label'] }}">{{ $service['short'] }}</span>
                </div>
            @endforeach
        </div>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 14px; padding-top: 12px; border-top: 1px solid #edf2f7; font-size: 0.7rem; color: #475569;">
            <div>KK: <strong>276</strong></div>
            <div>PRR: <strong>215</strong></div>
            <div>Akta Lahir: <strong>198</strong></div>
            <div>KTP: <strong>182</strong></div>
        </div>
    </article>

    {{-- GRAFIK TREN PELAYANAN BULANAN --}}
    <article class="dashboard-panel chart-panel">
        <div class="panel-heading">
            <div>
                <h2>Tren Pelayanan Bulanan</h2>
                <p>Jumlah pelayanan setiap bulan (Januari - Desember 2026)</p>
            </div>
            <span class="chart-year">{{ $year }}</span>
        </div>
        <div class="trend-chart" role="img" aria-label="Grafik tren bulanan tahun 2026">
            <svg viewBox="0 0 480 160" preserveAspectRatio="none" aria-hidden="true">
                <line x1="24" y1="28" x2="456" y2="28" />
                <line x1="24" y1="78" x2="456" y2="78" />
                <line x1="24" y1="128" x2="456" y2="128" />
                <polyline points="{{ $chartPoints }}" />
                @foreach ($monthlyTrend as $index => $item)
                    <circle cx="{{ 24 + ($index * 432 / 11) }}" cy="{{ 128 - ($item['count'] / $monthlyMax * 100) }}" r="4" />
                @endforeach
            </svg>
            <div class="month-labels">
                @foreach ($monthlyTrend as $item)
                    <span>{{ $item['month'] }}</span>
                @endforeach
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 14px; padding-top: 12px; border-top: 1px solid #edf2f7; font-size: 0.72rem; color: #64748b;">
            <span>Tren: <strong style="color: #16a34a;">↑ Meningkat di Triwulan III</strong></span>
            <span>Bulan puncak: <strong>September (186)</strong></span>
        </div>
    </article>
</section>

{{-- STATUS PELAYANAN --}}
<section class="dashboard-panel chart-panel" style="margin-top: 22px;">
    <div class="panel-heading">
        <div>
            <h2>Status Pelayanan</h2>
            <p>Perbandingan efektivitas dan status penyelesaian berkas</p>
        </div>
        <span class="badge-camat success">96.9% Selesai</span>
    </div>

    <div class="progress-bar-stacked" title="Distribusi Status">
        <div class="progress-bar-segment selesai" style="width: 96.9%;" title="Selesai: 1.210"></div>
        <div class="progress-bar-segment proses" style="width: 3.0%;" title="Proses: 38"></div>
        <div class="progress-bar-segment menunggu" style="width: 1.4%;" title="Menunggu: 17"></div>
        <div class="progress-bar-segment revisi" style="width: 1.0%;" title="Perlu Revisi: 12"></div>
    </div>

    <div class="status-card-grid">
        <div class="status-box selesai">
            <div class="status-box-header">
                <span>SELESAI</span>
                <span class="badge-camat success">96.9%</span>
            </div>
            <div class="status-box-val">1.210</div>
            <small style="color: #15803d; font-size: 0.72rem;">Berkas tuntas diproses</small>
        </div>

        <div class="status-box proses">
            <div class="status-box-header">
                <span>DALAM PROSES</span>
                <span class="badge-camat warning">3.0%</span>
            </div>
            <div class="status-box-val">38</div>
            <small style="color: #b45309; font-size: 0.72rem;">Pengerjaan operator</small>
        </div>

        <div class="status-box menunggu">
            <div class="status-box-header">
                <span>MENUNGGU</span>
                <span class="badge-camat info">1.4%</span>
            </div>
            <div class="status-box-val">17</div>
            <small style="color: #1e40af; font-size: 0.72rem;">Antrean verifikasi</small>
        </div>

        <div class="status-box revisi">
            <div class="status-box-header">
                <span>PERLU REVISI</span>
                <span class="badge-camat danger">1.0%</span>
            </div>
            <div class="status-box-val">12</div>
            <small style="color: #991b1b; font-size: 0.72rem;">Perbaikan dokumen</small>
        </div>
    </div>
</section>
@endsection
