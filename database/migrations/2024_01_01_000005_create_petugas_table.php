<?php
// TANGGUNG JAWAB: Farisha Huwaida Shofha (PBI-17)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nomor_pegawai')->unique();
            $table->enum('status_ketersediaan', ['tersedia', 'sibuk', 'tidak_aktif'])->default('tersedia');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('petugas'); }
};
