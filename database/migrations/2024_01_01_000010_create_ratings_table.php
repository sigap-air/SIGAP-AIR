<?php
// TANGGUNG JAWAB: Amanda Zuhra Azis (PBI-11)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->tinyInteger('bintang'); // 1-5
            $table->text('komentar')->nullable();
            $table->timestamp('tanggal_rating')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('ratings'); }
};
