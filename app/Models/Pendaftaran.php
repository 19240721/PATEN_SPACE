<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftarans';

    protected $fillable = [
        'nama_lengkap',
        'nama_kepala_keluarga',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'jalan',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'jenis_layanan',
        'no_telepon',
        'petugas',
        'bulan',
        'minggu_ke',
        'tanggal_kedatangan',
        'alamat_asal',
        'alamat_tujuan',
        'keterangan',
        'nomor_kk',
        'nama_kk',
        'dusun',
        'hubungan',
        'kepala_keluarga',
        'jumlah_anggota',
        'nama_anak',
        'jenis_kelamin_kk',
        'nik_anak',
        'keterangan_kk',
        'anggota_kk',
        'perubahan_kk',
        'nama_alm',
        'nama_ayah',
        'nama_ibu',
        'tempat_meninggal',
        'tanggal_meninggal',
        'keterangan_kematian',
        'sptjm_kematian',
        'nik_jenazah',
        'tempat_lahir_jenazah',
        'tanggal_lahir_jenazah',
        'alamat_jenazah',
        'penyebab_kematian',
        'yang_menyatakan',
        'nik_yang_menyatakan',
        'hubungan_yang_menyatakan',
        'nik_pelapor',
        'nama_pelapor',
        'nik_ayah',
        'nik_ibu',
        'nik_saksi1',
        'nama_saksi1',
        'nik_saksi2',
        'nama_saksi2',
        'nama_anak_lahir',
        'nama_ayah_lahir',
        'nama_ibu_lahir',
        'berat_badan',
        'panjang_badan',
        'tempat_lahir_detail',
        'nama_kedatangan',
        'nik_kedatangan',
        'alamat_asal_kedatangan',
        'alamat_tujuan_kedatangan',
        'keterangan_kedatangan',
        'nama_pindah',
        'nik_pindah',
        'alamat_asal_pindah',
        'alamat_tujuan_pindah',
        'alasan_pindah',
        'alasan_permohonan',
        'alasan_pembaruan',
        'tanggal_daftar',
        'status',
        'user_id',
        'documents',
        'intake_source',
        'alasan_penolakan',
        'catatan_petugas',
    ];

    protected function casts(): array
    {
        return [
            'sptjm_kematian' => 'boolean',
            'documents' => 'array',
            'anggota_kk' => 'array',
            'perubahan_kk' => 'array',
            'tanggal_daftar' => 'date',
        ];
    }

    public function aktivitasPelayanans()
    {
        return $this->hasMany(AktivitasPelayanan::class);
    }

    protected static function booted(): void
    {
        static::created(function (Pendaftaran $pendaftaran) {
            try {
                if (auth()->check()) {
                    $user = auth()->user();
                    $rawRole = strtolower($user->getRawOriginal('role') ?? '');
                    $role = strtolower($user->role ?? '');
                    if (!in_array($rawRole, ['masyarakat', 'camat'], true) && !in_array($role, ['masyarakat', 'camat'], true)) {
                        AktivitasPelayanan::catat(
                            aktivitas: 'Pendaftaran ' . ($pendaftaran->jenis_layanan ?? 'Pelayanan'),
                            jenisLayanan: $pendaftaran->jenis_layanan ?? 'Pelayanan',
                            deskripsi: 'Menambahkan berkas pendaftaran baru pemohon ' . ($pendaftaran->nama_lengkap ?? '-'),
                            pendaftaran: $pendaftaran,
                            status: 'Berhasil',
                            user: $user
                        );
                        return;
                    }
                }

                if (!empty($pendaftaran->petugas) && ($pendaftaran->intake_source === 'petugas' || empty($pendaftaran->intake_source))) {
                    AktivitasPelayanan::catatFallback(
                        namaPetugas: $pendaftaran->petugas,
                        aktivitas: 'Pendaftaran ' . ($pendaftaran->jenis_layanan ?? 'Pelayanan'),
                        jenisLayanan: $pendaftaran->jenis_layanan ?? 'Pelayanan',
                        deskripsi: 'Menambahkan berkas pendaftaran baru pemohon ' . ($pendaftaran->nama_lengkap ?? '-'),
                        pendaftaran: $pendaftaran,
                        status: 'Berhasil'
                    );
                }
            } catch (\Throwable $e) {
                // Cegah kegagalan flow utama jika tabel belum dimigrasi
            }
        });
    }
}
