<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip', 35)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('users', 'alamat_kantor')) {
                $table->string('alamat_kantor', 255)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'foto_profil')) {
                $table->string('foto_profil', 255)->nullable()->after('alamat_kantor');
            }
        });

        // Sinkronisasi data resmi Camat Jatisari pada akun Camat yang sudah ada
        DB::table('users')
            ->where('role', 'camat')
            ->orWhere('email', 'camat@patenspace.test')
            ->update([
                'name' => 'H. Surisno, SKM., MAP.',
                'nip' => '19690516 198903 1 003',
                'alamat_kantor' => 'Jl. Raya Jatisari No. 1, Jatisari, Kec. Jatisari, Kabupaten Karawang, Jawa Barat 41374',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('users', 'foto_profil')) {
                $columnsToDrop[] = 'foto_profil';
            }
            if (Schema::hasColumn('users', 'alamat_kantor')) {
                $columnsToDrop[] = 'alamat_kantor';
            }
            if (Schema::hasColumn('users', 'nip')) {
                $columnsToDrop[] = 'nip';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
