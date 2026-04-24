<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('kategori_id')->constrained('kategori_pengaduan');
            $table->foreignId('zona_id')->constrained('zona_wilayah');
            $table->text('lokasi');
            $table->text('deskripsi');
            $table->string('foto_bukti')->nullable();
            $table->enum('status', ['menunggu_verifikasi', 'ditolak', 'disetujui', 'ditugaskan', 'sedang_diproses', 'selesai'])->default('menunggu_verifikasi');
            $table->text('alasan_penolakan')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            // FIX ERR-4: tanggal_pengajuan ada di $fillable model tapi tidak ada di migration
            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->timestamps();

            $table->index('status', 'idx_status');
            $table->index('nomor_tiket', 'idx_nomor_tiket');
            $table->index('user_id', 'idx_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
