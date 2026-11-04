@extends('layouts.app')

@section('title', 'Daftar Permohonan - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page">
    <aside class="sidebar dark-sidebar">
        <a href="{{ route('dashboard') }}" class="brand-wrap">
            <img src="{{ asset('images/logo-karawang.png') }}" alt="Logo Kabupaten Karawang" class="brand-mark">
            <span class="brand-text"><span class="brand-title">PATEN SPACE</span><span class="brand-sub">Pelayanan Kecamatan</span></span>
        </a>
        <nav class="sidebar-nav">
            <div class="nav-group-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item"><span>◫</span> Dashboard</a>
            <a href="{{ route('pendaftaran.create') }}" class="nav-item"><span>＋</span> Pendaftaran Baru</a>
            <a href="{{ route('petugas.permohonan.index') }}" class="nav-item active"><span>▣</span> Daftar Permohonan @if(($counts['baru'] ?? 0) > 0)<span class="nav-counter">{{ $counts['baru'] }}</span>@endif</a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-item"><span>▥</span> Semua Data</a>
            <a href="{{ route('laporan') }}" class="nav-item"><span>▥</span> Data dan Laporan</a>
            <details class="service-menu" open>
                <summary class="nav-item service-menu-toggle"><span>▤</span> Jenis Layanan <span class="menu-chevron">⌄</span></summary>
                <div class="service-submenu">
                    @foreach ($services as $service)
                        <a href="{{ route('pendaftaran.create', ['jenis_layanan' => $service['type']]) }}" class="nav-item service-nav-item">{{ $service['title'] }}</a>
                    @endforeach
                </div>
            </details>
        </nav>
        <a href="{{ route('account.profile') }}" class="sidebar-profile sidebar-profile-link" aria-label="Buka profil {{ auth()->user()->name }}">
            <span class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <span><strong>{{ auth()->user()->name }}</strong><small>Operator Kecamatan</small></span>
        </a>
    </aside>

    <main class="main-panel">
        <header class="dashboard-topbar">
            <div><strong>Kecamatan Jatisari</strong><span>Kabupaten Karawang</span></div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="report-filter-button">Keluar</button>
            </form>
        </header>

        <div class="dashboard-content">
            <section class="dashboard-heading">
                <div>
                    <h1>Daftar Permohonan</h1>
                    <p>Pantau, verifikasi kelengkapan berkas, dan proses pengajuan pelayanan warga & loket.</p>
                </div>
                <a href="{{ route('pendaftaran.create') }}" class="dashboard-primary-link">
                    <span>+</span> Pendaftaran Baru
                </a>
            </section>

            @if(session('success'))
                <div style="background: #e8f5e9; border: 1px solid #a5d6a7; color: #1b5e20; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            {{-- Kartu Metrik Status (5 Kolom Sejajar, Desain Premium & Responsif) --}}
            @php
                $activeStatus = request('status');
                $totalAll = array_sum($counts);
                $statusCards = [
                    [
                        'key' => null,
                        'label' => 'Semua Permohonan',
                        'sub' => 'Total seluruh pengajuan',
                        'count' => $totalAll,
                        'isActive' => empty($activeStatus),
                        'color' => '#796743',
                        'bg' => '#ffffff',
                        'accent' => '#f5f2eb',
                        'border' => '#c7b991',
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>',
                    ],
                    [
                        'key' => 'Baru',
                        'label' => 'Permohonan Baru',
                        'sub' => 'Menunggu verifikasi',
                        'count' => $counts['baru'] ?? 0,
                        'isActive' => $activeStatus === 'Baru',
                        'color' => '#2563eb',
                        'bg' => '#ffffff',
                        'accent' => '#eff6ff',
                        'border' => '#93c5fd',
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
                    ],
                    [
                        'key' => 'Proses',
                        'label' => 'Dalam Proses',
                        'sub' => 'Sedang dikerjakan',
                        'count' => $counts['proses'] ?? 0,
                        'isActive' => $activeStatus === 'Proses',
                        'color' => '#d97706',
                        'bg' => '#ffffff',
                        'accent' => '#fffbeb',
                        'border' => '#fde68a',
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
                    ],
                    [
                        'key' => 'Selesai',
                        'label' => 'Selesai',
                        'sub' => 'Berkas telah terbit',
                        'count' => $counts['selesai'] ?? 0,
                        'isActive' => $activeStatus === 'Selesai',
                        'color' => '#059669',
                        'bg' => '#ffffff',
                        'accent' => '#ecfdf5',
                        'border' => '#a7f3d0',
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
                    ],
                    [
                        'key' => 'Ditolak',
                        'label' => 'Ditolak',
                        'sub' => 'Perlu revisi / kurang berkas',
                        'count' => $counts['ditolak'] ?? 0,
                        'isActive' => $activeStatus === 'Ditolak',
                        'color' => '#e11d48',
                        'bg' => '#ffffff',
                        'accent' => '#fff1f2',
                        'border' => '#fecdd3',
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
                    ],
                ];
            @endphp

            <section class="status-cards-grid" aria-label="Statistik Status Permohonan">
                @foreach ($statusCards as $card)
                    @php
                        $targetUrl = $card['key']
                            ? route('petugas.permohonan.index', array_merge(request()->except('page', 'status'), ['status' => $card['key']]))
                            : route('petugas.permohonan.index', request()->except('page', 'status'));
                    @endphp
                    <a href="{{ $targetUrl }}" class="status-metric-card {{ $card['isActive'] ? 'is-active' : '' }}" style="--card-color: {{ $card['color'] }}; --card-border: {{ $card['border'] }}; --card-accent: {{ $card['accent'] }};">
                        <div class="metric-header">
                            <span class="metric-icon">
                                {!! $card['icon'] !!}
                            </span>
                            @if($card['isActive'])
                                <span class="active-badge">Aktif</span>
                            @endif
                        </div>
                        <div class="metric-body">
                            <span class="metric-title">{{ $card['label'] }}</span>
                            <div class="metric-count">{{ number_format($card['count'], 0, ',', '.') }}</div>
                            <span class="metric-sub">{{ $card['sub'] }}</span>
                        </div>
                    </a>
                @endforeach
            </section>

            {{-- Filter Bar --}}
            <section class="dashboard-panel filter-panel" style="padding: 18px 22px; margin-bottom: 22px;">
                <form method="GET" action="{{ route('petugas.permohonan.index') }}" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end;">
                    <div style="flex: 1; min-width: 170px;">
                        <label style="font-size: 12px; font-weight: 700; color: #5b4b2d; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 6px; display: block;">Status Permohonan</label>
                        <select name="status" style="width: 100%; padding: 9px 12px; border: 1px solid #d1c9b6; border-radius: 8px; font-size: 13.5px; background: #fff; color: #17334c;">
                            <option value="">Semua Status</option>
                            <option value="Baru" {{ request('status') === 'Baru' ? 'selected' : '' }}>Baru (Menunggu)</option>
                            <option value="Proses" {{ request('status') === 'Proses' ? 'selected' : '' }}>Proses (Pengerjaan)</option>
                            <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div style="flex: 1; min-width: 190px;">
                        <label style="font-size: 12px; font-weight: 700; color: #5b4b2d; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 6px; display: block;">Jenis Layanan</label>
                        <select name="jenis_layanan" style="width: 100%; padding: 9px 12px; border: 1px solid #d1c9b6; border-radius: 8px; font-size: 13.5px; background: #fff; color: #17334c;">
                            <option value="">Semua Jenis Layanan</option>
                            @foreach(['PRR','KTP','KK','Akta Kematian','Akta Lahir','Kedatangan','Pindah'] as $layanan)
                                <option value="{{ $layanan }}" {{ request('jenis_layanan') === $layanan ? 'selected' : '' }}>{{ $layanan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex: 1; min-width: 170px;">
                        <label style="font-size: 12px; font-weight: 700; color: #5b4b2d; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 6px; display: block;">Sumber Berkas</label>
                        <select name="sumber" style="width: 100%; padding: 9px 12px; border: 1px solid #d1c9b6; border-radius: 8px; font-size: 13.5px; background: #fff; color: #17334c;">
                            <option value="">Semua Sumber</option>
                            <option value="masyarakat" {{ request('sumber') === 'masyarakat' ? 'selected' : '' }}>Warga Online</option>
                            <option value="petugas" {{ request('sumber') === 'petugas' ? 'selected' : '' }}>Loket (Petugas)</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 8px;">
                        <button type="submit" style="padding: 9px 20px; background: #796743; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 13.5px; font-weight: 700; transition: background .15s ease;">
                            Filter
                        </button>
                        <a href="{{ route('petugas.permohonan.index') }}" style="padding: 9px 16px; background: #e8e2d2; color: #3f3523; border-radius: 8px; font-size: 13.5px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;">
                            Reset
                        </a>
                    </div>
                </form>
            </section>

            {{-- Tabel Permohonan --}}
            <section class="dashboard-panel" style="overflow: hidden;">
                <div class="panel-heading" style="padding: 20px 24px; margin-bottom: 0; border-bottom: 1px solid #f0ede4; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h2 style="margin: 0; font-size: 1.15rem; color: #17334c;">
                            Daftar Permohonan
                            @if(request('status'))
                                <span style="font-size: 0.85rem; font-weight: 600; color: #796743;">• Status: {{ request('status') }}</span>
                            @endif
                        </h2>
                        <p style="margin: 3px 0 0; font-size: 0.8rem; color: #6b7c93;">Menampilkan data permohonan terkini</p>
                    </div>
                    <span style="background: #f5f2eb; color: #5b4b2d; font-size: 12.5px; font-weight: 700; padding: 5px 12px; border-radius: 999px; border: 1px solid #e1d8c2;">
                        {{ $pendaftarans->count() }} Permohonan
                    </span>
                </div>

                <div class="table-scroll">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid #e3ebf2;">
                                <th style="width: 48px; text-align: center;">No</th>
                                <th>Nama Pemohon</th>
                                <th>NIK</th>
                                <th>Jenis Layanan</th>
                                <th>Sumber</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th style="text-align: right; padding-right: 24px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendaftarans as $index => $item)
                                <tr style="border-bottom: 1px solid #f3f0e8; {{ ($item->status === 'Baru' && request('status') === 'Baru') ? 'background: #fffdf5;' : '' }}">
                                    <td style="text-align: center; color: #8898aa; font-weight: 600;">{{ $index + 1 }}</td>
                                    <td class="person-name" style="font-weight: 700; color: #17334c;">
                                        {{ $item->nama_lengkap }}
                                    </td>
                                    <td style="color: #475569; font-family: monospace; font-size: 0.84rem;">
                                        {{ $item->nik }}
                                    </td>
                                    <td>
                                        <span style="font-weight: 600; color: #263f55;">{{ $item->jenis_layanan }}</span>
                                    </td>
                                    <td>
                                        @if(($item->intake_source ?? '') === 'masyarakat')
                                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 700;">
                                                <span>📱</span> Warga (Online)
                                            </span>
                                        @else
                                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #f5f2eb; color: #5b4b2d; padding: 3px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 700;">
                                                <span>🏛️</span> Loket (Petugas)
                                            </span>
                                        @endif
                                    </td>
                                    <td style="color: #64748b; font-size: 0.82rem;">
                                        {{ \Illuminate\Support\Facades\Date::parse($item->tanggal_daftar)->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        @php $status = $item->status ?? 'Baru'; @endphp
                                        @if($status === 'Selesai')
                                            <span class="status-pill selesai">{{ $status }}</span>
                                        @elseif($status === 'Proses')
                                            <span class="status-pill proses">{{ $status }}</span>
                                        @elseif($status === 'Ditolak')
                                            <span class="status-pill" style="background: #fee2e2; color: #991b1b;">{{ $status }}</span>
                                        @else
                                            <span class="status-pill" style="background: #e8e2d2; color: #5b4b2d;">{{ $status }}</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right; padding-right: 24px;">
                                        <div style="display: inline-flex; gap: 8px; align-items: center;">
                                            <a href="{{ route('petugas.permohonan.show', $item) }}"
                                               style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; background: #796743; color: #fff; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 700; transition: background .15s ease;">
                                                <span>👁️</span> Detail & Status
                                            </a>
                                            <a href="{{ route('petugas.permohonan.formulir', $item) }}" target="_blank"
                                               style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; background: #b45309; color: #fff; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 700;"
                                               title="Cetak formulir resmi">
                                                <span>🖨️</span> Cetak Formulir
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; color: #8898aa; padding: 48px 24px;">
                                        <div style="font-size: 2.2rem; margin-bottom: 8px;">📂</div>
                                        <div style="font-size: 1rem; font-weight: 700; color: #17334c; margin-bottom: 4px;">Tidak Ada Permohonan Ditemukan</div>
                                        <div style="font-size: 0.85rem; color: #718198;">Silakan sesuaikan filter pencarian atau tunggu pengajuan berikutnya.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>

    <style>
        .status-cards-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .status-metric-card {
            background: #ffffff;
            border: 1px solid #e5e0d3;
            border-radius: 12px;
            padding: 16px 18px;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            gap: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            position: relative;
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .status-metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--card-color);
            opacity: 0.85;
            transition: height 0.18s ease;
        }

        .status-metric-card:hover {
            transform: translateY(-2px);
            border-color: var(--card-border);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.06);
        }

        .status-metric-card:hover::before {
            height: 4px;
        }

        .status-metric-card.is-active {
            border-color: var(--card-color);
            background: linear-gradient(180deg, #ffffff 0%, var(--card-accent) 100%);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        }

        .status-metric-card.is-active::before {
            height: 4px;
        }

        .metric-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .metric-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--card-accent);
            color: var(--card-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.18s ease;
        }

        .status-metric-card:hover .metric-icon {
            transform: scale(1.08);
        }

        .active-badge {
            font-size: 11px;
            font-weight: 700;
            color: var(--card-color);
            background: var(--card-accent);
            border: 1px solid var(--card-border);
            padding: 2px 7px;
            border-radius: 999px;
            letter-spacing: 0.02em;
        }

        .metric-body {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .metric-title {
            font-size: 0.76rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .metric-count {
            font-size: 1.85rem;
            font-weight: 800;
            color: #17334c;
            line-height: 1.1;
            margin: 2px 0;
            font-variant-numeric: tabular-nums;
        }

        .metric-sub {
            font-size: 0.74rem;
            color: #94a3b8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 1200px) {
            .status-cards-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 720px) {
            .status-cards-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .metric-count {
                font-size: 1.5rem;
            }
        }
    </style>
</div>
@endsection
