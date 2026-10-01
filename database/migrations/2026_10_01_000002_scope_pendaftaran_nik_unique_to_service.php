<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table): void {
            $table->dropUnique('pendaftarans_nik_unique');
            $table->unique(['nik', 'jenis_layanan'], 'pendaftarans_nik_service_unique');
        });
    }

    public function down(): void
    {
        $hasCrossServiceDuplicates = DB::table('pendaftarans')
            ->select('nik')
            ->groupBy('nik')
            ->havingRaw('COUNT(DISTINCT jenis_layanan) > 1')
            ->exists();

        if ($hasCrossServiceDuplicates) {
            throw new RuntimeException('Cannot restore global NIK uniqueness while a NIK is used by multiple service types.');
        }

        Schema::table('pendaftarans', function (Blueprint $table): void {
            $table->dropUnique('pendaftarans_nik_service_unique');
            $table->unique('nik');
        });
    }
};