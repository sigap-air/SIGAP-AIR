<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom koordinat GPS ke tabel pengaduan.
     * Digunakan oleh fitur Peta Interaktif Leaflet + GPS Geolocation.
     *
     * TANGGUNG JAWAB: Arthur Budi Maharesi
     */
    public function up(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('lokasi');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
