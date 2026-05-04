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
        if (! Schema::hasColumn('pengaduan', 'tanggal_pengajuan')) {
            Schema::table('pengaduan', function (Blueprint $table) {
                // Kolom ini ada di $fillable Pengaduan model tapi terlewat di migration awal
                $table->timestamp('tanggal_pengajuan')->nullable()->after('alasan_penolakan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pengaduan', 'tanggal_pengajuan')) {
            Schema::table('pengaduan', function (Blueprint $table) {
                $table->dropColumn('tanggal_pengajuan');
            });
        }
    }
};
