<?php
// TANGGUNG JAWAB: Sanitra Savitri (PBI-06)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained();
            $table->foreignId('petugas_id')->constrained();
            $table->foreignId('supervisor_id')->constrained('users');
            $table->text('instruksi')->nullable();
            $table->timestamp('jadwal_penanganan')->nullable();
            $table->enum('status_assignment', ['ditugaskan', 'diproses', 'selesai'])->default('ditugaskan');
            $table->text('catatan_penanganan')->nullable();
            $table->string('foto_hasil')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assignments'); }
};
