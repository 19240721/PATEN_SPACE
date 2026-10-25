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
            'phone' => '081234567890',
            'email' => 'warga@example.test',
            'password' => 'Paten@12345',
            'password_confirmation' => 'Paten@12345',
            'terms_accepted' => '1',
        ]);

        $response->assertRedirect(route('login.citizen'));
        $this->assertDatabaseHas('users', [
            'email' => 'warga@example.test',
            'nik' => null,
            'role' => 'masyarakat',
        ]);
    }

    public function test_only_citizen_login_page_has_public_registration_link(): void
    {
        $staffLogin = $this->get(route('login'));
        $staffLogin->assertOk()
            ->assertSee('Login khusus operator dan camat')
            ->assertDontSee('citizen-login-page')
            ->assertDontSee(route('account.register'));

        $citizenLogin = $this->get(route('login.citizen'));
        $citizenLogin->assertOk()
            ->assertSee(route('account.register'))
            ->assertSee(route('login.citizen.store'))
            ->assertSee('citizen-login-page');

        $citizenRegistration = $this->get(route('account.register'));
        $citizenRegistration->assertOk()
            ->assertSee('citizen-account-page')
            ->assertSee('Daftar Akun Masyarakat')
            ->assertDontSee('name="nik"', false)
            ->assertSee('/images/paten-building.jpg');
    }

    public function test_citizen_login_endpoint_rejects_staff_accounts(): void
    {
        $petugas = User::factory()->create([
            'email' => 'petugas-login@example.test',
            'password' => 'Paten@12345',
            'role' => 'petugas',
        ]);

        $this->withSession(['login_captcha' => 'ABC'])
            ->post(route('login.citizen.store'), [
                'email' => $petugas->email,
                'password' => 'Paten@12345',
                'captcha' => 'ABC',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_staff_login_endpoint_rejects_masyarakat_accounts(): void
    {
        $citizen = User::factory()->create([
            'email' => 'warga-login@example.test',
            'password' => 'Paten@12345',
            'role' => 'masyarakat',
        ]);

        $this->withSession(['login_captcha' => 'ABC'])
            ->post(route('login.store'), [
                'email' => $citizen->email,
                'password' => 'Paten@12345',
                'captcha' => 'ABC',
            ])
            ->assertSessionHasErrors('email');
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

    public function test_visiting_home_always_clears_an_existing_login_and_redirects_to_login(): void
    {
        $citizen = User::factory()->create(['role' => 'masyarakat']);

        $this->actingAs($citizen)
            ->get('/')
            ->assertRedirect(route('login'))
            ->assertGuest();
    }

    public function test_legacy_operator_role_can_open_the_petugas_dashboard(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $this->actingAs($operator)
            ->get(route('petugas.dashboard'))
            ->assertOk()
            ->assertSee('Pelayanan Berdasarkan Jenis');
    }

    public function test_dashboard_and_incoming_requests_share_the_same_staff_navigation(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $menuItems = ['Dashboard', 'Pendaftaran Baru', 'Daftar Permohonan', 'Semua Data', 'Data dan Laporan', 'PRR / KTP Baru'];

        $dashboard = $this->actingAs($petugas)->get(route('petugas.dashboard'));
        $dashboard->assertOk();
        $dashboard->assertSee('<details class="service-menu" open>', false);
        $dashboard->assertSee('<summary class="nav-item service-menu-toggle">', false);
        foreach ($menuItems as $menuItem) {
            $dashboard->assertSee($menuItem);
        }

        $incomingRequests = $this->get(route('petugas.permohonan.index'));
        $incomingRequests->assertOk();
        $incomingRequests->assertSee('<details class="service-menu" open>', false);
        $incomingRequests->assertSee('<summary class="nav-item service-menu-toggle">', false);
        foreach ($menuItems as $menuItem) {
            $incomingRequests->assertSee($menuItem);
        }

        $allData = $this->get(route('pendaftaran.index'));
        $allData->assertOk();
        $allData->assertSee('<a href="'.route('pendaftaran.index').'" class="nav-item active" aria-current="page">', false);
        $allData->assertSee('<details class="service-menu" open>', false);
        foreach ($menuItems as $menuItem) {
            $allData->assertSee($menuItem);
        }
    }

    public function test_user_can_update_their_profile_and_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Petugas Lama',
            'email' => 'petugas-lama@example.test',
            'nik' => '3200000000000010',
            'phone' => '081234567890',
            'password' => 'KataSandi@123',
            'role' => 'petugas',
        ]);

        $this->actingAs($user)
            ->get(route('account.profile'))
            ->assertOk()
            ->assertSee('Profil Saya')
            ->assertSee('Pengaturan')
            ->assertDontSee('name="name"', false);

        $this->get(route('account.profile.data'))->assertOk()->assertSee('Ubah Data Profil');
        $this->get(route('account.profile.password'))->assertOk()->assertSee('Ubah Password');
        $this->get(route('account.profile.settings'))
            ->assertOk()
            ->assertSee('Preferensi Tampilan')
            ->assertSee('Keamanan Akun')
            ->assertSee('Khaki');

        $this->put(route('account.profile.data.update'), [
            'name' => 'Petugas Baru',
            'email' => 'petugas-baru@example.test',
            'nik' => '3200000000000011',
            'phone' => '081234567891',
        ])->assertRedirect(route('account.profile'));

        $this->put(route('account.profile.password.update'), [
            'current_password' => 'KataSandi@123',
            'password' => 'KataSandiBaru@123',
            'password_confirmation' => 'KataSandiBaru@123',
        ])
            ->assertRedirect(route('account.profile'))
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('Petugas Baru', $user->name);
        $this->assertSame('petugas-baru@example.test', $user->email);
        $this->assertSame('3200000000000011', $user->nik);
        $this->assertSame('petugas', $user->role);
        $this->assertTrue(password_verify('KataSandiBaru@123', $user->password));
    }

    public function test_petugas_can_create_masyarakat_or_petugas_accounts(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $this->actingAs($petugas)
            ->get(route('account.users.create'))
            ->assertOk()
            ->assertSee('Buat Akun Baru')
            ->assertSee('Masyarakat')
            ->assertSee('Petugas');

        $this->post(route('account.users.store'), [
            'name' => 'Warga Baru',
            'nik' => '3200000000000012',
            'phone' => '081234567892',
            'email' => 'warga-baru@example.test',
            'role' => 'masyarakat',
            'password' => 'KataSandi@123',
            'password_confirmation' => 'KataSandi@123',
        ])
            ->assertRedirect(route('account.users.create'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'warga-baru@example.test',
            'role' => 'masyarakat',
        ]);

        $this->from(route('account.users.create'))
            ->post(route('account.users.store'), [
                'name' => 'Camat Baru',
                'nik' => '3200000000000013',
                'phone' => '081234567893',
                'email' => 'camat-baru@example.test',
                'role' => 'camat',
                'password' => 'KataSandi@123',
                'password_confirmation' => 'KataSandi@123',
            ])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'camat-baru@example.test']);
    }

    public function test_non_petugas_cannot_open_create_account_page(): void
    {
        $camat = User::factory()->create(['role' => 'camat']);

        $this->actingAs($camat)
            ->get(route('account.users.create'))
            ->assertForbidden();
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
