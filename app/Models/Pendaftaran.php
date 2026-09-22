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
        'nama_alm',
        'nama_ayah',
        'nama_ibu',
        'tempat_meninggal',
        'tanggal_meninggal',
        'keterangan_kematian',
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
    ];
}
