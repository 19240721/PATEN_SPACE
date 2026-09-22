<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nama_kepala_keluarga')->nullable();
            $table->string('nik')->unique();
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat');
            $table->string('jalan')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('jenis_layanan');
            $table->string('no_telepon')->nullable();
            $table->string('petugas')->nullable();
            $table->string('bulan')->nullable();
            $table->string('minggu_ke')->nullable();
            $table->date('tanggal_kedatangan')->nullable();
            $table->string('alamat_asal')->nullable();
            $table->string('alamat_tujuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('nomor_kk')->nullable();
            $table->string('nama_kk')->nullable();
            $table->string('dusun')->nullable();
            $table->string('hubungan')->nullable();
            $table->string('kepala_keluarga')->nullable();
            $table->string('jumlah_anggota')->nullable();
            $table->string('nama_anak')->nullable();
            $table->string('jenis_kelamin_kk')->nullable();
            $table->string('nik_anak')->nullable();
            $table->string('keterangan_kk')->nullable();
            $table->string('nama_alm')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('tempat_meninggal')->nullable();
            $table->date('tanggal_meninggal')->nullable();
            $table->string('keterangan_kematian')->nullable();
            $table->string('nama_anak_lahir')->nullable();
            $table->string('nama_ayah_lahir')->nullable();
            $table->string('nama_ibu_lahir')->nullable();
            $table->string('berat_badan')->nullable();
            $table->string('panjang_badan')->nullable();
            $table->string('tempat_lahir_detail')->nullable();
            $table->string('nama_kedatangan')->nullable();
            $table->string('nik_kedatangan')->nullable();
            $table->string('alamat_asal_kedatangan')->nullable();
            $table->string('alamat_tujuan_kedatangan')->nullable();
            $table->string('keterangan_kedatangan')->nullable();
            $table->string('nama_pindah')->nullable();
            $table->string('nik_pindah')->nullable();
            $table->string('alamat_asal_pindah')->nullable();
            $table->string('alamat_tujuan_pindah')->nullable();
            $table->string('alasan_pindah')->nullable();
            $table->date('tanggal_daftar');
            $table->string('status')->default('Baru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
