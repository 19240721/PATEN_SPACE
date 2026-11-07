<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CamatController extends Controller
{
    /**
     * Data dasar pelayanan Kecamatan Jatisari yang dihitung dinamis dari data Pendaftaran Operator
     */
    private function getServicesCatalog($pendaftarans = null): array
    {
        if ($pendaftarans === null) {
            $pendaftarans = Pendaftaran::all();
        }

        $catalogTemplates = [
            ['key' => 'prr', 'type' => 'PRR', 'label' => 'PRR / KTP Baru', 'short' => 'PRR', 'icon' => '▤', 'color' => '#0870c9'],
            ['key' => 'ktp', 'type' => 'KTP', 'label' => 'KTP', 'short' => 'KTP', 'icon' => '▣', 'color' => '#2463eb'],
            ['key' => 'kk', 'type' => 'KK', 'label' => 'Kartu Keluarga', 'short' => 'KK', 'icon' => '♧', 'color' => '#168047'],
            ['key' => 'akta_lahir', 'type' => 'Akta Lahir', 'label' => 'Akta Kelahiran', 'short' => 'Akta Lahir', 'icon' => '✳', 'color' => '#c57d14'],
            ['key' => 'akta_mati', 'type' => 'Akta Kematian', 'label' => 'Akta Kematian', 'short' => 'Akta Mati', 'icon' => '▧', 'color' => '#64748b'],
            ['key' => 'kedatangan', 'type' => 'Kedatangan', 'label' => 'Kedatangan', 'short' => 'Kedatangan', 'icon' => '⌂', 'color' => '#00a997'],
            ['key' => 'pindah', 'type' => 'Pindah', 'label' => 'Pindah', 'short' => 'Pindah', 'icon' => '→', 'color' => '#4f46e5'],
        ];

        $services = [];
        foreach ($catalogTemplates as $tmpl) {
            $items = $pendaftarans->filter(function ($item) use ($tmpl) {
                $jl = trim(strtolower($item->jenis_layanan ?? ''));
                return $jl === strtolower($tmpl['type']) ||
                       $jl === strtolower($tmpl['short']) ||
                       $jl === strtolower($tmpl['label']) ||
                       $jl === strtolower($tmpl['key']);
            });

            $count = $items->count();
            $selesai = $items->filter(fn($i) => strtolower($i->status ?? '') === 'selesai')->count();
            $proses = $items->filter(fn($i) => strtolower($i->status ?? '') === 'proses')->count();
            $menunggu = $items->filter(fn($i) => strtolower($i->status ?? '') === 'menunggu')->count();

            if ($proses == 0 && $menunggu == 0 && $count > $selesai) {
                $proses = $count - $selesai;
            }

            $services[] = [
                'key' => $tmpl['key'],
                'label' => $tmpl['label'],
                'short' => $tmpl['short'],
                'count' => $count,
                'selesai' => $selesai,
                'proses' => $proses,
                'menunggu' => $menunggu,
                'icon' => $tmpl['icon'],
                'color' => $tmpl['color'],
            ];
        }

        return $services;
    }

    /**
     * Tren data bulanan pelayanan tahun berjalan (berdasarkan data Pendaftaran)
     */
    private function getMonthlyTrend($pendaftarans = null, ?int $year = null): array
    {
        if ($pendaftarans === null) {
            $pendaftarans = Pendaftaran::all();
        }

        $year = $year ?? now()->year;
        $monthNames = [
            1 => ['short' => 'Jan', 'full' => 'Januari'],
            2 => ['short' => 'Feb', 'full' => 'Februari'],
            3 => ['short' => 'Mar', 'full' => 'Maret'],
            4 => ['short' => 'Apr', 'full' => 'April'],
            5 => ['short' => 'Mei', 'full' => 'Mei'],
            6 => ['short' => 'Jun', 'full' => 'Juni'],
            7 => ['short' => 'Jul', 'full' => 'Juli'],
            8 => ['short' => 'Agu', 'full' => 'Agustus'],
            9 => ['short' => 'Sep', 'full' => 'September'],
            10 => ['short' => 'Okt', 'full' => 'Oktober'],
            11 => ['short' => 'Nov', 'full' => 'November'],
            12 => ['short' => 'Des', 'full' => 'Desember'],
        ];

        $trend = [];
        foreach (range(1, 12) as $m) {
            $count = $pendaftarans->filter(function ($item) use ($year, $m) {
                if (blank($item->tanggal_daftar)) {
                    return false;
                }
                try {
                    $date = Carbon::parse($item->tanggal_daftar);
                    return $date->year === $year && $date->month === $m;
                } catch (\Exception $e) {
                    return false;
                }
            })->count();

            $trend[] = [
                'month' => $monthNames[$m]['short'],
                'name' => $monthNames[$m]['full'],
                'count' => $count,
            ];
        }

        return $trend;
    }

    /**
     * Halaman Dashboard Utama Camat
     */
    public function dashboard()
    {
        $pendaftarans = Pendaftaran::latest('tanggal_daftar')->get();
        $year = now()->year;
        $currentMonthName = now()->locale('id')->isoFormat('MMMM YYYY');

        $services = $this->getServicesCatalog($pendaftarans);
        $monthlyTrend = $this->getMonthlyTrend($pendaftarans, $year);

        $total = $pendaftarans->count();

        // Hitung pelayanan bulan ini
        $bulanIni = $pendaftarans->filter(function ($item) use ($year) {
            if (blank($item->tanggal_daftar)) return false;
            try {
                $d = Carbon::parse($item->tanggal_daftar);
                return $d->year === $year && $d->month === now()->month;
            } catch (\Exception $e) {
                return false;
            }
        })->count();

        $selesai = $pendaftarans->filter(fn($i) => strtolower($i->status ?? '') === 'selesai')->count();
        $dalamProses = $total - $selesai;

        $stats = [
            'total' => $total,
            'bulan_ini' => $bulanIni,
            'selesai' => $selesai,
            'dalam_proses' => $dalamProses,
            'perlu_perhatian' => 0,
        ];

        // Persentase Status Pelayanan
        $pctSelesai = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;
        $pctProses = $total > 0 ? round(($dalamProses / $total) * 100, 1) : 0;
        $menungguCount = $pendaftarans->filter(fn($i) => strtolower($i->status ?? '') === 'menunggu')->count();
        $pctMenunggu = $total > 0 ? round(($menungguCount / $total) * 100, 1) : 0;
        $revisiCount = $pendaftarans->filter(fn($i) => in_array(strtolower($i->status ?? ''), ['perlu revisi', 'revisi']))->count();
        $pctRevisi = $total > 0 ? round(($revisiCount / $total) * 100, 1) : 0;

        $statusPelayanan = [
            'selesai' => ['label' => 'Selesai', 'count' => $selesai, 'percentage' => $pctSelesai, 'class' => 'selesai'],
            'proses' => ['label' => 'Dalam Proses', 'count' => $dalamProses, 'percentage' => $pctProses, 'class' => 'proses'],
            'menunggu' => ['label' => 'Menunggu Verifikasi', 'count' => $menungguCount, 'percentage' => $pctMenunggu, 'class' => 'menunggu'],
            'perlu_revisi' => ['label' => 'Perlu Revisi Dokumen', 'count' => $revisiCount, 'percentage' => $pctRevisi, 'class' => 'revisi'],
        ];

        // Catatan Perlu Perhatian berbasis data pelayanan real
        $perhatian = [];
        if ($dalamProses > 0) {
            $perhatian[] = [
                'id' => 1,
                'pesan' => "{$dalamProses} pelayanan administrasi saat ini masih dalam proses pengerjaan operator",
                'level' => 'info',
                'badge' => 'Dalam Proses',
                'action_label' => 'Pantau Progres',
                'action_url' => route('camat.monitoring'),
            ];
        }
        if ($revisiCount > 0) {
            $perhatian[] = [
                'id' => 2,
                'pesan' => "{$revisiCount} berkas permohonan memerlukan verifikasi ulang dokumen",
                'level' => 'warning',
                'badge' => 'Perlu Perhatian',
                'action_label' => 'Lihat Data',
                'action_url' => route('camat.monitoring'),
            ];
        }

        $stats['perlu_perhatian'] = count($perhatian);

        // Pelayanan Terbaru (Data Agregat dari Pendaftaran real)
        $grouped = $pendaftarans->groupBy(function ($item) {
            $date = $item->tanggal_daftar ? Carbon::parse($item->tanggal_daftar)->format('Y-m-d') : 'tanpa_tanggal';
            return $date . '_' . ($item->jenis_layanan ?? 'Pelayanan');
        })->take(8);

        $pelayananTerbaru = [];
        $no = 1;
        foreach ($grouped as $key => $items) {
            $first = $items->first();
            $pelayananTerbaru[] = [
                'no' => sprintf('%02d', $no++),
                'tanggal' => $first->tanggal_daftar ? Carbon::parse($first->tanggal_daftar)->format('d M Y') : '—',
                'jenis' => $first->jenis_layanan ?? 'Pelayanan',
                'jumlah' => $items->count(),
                'status' => $items->every(fn($i) => strtolower($i->status ?? '') === 'selesai') ? 'Selesai' : 'Dalam Proses',
            ];
        }

        // Kalkulasi Grafik Jenis Pelayanan
        $countsArray = array_column($services, 'count');
        $maxServiceCount = !empty($countsArray) ? max($countsArray) : 0;
        $chartMax = max(1, $maxServiceCount);

        $highestService = collect($services)->sortByDesc('count')->first();
        $highestServiceLabel = ($total > 0 && $highestService && $highestService['count'] > 0)
            ? "{$highestService['label']} ({$highestService['count']})"
            : "-";

        // Kalkulasi Grafik Tren Bulanan (SVG Points)
        $monthlyCounts = array_column($monthlyTrend, 'count');
        $monthlyMax = max(1, !empty($monthlyCounts) ? max($monthlyCounts) : 0);
        $chartPoints = [];
        foreach ($monthlyCounts as $index => $count) {
            $x = 24 + ($index * 432 / 11);
            $y = 128 - ($count / $monthlyMax * 100);
            $chartPoints[] = round($x, 2) . ',' . round($y, 2);
        }
        $chartPointsString = implode(' ', $chartPoints);

        $peakMonth = collect($monthlyTrend)->sortByDesc('count')->first();
        $peakMonthLabel = ($total > 0 && $peakMonth && $peakMonth['count'] > 0)
            ? "{$peakMonth['name']} ({$peakMonth['count']} berkas)"
            : "-";

        return view('camat.dashboard', [
            'stats' => $stats,
            'services' => $services,
            'chartMax' => $chartMax,
            'monthlyTrend' => $monthlyTrend,
            'monthlyMax' => $monthlyMax,
            'chartPoints' => $chartPointsString,
            'statusPelayanan' => $statusPelayanan,
            'perhatian' => $perhatian,
            'pelayananTerbaru' => $pelayananTerbaru,
            'year' => $year,
            'currentMonthName' => ucfirst($currentMonthName),
            'highestServiceLabel' => $highestServiceLabel,
            'peakMonthLabel' => $peakMonthLabel,
            'totalPelayanan' => $total,
        ]);
    }

    /**
     * Halaman Monitoring Pelayanan untuk Camat
     */
    public function monitoring(Request $request)
    {
        $tahun = (string)$request->input('tahun', '2026');
        $periode = (string)$request->input('periode', 'semua');
        $selectedLayanan = (string)$request->input('jenis_layanan', 'semua');
        $status = (string)$request->input('status', 'semua');

        $allRecords = Pendaftaran::latest('tanggal_daftar')->latest('id')->get();
        $allServicesCatalog = $this->getServicesCatalog();

        $filtered = $allRecords;

        // 1. Filter Tahun
        if ($tahun !== 'semua' && !empty($tahun)) {
            $filtered = $filtered->filter(function ($item) use ($tahun) {
                if (blank($item->tanggal_daftar)) {
                    return false;
                }
                try {
                    return (string)Carbon::parse($item->tanggal_daftar)->year === (string)$tahun;
                } catch (\Exception $e) {
                    return false;
                }
            });
        }

        // 2. Filter Periode
        if ($periode === 'bulan_ini') {
            $currentMonth = (int)now()->month;
            $filtered = $filtered->filter(function ($item) use ($currentMonth) {
                try {
                    return (int)Carbon::parse($item->tanggal_daftar)->month === $currentMonth;
                } catch (\Exception $e) {
                    return false;
                }
            });
        } elseif ($periode === 'triwulan_1') {
            $filtered = $filtered->filter(function ($item) {
                try {
                    return in_array((int)Carbon::parse($item->tanggal_daftar)->month, [1, 2, 3]);
                } catch (\Exception $e) {
                    return false;
                }
            });
        } elseif ($periode === 'triwulan_2') {
            $filtered = $filtered->filter(function ($item) {
                try {
                    return in_array((int)Carbon::parse($item->tanggal_daftar)->month, [4, 5, 6]);
                } catch (\Exception $e) {
                    return false;
                }
            });
        } elseif ($periode === 'triwulan_3') {
            $filtered = $filtered->filter(function ($item) {
                try {
                    return in_array((int)Carbon::parse($item->tanggal_daftar)->month, [7, 8, 9]);
                } catch (\Exception $e) {
                    return false;
                }
            });
        } elseif ($periode === 'triwulan_4') {
            $filtered = $filtered->filter(function ($item) {
                try {
                    return in_array((int)Carbon::parse($item->tanggal_daftar)->month, [10, 11, 12]);
                } catch (\Exception $e) {
                    return false;
                }
            });
        }

        // 3. Filter Status (Selesai vs Dalam Proses)
        if ($status !== 'semua' && !empty($status)) {
            $normalizedStatus = strtolower(trim($status));
            if ($normalizedStatus === 'selesai') {
                $filtered = $filtered->filter(function ($item) {
                    return strtolower(trim($item->status ?? '')) === 'selesai';
                });
            } elseif (in_array($normalizedStatus, ['dalam_proses', 'dalam proses', 'proses'])) {
                $filtered = $filtered->filter(function ($item) {
                    $st = strtolower(trim($item->status ?? ''));
                    return in_array($st, ['proses', 'dalam proses', 'baru', 'menunggu']) ||
                           (!in_array($st, ['selesai', 'ditolak']) && !empty($st));
                });
            }
        }

        // Rekapitulasi per layanan berdasarkan data pendaftaran yang telah difilter
        $services = $this->getServicesCatalog($filtered);

        // 4. Filter Jenis Pelayanan
        if ($selectedLayanan !== 'semua' && !empty($selectedLayanan)) {
            $services = array_values(array_filter($services, function ($item) use ($selectedLayanan) {
                return stripos($item['key'], $selectedLayanan) !== false ||
                       stripos($item['short'], $selectedLayanan) !== false ||
                       stripos($item['label'], $selectedLayanan) !== false;
            }));
        }

        $totalPermohonan = array_sum(array_column($services, 'count'));
        $totalSelesai = array_sum(array_column($services, 'selesai'));
        $totalProses = array_sum(array_column($services, 'proses'));
        $totalMenunggu = array_sum(array_column($services, 'menunggu'));

        return view('camat.monitoring', [
            'services' => $services,
            'allServices' => $allServicesCatalog,
            'tahun' => $tahun,
            'year' => $tahun,
            'periode' => $periode,
            'selectedLayanan' => $selectedLayanan,
            'status' => $status,
            'totalPermohonan' => $totalPermohonan,
            'totalSelesai' => $totalSelesai,
            'totalProses' => $totalProses,
            'totalMenunggu' => $totalMenunggu,
        ]);
    }

    /**
     * Halaman Statistik Pelayanan
     */
    public function statistik(Request $request)
    {
        $pendaftarans = Pendaftaran::all();
        $tahunInput = (string)$request->input('tahun', (string)now()->year);
        $year = is_numeric($tahunInput) ? (int)$tahunInput : now()->year;
        $periode = (string)$request->input('periode', (string)$year);

        $services = $this->getServicesCatalog($pendaftarans);
        $monthlyTrend = $this->getMonthlyTrend($pendaftarans, $year);

        $countsArray = array_column($services, 'count');
        $chartMax = max(1, !empty($countsArray) ? max($countsArray) : 0);
        $monthlyCounts = array_column($monthlyTrend, 'count');
        $monthlyMax = max(1, !empty($monthlyCounts) ? max($monthlyCounts) : 0);

        $chartPoints = [];
        foreach ($monthlyCounts as $index => $count) {
            $x = 24 + ($index * 432 / 11);
            $y = 128 - ($count / $monthlyMax * 100);
            $chartPoints[] = round($x, 2) . ',' . round($y, 2);
        }
        $chartPointsString = implode(' ', $chartPoints);

        $totalPelayanan = $pendaftarans->count();
        $selesaiCount = $pendaftarans->filter(fn($i) => strtolower($i->status ?? '') === 'selesai')->count();
        $persenSelesai = $totalPelayanan > 0 ? round(($selesaiCount / $totalPelayanan) * 100, 1) : 0;
        $rataBulanan = round($totalPelayanan / 12, 1);

        $highestService = collect($services)->sortByDesc('count')->first();
        $highestServiceTitle = ($totalPelayanan > 0 && $highestService && $highestService['count'] > 0) ? $highestService['label'] : '-';
        $highestServiceCount = ($totalPelayanan > 0 && $highestService && $highestService['count'] > 0) ? "{$highestService['count']} Berkas Permohonan" : "0 Berkas";

        $peakMonth = collect($monthlyTrend)->sortByDesc('count')->first();
        $peakMonthLabel = ($totalPelayanan > 0 && $peakMonth && $peakMonth['count'] > 0) ? "{$peakMonth['name']} ({$peakMonth['count']})" : "-";

        // Filter data untuk Distribusi Status berdasarkan Tahun & Periode
        $filtered = $pendaftarans;
        if ($tahunInput !== 'semua' && !empty($tahunInput)) {
            $filtered = $filtered->filter(function ($item) use ($year) {
                $date = $item->tanggal_daftar ?? $item->created_at;
                if (blank($date)) {
                    return false;
                }
                try {
                    return (int)Carbon::parse($date)->year === $year;
                } catch (\Exception $e) {
                    return false;
                }
            });
        }

        if ($periode === 'bulan_ini') {
            $currentMonth = (int)now()->month;
            $filtered = $filtered->filter(function ($item) use ($currentMonth) {
                $date = $item->tanggal_daftar ?? $item->created_at;
                if (blank($date)) {
                    return false;
                }
                try {
                    return (int)Carbon::parse($date)->month === $currentMonth;
                } catch (\Exception $e) {
                    return false;
                }
            });
        } elseif (in_array($periode, ['tw1', 'triwulan_1'])) {
            $filtered = $filtered->filter(function ($item) {
                $date = $item->tanggal_daftar ?? $item->created_at;
                if (blank($date)) {
                    return false;
                }
                try {
                    return in_array((int)Carbon::parse($date)->month, [1, 2, 3]);
                } catch (\Exception $e) {
                    return false;
                }
            });
        } elseif (in_array($periode, ['tw2', 'triwulan_2'])) {
            $filtered = $filtered->filter(function ($item) {
                $date = $item->tanggal_daftar ?? $item->created_at;
                if (blank($date)) {
                    return false;
                }
                try {
                    return in_array((int)Carbon::parse($date)->month, [4, 5, 6]);
                } catch (\Exception $e) {
                    return false;
                }
            });
        } elseif (in_array($periode, ['tw3', 'triwulan_3'])) {
            $filtered = $filtered->filter(function ($item) {
                $date = $item->tanggal_daftar ?? $item->created_at;
                if (blank($date)) {
                    return false;
                }
                try {
                    return in_array((int)Carbon::parse($date)->month, [7, 8, 9]);
                } catch (\Exception $e) {
                    return false;
                }
            });
        } elseif (in_array($periode, ['tw4', 'triwulan_4'])) {
            $filtered = $filtered->filter(function ($item) {
                $date = $item->tanggal_daftar ?? $item->created_at;
                if (blank($date)) {
                    return false;
                }
                try {
                    return in_array((int)Carbon::parse($date)->month, [10, 11, 12]);
                } catch (\Exception $e) {
                    return false;
                }
            });
        }

        // Agregasi 4 status pelayanan
        $countSelesai = 0;
        $countProses = 0;
        $countMenunggu = 0;
        $countRevisi = 0;

        foreach ($filtered as $item) {
            $st = strtolower(trim($item->status ?? ''));
            if ($st === 'selesai') {
                $countSelesai++;
            } elseif (in_array($st, ['proses', 'dalam proses', 'dalam_proses', 'diproses', 'sedang diproses'])) {
                $countProses++;
            } elseif (in_array($st, ['ditolak', 'perlu revisi', 'perlu_revisi', 'revisi', 'tolak'])) {
                $countRevisi++;
            } else {
                // Menunggu / Baru / Antrean Verifikasi
                $countMenunggu++;
            }
        }

        $totalDistribusi = $countSelesai + $countProses + $countMenunggu + $countRevisi;

        $pctSelesai = $totalDistribusi > 0 ? round(($countSelesai / $totalDistribusi) * 100, 1) : 0;
        $pctProses = $totalDistribusi > 0 ? round(($countProses / $totalDistribusi) * 100, 1) : 0;
        $pctMenunggu = $totalDistribusi > 0 ? round(($countMenunggu / $totalDistribusi) * 100, 1) : 0;
        $pctRevisi = $totalDistribusi > 0 ? round(($countRevisi / $totalDistribusi) * 100, 1) : 0;

        $circ = 502.65; // Keliling lingkaran donat (2 * pi * 80)
        $runningOffset = 0;

        $rawItems = [
            'selesai' => [
                'key' => 'selesai',
                'label' => 'Selesai',
                'count' => $countSelesai,
                'percentage' => $pctSelesai,
                'color' => '#16a34a',
                'desc' => 'Berkas tuntas diproses',
            ],
            'proses' => [
                'key' => 'proses',
                'label' => 'Dalam Proses',
                'count' => $countProses,
                'percentage' => $pctProses,
                'color' => '#ea580c',
                'desc' => 'Pengerjaan operator',
            ],
            'menunggu' => [
                'key' => 'menunggu',
                'label' => 'Menunggu',
                'count' => $countMenunggu,
                'percentage' => $pctMenunggu,
                'color' => '#3b82f6',
                'desc' => 'Antrean verifikasi',
            ],
            'perlu_revisi' => [
                'key' => 'perlu_revisi',
                'label' => 'Perlu Revisi',
                'count' => $countRevisi,
                'percentage' => $pctRevisi,
                'color' => '#ef4444',
                'desc' => 'Perbaikan dokumen',
            ],
        ];

        $donutSegments = [];
        foreach ($rawItems as $key => $item) {
            $arcLen = $totalDistribusi > 0 ? round(($item['count'] / $totalDistribusi) * $circ, 2) : 0;
            $donutSegments[$key] = array_merge($item, [
                'count_formatted' => number_format($item['count'], 0, ',', '.'),
                'arc_length' => $arcLen,
                'dash_offset' => round($runningOffset, 2),
            ]);
            if ($item['count'] > 0) {
                $runningOffset -= $arcLen;
            }
        }

        $distribusiStatus = [
            'total' => $totalDistribusi,
            'total_formatted' => number_format($totalDistribusi, 0, ',', '.'),
            'circumference' => $circ,
            'items' => $donutSegments,
        ];

        $statusPelayanan = [
            'selesai' => ['count' => $countSelesai, 'percentage' => $pctSelesai],
            'proses' => ['count' => $countProses, 'percentage' => $pctProses],
            'menunggu' => ['count' => $countMenunggu, 'percentage' => $pctMenunggu],
            'perlu_revisi' => ['count' => $countRevisi, 'percentage' => $pctRevisi],
        ];

        // Daftar 14 Desa Resmi Kecamatan Jatisari, Kabupaten Karawang
        $daftarDesaJatisari = [
            'Balonggandu',
            'Barugbug',
            'Cikalongsari',
            'Cirejag',
            'Jatibaru',
            'Jatiragas',
            'Jatisari',
            'Jatiwangi',
            'Kalijati',
            'Mekarsari',
            'Pacing',
            'Situdam',
            'Sukamekar',
            'Telarsari',
        ];

        // Inisialisasi seluruh 14 desa dengan jumlah 0
        $desaCounts = [];
        foreach ($daftarDesaJatisari as $namaDesa) {
            $desaCounts[$namaDesa] = 0;
        }

        // Hitung agregasi pelayanan per desa berdasarkan data pendaftaran pemohon
        foreach ($filtered as $item) {
            $itemDesa = trim((string)($item->desa ?? ''));
            if ($itemDesa !== '') {
                foreach ($daftarDesaJatisari as $namaDesa) {
                    if (strcasecmp($itemDesa, $namaDesa) === 0) {
                        $desaCounts[$namaDesa]++;
                        break;
                    }
                }
            }
        }

        $totalDesaPelayanan = array_sum($desaCounts);
        $maxDesaCount = !empty($desaCounts) ? max($desaCounts) : 0;

        $desaStats = [];
        $orderIndex = 0;
        foreach ($daftarDesaJatisari as $namaDesa) {
            $count = $desaCounts[$namaDesa];
            $desaStats[] = [
                'nama' => $namaDesa,
                'desa' => $namaDesa,
                'jumlah' => $count,
                'jumlah_formatted' => number_format($count, 0, ',', '.'),
                'persentase' => $totalDesaPelayanan > 0 ? round(($count / $totalDesaPelayanan) * 100, 1) : 0,
                'bar_width' => $maxDesaCount > 0 ? max(2, round(($count / $maxDesaCount) * 100, 1)) : 0,
                'master_order' => $orderIndex++,
            ];
        }

        // Pengurutan: Jika sudah ada data pelayanan, urutkan dari terbanyak ke tersedikit (descending).
        // Jika seluruh desa bernilai 0, pertahankan urutan master desa.
        if ($maxDesaCount > 0) {
            usort($desaStats, function ($a, $b) {
                if ($b['jumlah'] !== $a['jumlah']) {
                    return $b['jumlah'] <=> $a['jumlah'];
                }
                return $a['master_order'] <=> $b['master_order'];
            });
        }

        // Skala sumbu X horizontal otomatis berdasarkan data aktual pelayanan tertinggi
        $ticksInfo = $this->calculateNiceDesaTicks($maxDesaCount);
        $desaChartMax = $ticksInfo['max'];
        $desaChartTicks = $ticksInfo['ticks'];

        // Hitung persentase lebar batang terhadap skala sumbu X
        foreach ($desaStats as &$d) {
            $d['bar_width'] = ($d['jumlah'] > 0 && $desaChartMax > 0)
                ? round(($d['jumlah'] / $desaChartMax) * 100, 2)
                : 0;
        }
        unset($d);

        // Dukungan respons JSON untuk sinkronisasi polling otomatis real-time akun Camat
        if ($request->expectsJson() || $request->ajax() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'timestamp' => now()->toIso8601String(),
                'totalDesaPelayanan' => $totalDesaPelayanan,
                'totalDesaPelayananFormatted' => number_format($totalDesaPelayanan, 0, ',', '.'),
                'maxDesaCount' => $maxDesaCount,
                'desaChartMax' => $desaChartMax,
                'desaChartTicks' => $desaChartTicks,
                'desaStats' => $desaStats,
                'distribusiStatus' => $distribusiStatus,
                'statusPelayanan' => $statusPelayanan,
            ]);
        }

        return view('camat.statistik', [
            'services' => $services,
            'monthlyTrend' => $monthlyTrend,
            'chartMax' => $chartMax,
            'monthlyMax' => $monthlyMax,
            'chartPoints' => $chartPointsString,
            'desaStats' => $desaStats,
            'totalDesaPelayanan' => $totalDesaPelayanan,
            'maxDesaCount' => $maxDesaCount,
            'desaChartMax' => $desaChartMax,
            'desaChartTicks' => $desaChartTicks,
            'daftarDesaJatisari' => $daftarDesaJatisari,
            'periode' => $periode,
            'year' => $year,
            'tahun' => (string)$year,
            'totalPelayanan' => $totalPelayanan,
            'rataBulanan' => $rataBulanan,
            'persenSelesai' => $persenSelesai,
            'highestServiceTitle' => $highestServiceTitle,
            'highestServiceCount' => $highestServiceCount,
            'peakMonthLabel' => $peakMonthLabel,
            'statusPelayanan' => $statusPelayanan,
            'distribusiStatus' => $distribusiStatus,
        ]);
    }

    /**
     * Hitung skala sumbu X horizontal yang proporsional, rapi, dan mudah dibaca.
     * - Jika data masih 0, gunakan skala cadangan: 0, 2, 4, 6, 8, 10.
     * - Jika data > 0, skala menyesuaikan nilai tertinggi secara otomatis.
     *   Contoh jika nilai tertinggi 238 -> 0, 60, 120, 180, 240.
     */
    protected function calculateNiceDesaTicks(int $max): array
    {
        if ($max <= 0) {
            return [
                'max' => 10,
                'ticks' => [0, 2, 4, 6, 8, 10],
            ];
        }

        if ($max <= 4) {
            return [
                'max' => $max,
                'ticks' => range(0, $max),
            ];
        }

        $targetIntervals = 4;
        $roughStep = $max / $targetIntervals;
        $magnitude = pow(10, floor(log10($roughStep)));
        $fraction = $roughStep / $magnitude;

        if ($magnitude >= 10) {
            $niceFractions = [1, 2, 2.5, 4, 5, 6, 8, 10];
        } else {
            $niceFractions = [1, 2, 4, 5, 6, 8, 10];
        }

        $selectedStep = 10 * $magnitude;
        foreach ($niceFractions as $nf) {
            $candidate = $nf * $magnitude;
            if ($candidate >= $roughStep) {
                $selectedStep = $candidate;
                break;
            }
        }

        $step = (int)ceil($selectedStep);
        if ($max === 10) {
            $step = 2;
        }

        $maxTick = (int)ceil($max / $step) * $step;
        $ticks = range(0, $maxTick, $step);

        return [
            'max' => $maxTick,
            'ticks' => $ticks,
        ];
    }

    /**
     * Helper untuk mengambil data rekapitulasi laporan terfilter
     */
    protected function getLaporanReportData(Request $request): array
    {
        Carbon::setLocale('id');

        $allRecords = Pendaftaran::latest('tanggal_daftar')->latest('id')->get();
        $allServicesCatalog = $this->getServicesCatalog();

        $tahun = (string)$request->input('tahun', (string)now()->year);
        $bulan = (string)$request->input('bulan', 'semua');
        $jenisLayanan = (string)$request->input('jenis_layanan', 'semua');
        $status = (string)$request->input('status', 'semua');
        $kertas = strtoupper((string)$request->input('kertas', 'A4'));
        $orientasi = strtolower((string)$request->input('orientasi', 'portrait'));

        $filtered = $allRecords;

        // 1. Filter Tahun
        if ($tahun !== 'semua' && !empty($tahun)) {
            $filtered = $filtered->filter(function ($item) use ($tahun) {
                if (blank($item->tanggal_daftar)) {
                    return false;
                }
                try {
                    return (string)Carbon::parse($item->tanggal_daftar)->year === $tahun;
                } catch (\Exception $e) {
                    return false;
                }
            });
        }

        // 2. Filter Bulan
        if ($bulan !== 'semua' && !empty($bulan)) {
            $monthMap = [
                'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
                'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
                'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
            ];
            $targetMonth = $monthMap[strtolower($bulan)] ?? (is_numeric($bulan) ? (int)$bulan : null);

            if ($targetMonth) {
                $filtered = $filtered->filter(function ($item) use ($targetMonth, $bulan) {
                    $matchDate = false;
                    if (!blank($item->tanggal_daftar)) {
                        try {
                            $matchDate = (int)Carbon::parse($item->tanggal_daftar)->month === $targetMonth;
                        } catch (\Exception $e) {
                            $matchDate = false;
                        }
                    }
                    $matchBulanCol = (!empty($item->bulan) && strtolower(trim($item->bulan)) === strtolower($bulan));
                    return $matchDate || $matchBulanCol;
                });
            }
        }

        // 3. Filter Status (Selesai vs Dalam Proses)
        if ($status !== 'semua' && !empty($status)) {
            $normalizedStatus = strtolower(trim($status));
            if ($normalizedStatus === 'selesai') {
                $filtered = $filtered->filter(function ($item) {
                    return strtolower(trim($item->status ?? '')) === 'selesai';
                });
            } elseif (in_array($normalizedStatus, ['dalam_proses', 'dalam proses', 'proses'])) {
                $filtered = $filtered->filter(function ($item) {
                    $st = strtolower(trim($item->status ?? ''));
                    return in_array($st, ['proses', 'dalam proses', 'baru', 'menunggu']) ||
                           (!in_array($st, ['selesai', 'ditolak']) && !empty($st));
                });
            }
        }

        // Rekapitulasi per layanan berdasarkan data pendaftaran yang telah difilter
        $services = $this->getServicesCatalog($filtered);

        // 4. Filter Jenis Pelayanan
        if ($jenisLayanan !== 'semua' && !empty($jenisLayanan)) {
            $services = array_values(array_filter($services, function ($item) use ($jenisLayanan) {
                return stripos($item['key'], $jenisLayanan) !== false ||
                       stripos($item['short'], $jenisLayanan) !== false ||
                       stripos($item['label'], $jenisLayanan) !== false;
            }));
        }

        $total = array_sum(array_column($services, 'count'));
        $totalSelesai = array_sum(array_column($services, 'selesai'));
        $totalProses = array_sum(array_column($services, 'proses'));

        $selectedLayananLabel = 'Semua Jenis Pelayanan';
        if ($jenisLayanan !== 'semua') {
            foreach ($allServicesCatalog as $s) {
                if ($s['key'] === $jenisLayanan || stripos($s['label'], $jenisLayanan) !== false) {
                    $selectedLayananLabel = $s['label'];
                    break;
                }
            }
        }

        $tanggalCetak = Carbon::now()->locale('id')->translatedFormat('d F Y');

        return [
            'services' => $services,
            'allServices' => $allServicesCatalog,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'jenisLayanan' => $jenisLayanan,
            'status' => $status,
            'selectedLayananLabel' => $selectedLayananLabel,
            'total' => $total,
            'totalSelesai' => $totalSelesai,
            'totalProses' => $totalProses,
            'tanggalCetak' => $tanggalCetak,
            'kertas' => $kertas,
            'orientasi' => $orientasi,
        ];
    }

    /**
     * Halaman Laporan Pelayanan untuk Camat
     */
    public function laporan(Request $request)
    {
        $data = $this->getLaporanReportData($request);

        return view('camat.laporan', $data);
    }

    /**
     * Halaman Cetak & Export PDF Laporan Pelayanan untuk Camat
     */
    public function cetak(Request $request)
    {
        $data = $this->getLaporanReportData($request);

        // Jika dipanggil via export PDF dan class Barryvdh\DomPDF terinstall
        if (($request->input('export') === 'pdf' || $request->boolean('pdf')) && class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $logoPath = public_path('images/logo-karawang.png');
            $logoBase64 = file_exists($logoPath)
                ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                : '';
            $data['logoBase64'] = $logoBase64;
            $data['isPdf'] = true;

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('camat.laporan-cetak', $data);

            if ($data['kertas'] === 'F4') {
                $paperSize = [0, 0, 609.45, 935.43]; // 215mm x 330mm
                $pdf->setPaper($paperSize, $data['orientasi']);
            } else {
                $pdf->setPaper(strtolower($data['kertas']), $data['orientasi']);
            }

            $filename = 'Laporan-Pelayanan-PATEN-Jatisari-' . $data['tahun'] . '.pdf';
            return $pdf->stream($filename);
        }

        return view('camat.laporan-cetak', $data);
    }

    /**
     * Halaman Aktivitas Pelayanan oleh Operator
     */
    public function aktivitas(Request $request)
    {
        $pendaftarans = Pendaftaran::latest()->get();
        $allLogs = [];
        $id = 1;

        foreach ($pendaftarans as $p) {
            $allLogs[] = [
                'id' => $id++,
                'operator' => !empty($p->petugas) ? $p->petugas : 'Petugas Operator',
                'aktivitas' => "Pendaftaran " . ($p->jenis_layanan ?? 'Pelayanan'),
                'jenis' => $p->jenis_layanan ?? 'Pelayanan',
                'waktu' => $p->created_at ? $p->created_at->format('H:i') : ($p->tanggal_daftar ? Carbon::parse($p->tanggal_daftar)->format('H:i') : '—'),
                'status' => 'Berhasil',
            ];
        }

        $filterOperator = $request->input('operator', 'semua');
        $filteredLogs = $allLogs;

        if ($filterOperator !== 'semua' && !empty($filterOperator)) {
            $filteredLogs = array_filter($filteredLogs, function ($item) use ($filterOperator) {
                return stripos($item['operator'], $filterOperator) !== false;
            });
        }

        $operatorsCount = $pendaftarans->pluck('petugas')->filter()->unique()->count();

        return view('camat.aktivitas', [
            'logs' => $filteredLogs,
            'filterOperator' => $filterOperator,
            'totalAktivitas' => count($allLogs),
            'operatorAktifCount' => max($operatorsCount, $pendaftarans->count() > 0 ? 1 : 0),
        ]);
    }

    /**
     * Halaman Notifikasi Camat
     */
    public function notifikasi()
    {
        $pendaftarans = Pendaftaran::all();
        $dalamProses = $pendaftarans->filter(fn($i) => strtolower($i->status ?? '') !== 'selesai')->count();
        $total = $pendaftarans->count();

        $notifications = [];
        $id = 1;

        if ($dalamProses > 0) {
            $notifications[] = [
                'id' => $id++,
                'judul' => "{$dalamProses} pelayanan masih dalam proses",
                'pesan' => "Sebanyak {$dalamProses} berkas sedang dalam proses pengerjaan oleh petugas operator kecamatan.",
                'kategori' => 'Proses',
                'badge_class' => 'info',
                'waktu' => 'Terbaru',
                'dibaca' => false,
                'link' => route('camat.monitoring'),
            ];
        }

        if ($total > 0) {
            $notifications[] = [
                'id' => $id++,
                'judul' => "Laporan Pelayanan Tersedia",
                'pesan' => "Rekapitulasi laporan pelayanan administrasi telah diperbarui sesuai data pendaftaran.",
                'kategori' => 'Laporan',
                'badge_class' => 'success',
                'waktu' => 'Terbaru',
                'dibaca' => false,
                'link' => route('camat.laporan'),
            ];
        }

        return view('camat.notifikasi', [
            'notifications' => $notifications,
            'unreadCount' => count(array_filter($notifications, fn ($n) => !$n['dibaca'])),
        ]);
    }

    /**
     * Halaman Profil Camat Jatisari
     */
    public function profil()
    {
        $profile = [
            'nama' => 'Camat Jatisari',
            'inisial' => 'CJ',
            'jabatan' => 'Pimpinan / Camat',
            'kecamatan' => 'Jatisari',
            'kabupaten' => 'Karawang',
            'instansi' => 'Pemerintah Kabupaten Karawang',
            'role' => 'Pimpinan / Camat (Monitoring & Pengawasan)',
            'nip' => '19740512 199803 1 004',
            'email' => 'camat.jatisari@karawangkab.go.id',
            'telepon' => '(0267) 861234',
            'alamat_kantor' => 'Jl. Raya Jatisari No. 1, Jatisari, Kec. Jatisari, Kabupaten Karawang, Jawa Barat 41374',
        ];

        return view('camat.profil', [
            'profile' => $profile,
        ]);
    }
}
