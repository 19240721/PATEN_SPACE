@extends('layouts.camat')

@section('title', 'Dashboard Camat - PATEN SPACE Kecamatan Jatisari')

@section('camat_content')
{{-- HEADING DASHBOARD CAMAT --}}
<section class="dashboard-heading">
    <div>
        <h1>Dashboard Camat</h1>
        <p>Ringkasan dan monitoring pelayanan Kecamatan Jatisari</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="{{ route('camat.laporan') }}" class="dashboard-primary-link" style="background: #1e3a5f;">
            <span>📋</span> Lihat Laporan
        </a>
        <a href="{{ route('camat.monitoring') }}" class="dashboard-primary-link">
            <span>🔍</span> Monitoring Lengkap
        </a>
    </div>
</section>

{{-- CARD RINGKASAN DI BAGIAN ATAS (5 SUMMARY CARDS REALISTIS) --}}
<section class="dashboard-stats" style="grid-template-columns: repeat(5, minmax(0, 1fr)); margin-bottom: 22px;" aria-label="Ringkasan Utama Camat">
    <div class="dashboard-stat total-stat" style="border-left: 4px solid #0870c9;">
        <span class="stat-symbol">▤</span>
        <div>
            <span>Total Pelayanan</span>
            <strong>{{ number_format($stats['total'], 0, ',', '.') }}</strong>
            <small style="color: #64748b; font-size: 0.68rem; margin-top: 2px;">Tahun {{ $year }}</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #2563eb;">
        <span class="stat-symbol blue">📅</span>
        <div>
            <span>Pelayanan Bulan Ini</span>
            <strong>{{ number_format($stats['bulan_ini'], 0, ',', '.') }}</strong>
            <small style="color: #2563eb; font-size: 0.68rem; margin-top: 2px;">{{ $currentMonthName }}</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #16a34a;">
        <span class="stat-symbol green">✓</span>
        <div>
            <span>Selesai</span>
            <strong style="color: #15803d;">{{ number_format($stats['selesai'], 0, ',', '.') }}</strong>
            <small style="color: #16a34a; font-size: 0.68rem; margin-top: 2px;">{{ $statusPelayanan['selesai']['percentage'] }}% Selesai</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #d97706;">
        <span class="stat-symbol gold">⏳</span>
        <div>
            <span>Dalam Proses</span>
            <strong style="color: #b45309;">{{ number_format($stats['dalam_proses'], 0, ',', '.') }}</strong>
            <small style="color: #d97706; font-size: 0.68rem; margin-top: 2px;">Sedang Ditangani</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #dc2626;">
        <span class="stat-symbol" style="background: #fee2e2; color: #dc2626;">⚠</span>
        <div>
            <span>Perlu Perhatian</span>
            <strong style="color: #b91c1c;">{{ number_format($stats['perlu_perhatian'], 0, ',', '.') }}</strong>
            <small style="color: #dc2626; font-size: 0.68rem; margin-top: 2px;">Tindakan Diperlukan</small>
        </div>
    </div>
</section>

{{-- BAGIAN "PERLU PERHATIAN" --}}
<section class="attention-card" aria-label="Informasi Perlu Perhatian Pimpinan">
    <div class="attention-header">
        <div class="attention-title">
            <span class="attention-icon-badge">⚠</span>
            <div>
                <h2>Perlu Perhatian</h2>
                <p>Status pelayanan yang membutuhkan pengawasan atau tindak lanjut Camat</p>
            </div>
        </div>
        <span class="badge-camat warning">{{ count($perhatian) }} Poin Pengawasan</span>
    </div>

    <div class="attention-list">
        @forelse ($perhatian as $item)
            @if (
                !str_contains(strtolower($item['pesan']), 'revisi dokumen') &&
                !str_contains(strtolower($item['pesan']), 'pengaduan warga')
            )
                <div class="attention-item">
                    <div class="attention-item-top">
                        <span class="warn-icon">⚠</span>
                        <div class="attention-item-text">{{ $item['pesan'] }}</div>
                    </div>
                    <div class="attention-item-action">
                        <span class="badge-camat {{ $item['level'] }}">{{ $item['badge'] }}</span>
                        <a href="{{ $item['action_url'] }}" class="attention-link">
                            {{ $item['action_label'] }} →
                        </a>
                    </div>
                </div>
            @endif
        @empty
            <div style="grid-column: 1 / -1; color: #64748b; font-size: 0.82rem; padding: 4px 0;">
                Tidak ada status pelayanan yang memerlukan perhatian khusus saat ini.
            </div>
        @endforelse
    </div>
</section>

{{-- GRAFIK PELAYANAN BERDASARKAN JENIS & TREN BULANAN --}}
<section class="dashboard-charts">
    <article class="dashboard-panel chart-panel">
        <div class="panel-heading">
            <div>
                <h2>Pelayanan Berdasarkan Jenis</h2>
                <p>Rekap jumlah pelayanan yang tercatat</p>
            </div>
            <span class="chart-year">Tahun {{ $year }}</span>
        </div>
        <div class="service-chart" role="img" aria-label="Grafik jumlah pelayanan menurut jenis">
            @foreach ($services as $service)
                <div class="service-bar-column">
                    <span class="bar-value">{{ $service['count'] }}</span>
                    <div class="service-bar" style="height: {{ max(6, ($service['count'] / $chartMax) * 100) }}%; background: {{ $service['color'] }};" title="{{ $service['label'] }}: {{ $service['count'] }} berkas"></div>
                    <span class="bar-label" title="{{ $service['label'] }}">{{ $service['short'] }}</span>
                </div>
            @endforeach
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 14px; padding-top: 10px; border-top: 1px solid #edf2f7; font-size: 0.73rem; color: #64748b;">
            <span>Layanan tertinggi: <strong>{{ $highestServiceLabel }}</strong></span>
            <span>Total: <strong>{{ number_format($stats['total'], 0, ',', '.') }} pelayanan</strong></span>
        </div>
    </article>

    <article class="dashboard-panel chart-panel">
        <div class="panel-heading">
            <div>
                <h2>Tren Pelayanan Bulanan</h2>
                <p>Jumlah pelayanan setiap bulan</p>
            </div>
            <span class="chart-year">{{ $year }}</span>
        </div>
        <div class="trend-chart" role="img" aria-label="Grafik tren pelayanan bulanan {{ $year }}">
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
                    <span title="{{ $item['name'] }}: {{ $item['count'] }}">{{ $item['month'] }}</span>
                @endforeach
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 14px; padding-top: 10px; border-top: 1px solid #edf2f7; font-size: 0.73rem; color: #64748b;">
            <span>Puncak pelayanan: <strong>{{ $peakMonthLabel }}</strong></span>
            <span style="color: #94a3b8;">* Data berjalan</span>
        </div>
    </article>
</section>

{{-- STATUS PELAYANAN (PROGRESS BAR & BREAKDOWN) --}}
<section class="dashboard-panel chart-panel" style="margin-bottom: 22px;">
    <div class="panel-heading">
        <div>
            <h2>Status Pelayanan</h2>
            <p>Distribusi dan efektivitas penyelesaian berkas administrasi</p>
        </div>
        <span class="badge-camat success">Tingkat Selesai {{ $statusPelayanan['selesai']['percentage'] }}%</span>
    </div>

    <div class="progress-bar-stacked" title="Distribusi Status">
        <div class="progress-bar-segment selesai" style="width: {{ $statusPelayanan['selesai']['percentage'] }}%;" title="Selesai: {{ $statusPelayanan['selesai']['count'] }}"></div>
        <div class="progress-bar-segment proses" style="width: {{ $statusPelayanan['proses']['percentage'] }}%;" title="Proses: {{ $statusPelayanan['proses']['count'] }}"></div>
        <div class="progress-bar-segment menunggu" style="width: {{ $statusPelayanan['menunggu']['percentage'] }}%;" title="Menunggu: {{ $statusPelayanan['menunggu']['count'] }}"></div>
        <div class="progress-bar-segment revisi" style="width: {{ $statusPelayanan['perlu_revisi']['percentage'] }}%;" title="Perlu Revisi: {{ $statusPelayanan['perlu_revisi']['count'] }}"></div>
    </div>

    <div class="status-card-grid">
        <div class="status-box selesai">
            <div class="status-box-header">
                <span>SELESAI</span>
                <span class="badge-camat success">{{ $statusPelayanan['selesai']['percentage'] }}%</span>
            </div>
            <div class="status-box-val">{{ number_format($statusPelayanan['selesai']['count'], 0, ',', '.') }}</div>
            <small style="color: #15803d; font-size: 0.72rem;">Berkas tuntas diproses</small>
        </div>

        <div class="status-box proses">
            <div class="status-box-header">
                <span>DALAM PROSES</span>
                <span class="badge-camat warning">{{ $statusPelayanan['proses']['percentage'] }}%</span>
            </div>
            <div class="status-box-val">{{ number_format($statusPelayanan['proses']['count'], 0, ',', '.') }}</div>
            <small style="color: #b45309; font-size: 0.72rem;">Sedang ditangani loket</small>
        </div>

        <div class="status-box menunggu">
            <div class="status-box-header">
                <span>MENUNGGU</span>
                <span class="badge-camat info">{{ $statusPelayanan['menunggu']['percentage'] }}%</span>
            </div>
            <div class="status-box-val">{{ number_format($statusPelayanan['menunggu']['count'], 0, ',', '.') }}</div>
            <small style="color: #1e40af; font-size: 0.72rem;">Antrean verifikasi petugas</small>
        </div>

        <div class="status-box revisi">
            <div class="status-box-header">
                <span>PERLU REVISI</span>
                <span class="badge-camat danger">{{ $statusPelayanan['perlu_revisi']['percentage'] }}%</span>
            </div>
            <div class="status-box-val">{{ number_format($statusPelayanan['perlu_revisi']['count'], 0, ',', '.') }}</div>
            <small style="color: #991b1b; font-size: 0.72rem;">Dokumen belum lengkap</small>
        </div>
    </div>
</section>

{{-- TABEL PELAYANAN TERBARU --}}
<section class="dashboard-panel recent-panel">
    <div class="panel-heading">
        <div>
            <h2>Pelayanan Terbaru</h2>
            <p>Rekapitulasi berkas pendaftaran pelayanan harian</p>
        </div>
        <a href="{{ route('camat.monitoring') }}" class="panel-link">Lihat Semua Pelayanan →</a>
    </div>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Tanggal</th>
                    <th>Jenis Pelayanan</th>
                    <th style="text-align: center;">Jumlah Berkas</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pelayananTerbaru as $item)
                    <tr>
                        <td><strong>{{ $item['no'] }}</strong></td>
                        <td>{{ $item['tanggal'] }}</td>
                        <td><strong>{{ $item['jenis'] }}</strong></td>
                        <td style="text-align: center;">
                            <span style="font-weight: 700; color: #1e3a5f; background: #eef2f6; padding: 3px 10px; border-radius: 6px;">
                                {{ $item['jumlah'] }} Pemohon
                            </span>
                        </td>
                        <td>
                            @if ($item['status'] === 'Selesai')
                                <span class="status-pill selesai">Selesai</span>
                            @else
                                <span class="status-pill proses">Dalam Proses</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('camat.monitoring', ['jenis_layanan' => $item['jenis']]) }}" class="table-action edit" style="text-decoration: none;" title="Pantau detail layanan">
                                Pantau
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state" style="text-align: center; color: #64748b; padding: 20px;">
                            Belum ada data pelayanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection