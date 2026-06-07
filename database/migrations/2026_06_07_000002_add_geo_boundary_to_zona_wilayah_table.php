<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom geo_boundary (GeoJSON Polygon) ke tabel zona_wilayah.
     * Digunakan untuk validasi spasial point-in-polygon dan visualisasi peta.
     *
     * TANGGUNG JAWAB: Arthur Budi Maharesi
     */
    public function up(): void
    {
        Schema::table('zona_wilayah', function (Blueprint $table) {
            // Menyimpan GeoJSON Polygon koordinat batas wilayah zona
            // Format: { "type": "Polygon", "coordinates": [[[lng, lat], ...]] }
            $table->json('geo_boundary')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('zona_wilayah', function (Blueprint $table) {
            $table->dropColumn('geo_boundary');
        });
    }
};
