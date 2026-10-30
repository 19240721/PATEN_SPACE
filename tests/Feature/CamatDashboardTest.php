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
        $response->assertSee('Camat Jatisari');
        $response->assertSee('CJ');
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
     * Test Laporan Pelayanan
     */
    public function test_camat_laporan_renders_successfully(): void
    {
        $response = $this->get('/camat/laporan');
        $response->assertStatus(200);
        $response->assertSee('Laporan Pelayanan');
        $response->assertSee('Cetak Laporan');
        $response->assertSee('Export PDF');
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
        $response->assertSee('Camat Jatisari');
    }
}
