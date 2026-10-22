<?php

namespace Tests\Feature;

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
