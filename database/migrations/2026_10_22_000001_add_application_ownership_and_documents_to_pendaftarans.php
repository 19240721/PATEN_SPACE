<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table): void {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->json('documents')->nullable()->after('status');
            $table->string('intake_source')->default('petugas')->after('documents');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['documents', 'intake_source']);
        });
    }
};
