<?php
// TANGGUNG JAWAB: Arthur Budi Maharesi (PBI-03) — Pivot zona ↔ petugas
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('zona_petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_id')->constrained()->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['zona_id', 'petugas_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('zona_petugas'); }
};
