<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->boolean('sptjm_kematian')->default(false)->after('keterangan_kematian');
            $table->string('nik_jenazah', 20)->nullable()->after('sptjm_kematian');
            $table->string('tempat_lahir_jenazah')->nullable()->after('nik_jenazah');
            $table->string('tanggal_lahir_jenazah')->nullable()->after('tempat_lahir_jenazah');
            $table->text('alamat_jenazah')->nullable()->after('tanggal_lahir_jenazah');
            $table->string('penyebab_kematian')->nullable()->after('alamat_jenazah');
            $table->string('yang_menyatakan')->nullable()->after('penyebab_kematian');
            $table->string('nik_yang_menyatakan', 20)->nullable()->after('yang_menyatakan');
            $table->string('hubungan_yang_menyatakan')->nullable()->after('nik_yang_menyatakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn([
                'sptjm_kematian',
                'nik_jenazah',
                'tempat_lahir_jenazah',
                'tanggal_lahir_jenazah',
                'alamat_jenazah',
                'penyebab_kematian',
                'yang_menyatakan',
                'nik_yang_menyatakan',
                'hubungan_yang_menyatakan',
            ]);
        });
    }
};
