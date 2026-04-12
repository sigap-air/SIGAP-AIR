<?php
// TANGGUNG JAWAB: Sanitra Savitri (PBI-04)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket')->unique(); // SIGAP-YYYYMMDD-XXXX
            $table->foreignId('user_id')->constrained();  // Pelapor
            $table->foreignId('kategori_id')->constrained();
            $table->foreignId('zona_id')->constrained();
            $table->text('lokasi');
            $table->text('deskripsi');
            $table->string('foto_bukti')->nullable();
            $table->enum('status', [
                'menunggu_verifikasi',
                'disetujui',
                'ditolak',
                'ditugaskan',
                'diproses',
                'selesai'
            ])->default('menunggu_verifikasi');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pengaduans'); }
};
