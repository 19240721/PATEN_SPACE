@extends('layouts.camat')

@section('title', 'Aktivitas Pelayanan - PATEN SPACE Camat Jatisari')

@section('camat_content')
{{-- HEADING AKTIVITAS PELAYANAN --}}
<section class="dashboard-heading">
    <div>
        <h1>Aktivitas Pelayanan</h1>
        <p>Log dan riwayat aktivitas operasional pelayanan oleh Petugas dan Operator Kecamatan Jatisari</p>
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
            <strong id="stat-total-aktivitas">{{ $totalAktivitas }} Aktivitas</strong>
            <small id="stat-sub-aktivitas" style="color: #64748b; font-size: 0.7rem;">{{ $totalAktivitas > 0 ? 'Sesi Operasional Terdata' : 'Belum Ada Aktivitas' }}</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #16a34a;">
        <span class="stat-symbol green">👥</span>
        <div>
            <span>Petugas Layanan Aktif</span>
            <strong id="stat-petugas-aktif">{{ $totalPetugasLayanan }} Petugas Layanan</strong>
            <small id="stat-sub-petugas" style="color: #16a34a; font-size: 0.7rem;">{{ $breakdownPetugas }}</small>
        </div>
    </div>
    <div class="dashboard-stat" style="border-left: 4px solid #2563eb;">
        <span class="stat-symbol blue">✓</span>
        <div>
            <span>Status Pelayanan</span>
            <strong id="stat-status-pelayanan" style="color: {{ $statusPelayananColor }};">{{ $statusPelayananText }}</strong>
            <small id="stat-sub-status" style="color: {{ $statusPelayananText === '—' ? '#64748b' : '#2563eb' }}; font-size: 0.7rem;">{{ $statusPelayananSub }}</small>
        </div>
    </div>
</section>

{{-- PANEL TABEL AKTIVITAS PELAYANAN --}}
<section class="dashboard-panel recent-panel">
    <div class="panel-heading">
        <div>
            <h2>Log Aktivitas Pelayanan</h2>
            <p>Riwayat tindakan pengelolaan pelayanan oleh operator dan petugas loket</p>
        </div>
        <span class="badge-camat info" id="live-activity-badge">Live Activity</span>
    </div>

    {{-- Filter Operator --}}
    <form method="GET" action="{{ route('camat.aktivitas') }}" class="report-filters" style="background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; margin-bottom: 0;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <label for="filter-operator-select" style="font-size: 0.8rem; font-weight: 700; color: #475569;">Filter Petugas / Operator:</label>
            <select name="operator" id="filter-operator-select" aria-label="Filter Operator" onchange="this.form.submit()">
                <option value="semua" {{ $filterOperator === 'semua' ? 'selected' : '' }}>Semua Operator & Petugas</option>
                @if(!empty($availableOperators))
                    @foreach ($availableOperators as $op)
                        <option value="{{ $op['name'] }}" {{ $filterOperator === $op['name'] ? 'selected' : '' }}>
                            {{ $op['name'] }} ({{ $op['peran'] }})
                        </option>
                    @endforeach
                @endif
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
                    <th>Pelaksana</th>
                    <th>Waktu</th>
                    <th style="text-align: center; width: 140px;">Status</th>
                </tr>
            </thead>
            <tbody id="log-table-body">
                @forelse ($logs as $index => $item)
                    <tr>
                        <td><strong>{{ $item['number'] }}</strong></td>
                        <td>
                            <strong style="color: #1e3a5f; font-size: 0.86rem;">{{ $item['aktivitas'] }}</strong>
                            <div style="font-size: 0.72rem; color: #64748b;">
                                Layanan: {{ $item['jenis'] }}
                                @if(!empty($item['deskripsi'])) &bull; {{ $item['deskripsi'] }} @endif
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: {{ ($item['peran'] ?? '') === 'Operator' ? '#0870c9' : '#796743' }}; color: #fff; font-size: 0.65rem; font-weight: 700; display: grid; place-items: center;">
                                    {{ ($item['peran'] ?? '') === 'Operator' ? 'OP' : 'PT' }}
                                </div>
                                <div>
                                    <span style="font-weight: 600; color: #334155; display: block; line-height: 1.2;">{{ $item['operator'] }}</span>
                                    <small style="font-size: 0.68rem; color: #64748b; font-weight: 600;">{{ $item['peran'] }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: #475569; background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 0.78rem;" title="{{ $item['waktu_detail'] ?? '' }}">
                                {{ $item['waktu'] }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @php
                                $st = strtolower($item['status'] ?? 'berhasil');
                                $pillClass = $st === 'berhasil' ? 'selesai' : ($st === 'ditolak' ? 'ditolak' : 'proses');
                                $pillIcon = $st === 'berhasil' ? '✓' : ($st === 'ditolak' ? '✕' : '⏱');
                            @endphp
                            <span class="status-pill {{ $pillClass }}">
                                {{ $pillIcon }} {{ $item['status'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr id="empty-state-row">
                        <td colspan="5" class="empty-state" style="text-align: center; padding: 32px 16px; color: #64748b;">
                            <div style="font-size: 1.8rem; margin-bottom: 6px;">📋</div>
                            <div style="font-weight: 600; font-size: 0.9rem; color: #334155;">
                                {{ $filterOperator !== 'semua' ? 'Tidak ada catatan aktivitas untuk petugas/operator yang dipilih.' : 'Belum ada catatan aktivitas pelayanan yang tersimpan di sistem.' }}
                            </div>
                            <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 4px;">
                                Aktivitas operasional yang dilakukan Operator dan Petugas akan tercatat secara otomatis di sini.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<script>
    // SINKRONISASI OTOMATIS LOG AKTIVITAS TANPA RELOAD HALAMAN (POLLING REAL-TIME)
    (function() {
        let isPolling = false;
        const pollInterval = 8000; // 8 detik

        async function fetchAktivitasData() {
            if (document.hidden || isPolling) {
                return;
            }

            try {
                isPolling = true;
                const filterSelect = document.getElementById('filter-operator-select');
                const currentFilter = filterSelect ? filterSelect.value : 'semua';
                const url = new URL("{{ route('camat.aktivitas') }}", window.location.origin);
                url.searchParams.set('format', 'json');
                if (currentFilter !== 'semua') {
                    url.searchParams.set('operator', currentFilter);
                }

                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) return;
                const data = await response.json();
                if (!data || !data.success) return;

                // 1. Perbarui Nilai Kartu Ringkasan
                const elTotal = document.getElementById('stat-total-aktivitas');
                const elSubTotal = document.getElementById('stat-sub-aktivitas');
                if (elTotal) elTotal.innerText = data.totalAktivitasFormatted;
                if (elSubTotal) elSubTotal.innerText = data.subAktivitas;

                const elPetugas = document.getElementById('stat-petugas-aktif');
                const elSubPetugas = document.getElementById('stat-sub-petugas');
                if (elPetugas) elPetugas.innerText = data.totalPetugasLayananFormatted;
                if (elSubPetugas) elSubPetugas.innerText = data.breakdownPetugas;

                const elStatus = document.getElementById('stat-status-pelayanan');
                const elSubStatus = document.getElementById('stat-sub-status');
                if (elStatus) {
                    elStatus.innerText = data.statusPelayananText;
                    elStatus.style.color = data.statusPelayananColor;
                }
                if (elSubStatus) {
                    elSubStatus.innerText = data.statusPelayananSub;
                    elSubStatus.style.color = data.statusPelayananText === '—' ? '#64748b' : '#2563eb';
                }

                // 2. Perbarui Tabel Log Aktivitas
                const tbody = document.getElementById('log-table-body');
                if (!tbody) return;

                if (!data.logs || data.logs.length === 0) {
                    const emptyMsg = currentFilter !== 'semua'
                        ? 'Tidak ada catatan aktivitas untuk petugas/operator yang dipilih.'
                        : 'Belum ada catatan aktivitas pelayanan yang tersimpan di sistem.';
                    tbody.innerHTML = `
                        <tr id="empty-state-row">
                            <td colspan="5" class="empty-state" style="text-align: center; padding: 32px 16px; color: #64748b;">
                                <div style="font-size: 1.8rem; margin-bottom: 6px;">📋</div>
                                <div style="font-weight: 600; font-size: 0.9rem; color: #334155;">${emptyMsg}</div>
                                <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 4px;">Aktivitas operasional yang dilakukan Operator dan Petugas akan tercatat secara otomatis di sini.</div>
                            </td>
                        </tr>
                    `;
                } else {
                    let rowsHtml = '';
                    data.logs.forEach(item => {
                        const avatarBg = item.peran === 'Operator' ? '#0870c9' : '#796743';
                        const avatarText = item.peran === 'Operator' ? 'OP' : 'PT';
                        const st = (item.status || 'Berhasil').toLowerCase();
                        const pillClass = st === 'berhasil' ? 'selesai' : (st === 'ditolak' ? 'ditolak' : 'proses');
                        const pillIcon = st === 'berhasil' ? '✓' : (st === 'ditolak' ? '✕' : '⏱');
                        const desc = item.deskripsi ? ` &bull; ${escapeHtml(item.deskripsi)}` : '';

                        rowsHtml += `
                            <tr>
                                <td><strong>${item.number}</strong></td>
                                <td>
                                    <strong style="color: #1e3a5f; font-size: 0.86rem;">${escapeHtml(item.aktivitas)}</strong>
                                    <div style="font-size: 0.72rem; color: #64748b;">Layanan: ${escapeHtml(item.jenis)}${desc}</div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 28px; height: 28px; border-radius: 50%; background: ${avatarBg}; color: #fff; font-size: 0.65rem; font-weight: 700; display: grid; place-items: center;">
                                            ${avatarText}
                                        </div>
                                        <div>
                                            <span style="font-weight: 600; color: #334155; display: block; line-height: 1.2;">${escapeHtml(item.operator)}</span>
                                            <small style="font-size: 0.68rem; color: #64748b; font-weight: 600;">${escapeHtml(item.peran)}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: #475569; background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 0.78rem;" title="${escapeHtml(item.waktu_detail || '')}">
                                        ${escapeHtml(item.waktu)}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="status-pill ${pillClass}">
                                        ${pillIcon} ${escapeHtml(item.status)}
                                    </span>
                                </td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = rowsHtml;
                }
            } catch (err) {
                console.warn('Gagal memuat pembaruan aktivitas:', err);
            } finally {
                isPolling = false;
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        setInterval(fetchAktivitasData, pollInterval);
    })();
</script>
@endsection
