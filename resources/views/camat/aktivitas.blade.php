@extends('layouts.camat')

@section('title', 'Aktivitas Pelayanan - PATEN SPACE Camat Jatisari')

@section('camat_content')
{{-- HEADING AKTIVITAS PELAYANAN --}}
<section class="dashboard-heading">
    <div>
        <h1>Aktivitas Pelayanan</h1>
        <p>Log dan riwayat aktivitas operasional pelayanan oleh Operator Kecamatan Jatisari</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('camat.monitoring') }}" class="dashboard-primary-link">
            <span>▣</span> Monitoring Pelayanan
        </a>
    </div>
</section>

{{-- STAT SUMMARY CARDS --}}
<section class="dashboard-stats" style="grid-template-columns: repeat(3, minmax(0, 1fr)); margin-bottom: 22px;">
    <div class="dashboard-stat total-stat" style="border-left: 4px solid #0870c9;">
        <span class="stat-symbol">⏱</span>
        <div>
            <span>Total Catatan Aktivitas</span>
            <strong>{{ $totalAktivitas }} Aktivitas</strong>
            <small style="color: #64748b; font-size: 0.7rem;">Sesi Operasional Hari Ini</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #16a34a;">
        <span class="stat-symbol green">👥</span>
        <div>
            <span>Operator Aktif</span>
            <strong>2 Operator Loket</strong>
            <small style="color: #16a34a; font-size: 0.7rem;">Operator 01 & Operator 02</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #2563eb;">
        <span class="stat-symbol blue">✓</span>
        <div>
            <span>Status Pelayanan</span>
            <strong style="color: #15803d;">100% Berhasil</strong>
            <small style="color: #2563eb; font-size: 0.7rem;">Semua Transaksi Tervalidasi</small>
        </div>
    </div>
</section>

{{-- PANEL TABEL AKTIVITAS PELAYANAN --}}
<section class="dashboard-panel recent-panel">
    <div class="panel-heading">
        <div>
            <h2>Log Aktivitas Pelayanan</h2>
            <p>Riwayat tindakan pengelolaan pelayanan oleh operator</p>
        </div>
        <span class="badge-camat info">Live Activity</span>
    </div>

    {{-- Filter Operator --}}
    <form method="GET" action="{{ route('camat.aktivitas') }}" class="report-filters" style="background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; margin-bottom: 0;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <label style="font-size: 0.8rem; font-weight: 700; color: #475569;">Filter Operator:</label>
            <select name="operator" aria-label="Filter Operator" onchange="this.form.submit()">
                <option value="semua" {{ $filterOperator === 'semua' ? 'selected' : '' }}>Semua Operator</option>
                <option value="Operator 01" {{ $filterOperator === 'Operator 01' ? 'selected' : '' }}>Operator 01</option>
                <option value="Operator 02" {{ $filterOperator === 'Operator 02' ? 'selected' : '' }}>Operator 02</option>
            </select>
        </div>
        <button type="submit" class="report-filter-button">Filter</button>

        @if ($filterOperator !== 'semua')
            <a href="{{ route('camat.aktivitas') }}" class="report-reset">Reset</a>
        @endif
    </form>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Aktivitas</th>
                    <th>Operator</th>
                    <th>Waktu</th>
                    <th style="text-align: center; width: 130px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $index => $item)
                    <tr>
                        <td><strong>0{{ $loop->iteration }}</strong></td>
                        <td>
                            <strong style="color: #1e3a5f; font-size: 0.86rem;">{{ $item['aktivitas'] }}</strong>
                            <div style="font-size: 0.72rem; color: #64748b;">Layanan: {{ $item['jenis'] }}</div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 26px; height: 26px; border-radius: 50%; background: #0870c9; color: #fff; font-size: 0.65rem; font-weight: 700; display: grid; place-items: center;">
                                    OP
                                </div>
                                <span style="font-weight: 600; color: #334155;">{{ $item['operator'] }}</span>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: #475569; background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 0.78rem;">
                                {{ $item['waktu'] }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <span class="status-pill selesai">
                                ✓ {{ $item['status'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">Tidak ada log aktivitas untuk operator yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
