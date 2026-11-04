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
            $table->string('nik_pelapor', 16)->nullable()->after('keterangan_kematian');
            $table->string('nama_pelapor')->nullable()->after('nik_pelapor');
            $table->string('nik_ayah', 16)->nullable()->after('nama_pelapor');
            $table->string('nik_ibu', 16)->nullable()->after('nik_ayah');
            $table->string('nik_saksi1', 16)->nullable()->after('nik_ibu');
            $table->string('nama_saksi1')->nullable()->after('nik_saksi1');
            $table->string('nik_saksi2', 16)->nullable()->after('nama_saksi1');
            $table->string('nama_saksi2')->nullable()->after('nik_saksi2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn([
                'nik_pelapor',
                'nama_pelapor',
                'nik_ayah',
                'nik_ibu',
                'nik_saksi1',
                'nama_saksi1',
                'nik_saksi2',
                'nama_saksi2',
            ]);
        });
    }
};

