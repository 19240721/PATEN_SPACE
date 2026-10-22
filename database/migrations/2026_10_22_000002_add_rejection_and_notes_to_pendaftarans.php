<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->text('alasan_penolakan')->nullable()->after('status');
            $table->text('catatan_petugas')->nullable()->after('alasan_penolakan');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn(['alasan_penolakan', 'catatan_petugas']);
        });
    }
};
