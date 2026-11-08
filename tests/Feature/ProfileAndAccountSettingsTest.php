<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileAndAccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Test Tombol Logout merah tidak ada di sidebar Camat & Petugas
     */
    public function test_red_logout_button_is_removed_from_sidebar(): void
    {
        $camat = User::factory()->create(['role' => 'camat', 'name' => 'H. Surisno, SKM., MAP.']);
        $this->actingAs($camat)
            ->get(route('camat.dashboard'))
            ->assertOk()
            ->assertDontSee('logout-nav-item')
            ->assertSee(route('camat.profil'));

        $petugas = User::factory()->create(['role' => 'petugas', 'name' => 'Petugas Loket']);
        $this->actingAs($petugas)
            ->get(route('petugas.dashboard'))
            ->assertOk()
            ->assertDontSee('logout-btn')
            ->assertSee(route('profil'));
    }

    /**
     * 2. Test Halaman Profil menampilkan data sesuai Role
     */
    public function test_profile_page_displays_identity_by_role(): void
    {
        // Role Camat redirect ke /camat/profil
        $camat = User::factory()->create([
            'role' => 'camat',
            'name' => 'H. Surisno, SKM., MAP.',
            'nip' => '19690516 198903 1 003',
            'phone' => '081200000001',
            'alamat_kantor' => 'Jl. Raya Jatisari No. 1, Jatisari, Karawang',
        ]);
        $this->actingAs($camat)
            ->get(route('profil'))
            ->assertRedirect(route('camat.profil'));

        $this->actingAs($camat)
            ->get(route('camat.profil'))
            ->assertOk()
            ->assertSee('H. Surisno, SKM., MAP.')
            ->assertSee('19690516 198903 1 003')
            ->assertSee('Pimpinan / Camat')
            ->assertSee('Kecamatan Jatisari')
            ->assertSee('Kabupaten Karawang');

        // Role Operator
        $operator = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Operator PATEN',
            'email' => 'operator@patenspace.test',
            'phone' => '081234567890',
        ]);
        $this->actingAs($operator)
            ->get(route('profil'))
            ->assertOk()
            ->assertSee('Operator PATEN')
            ->assertSee('Operator Kecamatan')
            ->assertSee('Pemerintah Kabupaten Karawang')
            ->assertSee('Kecamatan Jatisari');

        // Role Petugas
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Petugas Loket PATEN',
            'email' => 'petugas@patenspace.test',
            'phone' => '081298765432',
        ]);
        $this->actingAs($petugas)
            ->get(route('profil'))
            ->assertOk()
            ->assertSee('Petugas Loket PATEN')
            ->assertSee('Petugas Loket')
            ->assertSee('Kecamatan Jatisari');

        // Role Masyarakat
        $warga = User::factory()->create([
            'role' => 'masyarakat',
            'name' => 'Warga Jatisari',
            'nik' => '3215000000000001',
            'email' => 'warga@patenspace.test',
            'phone' => '081211112222',
        ]);
        $this->actingAs($warga)
            ->get(route('profil'))
            ->assertOk()
            ->assertSee('Warga Jatisari')
            ->assertSee('3215000000000001')
            ->assertSee('Masyarakat');
    }

    /**
     * 3. Test Pengaturan Akun menyediakan menu lengkap termasuk Kembali ke Profil dan Logout konfirmasi
     */
    public function test_account_settings_menus_and_confirmation(): void
    {
        $user = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Petugas Uji',
        ]);

        $this->actingAs($user)
            ->get(route('account.profile'))
            ->assertOk()
            ->assertSee('Pengaturan Akun')
            ->assertSee(route('account.profile.data'))
            ->assertSee(route('account.profile.password'))
            ->assertSee(route('account.profile.settings'))
            ->assertSee(route('profil'))
            ->assertSee(route('logout'))
            ->assertSee('Apakah Anda yakin ingin keluar dari akun PATEN SPACE?');
    }

    /**
     * 4. Test Pembaruan Email, Nomor Telepon, dan Foto Profil tersimpan dengan benar
     */
    public function test_user_can_update_email_phone_and_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Petugas Akun',
            'email' => 'petugas.lama@patenspace.test',
            'phone' => '081200001111',
        ]);

        $fakePhoto = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        $response = $this->actingAs($user)
            ->put(route('account.profile.data.update'), [
                'email' => 'petugas.baru@patenspace.test',
                'phone' => '081299998888',
                'foto_profil' => $fakePhoto,
            ]);

        $response->assertRedirect(route('account.profile'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('petugas.baru@patenspace.test', $user->email);
        $this->assertSame('081299998888', $user->phone);
        $this->assertNotNull($user->foto_profil);
        Storage::disk('public')->assertExists($user->foto_profil);
    }

    /**
     * 5. Test Logout mengakhiri session dan redirect ke login
     */
    public function test_logout_ends_session_and_redirects_to_login(): void
    {
        $user = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($user)->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        // Cek halaman terproteksi tidak bisa diakses
        $this->get(route('profil'))->assertRedirect(route('login'));
        $this->get(route('account.profile'))->assertRedirect(route('login'));
    }
}
