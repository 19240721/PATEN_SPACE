<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'operator']));
    }

    public function test_dashboard_page_can_be_rendered(): void
    {
        $response = $this->get('/petugas/dashboard');

        $response->assertOk();
        $response->assertSee('Pelayanan Berdasarkan Jenis');
        $response->assertSee('Tren Pelayanan Bulanan');
        $response->assertSee('Data Pelayanan Terbaru');
    }

    public function test_create_data_page_can_be_rendered(): void
    {
        $response = $this->get('/pendaftaran/create');

        $response->assertOk();
        $response->assertSee('Registrasi');
        $response->assertSee('class="sidebar-toggle"', false);
        $response->assertSee('aria-label="Tutup menu samping"', false);
        $response->assertSee('aria-expanded="true"', false);
        $response->assertDontSee('class="back-button"', false);
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

        $response = $this->get('/petugas/dashboard');

        $response->assertOk();
        $response->assertSee('Siti Nurhaliza');
        $response->assertSee('KK');
    }

    public function test_service_page_only_shows_matching_service_records(): void
    {
        Pendaftaran::create([
            'nama_lengkap' => 'Siti Keluarga',
            'nik' => '3201010101010011',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-22',
        ]);
        Pendaftaran::create([
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
        Pendaftaran::create([
            'nama_lengkap' => 'Siti Keluarga',
            'nik' => '3201010101010021',
            'alamat' => 'Jl. Jatisari',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => '2026-09-22',
        ]);
        Pendaftaran::create([
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

        $pendaftaran = Pendaftaran::where('nik', '3201010101010007')->first();
        $this->assertNotNull($pendaftaran);
        $this->assertNull($pendaftaran->kecamatan);
    }

    public function test_laporan_page_shows_summary_and_data(): void
    {
        Pendaftaran::create([
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
        $response->assertSee('Menu Utama');
        $response->assertSee('<details class="service-menu" open>', false);
        foreach (['Dashboard', 'PRR / KTP Baru', 'Kartu Keluarga'] as $menuItem) {
            $response->assertSee($menuItem);
        }
        $response->assertDontSee('<span>＋</span> Pendaftaran Baru', false);
        $response->assertDontSee('Daftar Permohonan');
        $response->assertDontSee('Data dan Laporan');
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
        $pendaftaran = Pendaftaran::create([
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

        $response = $this->put('/pendaftaran/'.$pendaftaran->id, [
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

    public function test_registration_can_be_deleted_by_petugas(): void
    {
        $pendaftaran = Pendaftaran::create([
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

        $response = $this->delete('/pendaftaran/'.$pendaftaran->id);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseMissing('pendaftarans', [
            'id' => $pendaftaran->id,
        ]);
    }

    public function test_register_akta_kelahiran_with_parents_reporter_and_witnesses(): void
    {
        $response = $this->post('/pendaftaran', [
            'nama_lengkap' => 'Ahmad Fauzi',
            'nik' => '3215000000000011',
            'tempat_lahir' => 'Karawang',
            'tanggal_lahir' => '2026-01-01',
            'alamat' => 'Desa Balonggandu RT 01 RW 02',
            'jenis_layanan' => 'Akta Lahir',
            'tanggal_daftar' => '2026-11-04',
            'nama_anak_lahir' => 'Bayi Ahmad',
            'nama_ayah_lahir' => 'Fauzi Rohman',
            'nik_ayah' => '3215000000000012',
            'nama_ibu_lahir' => 'Siti Aisyah',
            'nik_ibu' => '3215000000000013',
            'nama_pelapor' => 'Fauzi Rohman',
            'nik_pelapor' => '3215000000000012',
            'nama_saksi1' => 'Budi Utomo',
            'nik_saksi1' => '3215000000000014',
            'nama_saksi2' => 'Joko Susilo',
            'nik_saksi2' => '3215000000000015',
        ]);

        $response->assertRedirect(route('pendaftaran.success'));
        $this->assertDatabaseHas('pendaftarans', [
            'nik' => '3215000000000011',
            'nama_anak_lahir' => 'Bayi Ahmad',
            'nik_ayah' => '3215000000000012',
            'nama_ayah_lahir' => 'Fauzi Rohman',
            'nik_ibu' => '3215000000000013',
            'nama_ibu_lahir' => 'Siti Aisyah',
            'nama_pelapor' => 'Fauzi Rohman',
            'nik_pelapor' => '3215000000000012',
            'nama_saksi1' => 'Budi Utomo',
            'nik_saksi1' => '3215000000000014',
            'nama_saksi2' => 'Joko Susilo',
            'nik_saksi2' => '3215000000000015',
        ]);
    }

    public function test_register_akta_kematian_with_parents_reporter_and_witnesses(): void
    {
        $response = $this->post('/pendaftaran', [
            'nama_lengkap' => 'Warga Pelapor',
            'nik' => '3215000000000021',
            'tempat_lahir' => 'Karawang',
            'tanggal_lahir' => '1980-01-01',
            'alamat' => 'Desa Jatisari RT 03 RW 01',
            'jenis_layanan' => 'Akta Kematian',
            'tanggal_daftar' => '2026-11-04',
            'nama_alm' => 'Alm. Sukardi',
            'nama_ayah' => 'Ayah Alm',
            'nik_ayah' => '3215000000000022',
            'nama_ibu' => 'Ibu Alm',
            'nik_ibu' => '3215000000000023',
            'nama_pelapor' => 'Warga Pelapor',
            'nik_pelapor' => '3215000000000021',
            'nama_saksi1' => 'Saksi Pertama',
            'nik_saksi1' => '3215000000000024',
            'nama_saksi2' => 'Saksi Kedua',
            'nik_saksi2' => '3215000000000025',
        ]);

        $response->assertRedirect(route('pendaftaran.success'));
        $this->assertDatabaseHas('pendaftarans', [
            'nik' => '3215000000000021',
            'nama_alm' => 'Alm. Sukardi',
            'nama_ayah' => 'Ayah Alm',
            'nik_ayah' => '3215000000000022',
            'nama_ibu' => 'Ibu Alm',
            'nik_ibu' => '3215000000000023',
            'nama_pelapor' => 'Warga Pelapor',
            'nik_pelapor' => '3215000000000021',
            'nama_saksi1' => 'Saksi Pertama',
            'nik_saksi1' => '3215000000000024',
            'nama_saksi2' => 'Saksi Kedua',
            'nik_saksi2' => '3215000000000025',
        ]);
    }

    public function test_masyarakat_kk_submission_does_not_require_birth_place_and_date(): void
    {
        $citizen = User::factory()->create([
            'role' => 'masyarakat',
            'nik' => '3215112233440001',
        ]);

        $response = $this->actingAs($citizen)->post(route('masyarakat.permohonan.store'), [
            'nama_lengkap' => 'Kepala Keluarga Contoh',
            'nik' => '3215112233440001',
            'jenis_kelamin' => 'L',
            'no_telepon' => '081234567890',
            'desa' => 'Jatisari',
            'jalan' => 'Jl. Merdeka No. 1',
            'rt' => '001',
            'rw' => '002',
            'jenis_layanan' => 'KK',
            'nomor_kk' => '3215110000000001',
            'kepala_keluarga' => 'Kepala Keluarga Contoh',
            'hubungan' => 'Kepala Keluarga',
            'jumlah_anggota' => '4',
            'tanggal_daftar' => now()->toDateString(),
        ]);

        $pendaftaran = Pendaftaran::where('nik', '3215112233440001')->first();
        $this->assertNotNull($pendaftaran);
        $response->assertRedirect(route('masyarakat.permohonan.success', $pendaftaran));
        $this->assertNull($pendaftaran->tempat_lahir);
        $this->assertNull($pendaftaran->tanggal_lahir);
    }

    public function test_akta_kematian_can_be_saved_with_sptjm_fields(): void
    {
        $user = User::factory()->create([
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($user)->post(route('pendaftaran.store'), [
            'nama_lengkap' => 'Warga Pelapor',
            'nik' => '3215000000000099',
            'tempat_lahir' => 'Karawang',
            'tanggal_lahir' => '1985-05-10',
            'alamat' => 'Desa Barugbug RT 01 RW 02',
            'jenis_layanan' => 'Akta Kematian',
            'tanggal_daftar' => '2026-11-09',
            'nama_alm' => 'Alm. Budi Santoso',
            'tempat_meninggal' => 'Karawang',
            'tanggal_meninggal' => '2026-11-01',
            'keterangan_kematian' => 'Sakit wajar',
            'sptjm_kematian' => 1,
            'nik_jenazah' => '3215000000000088',
            'tempat_lahir_jenazah' => 'Subang',
            'tanggal_lahir_jenazah' => '1950-02-15',
            'alamat_jenazah' => 'Desa Barugbug',
            'penyebab_kematian' => 'Usia lanjut',
            'yang_menyatakan' => 'Warga Pelapor',
            'nik_yang_menyatakan' => '3215000000000099',
            'hubungan_yang_menyatakan' => 'Anak Kandung',
            'nama_saksi1' => 'Saksi Satu',
            'nik_saksi1' => '3215000000000077',
            'nama_saksi2' => 'Saksi Dua',
            'nik_saksi2' => '3215000000000066',
        ]);

        $response->assertRedirect(route('pendaftaran.success'));
        $this->assertDatabaseHas('pendaftarans', [
            'nik' => '3215000000000099',
            'nama_alm' => 'Alm. Budi Santoso',
            'sptjm_kematian' => 1,
            'nik_jenazah' => '3215000000000088',
            'tempat_lahir_jenazah' => 'Subang',
            'penyebab_kematian' => 'Usia lanjut',
            'hubungan_yang_menyatakan' => 'Anak Kandung',
        ]);
    }

    public function test_sptjm_kematian_page_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'role' => 'petugas',
        ]);

        $pendaftaran = Pendaftaran::create([
            'nama_lengkap' => 'Warga Pelapor',
            'nik' => '3215000000000099',
            'jenis_layanan' => 'Akta Kematian',
            'tanggal_daftar' => '2026-11-09',
            'nama_alm' => 'Alm. Budi Santoso',
            'tempat_meninggal' => 'Karawang',
            'tanggal_meninggal' => '2026-11-01',
            'keterangan_kematian' => 'Sakit wajar',
            'sptjm_kematian' => 1,
            'nik_jenazah' => '3215000000000088',
            'tempat_lahir_jenazah' => 'Subang',
            'tanggal_lahir_jenazah' => '1950-02-15',
            'alamat_jenazah' => 'Desa Barugbug',
            'penyebab_kematian' => 'Usia lanjut',
            'yang_menyatakan' => 'Warga Pelapor',
            'nik_yang_menyatakan' => '3215000000000099',
            'hubungan_yang_menyatakan' => 'Anak Kandung',
            'nama_saksi1' => 'Saksi Satu',
            'nik_saksi1' => '3215000000000077',
            'nama_saksi2' => 'Saksi Dua',
            'nik_saksi2' => '3215000000000066',
            'status' => 'Baru',
            'intake_source' => 'loket',
        ]);

        $response = $this->actingAs($user)->get(route('petugas.permohonan.sptjm', $pendaftaran));
        $response->assertOk();
        $response->assertSee('SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK');
        $response->assertSee('Alm. Budi Santoso');
        $response->assertSee('MATERAI');
        $response->assertSee('Saksi Satu');
        $response->assertSee('Saksi Dua');
    }
}
