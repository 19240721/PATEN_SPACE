<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'nama_kepala_keluarga' => static fn (Blueprint $table) => $table->string('nama_kepala_keluarga')->nullable(),
            'jenis_kelamin' => static fn (Blueprint $table) => $table->string('jenis_kelamin')->nullable(),
            'jalan' => static fn (Blueprint $table) => $table->string('jalan')->nullable(),
            'rt' => static fn (Blueprint $table) => $table->string('rt')->nullable(),
            'rw' => static fn (Blueprint $table) => $table->string('rw')->nullable(),
            'desa' => static fn (Blueprint $table) => $table->string('desa')->nullable(),
            'kecamatan' => static fn (Blueprint $table) => $table->string('kecamatan')->nullable(),
            'bulan' => static fn (Blueprint $table) => $table->string('bulan')->nullable(),
            'minggu_ke' => static fn (Blueprint $table) => $table->string('minggu_ke')->nullable(),
            'tanggal_kedatangan' => static fn (Blueprint $table) => $table->date('tanggal_kedatangan')->nullable(),
            'alamat_asal' => static fn (Blueprint $table) => $table->string('alamat_asal')->nullable(),
            'alamat_tujuan' => static fn (Blueprint $table) => $table->string('alamat_tujuan')->nullable(),
            'keterangan' => static fn (Blueprint $table) => $table->text('keterangan')->nullable(),
            'nomor_kk' => static fn (Blueprint $table) => $table->string('nomor_kk')->nullable(),
            'nama_kk' => static fn (Blueprint $table) => $table->string('nama_kk')->nullable(),
            'dusun' => static fn (Blueprint $table) => $table->string('dusun')->nullable(),
            'hubungan' => static fn (Blueprint $table) => $table->string('hubungan')->nullable(),
            'kepala_keluarga' => static fn (Blueprint $table) => $table->string('kepala_keluarga')->nullable(),
            'jumlah_anggota' => static fn (Blueprint $table) => $table->string('jumlah_anggota')->nullable(),
            'nama_anak' => static fn (Blueprint $table) => $table->string('nama_anak')->nullable(),
            'jenis_kelamin_kk' => static fn (Blueprint $table) => $table->string('jenis_kelamin_kk')->nullable(),
            'nik_anak' => static fn (Blueprint $table) => $table->string('nik_anak')->nullable(),
            'keterangan_kk' => static fn (Blueprint $table) => $table->string('keterangan_kk')->nullable(),
            'nama_alm' => static fn (Blueprint $table) => $table->string('nama_alm')->nullable(),
            'nama_ayah' => static fn (Blueprint $table) => $table->string('nama_ayah')->nullable(),
            'nama_ibu' => static fn (Blueprint $table) => $table->string('nama_ibu')->nullable(),
            'tempat_meninggal' => static fn (Blueprint $table) => $table->string('tempat_meninggal')->nullable(),
            'tanggal_meninggal' => static fn (Blueprint $table) => $table->date('tanggal_meninggal')->nullable(),
            'keterangan_kematian' => static fn (Blueprint $table) => $table->string('keterangan_kematian')->nullable(),
            'nama_anak_lahir' => static fn (Blueprint $table) => $table->string('nama_anak_lahir')->nullable(),
            'nama_ayah_lahir' => static fn (Blueprint $table) => $table->string('nama_ayah_lahir')->nullable(),
            'nama_ibu_lahir' => static fn (Blueprint $table) => $table->string('nama_ibu_lahir')->nullable(),
            'berat_badan' => static fn (Blueprint $table) => $table->string('berat_badan')->nullable(),
            'panjang_badan' => static fn (Blueprint $table) => $table->string('panjang_badan')->nullable(),
            'tempat_lahir_detail' => static fn (Blueprint $table) => $table->string('tempat_lahir_detail')->nullable(),
            'nama_kedatangan' => static fn (Blueprint $table) => $table->string('nama_kedatangan')->nullable(),
            'nik_kedatangan' => static fn (Blueprint $table) => $table->string('nik_kedatangan')->nullable(),
            'alamat_asal_kedatangan' => static fn (Blueprint $table) => $table->string('alamat_asal_kedatangan')->nullable(),
            'alamat_tujuan_kedatangan' => static fn (Blueprint $table) => $table->string('alamat_tujuan_kedatangan')->nullable(),
            'keterangan_kedatangan' => static fn (Blueprint $table) => $table->string('keterangan_kedatangan')->nullable(),
            'nama_pindah' => static fn (Blueprint $table) => $table->string('nama_pindah')->nullable(),
            'nik_pindah' => static fn (Blueprint $table) => $table->string('nik_pindah')->nullable(),
            'alamat_asal_pindah' => static fn (Blueprint $table) => $table->string('alamat_asal_pindah')->nullable(),
            'alamat_tujuan_pindah' => static fn (Blueprint $table) => $table->string('alamat_tujuan_pindah')->nullable(),
            'alasan_pindah' => static fn (Blueprint $table) => $table->string('alasan_pindah')->nullable(),
            'alasan_permohonan' => static fn (Blueprint $table) => $table->string('alasan_permohonan')->nullable(),
            'alasan_pembaruan' => static fn (Blueprint $table) => $table->string('alasan_pembaruan')->nullable(),
        ];

        $missingColumns = [];
        foreach ($columns as $name => $addColumn) {
            if (!Schema::hasColumn('pendaftarans', $name)) {
                $missingColumns[] = $addColumn;
            }
        }

        if ($missingColumns !== []) {
            Schema::table('pendaftarans', function (Blueprint $table) use ($missingColumns): void {
                foreach ($missingColumns as $addColumn) {
                    $addColumn($table);
                }
            });
        }
    }

    public function down(): void
    {
    }
};