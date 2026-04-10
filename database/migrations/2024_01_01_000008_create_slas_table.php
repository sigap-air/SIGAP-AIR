<?php
// TANGGUNG JAWAB: Falah Adhi Chandra (PBI-09)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('slas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained()->cascadeOnDelete();
            $table->timestamp('deadline');
            $table->boolean('is_overdue')->default(false);
            $table->boolean('is_fulfilled')->default(false);
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('slas'); }
};
