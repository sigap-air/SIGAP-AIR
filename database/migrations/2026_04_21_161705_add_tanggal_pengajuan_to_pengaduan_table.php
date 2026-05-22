<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('pengaduan', 'tanggal_pengajuan')) {
            Schema::table('pengaduan', function (Blueprint $table) {
                $table->timestamp('tanggal_pengajuan')->nullable()->after('alasan_penolakan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pengaduan', 'tanggal_pengajuan')) {
            Schema::table('pengaduan', function (Blueprint $table) {
                $table->dropColumn('tanggal_pengajuan');
            });
        }
    }
};