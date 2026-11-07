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
        Schema::create('aktivitas_pelayanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_petugas');
            $table->string('peran')->default('Petugas'); // 'Operator' atau 'Petugas'
            $table->foreignId('pendaftaran_id')->nullable()->constrained('pendaftarans')->nullOnDelete();
            $table->string('jenis_layanan')->nullable();
            $table->string('aktivitas');
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('Berhasil');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['nama_petugas', 'created_at']);
            $table->index(['peran', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_pelayanans');
    }
};
