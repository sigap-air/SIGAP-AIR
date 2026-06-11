<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('officer_zone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained('petugas')->cascadeOnDelete();
            $table->foreignId('zone_id')->constrained('zona_wilayah')->cascadeOnDelete();
            $table->timestamps();
        });

        // Pindahkan data mapping petugas.zona_id ke tabel pivot officer_zone
        $petugasWithZones = DB::table('petugas')->whereNotNull('zona_id')->get();
        foreach ($petugasWithZones as $p) {
            DB::table('officer_zone')->insert([
                'officer_id' => $p->id,
                'zone_id' => $p->zona_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('officer_zone');
    }
};
