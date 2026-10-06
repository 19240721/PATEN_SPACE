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
        $pendaftarans = Pendaftaran::latest('tanggal_daftar')->get();
        $allServices = $this->getServicesCatalog($pendaftarans);
        $periode = $request->input('periode', 'semua');
        $selectedLayanan = $request->input('jenis_layanan', 'semua');

        $filteredServices = $allServices;

        if ($selectedLayanan !== 'semua' && !empty($selectedLayanan)) {
            $filteredServices = array_filter($filteredServices, function ($item) use ($selectedLayanan) {
                return stripos($item['key'], $selectedLayanan) !== false ||
                       stripos($item['short'], $selectedLayanan) !== false ||
                       stripos($item['label'], $selectedLayanan) !== false;
            });
        }

        $totalPermohonan = array_sum(array_column($filteredServices, 'count'));
        $totalSelesai = array_sum(array_column($filteredServices, 'selesai'));
        $totalProses = array_sum(array_column($filteredServices, 'proses'));
        $totalMenunggu = array_sum(array_column($filteredServices, 'menunggu'));

        return view('camat.monitoring', [
            'services' => $filteredServices,
            'allServices' => $allServices,
            'periode' => $periode,
            'selectedLayanan' => $selectedLayanan,
            'totalPermohonan' => $totalPermohonan,
            'totalSelesai' => $totalSelesai,
            'totalProses' => $totalProses,
            'totalMenunggu' => $totalMenunggu,
            'year' => now()->year,
        ]);
    }

    /**
     * Halaman Statistik Pelayanan
     */
    public function statistik(Request $request)
    {
        $pendaftarans = Pendaftaran::all();
        $services = $this->getServicesCatalog($pendaftarans);
        $year = now()->year;
        $monthlyTrend = $this->getMonthlyTrend($pendaftarans, $year);
        $periode = $request->input('periode', (string)$year);

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

        // Data sebaran per desa di Kecamatan Jatisari
        $desaGrouped = $pendaftarans->groupBy(fn($i) => !empty($i->desa) ? trim($i->desa) : 'Lainnya');
        $desaStats = [];
        foreach ($desaGrouped as $namaDesa => $items) {
            $desaStats[] = [
                'desa' => $namaDesa,
                'jumlah' => $items->count(),
                'selesai' => $items->filter(fn($i) => strtolower($i->status ?? '') === 'selesai')->count(),
            ];
        }

        $totalPelayanan = $pendaftarans->count();
        $selesaiCount = $pendaftarans->filter(fn($i) => strtolower($i->status ?? '') === 'selesai')->count();
        $persenSelesai = $totalPelayanan > 0 ? round(($selesaiCount / $totalPelayanan) * 100, 1) : 0;
        $rataBulanan = round($totalPelayanan / 12, 1);

        $highestService = collect($services)->sortByDesc('count')->first();
        $highestServiceTitle = ($totalPelayanan > 0 && $highestService && $highestService['count'] > 0) ? $highestService['label'] : '-';
        $highestServiceCount = ($totalPelayanan > 0 && $highestService && $highestService['count'] > 0) ? "{$highestService['count']} Berkas Permohonan" : "0 Berkas";

        $peakMonth = collect($monthlyTrend)->sortByDesc('count')->first();
        $peakMonthLabel = ($totalPelayanan > 0 && $peakMonth && $peakMonth['count'] > 0) ? "{$peakMonth['name']} ({$peakMonth['count']})" : "-";

        $dalamProses = $totalPelayanan - $selesaiCount;
        $statusPelayanan = [
            'selesai' => ['count' => $selesaiCount, 'percentage' => $persenSelesai],
            'proses' => ['count' => $dalamProses, 'percentage' => $totalPelayanan > 0 ? round(($dalamProses / $totalPelayanan) * 100, 1) : 0],
            'menunggu' => ['count' => 0, 'percentage' => 0],
            'perlu_revisi' => ['count' => 0, 'percentage' => 0],
        ];

        return view('camat.statistik', [
            'services' => $services,
            'monthlyTrend' => $monthlyTrend,
            'chartMax' => $chartMax,
            'monthlyMax' => $monthlyMax,
            'chartPoints' => $chartPointsString,
            'desaStats' => $desaStats,
            'periode' => $periode,
            'year' => $year,
            'totalPelayanan' => $totalPelayanan,
            'rataBulanan' => $rataBulanan,
            'persenSelesai' => $persenSelesai,
            'highestServiceTitle' => $highestServiceTitle,
            'highestServiceCount' => $highestServiceCount,
            'peakMonthLabel' => $peakMonthLabel,
            'statusPelayanan' => $statusPelayanan,
        ]);
    }

    /**
     * Halaman Laporan Pelayanan untuk Camat
     */
    public function laporan(Request $request)
    {
        $pendaftarans = Pendaftaran::latest('tanggal_daftar')->get();
        $allServices = $this->getServicesCatalog($pendaftarans);
        $tahun = $request->input('tahun', (string)now()->year);
        $bulan = $request->input('bulan', 'semua');
        $jenisLayanan = $request->input('jenis_layanan', 'semua');

        $filteredServices = $allServices;
        if ($jenisLayanan !== 'semua' && !empty($jenisLayanan)) {
            $filteredServices = array_filter($filteredServices, function ($item) use ($jenisLayanan) {
                return stripos($item['key'], $jenisLayanan) !== false ||
                       stripos($item['short'], $jenisLayanan) !== false ||
                       stripos($item['label'], $jenisLayanan) !== false;
            });
        }

        $total = array_sum(array_column($filteredServices, 'count'));
        $totalSelesai = array_sum(array_column($filteredServices, 'selesai'));
        $totalProses = array_sum(array_column($filteredServices, 'proses'));

        return view('camat.laporan', [
            'services' => $filteredServices,
            'allServices' => $allServices,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'jenisLayanan' => $jenisLayanan,
            'total' => $total,
            'totalSelesai' => $totalSelesai,
            'totalProses' => $totalProses,
            'tanggalCetak' => now()->format('d/m/Y'),
        ]);
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
