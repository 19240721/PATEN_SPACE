<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Notifications\StatusPermohonanNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasyarakatStatusNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_masyarakat_receives_a_popup_for_submission_and_each_status_change(): void
    {
        $citizen = User::factory()->create([
            'name' => 'Warga Jatisari',
            'nik' => '3200000000000091',
            'role' => 'masyarakat',
        ]);

        $response = $this->actingAs($citizen)->post(route('masyarakat.permohonan.store'), [
            'nama_lengkap' => $citizen->name,
            'nik' => $citizen->nik,
            'alamat' => 'Jatisari, Karawang',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => now()->toDateString(),
        ]);

        $application = Pendaftaran::where('user_id', $citizen->id)->firstOrFail();
        $response->assertRedirect(route('masyarakat.permohonan.success', $application));
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $citizen->id,
            'type' => StatusPermohonanNotification::class,
        ]);

        $this->get(route('masyarakat.permohonan.success', $application))
            ->assertOk()
            ->assertSee('Pengajuan Diterima')
            ->assertSee('Pembaruan Permohonan');

        $staff = User::factory()->create(['role' => 'petugas']);
        $statusUpdates = [
            ['status' => 'Proses'],
            ['status' => 'Selesai'],
            ['status' => 'Ditolak', 'alasan_penolakan' => 'Dokumen belum lengkap.'],
        ];

        foreach ($statusUpdates as $update) {
            $this->travel(1)->seconds();
            $this->actingAs($staff)
                ->patch(route('petugas.permohonan.status', $application), $update)
                ->assertRedirect(route('petugas.permohonan.show', $application));
        }

        $this->assertSame(4, $citizen->notifications()->where('type', StatusPermohonanNotification::class)->count());

        $expectedTitles = [
            'Pengajuan Ditolak',
            'Pengajuan Selesai',
            'Pengajuan Sedang Diproses',
            'Pengajuan Diterima',
        ];

        foreach ($expectedTitles as $title) {
            $notification = $citizen->unreadNotifications()
                ->where('type', StatusPermohonanNotification::class)
                ->latest()
                ->firstOrFail();

            $this->actingAs($citizen)
                ->get(route('masyarakat.dashboard'))
                ->assertOk()
                ->assertSee($title)
                ->assertSee('Pembaruan Permohonan');

            $this->post(route('masyarakat.notifikasi.read', $notification->id))
                ->assertRedirect();

            $notification->refresh();
            $this->assertNotNull($notification->read_at);
        }

        $this->assertSame(0, $citizen->unreadNotifications()->where('type', StatusPermohonanNotification::class)->count());
    }

    public function test_masyarakat_cannot_mark_another_users_status_notification_as_read(): void
    {
        $owner = User::factory()->create(['role' => 'masyarakat']);
        $otherCitizen = User::factory()->create(['role' => 'masyarakat']);
        $application = Pendaftaran::create([
            'nama_lengkap' => $owner->name,
            'nik' => '3200000000000092',
            'alamat' => 'Jatisari, Karawang',
            'jenis_layanan' => 'KK',
            'tanggal_daftar' => now()->toDateString(),
            'status' => 'Baru',
            'user_id' => $owner->id,
            'intake_source' => 'masyarakat',
        ]);
        $owner->notify(new StatusPermohonanNotification($application));
        $notification = $owner->notifications()->firstOrFail();

        $this->actingAs($otherCitizen)
            ->post(route('masyarakat.notifikasi.read', $notification->id))
            ->assertNotFound();

        $this->assertNull($notification->fresh()->read_at);
    }
}
