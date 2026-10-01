<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_can_be_rendered(): void
    {
        $response = $this->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard');
    }

    public function test_create_data_page_can_be_rendered(): void
    {
        $response = $this->get('/pendaftaran/create');

        $response->assertOk();
        $response->assertSee('Registrasi');
    }

    public function test_service_specific_form_hides_service_selector_and_keeps_service_value(): void
    {
        $response = $this->get('/pendaftaran/create?jenis_layanan=KK');

        $response->assertOk();
        $response->assertDontSee('id="jenis_layanan"', false);
        $response->assertSee('type="hidden" name="jenis_layanan" value="KK"', false);
    }

    public function test_register_form_uses_service_specific_detail_fields(): void
    {
        $response = $this->get('/pendaftaran/create');

        $response->assertOk();
        $response->assertSee('PRR / Pembuatan KTP Baru');
        $response->assertSee('KTP / Pembaharuan (Hilang/Rusak)');
        $response->assertSee('Alasan Pembaruan');
        $response->assertSee('Hilang');
        $response->assertSee('Rusak');
        $response->assertSee('Update Data');
    }

    public function test_register_can_be_saved(): void
    {
        $response = $this->post('/pendaftaran', [
            'nama_lengkap' => 'Andi Saputra',
            'nik' => '3201010101010001',
            'tempat_lahir' => 'Karawang',
            'tanggal_lahir' => '1998-05-10',
            'alamat' => 'Jl. Raya Jatisari No. 12',
            'jenis_layanan' => 'KTP',
            'no_telepon' => '081234567890',
            'petugas' => 'Andi Saputra',
            'tanggal_daftar' => '2026-09-22',
            'status' => 'Proses',
        ]);

        $response->assertRedirect('/pendaftaran/success');
        $this->assertDatabaseHas('pendaftarans', [
            'nik' => '3201010101010001',
            'jenis_layanan' => 'KTP',
        ]);
    }

    public function test_same_nik_can_be_registered_for_different_services(): void
    {
        $registration = [
            'nama_lengkap' => 'Demo Warga KTP',
            'nik' => '3201010101010099',
            'alamat' => 'Jl. Contoh',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-09-22',
        ];

        $this->post('/pendaftaran', $registration)->assertRedirect('/pendaftaran/success');

        $registration['nama_lengkap'] = 'Demo Warga KK';
        $registration['jenis_layanan'] = 'KK';
        $this->post('/pendaftaran', $registration)->assertRedirect('/pendaftaran/success');

        $this->assertDatabaseHas('pendaftarans', [
            'nik' => '3201010101010099',
            'jenis_layanan' => 'KTP',
        ]);
        $this->assertDatabaseHas('pendaftarans', [
            'nik' => '3201010101010099',
            'jenis_layanan' => 'KK',
        ]);
    }

    public function test_dashboard_shows_saved_registration_data(): void
    {
        $this->post('/pendaftaran', [
            'nama_lengkap' => 'Siti Nurhaliza',
            'nik' => '3201010101010002',
            'tempat_lahir' => 'Jatisari',
            'tanggal_lahir' => '2000-01-20',
            'alamat' => 'Jl. Desa Jatisari',
            'jenis_layanan' => 'KK',
            'no_telepon' => '081111222333',
            'petugas' => 'Siti Nurhaliza',
            'tanggal_daftar' => '2026-09-22',
            'status' => 'Selesai',
        ]);

        $response = $this->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Siti Nurhaliza');
        $response->assertSee('KK');
    }

    public function test_service_page_only_shows_matching_service_records(): void
    {
        \App\Models\Pendaftaran::create([
            'nama_lengkap' => 'Siti Keluarga',
            'nik' => '3201010101010011',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-22',
        ]);
        \App\Models\Pendaftaran::create([
            'nama_lengkap' => 'Andi KTP',
            'nik' => '3201010101010012',
            'alamat' => 'Jl. Cikampek',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-09-22',
        ]);

        $response = $this->get('/pelayanan/kartu-keluarga');

        $response->assertOk();
        $response->assertSee('Data Kartu Keluarga');
        $response->assertSee('Siti Keluarga');
        $response->assertDontSee('Andi KTP');
    }

    public function test_kedatangan_page_has_dates_and_no_keterangan_field(): void
    {
        $response = $this->get('/pelayanan/kedatangan');

        $response->assertOk();
        $response->assertSee('Tanggal Kedatangan');
        $response->assertDontSee('<th>Keterangan</th>', false);

        $formResponse = $this->get('/pendaftaran/create?jenis_layanan=Kedatangan');
        $formResponse->assertOk();
        $formResponse->assertDontSee('name="keterangan_kedatangan"', false);
    }

    public function test_all_data_page_shows_all_services_and_can_filter_by_service(): void
    {
        \App\Models\Pendaftaran::create([
            'nama_lengkap' => 'Siti Keluarga',
            'nik' => '3201010101010021',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-22',
        ]);
        \App\Models\Pendaftaran::create([
            'nama_lengkap' => 'Andi KTP',
            'nik' => '3201010101010022',
            'alamat' => 'Jl. Cikampek',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => '2026-09-22',
        ]);

        $response = $this->get('/semua-data?jenis_layanan=kartu-keluarga');

        $response->assertOk();
        $response->assertSee('Semua Data Pelayanan');
        $response->assertSee('Siti Keluarga');
        $response->assertDontSee('Andi KTP');
    }

    public function test_kecamatan_is_not_auto_filled_when_blank(): void
    {
        $response = $this->post('/pendaftaran', [
            'nama_lengkap' => 'Rina Aulia',
            'nik' => '3201010101010007',
            'tempat_lahir' => 'Jatisari',
            'tanggal_lahir' => '1999-02-14',
            'alamat' => 'Jl. Sukamaju No. 8',
            'jenis_layanan' => 'KTP',
            'no_telepon' => '081222444555',
            'petugas' => 'Andi Saputra',
            'tanggal_daftar' => '2026-09-22',
            'status' => 'Baru',
        ]);

        $response->assertRedirect('/pendaftaran/success');

        $pendaftaran = \App\Models\Pendaftaran::where('nik', '3201010101010007')->first();
        $this->assertNotNull($pendaftaran);
        $this->assertNull($pendaftaran->kecamatan);
    }

    public function test_laporan_page_shows_summary_and_data(): void
    {
        \App\Models\Pendaftaran::create([
            'nama_lengkap' => 'Rudi Hartono',
            'nik' => '3201010101010031',
            'alamat' => 'Jl. Desa Jatisari 2',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-22',
            'status' => 'Proses',
        ]);

        $response = $this->get('/laporan');

        $response->assertOk();
        $response->assertSee('Laporan Pelayanan');
        $response->assertSee('Rudi Hartono');
        $response->assertSee('Kartu Keluarga');
    }

    public function test_register_page_is_no_longer_available(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_register_can_be_saved_with_family_and_kk_fields(): void
    {
        $response = $this->post('/pendaftaran', [
            'nama_lengkap' => 'Sulastri',
            'nama_kepala_keluarga' => 'Bambang Setiawan',
            'nik' => '3201010101010006',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Jatisari',
            'tanggal_lahir' => '1988-04-11',
            'alamat' => 'Jl. Cintawana No. 5',
            'jalan' => 'Jl. Cintawana',
            'rt' => '2',
            'rw' => '5',
            'desa' => 'Jatisari',
            'jenis_layanan' => 'KK',
            'no_telepon' => '081555666777',
            'petugas' => 'Siti Nurhaliza',
            'tanggal_daftar' => '2026-09-22',
            'status' => 'Baru',
        ]);

        $response->assertRedirect('/pendaftaran/success');
        $this->assertDatabaseHas('pendaftarans', [
            'nik' => '3201010101010006',
            'jenis_layanan' => 'KK',
            'nama_kepala_keluarga' => 'Bambang Setiawan',
            'jenis_kelamin' => 'P',
            'jalan' => 'Jl. Cintawana',
            'rt' => '2',
            'rw' => '5',
            'desa' => 'Jatisari',
        ]);
    }

    public function test_registration_can_be_updated(): void
    {
        $pendaftaran = \App\Models\Pendaftaran::create([
            'nama_lengkap' => 'Dewi Lestari',
            'nik' => '3201010101010004',
            'tempat_lahir' => 'Karawang',
            'tanggal_lahir' => '2001-11-11',
            'alamat' => 'Jl. Citarum',
            'jenis_layanan' => 'KTP',
            'no_telepon' => '081999888777',
            'petugas' => 'Andi Saputra',
            'tanggal_daftar' => '2026-09-22',
            'status' => 'Baru',
        ]);

        $response = $this->put('/pendaftaran/' . $pendaftaran->id, [
            'nama_lengkap' => 'Dewi Lestari',
            'nik' => '3201010101010004',
            'tempat_lahir' => 'Karawang',
            'tanggal_lahir' => '2001-11-11',
            'alamat' => 'Jl. Citarum Baru',
            'jenis_layanan' => 'KK',
            'no_telepon' => '081999888777',
            'petugas' => 'Andi Saputra',
            'tanggal_daftar' => '2026-09-22',
            'status' => 'Selesai',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
            'jenis_layanan' => 'KK',
            'status' => 'Selesai',
            'alamat' => 'Jl. Citarum Baru',
        ]);
    }

    public function test_registration_cannot_be_deleted(): void
    {
        $pendaftaran = \App\Models\Pendaftaran::create([
            'nama_lengkap' => 'Agus Pratama',
            'nik' => '3201010101010005',
            'tempat_lahir' => 'Cikampek',
            'tanggal_lahir' => '1994-09-09',
            'alamat' => 'Jl. Panglima Sudirman',
            'jenis_layanan' => 'KK',
            'no_telepon' => '081777666555',
            'petugas' => 'Siti Nurhaliza',
            'tanggal_daftar' => '2026-09-22',
            'status' => 'Proses',
        ]);

        $response = $this->delete('/pendaftaran/' . $pendaftaran->id);

        $response->assertStatus(405);
        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
        ]);
    }
}
