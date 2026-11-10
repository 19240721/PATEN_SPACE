<?php

namespace App\Notifications;

use App\Models\Pendaftaran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusPermohonanNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Pendaftaran $pendaftaran)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public static function supportsStatus(?string $status): bool
    {
        return in_array($status, ['Baru', 'Proses', 'Selesai', 'Ditolak'], true);
    }

    public function toDatabase(object $notifiable): array
    {
        [$title, $message] = match ($this->pendaftaran->status) {
            'Baru' => [
                'Pengajuan Diterima',
                'Pengajuan ' . $this->pendaftaran->jenis_layanan . ' Anda telah diterima dan menunggu verifikasi petugas.',
            ],
            'Proses' => [
                'Pengajuan Sedang Diproses',
                'Pengajuan ' . $this->pendaftaran->jenis_layanan . ' Anda sedang diproses oleh petugas.',
            ],
            'Selesai' => [
                'Pengajuan Selesai',
                'Pengajuan ' . $this->pendaftaran->jenis_layanan . ' Anda telah selesai. Silakan cek detail pengajuan Anda.',
            ],
            'Ditolak' => [
                'Pengajuan Ditolak',
                'Pengajuan ' . $this->pendaftaran->jenis_layanan . ' Anda ditolak. Silakan cek alasan penolakan.',
            ],
        };

        return [
            'title' => $title,
            'message' => $message,
            'status' => $this->pendaftaran->status,
            'jenis_layanan' => $this->pendaftaran->jenis_layanan,
            'pendaftaran_id' => $this->pendaftaran->id,
            'alasan_penolakan' => $this->pendaftaran->alasan_penolakan,
            'catatan_petugas' => $this->pendaftaran->catatan_petugas,
        ];
    }
}
