<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CamatDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'camat']));
    }

    /**
     * Test Dashboard Operator route tetap terdaftar
     */
    public function test_operator_dashboard_route_is_defined(): void
    {
        $this->assertTrue(Route::has('dashboard'));
        $this->assertTrue(Route::has('pendaftaran.index'));
        $this->assertTrue(Route::has('laporan'));
    }

    /**
     * Test redirect route /camat ke /camat/dashboard
     */
    public function test_camat_root_redirects_to_dashboard(): void
    {
        $response = $this->get('/camat');
        $response->assertRedirect('/camat/dashboard');
    }

    /**
     * Test Dashboard Camat utama
     */
    public function test_camat_dashboard_renders_successfully(): void
    {
        $response = $this->get('/camat/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Camat');
        $response->assertDontSee('operator-chip');
        $response->assertSee('Keluar');
        $response->assertSee('Total Pelayanan');
        $response->assertSee('Pelayanan Berdasarkan Jenis');
        $response->assertSee('Tren Pelayanan Bulanan');
        $response->assertSee('Perlu Perhatian');
        $response->assertSee('Pelayanan Terbaru');
    }

    /**
     * Test Monitoring Pelayanan
     */
    public function test_camat_monitoring_renders_successfully(): void
    {
        $response = $this->get('/camat/monitoring');
        $response->assertStatus(200);
        $response->assertSee('Monitoring Pelayanan');
        $response->assertSee('Kartu Keluarga');
        $response->assertSee('KTP');
    }

    /**
     * Test Monitoring Pelayanan menampilkan 4 filter lengkap dan format periode persis
     */
    public function test_camat_monitoring_displays_all_four_filters_and_exact_periods(): void
    {
        $response = $this->get('/camat/monitoring');
        $response->assertStatus(200);
        $response->assertSee('Tahun');
        $response->assertSee('Periode');
        $response->assertSee('Jenis Pelayanan');
        $response->assertSee('Status');
        $response->assertSee('Semua Periode');
        $response->assertSee('Bulan Ini');
        $response->assertSee('Triwulan I (Jan - Mar)');
        $response->assertSee('Triwulan II (Apr - Jun)');
        $response->assertSee('Triwulan III (Jul - Sep)');
        $response->assertSee('Triwulan IV (Okt - Des)');
        $response->assertSee('Terapkan Filter');
        $response->assertSee('Rata-rata Waktu');
        $response->assertSee('Belum Ada Data');
    }

    /**
     * Test Monitoring Pelayanan memfilter berdasarkan tahun dan triwulan
     */
    public function test_camat_monitoring_filters_by_year_and_quarter(): void
    {
        // 1. Data Q1 2026 (Februari 2026)
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Q1 2026',
            'nik' => '3201010101010901',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-02-15',
            'status' => 'Selesai',
        ]);

        // 2. Data Q2 2026 (Mei 2026)
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Q2 2026',
            'nik' => '3201010101010902',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-05-15',
            'status' => 'Selesai',
        ]);

        // 3. Data Q1 2025 (Februari 2025)
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Q1 2025',
            'nik' => '3201010101010903',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2025-02-15',
            'status' => 'Selesai',
        ]);

        $response = $this->get('/camat/monitoring?tahun=2026&periode=triwulan_1');
        $response->assertStatus(200);
        $response->assertViewHas('totalPermohonan', 1);
        $response->assertViewHas('totalSelesai', 1);

        $responseQ2 = $this->get('/camat/monitoring?tahun=2026&periode=triwulan_2');
        $responseQ2->assertStatus(200);
        $responseQ2->assertViewHas('totalPermohonan', 1);

        $responseQ1_2025 = $this->get('/camat/monitoring?tahun=2025&periode=triwulan_1');
        $responseQ1_2025->assertStatus(200);
        $responseQ1_2025->assertViewHas('totalPermohonan', 1);
    }

    /**
     * Test Monitoring Pelayanan memfilter status Selesai dan Dalam Proses
     */
    public function test_camat_monitoring_filters_by_status(): void
    {
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Mon Selesai',
            'nik' => '3201010101010911',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-09-15',
            'status' => 'Selesai',
        ]);

        Pendaftaran::create([
            'nama_lengkap' => 'Warga Mon Proses',
            'nik' => '3201010101010912',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-09-16',
            'status' => 'Proses',
        ]);

        $responseSelesai = $this->get('/camat/monitoring?status=selesai');
        $responseSelesai->assertStatus(200);
        $responseSelesai->assertViewHas('totalPermohonan', 1);
        $responseSelesai->assertViewHas('totalSelesai', 1);
        $responseSelesai->assertViewHas('totalProses', 0);

        $responseProses = $this->get('/camat/monitoring?status=dalam_proses');
        $responseProses->assertStatus(200);
        $responseProses->assertViewHas('totalPermohonan', 1);
        $responseProses->assertViewHas('totalSelesai', 0);
        $responseProses->assertViewHas('totalProses', 1);
    }

    /**
     * Test Statistik Pelayanan
     */
    public function test_camat_statistik_renders_successfully(): void
    {
        $response = $this->get('/camat/statistik');
        $response->assertStatus(200);
        $response->assertSee('Statistik Pelayanan');
        $response->assertSee('Pelayanan Berdasarkan Jenis');
    }

    /**
     * Test Camat Statistik menampilkan card Distribusi Status dalam kondisi kosong
     */
    public function test_camat_statistik_displays_distribusi_status_card_and_empty_state(): void
    {
        $response = $this->get('/camat/statistik');
        $response->assertStatus(200);
        $response->assertSee('Distribusi Status');
        $response->assertSee('Komposisi status seluruh pelayanan');
        $response->assertSee('Belum ada data pelayanan');
        $response->assertSee('donut-tooltip');
        $response->assertSee('Selesai');
        $response->assertSee('Dalam Proses');
        $response->assertSee('Menunggu');
        $response->assertSee('Perlu Revisi');

        $response->assertViewHas('distribusiStatus', function ($dist) {
            return $dist['total'] === 0
                && $dist['items']['selesai']['count'] === 0
                && $dist['items']['proses']['count'] === 0
                && $dist['items']['menunggu']['count'] === 0
                && $dist['items']['perlu_revisi']['count'] === 0;
        });
    }

    /**
     * Test Camat Statistik Distribusi Status mengagregasi 4 status dengan benar
     */
    public function test_camat_statistik_distribusi_status_aggregates_all_four_categories(): void
    {
        // 2 Selesai
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Selesai 1',
            'nik' => '3201010101010111',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-02-10',
            'status' => 'Selesai',
        ]);
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Selesai 2',
            'nik' => '3201010101010112',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-02-11',
            'status' => 'Selesai',
        ]);

        // 1 Dalam Proses
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Proses 1',
            'nik' => '3201010101010113',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'Akta',
            'tanggal_daftar' => '2026-02-12',
            'status' => 'Proses',
        ]);

        // 1 Menunggu (Baru)
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Menunggu 1',
            'nik' => '3201010101010114',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'Pindah',
            'tanggal_daftar' => '2026-02-13',
            'status' => 'Baru',
        ]);

        // 1 Perlu Revisi
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Revisi 1',
            'nik' => '3201010101010115',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'Kedatangan',
            'tanggal_daftar' => '2026-02-14',
            'status' => 'Perlu Revisi',
        ]);

        $response = $this->get('/camat/statistik?tahun=2026&periode=tw1');
        $response->assertStatus(200);

        $response->assertViewHas('distribusiStatus', function ($dist) {
            return $dist['total'] === 5
                && $dist['items']['selesai']['count'] === 2
                && $dist['items']['selesai']['percentage'] == 40.0
                && $dist['items']['proses']['count'] === 1
                && $dist['items']['proses']['percentage'] == 20.0
                && $dist['items']['menunggu']['count'] === 1
                && $dist['items']['menunggu']['percentage'] == 20.0
                && $dist['items']['perlu_revisi']['count'] === 1
                && $dist['items']['perlu_revisi']['percentage'] == 20.0;
        });

        // Segments rendered in SVG
        $response->assertSee('data-label="Selesai"', false);
        $response->assertSee('data-label="Dalam Proses"', false);
        $response->assertSee('data-label="Menunggu"', false);
        $response->assertSee('data-label="Perlu Revisi"', false);
        $response->assertSee('Selesai: 2 pelayanan (40%)');
    }

    /**
     * Test Camat Statistik Distribusi Status sinkron dengan filter Tahun dan Periode
     */
    public function test_camat_statistik_distribusi_status_syncs_with_tahun_and_periode_filter(): void
    {
        // 2026 Q1
        Pendaftaran::create([
            'nama_lengkap' => 'Warga 2026 Q1',
            'nik' => '3201010101010121',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-01-15',
            'status' => 'Selesai',
        ]);

        // 2026 Q2
        Pendaftaran::create([
            'nama_lengkap' => 'Warga 2026 Q2',
            'nik' => '3201010101010122',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-05-15',
            'status' => 'Proses',
        ]);

        // 2025
        Pendaftaran::create([
            'nama_lengkap' => 'Warga 2025',
            'nik' => '3201010101010123',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2025-03-15',
            'status' => 'Selesai',
        ]);

        // Filter 2026 Q1
        $resQ1 = $this->get('/camat/statistik?tahun=2026&periode=tw1');
        $resQ1->assertStatus(200);
        $resQ1->assertViewHas('distribusiStatus', function ($dist) {
            return $dist['total'] === 1 && $dist['items']['selesai']['count'] === 1;
        });

        // Filter 2026 Q2
        $resQ2 = $this->get('/camat/statistik?tahun=2026&periode=tw2');
        $resQ2->assertStatus(200);
        $resQ2->assertViewHas('distribusiStatus', function ($dist) {
            return $dist['total'] === 1 && $dist['items']['proses']['count'] === 1;
        });

        // Filter 2025
        $res2025 = $this->get('/camat/statistik?tahun=2025');
        $res2025->assertStatus(200);
        $res2025->assertViewHas('distribusiStatus', function ($dist) {
            return $dist['total'] === 1 && $dist['items']['selesai']['count'] === 1;
        });
    }

    /**
     * Test Camat Statistik menampilkan seluruh 14 desa Kecamatan Jatisari saat database kosong
     */
    public function test_camat_statistik_displays_all_14_villages_in_empty_state(): void
    {
        $response = $this->get('/camat/statistik');
        $response->assertStatus(200);
        $response->assertSee('Statistik per Desa');
        $response->assertSee('Jumlah pelayanan berdasarkan desa');
        $response->assertSee('14 Desa Kecamatan Jatisari');
        $response->assertSee('desa-chart-scroll-box');
        $response->assertSee('desa-plot-area');
        $response->assertSee('desa-bar-tooltip');
        $response->assertDontSee('desa-row-item');

        // Verifikasi urutan tata letak: 1. Status Pelayanan -> 2. Distribusi Status -> 3. Statistik per Desa
        $content = $response->getContent();
        $posStatusPelayanan = strpos($content, 'Status Pelayanan');
        $posDistribusiStatus = strpos($content, 'Distribusi Status');
        $posStatistikDesa = strpos($content, 'Statistik per Desa');
        $this->assertTrue($posStatusPelayanan !== false && $posDistribusiStatus !== false && $posStatistikDesa !== false);
        $this->assertTrue($posStatusPelayanan < $posDistribusiStatus, 'Status Pelayanan harus berada sebelum Distribusi Status');
        $this->assertTrue($posDistribusiStatus < $posStatistikDesa, 'Distribusi Status harus berada sebelum Statistik per Desa');

        $expectedDesa = [
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

        foreach ($expectedDesa as $desa) {
            $response->assertSee($desa);
        }

        $response->assertViewHas('totalDesaPelayanan', 0);
        $response->assertViewHas('desaChartMax', 10);
        $response->assertViewHas('desaChartTicks', [0, 2, 4, 6, 8, 10]);
        $response->assertSee('Belum ada data pelayanan untuk periode yang dipilih.');
        $response->assertDontSee('Sinkron Otomatis');
        $response->assertViewHas('desaStats', function ($stats) use ($expectedDesa) {
            if (count($stats) !== 14) {
                return false;
            }
            foreach ($stats as $idx => $s) {
                if ($s['jumlah'] !== 0 || $s['nama'] !== $expectedDesa[$idx]) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Test Camat Statistik mengurutkan desa terbanyak ke tersedikit dan mempertahankan seluruh 14 desa
     */
    public function test_camat_statistik_sorts_villages_by_service_count_descending(): void
    {
        // 3 pendaftaran desa Jatisari
        for ($i = 0; $i < 3; $i++) {
            Pendaftaran::create([
                'nama_lengkap' => "Warga Jatisari $i",
                'nik' => "320101010101020$i",
                'alamat' => 'Jl. Jatisari',
                'desa' => 'Jatisari',
                'jenis_layanan' => 'KK',
                'tanggal_daftar' => '2026-05-10',
                'status' => 'Selesai',
            ]);
        }

        // 1 pendaftaran desa Cirejag
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Cirejag',
            'nik' => '3201010101010210',
            'alamat' => 'Jl. Cirejag',
            'desa' => 'Cirejag',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-05-11',
            'status' => 'Proses',
        ]);

        $response = $this->get('/camat/statistik');
        $response->assertStatus(200);
        $response->assertViewHas('totalDesaPelayanan', 4);
        $response->assertViewHas('desaStats', function ($stats) {
            // Pertama harus Jatisari (3)
            if ($stats[0]['nama'] !== 'Jatisari' || $stats[0]['jumlah'] !== 3) {
                return false;
            }
            // Kedua harus Cirejag (1)
            if ($stats[1]['nama'] !== 'Cirejag' || $stats[1]['jumlah'] !== 1) {
                return false;
            }
            // Total tetap harus 14 desa
            if (count($stats) !== 14) {
                return false;
            }
            // Sisa 12 desa harus bernilai 0
            for ($k = 2; $k < 14; $k++) {
                if ($stats[$k]['jumlah'] !== 0) {
                    return false;
                }
            }
            return true;
        });

        // Verifikasi tooltip data attributes
        $response->assertSee('data-desa="Jatisari"', false);
        $response->assertSee('data-jumlah="3"', false);
    }

    /**
     * Test Camat Statistik sinkron otomatis saat operator menambah pelayanan baru
     */
    public function test_camat_statistik_syncs_automatically_when_operator_creates_registration(): void
    {
        $responseBefore = $this->get('/camat/statistik');
        $responseBefore->assertViewHas('desaStats', function ($stats) {
            $pacing = collect($stats)->firstWhere('nama', 'Pacing');
            return $pacing && $pacing['jumlah'] === 0;
        });

        // Operator mendaftarkan pelayanan baru dari desa Pacing
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Pacing',
            'nik' => '3201010101010299',
            'alamat' => 'Dusun Babakan',
            'desa' => 'Pacing',
            'jenis_layanan' => 'Pindah',
            'tanggal_daftar' => '2026-06-01',
            'status' => 'Baru',
        ]);

        $responseAfter = $this->get('/camat/statistik');
        $responseAfter->assertViewHas('desaStats', function ($stats) {
            $pacing = collect($stats)->firstWhere('nama', 'Pacing');
            return $pacing && $pacing['jumlah'] === 1;
        });
        $responseAfter->assertSee('Pacing');
        $responseAfter->assertSee('1 pelayanan');
    }

    /**
     * Test Camat Statistik sinkron otomatis saat operator mengubah atau menghapus data pelayanan
     */
    public function test_camat_statistik_syncs_automatically_when_operator_updates_or_deletes_registration(): void
    {
        // 1. Operator membuat data baru untuk desa Cikalongsari
        $pendaftaran = Pendaftaran::create([
            'nama_lengkap' => 'Warga Cikalongsari',
            'nik' => '3201010101010888',
            'alamat' => 'Kp. Babakan',
            'desa' => 'Cikalongsari',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-07-01',
            'status' => 'Proses',
        ]);

        $res1 = $this->get('/camat/statistik');
        $res1->assertViewHas('desaStats', function ($stats) {
            $cikalong = collect($stats)->firstWhere('nama', 'Cikalongsari');
            return $cikalong && $cikalong['jumlah'] === 1;
        });

        // 2. Operator mengedit data: ganti desa menjadi Mekarsari
        $pendaftaran->update(['desa' => 'Mekarsari']);

        $res2 = $this->get('/camat/statistik');
        $res2->assertViewHas('desaStats', function ($stats) {
            $cikalong = collect($stats)->firstWhere('nama', 'Cikalongsari');
            $mekar = collect($stats)->firstWhere('nama', 'Mekarsari');
            return $cikalong && $cikalong['jumlah'] === 0
                && $mekar && $mekar['jumlah'] === 1;
        });

        // 3. Operator menghapus data
        $pendaftaran->delete();

        $res3 = $this->get('/camat/statistik');
        $res3->assertViewHas('desaStats', function ($stats) {
            $mekar = collect($stats)->firstWhere('nama', 'Mekarsari');
            return $mekar && $mekar['jumlah'] === 0;
        });
        $res3->assertViewHas('totalDesaPelayanan', 0);
    }

    /**
     * Test Camat Statistik JSON endpoint mengembalikan format valid untuk live polling
     */
    public function test_camat_statistik_json_endpoint_returns_data_for_live_polling(): void
    {
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Jatibaru',
            'nik' => '3201010101010777',
            'alamat' => 'Jl. Jatibaru No 1',
            'desa' => 'Jatibaru',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-08-10',
            'status' => 'Selesai',
        ]);

        $response = $this->getJson('/camat/statistik?format=json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'timestamp',
            'totalDesaPelayanan',
            'totalDesaPelayananFormatted',
            'maxDesaCount',
            'desaChartMax',
            'desaChartTicks',
            'desaStats' => [
                '*' => [
                    'nama',
                    'desa',
                    'jumlah',
                    'jumlah_formatted',
                    'persentase',
                    'bar_width',
                ]
            ],
            'distribusiStatus',
            'statusPelayanan',
        ]);

        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(1, $data['totalDesaPelayanan']);
        $this->assertEquals('Jatibaru', $data['desaStats'][0]['nama']);
        $this->assertEquals(1, $data['desaStats'][0]['jumlah']);
    }

    /**
     * Test Camat Statistik skala menyesuaikan data volume tinggi seperti contoh 238
     */
    public function test_camat_statistik_scale_adapts_to_high_service_volume_like_238(): void
    {
        // Masukkan data 238 berkas untuk Jatisari
        for ($i = 0; $i < 238; $i++) {
            Pendaftaran::create([
                'nama_lengkap' => "Warga Jatisari $i",
                'nik' => '320101010101' . str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                'alamat' => 'Dusun Jatisari',
                'desa' => 'Jatisari',
                'jenis_layanan' => 'KTP',
                'tanggal_daftar' => '2026-04-10',
                'status' => 'Selesai',
            ]);
        }

        $response = $this->get('/camat/statistik');
        $response->assertStatus(200);
        $response->assertViewHas('totalDesaPelayanan', 238);
        $response->assertViewHas('desaChartMax', 240);
        $response->assertViewHas('desaChartTicks', [0, 60, 120, 180, 240]);
    }

    /**
     * Test Laporan Pelayanan
     */
    public function test_camat_laporan_renders_successfully(): void
    {
        $response = $this->get('/camat/laporan');
        $response->assertStatus(200);
        $response->assertSee('Laporan Pelayanan');
        $response->assertSee('Cetak Laporan');
        $response->assertSee('Export PDF');
        $response->assertSee('Ketepatan Waktu');
        $response->assertSee('Belum Ada Data');
        $response->assertDontSee('98.4%');
    }

    /**
     * Test Laporan Pelayanan menampilkan 4 label filter dan opsi status Dalam Proses
     */
    public function test_camat_laporan_displays_all_four_filter_labels_and_status_options(): void
    {
        $response = $this->get('/camat/laporan');
        $response->assertStatus(200);
        $response->assertSee('Tahun');
        $response->assertSee('Bulan');
        $response->assertSee('Jenis Pelayanan');
        $response->assertSee('Status');
        $response->assertSee('Dalam Proses');
        $response->assertSee('Selesai');
        $response->assertSee('Terapkan');
    }

    /**
     * Test Laporan Pelayanan memfilter status Selesai
     */
    public function test_camat_laporan_filters_by_status_selesai(): void
    {
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Selesai',
            'nik' => '3201010101010091',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-15',
            'status' => 'Selesai',
        ]);

        Pendaftaran::create([
            'nama_lengkap' => 'Warga Dalam Proses',
            'nik' => '3201010101010092',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-16',
            'status' => 'Proses',
        ]);

        $response = $this->get('/camat/laporan?status=selesai');
        $response->assertStatus(200);
        $response->assertViewHas('total', 1);
        $response->assertViewHas('totalSelesai', 1);
        $response->assertViewHas('totalProses', 0);
    }

    /**
     * Test Laporan Pelayanan memfilter status Dalam Proses
     */
    public function test_camat_laporan_filters_by_status_dalam_proses(): void
    {
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Selesai',
            'nik' => '3201010101010081',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-09-15',
            'status' => 'Selesai',
        ]);

        Pendaftaran::create([
            'nama_lengkap' => 'Warga Proses',
            'nik' => '3201010101010082',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-09-16',
            'status' => 'Proses',
        ]);

        $response = $this->get('/camat/laporan?status=dalam_proses');
        $response->assertStatus(200);
        $response->assertViewHas('total', 1);
        $response->assertViewHas('totalSelesai', 0);
        $response->assertViewHas('totalProses', 1);
    }

    /**
     * Test Laporan Pelayanan memfilter 4 parameter bersamaan
     */
    public function test_camat_laporan_filters_by_four_parameters_simultaneously(): void
    {
        // Data 1: Cocok dengan filter (2026, September, KK, Selesai)
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Target',
            'nik' => '3201010101010071',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-20',
            'status' => 'Selesai',
        ]);

        // Data 2: Beda bulan (Agustus)
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Agustus',
            'nik' => '3201010101010072',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-08-20',
            'status' => 'Selesai',
        ]);

        // Data 3: Beda jenis layanan (KTP)
        Pendaftaran::create([
            'nama_lengkap' => 'Warga KTP',
            'nik' => '3201010101010073',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-09-21',
            'status' => 'Selesai',
        ]);

        // Data 4: Beda status (Dalam Proses)
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Proses',
            'nik' => '3201010101010074',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-22',
            'status' => 'Proses',
        ]);

        $response = $this->get('/camat/laporan?tahun=2026&bulan=september&jenis_layanan=kk&status=selesai');
        $response->assertStatus(200);
        $response->assertViewHas('total', 1);
        $response->assertViewHas('totalSelesai', 1);
        $response->assertViewHas('totalProses', 0);
    }

    /**
     * Test Laporan Pelayanan menampilkan seluruh 7 jenis pelayanan saat database kosong
     */
    public function test_camat_laporan_displays_all_seven_services_when_database_is_empty(): void
    {
        $response = $this->get('/camat/laporan');
        $response->assertStatus(200);
        $response->assertViewHas('total', 0);
        $response->assertSee('PRR / KTP Baru');
        $response->assertSee('KTP');
        $response->assertSee('Kartu Keluarga');
        $response->assertSee('Akta Kelahiran');
        $response->assertSee('Akta Kematian');
        $response->assertSee('Kedatangan');
        $response->assertSee('Pindah');
        $response->assertSee('Belum Ada Data');
        $response->assertSee('TOTAL REKAPITULASI:');
    }

    /**
     * Test Laporan Pelayanan tetap menampilkan seluruh 7 jenis pelayanan saat hanya sebagian yang memiliki data
     */
    public function test_camat_laporan_displays_all_seven_services_when_only_some_have_data(): void
    {
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Kartu Keluarga Selesai',
            'nik' => '3201010101010061',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-15',
            'status' => 'Selesai',
        ]);

        Pendaftaran::create([
            'nama_lengkap' => 'Warga Kartu Keluarga Proses',
            'nik' => '3201010101010062',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-16',
            'status' => 'Proses',
        ]);

        $response = $this->get('/camat/laporan');
        $response->assertStatus(200);
        $response->assertViewHas('total', 2);
        $response->assertViewHas('totalSelesai', 1);
        $response->assertViewHas('totalProses', 1);

        // Seluruh 7 layanan harus tetap ada di tabel
        $response->assertSee('PRR / KTP Baru');
        $response->assertSee('KTP');
        $response->assertSee('Kartu Keluarga');
        $response->assertSee('Akta Kelahiran');
        $response->assertSee('Akta Kematian');
        $response->assertSee('Kedatangan');
        $response->assertSee('Pindah');

        // Layanan yang tidak ada data harus menampilkan Belum Ada Data
        $response->assertSee('Belum Ada Data');
    }

    /**
     * Test Laporan Pelayanan menampilkan seluruh 7 jenis pelayanan saat semua jenis memiliki data
     */
    public function test_camat_laporan_displays_all_seven_services_when_all_have_data(): void
    {
        $services = ['PRR', 'KTP', 'KK', 'Akta Lahir', 'Akta Mati', 'Kedatangan', 'Pindah'];
        foreach ($services as $index => $layanan) {
            Pendaftaran::create([
                'nama_lengkap' => 'Warga ' . $layanan,
                'nik' => '32010101010101' . sprintf('%02d', $index),
                'alamat' => 'Jl. Jatisari',
                'jenis_layanan' => $layanan,
                'tanggal_daftar' => '2026-09-10',
                'status' => 'Selesai',
            ]);
        }

        $response = $this->get('/camat/laporan');
        $response->assertStatus(200);
        $response->assertViewHas('total', 7);
        $response->assertViewHas('totalSelesai', 7);
        $response->assertViewHas('totalProses', 0);
        $response->assertSee('Pelayanan Selesai 100%');
    }

    /**
     * Test Laporan Pelayanan memfilter 1 jenis pelayanan ketika dipilih spesifik
     */
    public function test_camat_laporan_filters_single_service_when_jenis_layanan_selected(): void
    {
        Pendaftaran::create([
            'nama_lengkap' => 'Warga KK',
            'nik' => '3201010101010051',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-15',
            'status' => 'Selesai',
        ]);

        $response = $this->get('/camat/laporan?jenis_layanan=kk');
        $response->assertStatus(200);
        $response->assertViewHas('total', 1);
        $response->assertSee('Kartu Keluarga');
        $services = $response->viewData('services');
        $this->assertCount(1, $services);
        $this->assertEquals('kk', $services[0]['key']);
    }

    /**
     * Test Aktivitas Pelayanan
     */
    public function test_camat_aktivitas_renders_successfully(): void
    {
        $response = $this->get('/camat/aktivitas');
        $response->assertStatus(200);
        $response->assertSee('Aktivitas Pelayanan');
        $response->assertSee('Log Aktivitas Pelayanan');
    }

    public function test_camat_aktivitas_filter_pelaksana_and_excludes_admin(): void
    {
        // Setup user: 1 Operator, 1 Admin, 1 Petugas
        User::factory()->create([
            'name' => 'Operator PATEN',
            'email' => 'operator@patenspace.test',
            'role' => 'petugas',
        ]);
        User::factory()->create([
            'name' => 'Admin PATEN',
            'email' => 'admin@patenspace.test',
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'Petugas PATEN',
            'email' => 'petugas@patenspace.test',
            'role' => 'petugas',
        ]);

        $response = $this->get('/camat/aktivitas');
        $response->assertStatus(200);

        // Filter label dan opsi
        $response->assertSee('Filter Pelaksana:');
        $response->assertSee('Semua Pelaksana');
        $response->assertSee('Operator PATEN (Operator)');
        $response->assertSee('Petugas PATEN (Petugas)');

        // Admin PATEN harus dikecualikan dari dropdown
        $response->assertDontSee('Admin PATEN (Petugas)');
        $response->assertDontSee('Admin PATEN (Operator)');
        $response->assertDontSee('Admin PATEN (Admin)');

        // Kartu Petugas Layanan Aktif mengecualikan Admin (2 Petugas Layanan = 1 Operator · 1 Petugas)
        $response->assertSee('2 Petugas Layanan');
        $response->assertSee('1 Operator · 1 Petugas');

        // Tampilan data kosong tanpa clipboard emoji
        $response->assertSee('Belum ada catatan aktivitas pelayanan yang tersimpan di sistem.');
        $response->assertDontSee('📋');
    }

    public function test_camat_aktivitas_json_endpoint_realtime(): void
    {
        // Setup user: 1 Operator, 1 Admin, 1 Petugas
        User::factory()->create([
            'name' => 'Operator PATEN',
            'email' => 'operator@patenspace.test',
            'role' => 'petugas',
        ]);
        User::factory()->create([
            'name' => 'Admin PATEN',
            'email' => 'admin@patenspace.test',
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'Petugas PATEN',
            'email' => 'petugas@patenspace.test',
            'role' => 'petugas',
        ]);

        $response = $this->get('/camat/aktivitas?format=json');
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'totalPetugasLayanan' => 2,
            'totalPetugasLayananFormatted' => '2 Petugas Layanan',
            'breakdownPetugas' => '1 Operator · 1 Petugas',
        ]);
    }

    public function test_camat_aktivitas_filter_actions_and_logs_display(): void
    {
        User::factory()->create([
            'name' => 'Operator PATEN',
            'email' => 'operator@patenspace.test',
            'role' => 'petugas',
        ]);
        User::factory()->create([
            'name' => 'Petugas PATEN',
            'email' => 'petugas@patenspace.test',
            'role' => 'petugas',
        ]);

        // Filter parameter pelaksana=Operator
        $resOp = $this->get('/camat/aktivitas?pelaksana=Operator');
        $resOp->assertStatus(200);

        // Filter parameter pelaksana=Petugas
        $resPt = $this->get('/camat/aktivitas?pelaksana=Petugas');
        $resPt->assertStatus(200);

        // Filter parameter pelaksana=semua
        $resAll = $this->get('/camat/aktivitas?pelaksana=semua');
        $resAll->assertStatus(200);
    }

    /**
     * Test Notifikasi Camat
     */
    public function test_camat_notifikasi_renders_successfully(): void
    {
        $response = $this->get('/camat/notifikasi');
        $response->assertStatus(200);
        $response->assertSee('<h1>Notifikasi</h1>', false);
    }

    /**
     * Test Profil Camat
     */
    public function test_camat_profil_renders_successfully(): void
    {
        $response = $this->get('/camat/profil');
        $response->assertStatus(200);
        $response->assertSee('Profil Camat');
        $response->assertDontSee('operator-chip');
    }

    /**
     * Test Cetak Laporan Camat merender dokumen resmi Word-style lengkap
     */
    public function test_camat_laporan_cetak_renders_official_word_style_document(): void
    {
        $response = $this->get('/camat/laporan/cetak');
        $response->assertStatus(200);
        $response->assertSee('PEMERINTAH KABUPATEN KARAWANG');
        $response->assertSee('KECAMATAN JATISARI');
        $response->assertSee('logo-karawang.png');
        $response->assertSee('REKAPITULASI LAPORAN PELAYANAN PATEN');
        $response->assertSee('A. RINGKASAN HASIL PELAYANAN');
        $response->assertSee('Total Berkas Pelayanan');
        $response->assertSee('B. RINCIAN REKAPITULASI PELAYANAN');
        $response->assertSee('PRR / KTP Baru');
        $response->assertSee('KTP');
        $response->assertSee('Kartu Keluarga');
        $response->assertSee('Akta Kelahiran');
        $response->assertSee('Akta Kematian');
        $response->assertSee('Kedatangan');
        $response->assertSee('Pindah');
        $response->assertSee('CAMAT JATISARI');
        $response->assertSee('H. SURISNO.SKM.MAP');
        $response->assertSee('NIP. 19690516 1989903 1 003');
    }

    /**
     * Test Cetak Laporan Camat mendukung pemilihan ukuran kertas F4 dan A4 serta orientasi
     */
    public function test_camat_laporan_cetak_supports_f4_and_a4_paper_sizes(): void
    {
        $responseF4 = $this->get('/camat/laporan/cetak?kertas=F4&orientasi=landscape');
        $responseF4->assertStatus(200);
        $responseF4->assertSee('330mm 215mm');

        $responseA4 = $this->get('/camat/laporan/cetak?kertas=A4&orientasi=portrait');
        $responseA4->assertStatus(200);
        $responseA4->assertSee('A4 portrait');
    }

    /**
     * Test Cetak Laporan Camat otomatis sinkron dengan filter laporan
     */
    public function test_camat_laporan_cetak_syncs_with_filters(): void
    {
        Pendaftaran::create([
            'nama_lengkap' => 'Warga Cetak Selesai',
            'nik' => '3201010101019901',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-15',
            'status' => 'Selesai',
        ]);

        Pendaftaran::create([
            'nama_lengkap' => 'Warga Cetak Proses',
            'nik' => '3201010101019902',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-16',
            'status' => 'Proses',
        ]);

        $response = $this->get('/camat/laporan/cetak?tahun=2026&bulan=september&status=selesai');
        $response->assertStatus(200);
        $response->assertViewHas('total', 1);
        $response->assertViewHas('totalSelesai', 1);
        $response->assertViewHas('totalProses', 0);
    }

    /**
     * Test Halaman Laporan menampilkan identitas Camat baru tanpa tanggal di bawah tabel
     */
    public function test_camat_laporan_displays_new_camat_identity(): void
    {
        $response = $this->get('/camat/laporan');
        $response->assertStatus(200);
        $response->assertSee('CAMAT JATISARI');
        $response->assertSee('H. SURISNO.SKM.MAP');
        $response->assertSee('NIP. 19690516 1989903 1 003');
    }
}
