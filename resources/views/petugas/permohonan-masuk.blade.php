@extends('layouts.app')

@section('title', 'Permohonan Masuk - PATEN SPACE')

@section('content')
<div class="app-shell">
    <aside class="sidebar dark-sidebar">
        <button class="sidebar-toggle" type="button" aria-label="Buka atau tutup sidebar" aria-expanded="true">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="brand-wrap">
            <div class="brand-mark">P</div>
            <div class="brand-text">
                <div class="brand-title">PATEN SPACE</div>
                <div class="brand-sub">Kecamatan Jatisari</div>
            </div>
        </div>

        <div class="sidebar-nav">
            <div class="nav-group-label">Dashboard</div>
            <a href="{{ route('petugas.dashboard') }}" class="nav-item">Dashboard</a>

            <div class="nav-group-label">Permohonan</div>
            <a href="{{ route('petugas.permohonan.index') }}" class="nav-item active">
                Permohonan Masuk
                @if(($counts['baru'] ?? 0) > 0)
                    <span style="background:#ef4444;color:#fff;border-radius:9999px;padding:2px 8px;font-size:11px;margin-left:6px;font-weight:700;">{{ $counts['baru'] }}</span>
                @endif
            </a>
            <a href="{{ route('pendaftaran.create') }}" class="nav-item">Input Manual</a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item">Semua Data</a>

            <div class="nav-group-label">Laporan</div>
            <a href="{{ route('laporan') }}" class="nav-item">Laporan</a>
        </div>

        <form method="POST" action="{{ route('logout') }}" style="padding: 16px;">
            @csrf
            <button class="logout-btn" type="submit">Keluar</button>
        </form>
    </aside>

    <main class="main-panel">
        <header class="topbar">
            <div>
                <h1>Permohonan Masuk</h1>
                <p>Daftar permohonan layanan yang diajukan langsung oleh masyarakat.</p>
            </div>
            <div class="date-pill">{{ now()->translatedFormat('l, d F Y') }}</div>
        </header>

        @if(session('success'))
            <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:12px 16px;border-radius:8px;margin:16px 24px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Statistik Ringkas --}}
        <section class="stats-grid" style="padding: 0 24px; margin-bottom: 16px;">
            <a href="{{ route('petugas.permohonan.index') }}" style="text-decoration:none;" class="stat-card">
                <div class="stat-content">
                    <div class="stat-label">Semua</div>
                    <div class="stat-value">{{ array_sum($counts) }}</div>
                </div>
            </a>
            <a href="{{ route('petugas.permohonan.index', ['status' => 'Baru']) }}" style="text-decoration:none;" class="stat-card">
                <div class="stat-icon mint">🆕</div>
                <div class="stat-content">
                    <div class="stat-label">Baru</div>
                    <div class="stat-value">{{ $counts['baru'] }}</div>
                </div>
            </a>
            <a href="{{ route('petugas.permohonan.index', ['status' => 'Proses']) }}" style="text-decoration:none;" class="stat-card">
                <div class="stat-icon violet">⚙️</div>
                <div class="stat-content">
                    <div class="stat-label">Proses</div>
                    <div class="stat-value">{{ $counts['proses'] }}</div>
                </div>
            </a>
            <a href="{{ route('petugas.permohonan.index', ['status' => 'Selesai']) }}" style="text-decoration:none;" class="stat-card">
                <div class="stat-icon green">✅</div>
                <div class="stat-content">
                    <div class="stat-label">Selesai</div>
                    <div class="stat-value">{{ $counts['selesai'] }}</div>
                </div>
            </a>
            <a href="{{ route('petugas.permohonan.index', ['status' => 'Ditolak']) }}" style="text-decoration:none;" class="stat-card">
                <div class="stat-icon orange">❌</div>
                <div class="stat-content">
                    <div class="stat-label">Ditolak</div>
                    <div class="stat-value">{{ $counts['ditolak'] }}</div>
                </div>
            </a>
        </section>

        {{-- Filter --}}
        <section style="padding: 0 24px; margin-bottom: 20px;">
            <form method="GET" action="{{ route('petugas.permohonan.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
                <div class="field-group" style="margin:0;min-width:180px;">
                    <label style="font-size:13px;color:#6b7280;margin-bottom:4px;display:block;">Filter Status</label>
                    <select name="status" class="form-control" style="padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;">
                        <option value="">Semua Status</option>
                        <option value="Baru" {{ request('status') === 'Baru' ? 'selected' : '' }}>Baru</option>
                        <option value="Proses" {{ request('status') === 'Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="field-group" style="margin:0;min-width:180px;">
                    <label style="font-size:13px;color:#6b7280;margin-bottom:4px;display:block;">Filter Jenis Layanan</label>
                    <select name="jenis_layanan" style="padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;">
                        <option value="">Semua Layanan</option>
                        @foreach(['PRR','KTP','KK','Akta Kematian','Akta Lahir','Kedatangan','Pindah'] as $layanan)
                            <option value="{{ $layanan }}" {{ request('jenis_layanan') === $layanan ? 'selected' : '' }}>{{ $layanan }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:8px;">
                    <button type="submit" style="padding:8px 18px;background:#2563eb;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:14px;">Filter</button>
                    <a href="{{ route('petugas.permohonan.index') }}" style="padding:8px 18px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:14px;text-decoration:none;">Reset</a>
                </div>
            </form>
        </section>

        {{-- Tabel --}}
        <section class="table-panel">
            <div class="section-header">
                <h2>
                    Daftar Permohonan
                    @if(request('status'))
                        <span style="font-size:14px;font-weight:400;color:#6b7280;">— {{ request('status') }}</span>
                    @endif
                </h2>
                <span style="color:#6b7280;font-size:14px;">{{ $pendaftarans->count() }} permohonan</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Jenis Layanan</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendaftarans as $index => $item)
                        <tr style="{{ ($item->status === 'Baru' && request('status') === 'Baru') ? 'background:#fefce8;' : '' }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->nik }}</td>
                            <td>{{ $item->jenis_layanan }}</td>
                            <td>{{ \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d-m-Y') }}</td>
                            <td>
                                @php $status = $item->status ?? 'Baru'; @endphp
                                @if($status === 'Selesai')
                                    <span class="badge selected">{{ $status }}</span>
                                @elseif($status === 'Proses')
                                    <span class="badge processing">{{ $status }}</span>
                                @elseif($status === 'Ditolak')
                                    <span class="badge" style="background:#fee2e2;color:#991b1b;">{{ $status }}</span>
                                @else
                                    <span class="badge" style="background:#f3f4f6;color:#374151;">{{ $status }}</span>
                                @endif
                            </td>
                            <td>
                                @php $docCount = count($item->documents ?? []); @endphp
                                @if($docCount > 0)
                                    <span style="color:#2563eb;font-weight:600;">{{ $docCount }} file</span>
                                @else
                                    <span style="color:#9ca3af;">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('petugas.permohonan.show', $item) }}" class="table-action edit">Detail & Update Status</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; color:#667085; padding: 24px;">Tidak ada permohonan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            if (!sidebar || !toggle) return;
            toggle.addEventListener('click', function () {
                const isCollapsed = sidebar.classList.toggle('collapsed');
                toggle.setAttribute('aria-expanded', String(!isCollapsed));
            });
        });
    </script>
</div>
@endsection
