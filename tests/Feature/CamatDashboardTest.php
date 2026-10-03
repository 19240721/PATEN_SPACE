<?php

namespace Tests\Feature;

use Tests\TestCase;

class CamatDashboardTest extends TestCase
{
    /**
     * Test Dashboard Operator route tetap terdaftar
     */
    public function test_operator_dashboard_route_is_defined(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('dashboard'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('pendaftaran.index'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('laporan'));
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
        $response->assertSee('1.248'); // Total Pelayanan
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
        $response->assertSee('Sebaran Pelayanan per Desa');
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
     * Test Aktivitas Pelayanan
     */
    public function test_camat_aktivitas_renders_successfully(): void
    {
        $response = $this->get('/camat/aktivitas');
        $response->assertStatus(200);
        $response->assertSee('Aktivitas Pelayanan');
        $response->assertSee('Log Riwayat Tindakan Operator');
    }

    /**
     * Test Notifikasi Camat
     */
    public function test_camat_notifikasi_renders_successfully(): void
    {
        $response = $this->get('/camat/notifikasi');
        $response->assertStatus(200);
        $response->assertSee('Notifikasi & Pengawasan Pimpinan', false);
    }

    /**
     * Test Profil Camat
     */
    public function test_camat_profil_renders_successfully(): void
    {
        $response = $this->get('/camat/profil');
        $response->assertStatus(200);
        $response->assertSee('Profil Pimpinan Camat');
        $response->assertSee('Drs. H. Ahmad Sudrajat, M.Si');
    }
}
