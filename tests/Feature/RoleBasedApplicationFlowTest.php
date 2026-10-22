<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoleBasedApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_self_registration_creates_a_masyarakat_account(): void
    {
        $response = $this->post(route('account.register.store'), [
            'name' => 'Warga Jatisari',
            'nik' => '3200000000000001',
            'phone' => '081234567890',
            'email' => 'warga@example.test',
            'password' => 'Paten@12345',
            'password_confirmation' => 'Paten@12345',
            'terms_accepted' => '1',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', [
            'email' => 'warga@example.test',
            'role' => 'masyarakat',
        ]);
    }

    public function test_masyarakat_application_is_added_to_the_petugas_queue(): void
    {
        Storage::fake('local');
        $citizen = User::factory()->create([
            'nik' => '3200000000000002',
            'role' => 'masyarakat',
        ]);

        $response = $this->actingAs($citizen)->post(route('masyarakat.permohonan.store'), [
            'nama_lengkap' => $citizen->name,
            'nik' => $citizen->nik,
            'alamat' => 'Jatisari, Karawang',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => now()->toDateString(),
            'status' => 'Selesai',
            'documents' => [UploadedFile::fake()->create('kartu-keluarga.pdf', 100, 'application/pdf')],
        ]);

        $application = Pendaftaran::where('user_id', $citizen->id)->firstOrFail();
        $response->assertRedirect(route('masyarakat.permohonan.success', $application));
        $this->assertSame('Baru', $application->status);
        $this->assertSame('masyarakat', $application->intake_source);
        $this->assertCount(1, $application->documents);

        $this->actingAs($citizen)
            ->get(route('masyarakat.dokumen', [$application, 0]))
            ->assertOk();

        $anotherCitizen = User::factory()->create(['role' => 'masyarakat']);
        $this->actingAs($anotherCitizen)
            ->get(route('masyarakat.dokumen', [$application, 0]))
            ->assertNotFound();

        $this->actingAs(User::factory()->create(['role' => 'petugas']))
            ->get(route('pendaftaran.index'))
            ->assertOk()
            ->assertSee('Daring')
            ->assertSee('kartu-keluarga.pdf');
    }

    public function test_petugas_can_register_a_walk_in_application(): void
    {
        $petugas = User::factory()->create(['name' => 'Petugas Loket', 'role' => 'petugas']);

        $response = $this->actingAs($petugas)->post(route('pendaftaran.store'), [
            'nama_lengkap' => 'Warga tanpa ponsel',
            'nik' => '3200000000000003',
            'alamat' => 'Desa Jatisari',
            'jenis_layanan' => 'KTP',
            'tanggal_daftar' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('pendaftaran.success'));
        $this->assertDatabaseHas('pendaftarans', [
            'nik' => '3200000000000003',
            'status' => 'Baru',
            'intake_source' => 'petugas',
            'petugas' => 'Petugas Loket',
        ]);
    }

    public function test_roles_cannot_open_another_roles_pages(): void
    {
        $citizen = User::factory()->create(['role' => 'masyarakat']);

        $this->actingAs($citizen)->get(route('pendaftaran.index'))->assertForbidden();
        $this->actingAs($citizen)->get(route('camat.dashboard'))->assertForbidden();
    }

    public function test_dashboard_dispatches_each_role_to_its_own_homepage(): void
    {
        $citizen = User::factory()->create(['role' => 'masyarakat']);
        $petugas = User::factory()->create(['role' => 'petugas']);
        $camat = User::factory()->create(['role' => 'camat']);

        $this->actingAs($citizen)->get(route('dashboard'))->assertRedirect(route('masyarakat.dashboard'));
        $this->actingAs($petugas)->get(route('dashboard'))->assertRedirect(route('petugas.dashboard'));
        $this->actingAs($camat)->get(route('dashboard'))->assertRedirect(route('camat.dashboard'));
    }

    public function test_each_operational_role_can_open_its_registration_form(): void
    {
        $citizen = User::factory()->create([
            'nik' => '3200000000000004',
            'role' => 'masyarakat',
        ]);
        $petugas = User::factory()->create(['role' => 'petugas']);

        $this->actingAs($citizen)
            ->get(route('masyarakat.permohonan.create'))
            ->assertOk()
            ->assertSee('Kirim Permohonan');

        $this->actingAs($petugas)
            ->get(route('pendaftaran.create'))
            ->assertOk()
            ->assertSee('belum memiliki ponsel');
    }
}
