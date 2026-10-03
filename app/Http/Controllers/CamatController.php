<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CamatController extends Controller
{
    /**
     * Data dasar pelayanan Kecamatan Jatisari untuk Dashboard Camat
     */
    private function getServicesCatalog(): array
    {
        return [
            ['key' => 'prr', 'label' => 'PRR / KTP Baru', 'short' => 'PRR', 'count' => 215, 'selesai' => 208, 'proses' => 7, 'menunggu' => 0, 'icon' => '▤', 'color' => '#0870c9'],
            ['key' => 'ktp', 'label' => 'KTP', 'short' => 'KTP', 'count' => 182, 'selesai' => 175, 'proses' => 7, 'menunggu' => 0, 'icon' => '▣', 'color' => '#2463eb'],
            ['key' => 'kk', 'label' => 'Kartu Keluarga', 'short' => 'KK', 'count' => 276, 'selesai' => 268, 'proses' => 8, 'menunggu' => 0, 'icon' => '♧', 'color' => '#168047'],
            ['key' => 'akta_lahir', 'label' => 'Akta Kelahiran', 'short' => 'Akta Lahir', 'count' => 198, 'selesai' => 190, 'proses' => 8, 'menunggu' => 0, 'icon' => '✳', 'color' => '#c57d14'],
            ['key' => 'akta_mati', 'label' => 'Akta Kematian', 'short' => 'Akta Mati', 'count' => 87, 'selesai' => 82, 'proses' => 5, 'menunggu' => 0, 'icon' => '▧', 'color' => '#64748b'],
            ['key' => 'kedatangan', 'label' => 'Kedatangan', 'short' => 'Kedatangan', 'count' => 156, 'selesai' => 150, 'proses' => 6, 'menunggu' => 0, 'icon' => '⌂', 'color' => '#00a997'],
            ['key' => 'pindah', 'label' => 'Pindah', 'short' => 'Pindah', 'count' => 134, 'selesai' => 130, 'proses' => 4, 'menunggu' => 0, 'icon' => '→', 'color' => '#4f46e5'],
        ];
    }

    /**
     * Tren data bulanan pelayanan tahun 2026
     */
    private function getMonthlyTrend(): array
    {
        return [
            ['month' => 'Jan', 'name' => 'Januari', 'count' => 82],
            ['month' => 'Feb', 'name' => 'Februari', 'count' => 95],
            ['month' => 'Mar', 'name' => 'Maret', 'count' => 108],
            ['month' => 'Apr', 'name' => 'April', 'count' => 91],
            ['month' => 'Mei', 'name' => 'Mei', 'count' => 126],
            ['month' => 'Jun', 'name' => 'Juni', 'count' => 137],
            ['month' => 'Jul', 'name' => 'Juli', 'count' => 145],
            ['month' => 'Agu', 'name' => 'Agustus', 'count' => 151],
            ['month' => 'Sep', 'name' => 'September', 'count' => 186],
            ['month' => 'Okt', 'name' => 'Oktober', 'count' => 0],
            ['month' => 'Nov', 'name' => 'November', 'count' => 0],
            ['month' => 'Des', 'name' => 'Desember', 'count' => 0],
        ];
    }

    /**
     * Halaman Dashboard Utama Camat
     */
    public function dashboard()
    {
        $services = $this->getServicesCatalog();
        $monthlyTrend = $this->getMonthlyTrend();

        // Ringkasan Card Utama
        $stats = [
            'total' => 1248,
            'bulan_ini' => 186,
            'selesai' => 1210,
            'dalam_proses' => 38,
            'perlu_perhatian' => 12,
        ];

        // Status Pelayanan
        $statusPelayanan = [
            'selesai' => ['label' => 'Selesai', 'count' => 1210, 'percentage' => 96.9, 'class' => 'selesai'],
            'proses' => ['label' => 'Dalam Proses', 'count' => 38, 'percentage' => 3.0, 'class' => 'proses'],
            'menunggu' => ['label' => 'Menunggu Verifikasi', 'count' => 17, 'percentage' => 1.4, 'class' => 'menunggu'],
            'perlu_revisi' => ['label' => 'Perlu Revisi Dokumen', 'count' => 12, 'percentage' => 1.0, 'class' => 'revisi'],
        ];

        // Catatan Perlu Perhatian
        $perhatian = [
            [
                'id' => 1,
                'pesan' => '12 berkas permohonan masih memerlukan revisi dokumen warga',
                'level' => 'warning',
                'badge' => 'Perlu Revisi',
                'action_label' => 'Lihat Data',
                'action_url' => route('camat.monitoring'),
            ],
            [
                'id' => 2,
                'pesan' => '38 pelayanan administrasi saat ini masih dalam proses pengerjaan operator',
                'level' => 'info',
                'badge' => 'Dalam Proses',
                'action_label' => 'Pantau Progres',
                'action_url' => route('camat.monitoring'),
            ],
            [
                'id' => 3,
                'pesan' => '5 pengaduan warga pada meja pelayanan belum ditindaklanjuti',
                'level' => 'danger',
                'badge' => 'Pengaduan',
                'action_label' => 'Periksa Notifikasi',
                'action_url' => route('camat.notifikasi'),
            ],
        ];

        // Pelayanan Terbaru (Data Agregat - Tanpa data pribadi sensitif)
        $pelayananTerbaru = [
            ['no' => '01', 'tanggal' => '30 Sep 2026', 'jenis' => 'Kartu Keluarga', 'jumlah' => 18, 'status' => 'Selesai'],
            ['no' => '02', 'tanggal' => '30 Sep 2026', 'jenis' => 'KTP', 'jumlah' => 12, 'status' => 'Selesai'],
            ['no' => '03', 'tanggal' => '29 Sep 2026', 'jenis' => 'Akta Kelahiran', 'jumlah' => 9, 'status' => 'Dalam Proses'],
            ['no' => '04', 'tanggal' => '29 Sep 2026', 'jenis' => 'Pindah', 'jumlah' => 7, 'status' => 'Selesai'],
            ['no' => '05', 'tanggal' => '28 Sep 2026', 'jenis' => 'PRR / KTP Baru', 'jumlah' => 14, 'status' => 'Selesai'],
            ['no' => '06', 'tanggal' => '28 Sep 2026', 'jenis' => 'Kedatangan', 'jumlah' => 6, 'status' => 'Selesai'],
            ['no' => '07', 'tanggal' => '27 Sep 2026', 'jenis' => 'Akta Kematian', 'jumlah' => 3, 'status' => 'Selesai'],
            ['no' => '08', 'tanggal' => '26 Sep 2026', 'jenis' => 'Kartu Keluarga', 'jumlah' => 15, 'status' => 'Selesai'],
        ];

        // Kalkulasi untuk Grafik Batang Jenis Pelayanan
        $chartMax = max(1, max(array_column($services, 'count')));

        // Kalkulasi untuk Grafik Tren Bulanan (SVG Points)
        $monthlyCounts = array_column($monthlyTrend, 'count');
        $monthlyMax = max(1, max($monthlyCounts));
        $chartPoints = [];
        foreach ($monthlyCounts as $index => $count) {
            $x = 24 + ($index * 432 / 11);
            $y = 128 - ($count / $monthlyMax * 100);
            $chartPoints[] = round($x, 2) . ',' . round($y, 2);
        }
        $chartPointsString = implode(' ', $chartPoints);

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
            'year' => 2026,
        ]);
    }

    /**
     * Halaman Monitoring Pelayanan untuk Camat
     */
    public function monitoring(Request $request)
    {
        $allServices = $this->getServicesCatalog();
        $periode = $request->input('periode', 'semua');
        $selectedLayanan = $request->input('jenis_layanan', 'semua');

        $filteredServices = $allServices;

        if ($selectedLayanan !== 'semua' && !empty($selectedLayanan)) {
            $filteredServices = array_filter($filteredServices, function ($item) use ($selectedLayanan) {
                return $item['key'] === $selectedLayanan || $item['short'] === $selectedLayanan || $item['label'] === $selectedLayanan;
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
            'year' => 2026,
        ]);
    }

    /**
     * Halaman Statistik Pelayanan
     */
    public function statistik(Request $request)
    {
        $services = $this->getServicesCatalog();
        $monthlyTrend = $this->getMonthlyTrend();
        $periode = $request->input('periode', '2026');

        $chartMax = max(1, max(array_column($services, 'count')));
        $monthlyCounts = array_column($monthlyTrend, 'count');
        $monthlyMax = max(1, max($monthlyCounts));
        $chartPoints = [];
        foreach ($monthlyCounts as $index => $count) {
            $x = 24 + ($index * 432 / 11);
            $y = 128 - ($count / $monthlyMax * 100);
            $chartPoints[] = round($x, 2) . ',' . round($y, 2);
        }
        $chartPointsString = implode(' ', $chartPoints);

        // Data sebaran per desa di Kecamatan Jatisari
        $desaStats = [
            ['desa' => 'Jatisari', 'jumlah' => 142, 'selesai' => 138],
            ['desa' => 'Balonggandu', 'jumlah' => 125, 'selesai' => 121],
            ['desa' => 'Cirejag', 'jumlah' => 110, 'selesai' => 106],
            ['desa' => 'Kalijati', 'jumlah' => 98, 'selesai' => 95],
            ['desa' => 'Mekarsari', 'jumlah' => 105, 'selesai' => 102],
            ['desa' => 'Pacing', 'jumlah' => 92, 'selesai' => 89],
            ['desa' => 'Jatibaru', 'jumlah' => 86, 'selesai' => 83],
            ['desa' => 'Jatiragas', 'jumlah' => 81, 'selesai' => 79],
            ['desa' => 'Barugbug', 'jumlah' => 78, 'selesai' => 76],
            ['desa' => 'Telarsari', 'jumlah' => 89, 'selesai' => 86],
            ['desa' => 'Sukamekar', 'jumlah' => 74, 'selesai' => 72],
            ['desa' => 'Cikalongsari', 'jumlah' => 94, 'selesai' => 91],
            ['desa' => 'Gembongsari', 'jumlah' => 74, 'selesai' => 72],
        ];

        return view('camat.statistik', [
            'services' => $services,
            'monthlyTrend' => $monthlyTrend,
            'chartMax' => $chartMax,
            'monthlyMax' => $monthlyMax,
            'chartPoints' => $chartPointsString,
            'desaStats' => $desaStats,
            'periode' => $periode,
            'year' => 2026,
            'totalPelayanan' => 1248,
            'rataBulanan' => 138,
            'persenSelesai' => 96.9,
        ]);
    }

    /**
     * Halaman Laporan Pelayanan untuk Camat
     */
    public function laporan(Request $request)
    {
        $allServices = $this->getServicesCatalog();
        $tahun = $request->input('tahun', '2026');
        $bulan = $request->input('bulan', 'semua');
        $jenisLayanan = $request->input('jenis_layanan', 'semua');

        $filteredServices = $allServices;
        if ($jenisLayanan !== 'semua' && !empty($jenisLayanan)) {
            $filteredServices = array_filter($filteredServices, function ($item) use ($jenisLayanan) {
                return $item['key'] === $jenisLayanan || $item['short'] === $jenisLayanan || $item['label'] === $jenisLayanan;
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
        $allLogs = [
            ['id' => 1, 'operator' => 'Operator 01', 'aktivitas' => 'Menambahkan data KTP', 'jenis' => 'KTP', 'waktu' => '08:21', 'status' => 'Berhasil'],
            ['id' => 2, 'operator' => 'Operator 02', 'aktivitas' => 'Memperbarui data KK', 'jenis' => 'Kartu Keluarga', 'waktu' => '09:15', 'status' => 'Berhasil'],
            ['id' => 3, 'operator' => 'Operator 01', 'aktivitas' => 'Menyelesaikan pelayanan Akta Kelahiran', 'jenis' => 'Akta Kelahiran', 'waktu' => '10:02', 'status' => 'Berhasil'],
            ['id' => 4, 'operator' => 'Operator 02', 'aktivitas' => 'Memperbarui status pelayanan', 'jenis' => 'Pelayanan', 'waktu' => '11:20', 'status' => 'Berhasil'],
            ['id' => 5, 'operator' => 'Operator 01', 'aktivitas' => 'Membuat laporan pelayanan', 'jenis' => 'Laporan', 'waktu' => '13:10', 'status' => 'Berhasil'],
        ];

        $filterOperator = $request->input('operator', 'semua');
        $filteredLogs = $allLogs;

        if ($filterOperator !== 'semua' && !empty($filterOperator)) {
            $filteredLogs = array_filter($filteredLogs, function ($item) use ($filterOperator) {
                return stripos($item['operator'], $filterOperator) !== false;
            });
        }

        return view('camat.aktivitas', [
            'logs' => $filteredLogs,
            'filterOperator' => $filterOperator,
            'totalAktivitas' => count($allLogs),
        ]);
    }

    /**
     * Halaman Notifikasi Camat
     */
    public function notifikasi()
    {
        $notifications = [
            [
                'id' => 1,
                'judul' => '12 pelayanan membutuhkan perhatian',
                'pesan' => 'Terdapat 12 berkas pelayanan yang membutuhkan verifikasi ulang dan perbaikan dokumen pemohon.',
                'kategori' => 'Perhatian',
                'badge_class' => 'warning',
                'waktu' => '10 menit yang lalu',
                'dibaca' => false,
                'link' => route('camat.monitoring'),
            ],
            [
                'id' => 2,
                'judul' => '38 pelayanan masih dalam proses',
                'pesan' => 'Sebanyak 38 berkas sedang dalam proses pengerjaan oleh petugas operator kecamatan.',
                'kategori' => 'Proses',
                'badge_class' => 'info',
                'waktu' => '1 jam yang lalu',
                'dibaca' => false,
                'link' => route('camat.monitoring'),
            ],
            [
                'id' => 3,
                'judul' => 'Laporan September 2026 tersedia',
                'pesan' => 'Rekapitulasi laporan pelayanan administrasi bulan September 2026 telah selesai dan siap ditinjau/dicetak.',
                'kategori' => 'Laporan',
                'badge_class' => 'success',
                'waktu' => 'Kemarin, 16:30 WIB',
                'dibaca' => false,
                'link' => route('camat.laporan'),
            ],
            [
                'id' => 4,
                'judul' => 'Terdapat 5 pengaduan yang belum ditindaklanjuti',
                'pesan' => 'Terdapat 5 laporan aspirasi/pengaduan masyarakat yang membutuhkan tindak lanjut pimpinan.',
                'kategori' => 'Pengaduan',
                'badge_class' => 'danger',
                'waktu' => '29 Sep 2026, 14:15 WIB',
                'dibaca' => false,
                'link' => route('camat.dashboard'),
            ],
        ];

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
