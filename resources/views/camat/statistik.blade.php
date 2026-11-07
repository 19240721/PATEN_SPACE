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
            <small style="color: #64748b; font-size: 0.7rem;">Tahun {{ $year }}</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #2563eb;">
        <span class="stat-symbol blue">⚖</span>
        <div>
            <span>Rata-rata per Bulan</span>
            <strong>{{ $rataBulanan }} Pelayanan</strong>
            <small style="color: #2563eb; font-size: 0.7rem;">Tingkat Aktivitas Pelayanan</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #16a34a;">
        <span class="stat-symbol green">🎯</span>
        <div>
            <span>Tingkat Penyelesaian</span>
            <strong style="color: #15803d;">{{ $persenSelesai }}%</strong>
            <small style="color: #16a34a; font-size: 0.7rem;">Persentase Selesai</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #8b5cf6;">
        <span class="stat-symbol" style="background: #ede9fe; color: #7c3aed;">🏆</span>
        <div>
            <span>Layanan Tertinggi</span>
            <strong style="font-size: 1.15rem; color: #6d28d9;">{{ $highestServiceTitle }}</strong>
            <small style="color: #7c3aed; font-size: 0.7rem;">{{ $highestServiceCount }}</small>
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
                    <option value="2026" {{ (string)$year === '2026' ? 'selected' : '' }}>2026</option>
                    <option value="2025" {{ (string)$year === '2025' ? 'selected' : '' }}>2025</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <label style="font-size: 0.82rem; font-weight: 700; color: #334155;">Periode:</label>
                <select name="periode" style="height: 38px; padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 600;" onchange="this.form.submit()">
                    <option value="{{ $year }}" {{ $periode === (string)$year || $periode === 'semua' ? 'selected' : '' }}>Semua Periode</option>
                    <option value="bulan_ini" {{ $periode === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tw1" {{ $periode === 'tw1' || $periode === 'triwulan_1' ? 'selected' : '' }}>Triwulan I (Jan - Mar)</option>
                    <option value="tw2" {{ $periode === 'tw2' || $periode === 'triwulan_2' ? 'selected' : '' }}>Triwulan II (Apr - Jun)</option>
                    <option value="tw3" {{ $periode === 'tw3' || $periode === 'triwulan_3' ? 'selected' : '' }}>Triwulan III (Jul - Sep)</option>
                    <option value="tw4" {{ $periode === 'tw4' || $periode === 'triwulan_4' ? 'selected' : '' }}>Triwulan IV (Okt - Des)</option>
                </select>
            </div>

            <button type="submit" class="report-filter-button" style="height: 38px;">Terapkan Filter</button>
        </div>

        <span class="badge-camat info" style="font-size: 0.78rem;">Data Statistik SIAK PATEN {{ $year }}</span>
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
            @foreach(array_slice($services, 0, 4) as $s)
                <div>{{ $s['short'] }}: <strong>{{ $s['count'] }}</strong></div>
            @endforeach
        </div>
    </article>

    {{-- GRAFIK TREN PELAYANAN BULANAN --}}
    <article class="dashboard-panel chart-panel">
        <div class="panel-heading">
            <div>
                <h2>Tren Pelayanan Bulanan</h2>
                <p>Jumlah pelayanan setiap bulan (Januari - Desember {{ $year }})</p>
            </div>
            <span class="chart-year">{{ $year }}</span>
        </div>
        <div class="trend-chart" role="img" aria-label="Grafik tren bulanan tahun {{ $year }}">
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
            <span>Tren: <strong style="color: #16a34a;">Data Real-time</strong></span>
            <span>Bulan puncak: <strong>{{ $peakMonthLabel }}</strong></span>
        </div>
    </article>
</section>

{{-- STATISTIK PELAYANAN PER DESA (HORIZONTAL BAR CHART FULL WIDTH) --}}
<section class="dashboard-panel chart-panel" style="margin-top: 22px; position: relative;">
    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">
        <div>
            <h2>Statistik per Desa</h2>
            <p>Jumlah pelayanan berdasarkan desa</p>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <span class="badge-camat info" style="font-size: 0.76rem;">14 Desa Kecamatan Jatisari</span>
            @if($totalDesaPelayanan > 0)
                <span class="badge-camat success" style="font-size: 0.76rem;">{{ number_format($totalDesaPelayanan, 0, ',', '.') }} Pelayanan</span>
            @else
                <span class="badge-camat warning" style="font-size: 0.76rem;">Belum Ada Data</span>
            @endif
        </div>
    </div>

    <!-- Floating Interactive Tooltip di level Card (bebas dari overflow clipping) -->
    <div id="desa-bar-tooltip" style="display: none; position: absolute; pointer-events: none; z-index: 1050; background: #1e293b; color: #ffffff; padding: 8px 14px; border-radius: 6px; font-size: 0.8rem; box-shadow: 0 4px 14px rgba(0,0,0,0.25); white-space: nowrap; transform: translate(-50%, -125%); transition: opacity 0.15s ease;">
    </div>

    @if($totalDesaPelayanan == 0)
        <div style="margin: 0 0 14px 0; padding: 10px 14px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; color: #64748b; font-size: 0.82rem; display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 0.95rem;">ℹ</span>
            <span>Belum ada data pelayanan untuk periode yang dipilih. Sumbu grafik menampilkan seluruh 14 desa Kecamatan Jatisari dengan nilai 0.</span>
        </div>
    @endif

    <!-- Kontainer Grafik Horizontal Batang Responsif Penuh (100% Lebar Card) -->
    <div class="desa-barchart-responsive" style="width: 100%; display: flex; flex-direction: column;">
        
        <!-- Area Scroll Vertikal untuk 14 Desa (Tinggi Tetap & Proporsional) -->
        <div class="desa-chart-scroll-box" style="max-height: 420px; overflow-y: auto; overflow-x: hidden; padding-right: 6px;">
            <div style="display: flex; width: 100%; position: relative;">
                
                <!-- Kolom Sumbu Y: Nama 14 Desa di Sebelah Kiri Batang -->
                <div style="width: 140px; flex-shrink: 0; display: flex; flex-direction: column;">
                    @foreach ($desaStats as $desa)
                        <div style="height: 32px; display: flex; align-items: center; justify-content: flex-end; padding-right: 12px; font-size: 0.82rem; font-weight: 600; color: #334155; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $desa['nama'] }}">
                            {{ $desa['nama'] }}
                        </div>
                    @endforeach
                </div>

                <!-- Bidang Plot Area Grafik (Memanfaatkan 100% Lebar Tersedia Tanpa Menyisakan Ruang Kosong) -->
                <div class="desa-plot-area" style="flex: 1; min-width: 0; position: relative; border-left: 2px solid #cbd5e1; border-bottom: 2px solid #cbd5e1;">
                    
                    <!-- Garis Grid Vertikal yang Membentang Proporsional Menyeluruh -->
                    <div style="position: absolute; inset: 0; pointer-events: none;">
                        @if($desaChartMax > 0 && count($desaChartTicks) > 1)
                            @foreach ($desaChartTicks as $tick)
                                @php
                                    $tickPct = round(($tick / $desaChartMax) * 100, 2);
                                @endphp
                                <div style="position: absolute; top: 0; bottom: 0; left: {{ $tickPct }}%; width: 1px; border-right: 1px dashed {{ $tick === 0 ? '#cbd5e1' : '#f1f5f9' }};"></div>
                            @endforeach
                        @else
                            <div style="position: absolute; top: 0; bottom: 0; left: 0%; width: 1px; border-right: 1px dashed #cbd5e1;"></div>
                            <div style="position: absolute; top: 0; bottom: 0; left: 25%; width: 1px; border-right: 1px dashed #f1f5f9;"></div>
                            <div style="position: absolute; top: 0; bottom: 0; left: 50%; width: 1px; border-right: 1px dashed #f1f5f9;"></div>
                            <div style="position: absolute; top: 0; bottom: 0; left: 75%; width: 1px; border-right: 1px dashed #f1f5f9;"></div>
                            <div style="position: absolute; top: 0; bottom: 0; left: 100%; width: 1px; border-right: 1px dashed #f1f5f9;"></div>
                        @endif
                    </div>

                    <!-- 14 Baris Batang Horizontal Desa -->
                    @foreach ($desaStats as $desa)
                        <div class="desa-bar-row-hitbox"
                             data-desa="{{ $desa['nama'] }}"
                             data-jumlah="{{ $desa['jumlah_formatted'] }}"
                             style="height: 32px; display: flex; align-items: center; position: relative; cursor: pointer; padding: 0 2px; transition: background 0.15s ease;">
                            
                            <!-- Batang Horizontal Hijau yang Memanjang Proporsional Sesuai Sumbu X -->
                            @if($desa['jumlah'] > 0 && $desaChartMax > 0)
                                @php
                                    $barWidthPct = min(100, max(0.5, round(($desa['jumlah'] / $desaChartMax) * 100, 2)));
                                @endphp
                                <div class="desa-bar-fill"
                                     style="height: 18px; width: {{ $barWidthPct }}%; background: #16a34a; border-radius: 3px; transition: width 0.4s ease, background 0.15s ease; box-shadow: 0 1px 3px rgba(22, 163, 74, 0.25);"
                                     title="{{ $desa['nama'] }}&#10;Jumlah: {{ $desa['jumlah_formatted'] }} pelayanan">
                                </div>
                            @else
                                <!-- Titik Pangkal Sumbu saat Nilai 0 -->
                                <div class="desa-bar-zero"
                                     style="height: 18px; width: 2px; background: #cbd5e1; border-radius: 1px; opacity: 0.5;"
                                     title="{{ $desa['nama'] }}&#10;Jumlah: 0 pelayanan">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sumbu Horizontal X-Axis Skala & Label (Tepat di Bawah Plot Area & Sejajar) -->
        <div style="display: flex; width: 100%; margin-top: 4px; padding-right: 6px;">
            <!-- Penyeimbang Lebar Kolom Sumbu Y -->
            <div style="width: 140px; flex-shrink: 0;"></div>

            <!-- Area Skala Nilai Sumbu X (Mengikuti 100% Lebar Plot Area) -->
            <div style="flex: 1; min-width: 0; position: relative; height: 38px;">
                @if($desaChartMax > 0 && count($desaChartTicks) > 1)
                    @foreach ($desaChartTicks as $tick)
                        @php
                            $tickPct = round(($tick / $desaChartMax) * 100, 2);
                        @endphp
                        <div style="position: absolute; left: {{ $tickPct }}%; transform: translateX(-50%); display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 1px; height: 6px; background: #94a3b8;"></div>
                            <span style="font-size: 0.72rem; font-weight: 600; color: #64748b; margin-top: 2px;">
                                {{ $tick }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <div style="position: absolute; left: 0%; transform: translateX(0); display: flex; flex-direction: column; align-items: flex-start;">
                        <div style="width: 1px; height: 6px; background: #94a3b8;"></div>
                        <span style="font-size: 0.72rem; font-weight: 600; color: #64748b; margin-top: 2px;">0</span>
                    </div>
                @endif

                <div style="text-align: center; font-size: 0.74rem; font-weight: 600; color: #475569; position: absolute; width: 100%; bottom: 0;">
                    Jumlah Pelayanan (Berkas)
                </div>
            </div>
        </div>
    </div>
</section>

{{-- GRID 2 KOLOM BERDAMPINGAN: DISTRIBUSI STATUS (KIRI) & STATUS PELAYANAN (KANAN) --}}
<div class="status-charts-grid" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px; margin-top: 22px;">
    
    {{-- KOLOM KIRI: DISTRIBUSI STATUS (DOUGHNUT CHART) --}}
    <article class="dashboard-panel chart-panel" style="margin-top: 0; position: relative; display: flex; flex-direction: column;">
        <div class="panel-heading">
            <div>
                <h2>Distribusi Status</h2>
                <p>Komposisi status seluruh pelayanan</p>
            </div>
            <span class="badge-camat {{ $distribusiStatus['total'] > 0 ? 'info' : 'warning' }}">
                {{ $distribusiStatus['total'] > 0 ? number_format($distribusiStatus['total'], 0, ',', '.') . ' Pelayanan' : 'Belum Ada Data' }}
            </span>
        </div>

        <div style="position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px 16px; flex: 1;">
            <!-- Floating Interactive Tooltip -->
            <div id="donut-tooltip" class="status-donut-tooltip" style="display: none; position: absolute; pointer-events: none; z-index: 1000; background: #1e293b; color: #ffffff; padding: 8px 14px; border-radius: 6px; font-size: 0.8rem; font-weight: 500; box-shadow: 0 4px 14px rgba(0,0,0,0.2); white-space: nowrap; transform: translate(-50%, -120%); transition: opacity 0.15s ease;">
            </div>

            <!-- SVG Doughnut Chart Container -->
            <div style="position: relative; width: 200px; height: 200px;">
                <svg viewBox="0 0 220 220" width="200" height="200" style="overflow: visible; display: block;">
                    <!-- Background Base Ring -->
                    <circle cx="110" cy="110" r="80" fill="none" stroke="#f1f5f9" stroke-width="32" />

                    @if($distribusiStatus['total'] == 0)
                        <!-- Empty State Neutral Ring -->
                        <circle cx="110" cy="110" r="80" fill="none" stroke="#e2e8f0" stroke-width="32" class="donut-slice"
                                data-label="Belum Ada Data"
                                data-count="0"
                                data-percent="0%"
                                data-color="#94a3b8"
                                style="cursor: default;">
                            <title>Belum ada data pelayanan: 0 pelayanan (0%)</title>
                        </circle>
                        <!-- Center Text for Empty State -->
                        <text x="110" y="105" text-anchor="middle" font-size="22" font-weight="700" fill="#94a3b8">0</text>
                        <text x="110" y="125" text-anchor="middle" font-size="11" font-weight="600" fill="#94a3b8">Pelayanan</text>
                    @else
                        <!-- Slices for each status category -->
                        @foreach($distribusiStatus['items'] as $segment)
                            @if($segment['count'] > 0)
                                <circle class="donut-slice"
                                        cx="110" cy="110" r="80"
                                        fill="none"
                                        stroke="{{ $segment['color'] }}"
                                        stroke-width="32"
                                        stroke-dasharray="{{ $segment['arc_length'] }} {{ $distribusiStatus['circumference'] }}"
                                        stroke-dashoffset="{{ $segment['dash_offset'] }}"
                                        transform="rotate(-90 110 110)"
                                        data-label="{{ $segment['label'] }}"
                                        data-count="{{ $segment['count_formatted'] }}"
                                        data-percent="{{ $segment['percentage'] }}%"
                                        data-color="{{ $segment['color'] }}"
                                        style="cursor: pointer; transition: stroke-width 0.2s ease, opacity 0.2s ease;">
                                    <title>{{ $segment['label'] }}: {{ $segment['count_formatted'] }} pelayanan ({{ $segment['percentage'] }}%)</title>
                                </circle>
                            @endif
                        @endforeach

                        <!-- Center Text for Available Data -->
                        <text x="110" y="104" text-anchor="middle" font-size="22" font-weight="700" fill="#1e293b">{{ $distribusiStatus['total_formatted'] }}</text>
                        <text x="110" y="124" text-anchor="middle" font-size="11" font-weight="600" fill="#64748b">Total Pelayanan</text>
                    @endif
                </svg>
            </div>

            @if($distribusiStatus['total'] == 0)
                <div style="margin-top: 10px; color: #64748b; font-size: 0.82rem; font-weight: 500;">
                    Belum ada data pelayanan
                </div>
            @endif

            <!-- Legenda Distribusi Status -->
            <div class="donut-legend-grid" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px 16px; margin-top: 18px; width: 100%; padding-top: 14px; border-top: 1px solid #edf2f7;">
                @foreach($distribusiStatus['items'] as $item)
                    <div class="legend-card-item"
                         data-key="{{ $item['key'] }}"
                         data-label="{{ $item['label'] }}"
                         data-count="{{ $item['count_formatted'] }}"
                         data-percent="{{ $item['percentage'] }}%"
                         data-color="{{ $item['color'] }}"
                         style="display: flex; align-items: center; gap: 8px; padding: 4px 8px; border-radius: 6px; cursor: default; transition: background 0.15s ease;">
                        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: {{ $item['color'] }}; flex-shrink: 0;"></span>
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 0.78rem; font-weight: 700; color: #1e293b;">{{ $item['label'] }}</span>
                            <span style="font-size: 0.72rem; color: #64748b;">
                                {{ $item['count_formatted'] }} ({{ $item['percentage'] }}%)
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </article>

    {{-- KOLOM KANAN: STATUS PELAYANAN --}}
    <article class="dashboard-panel chart-panel" style="margin-top: 0; display: flex; flex-direction: column;">
        <div class="panel-heading">
            <div>
                <h2>Status Pelayanan</h2>
                <p>Perbandingan efektivitas dan status penyelesaian berkas</p>
            </div>
            <span class="badge-camat success">{{ $statusPelayanan['selesai']['percentage'] }}% Selesai</span>
        </div>

        <div style="padding: 10px 4px 4px 4px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div class="progress-bar-stacked" title="Distribusi Status">
                    <div class="progress-bar-segment selesai" style="width: {{ $statusPelayanan['selesai']['percentage'] }}%;" title="Selesai: {{ $statusPelayanan['selesai']['count'] }}"></div>
                    <div class="progress-bar-segment proses" style="width: {{ $statusPelayanan['proses']['percentage'] }}%;" title="Proses: {{ $statusPelayanan['proses']['count'] }}"></div>
                    <div class="progress-bar-segment menunggu" style="width: {{ $statusPelayanan['menunggu']['percentage'] }}%;" title="Menunggu: {{ $statusPelayanan['menunggu']['count'] }}"></div>
                    <div class="progress-bar-segment revisi" style="width: {{ $statusPelayanan['perlu_revisi']['percentage'] }}%;" title="Perlu Revisi: {{ $statusPelayanan['perlu_revisi']['count'] }}"></div>
                </div>
            </div>

            <div class="status-card-grid" style="margin-top: 18px; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px;">
                <div class="status-box selesai" style="margin-bottom: 0;">
                    <div class="status-box-header">
                        <span>SELESAI</span>
                        <span class="badge-camat success">{{ $statusPelayanan['selesai']['percentage'] }}%</span>
                    </div>
                    <div class="status-box-val">{{ number_format($statusPelayanan['selesai']['count'], 0, ',', '.') }}</div>
                    <small style="color: #15803d; font-size: 0.72rem;">Berkas tuntas diproses</small>
                </div>

                <div class="status-box proses" style="margin-bottom: 0;">
                    <div class="status-box-header">
                        <span>DALAM PROSES</span>
                        <span class="badge-camat warning">{{ $statusPelayanan['proses']['percentage'] }}%</span>
                    </div>
                    <div class="status-box-val">{{ number_format($statusPelayanan['proses']['count'], 0, ',', '.') }}</div>
                    <small style="color: #b45309; font-size: 0.72rem;">Pengerjaan operator</small>
                </div>

                <div class="status-box menunggu" style="margin-bottom: 0;">
                    <div class="status-box-header">
                        <span>MENUNGGU</span>
                        <span class="badge-camat info">{{ $statusPelayanan['menunggu']['percentage'] }}%</span>
                    </div>
                    <div class="status-box-val">{{ number_format($statusPelayanan['menunggu']['count'], 0, ',', '.') }}</div>
                    <small style="color: #1e40af; font-size: 0.72rem;">Antrean verifikasi</small>
                </div>

                <div class="status-box revisi" style="margin-bottom: 0;">
                    <div class="status-box-header">
                        <span>PERLU REVISI</span>
                        <span class="badge-camat danger">{{ $statusPelayanan['perlu_revisi']['percentage'] }}%</span>
                    </div>
                    <div class="status-box-val">{{ number_format($statusPelayanan['perlu_revisi']['count'], 0, ',', '.') }}</div>
                    <small style="color: #991b1b; font-size: 0.72rem;">Perbaikan dokumen</small>
                </div>
            </div>
        </div>
    </article>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tooltip = document.getElementById('donut-tooltip');
    const slices = document.querySelectorAll('.donut-slice');
    const legendItems = document.querySelectorAll('.legend-card-item');

    function showTooltip(e, label, count, percent, color) {
        if (!tooltip) return;
        tooltip.innerHTML = `<span style="display:inline-block;width:9px;height:9px;border-radius:50%;background:${color};margin-right:6px;vertical-align:middle;"></span><strong>${label}:</strong> ${count} pelayanan (${percent})`;
        tooltip.style.display = 'block';
        tooltip.style.opacity = '1';
        positionTooltip(e);
    }

    function positionTooltip(e) {
        if (!tooltip) return;
        const parentRect = tooltip.parentElement.getBoundingClientRect();
        const mouseX = e.clientX - parentRect.left;
        const mouseY = e.clientY - parentRect.top;
        tooltip.style.left = mouseX + 'px';
        tooltip.style.top = mouseY + 'px';
    }

    function hideTooltip() {
        if (!tooltip) return;
        tooltip.style.display = 'none';
        tooltip.style.opacity = '0';
    }

    slices.forEach(slice => {
        slice.addEventListener('mouseenter', function (e) {
            const label = this.getAttribute('data-label');
            const count = this.getAttribute('data-count');
            const percent = this.getAttribute('data-percent');
            const color = this.getAttribute('data-color');
            if (this.getAttribute('stroke') !== '#e2e8f0') {
                this.style.strokeWidth = '36';
            }
            slices.forEach(s => {
                if (s !== this) s.style.opacity = '0.65';
            });
            showTooltip(e, label, count, percent, color);
        });

        slice.addEventListener('mousemove', function (e) {
            positionTooltip(e);
        });

        slice.addEventListener('mouseleave', function () {
            this.style.strokeWidth = '32';
            slices.forEach(s => {
                s.style.opacity = '1';
            });
            hideTooltip();
        });
    });

    legendItems.forEach(item => {
        item.addEventListener('mouseenter', function (e) {
            const label = this.getAttribute('data-label');
            const count = this.getAttribute('data-count');
            const percent = this.getAttribute('data-percent');
            const color = this.getAttribute('data-color');

            this.style.background = '#f8fafc';
            slices.forEach(s => {
                if (s.getAttribute('data-label') === label) {
                    s.style.strokeWidth = '36';
                } else {
                    s.style.opacity = '0.65';
                }
            });
            showTooltip(e, label, count, percent, color);
        });

        item.addEventListener('mousemove', function (e) {
            positionTooltip(e);
        });

        item.addEventListener('mouseleave', function () {
            this.style.background = 'transparent';
            slices.forEach(s => {
                s.style.strokeWidth = '32';
                s.style.opacity = '1';
            });
            hideTooltip();
        });
    });

    // Tooltip Interaktif untuk Grafik Horizontal Batang Desa (SVG)
    const desaTooltip = document.getElementById('desa-bar-tooltip');
    const desaHitboxes = document.querySelectorAll('.desa-bar-hitbox');
    const desaCard = desaTooltip ? desaTooltip.closest('.dashboard-panel') : null;
    const desaSvg = document.getElementById('desa-barchart-svg');

    if (desaTooltip && desaCard && desaSvg) {
        function showDesaTooltip(e, desa, jumlah) {
            desaTooltip.innerHTML = `
                <div style="font-weight: 700; font-size: 0.84rem; color: #ffffff; margin-bottom: 2px;">${desa}</div>
                <div style="color: #e2e8f0; font-size: 0.76rem;">Jumlah: <strong style="color: #4ade80;">${jumlah} pelayanan</strong></div>
            `;
            desaTooltip.style.display = 'block';
            desaTooltip.style.opacity = '1';
            positionDesaTooltip(e);
        }

        function positionDesaTooltip(e) {
            const cardRect = desaCard.getBoundingClientRect();
            const mouseX = e.clientX - cardRect.left;
            const mouseY = e.clientY - cardRect.top;
            desaTooltip.style.left = mouseX + 'px';
            desaTooltip.style.top = (mouseY - 14) + 'px';
        }

        function hideDesaTooltip() {
            desaTooltip.style.display = 'none';
            desaTooltip.style.opacity = '0';
        }

        desaHitboxes.forEach(hitbox => {
            hitbox.addEventListener('mouseenter', function (e) {
                const desa = this.getAttribute('data-desa');
                const jumlah = this.getAttribute('data-jumlah');
                const bar = desaSvg.querySelector(`.desa-bar[data-desa="${CSS.escape(desa)}"]`);
                if (bar && !bar.classList.contains('zero-bar')) {
                    bar.setAttribute('fill', '#15803d');
                    bar.style.filter = 'drop-shadow(0 2px 4px rgba(22, 163, 74, 0.4))';
                }
                showDesaTooltip(e, desa, jumlah);
            });

            hitbox.addEventListener('mousemove', function (e) {
                positionDesaTooltip(e);
            });

            hitbox.addEventListener('mouseleave', function () {
                const desa = this.getAttribute('data-desa');
                const bar = desaSvg.querySelector(`.desa-bar[data-desa="${CSS.escape(desa)}"]`);
                if (bar && !bar.classList.contains('zero-bar')) {
                    bar.setAttribute('fill', '#16a34a');
                    bar.style.filter = 'none';
                }
                hideDesaTooltip();
            });
        });
    }
});
</script>

<style>
.desa-chart-scroll-box::-webkit-scrollbar {
    width: 6px;
}
.desa-chart-scroll-box::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 4px;
}
.desa-chart-scroll-box::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.desa-chart-scroll-box::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection
