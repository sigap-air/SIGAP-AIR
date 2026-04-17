<?php
// TANGGUNG JAWAB: Arthur Budi Maharesi (PBI-01)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('zona_id')->constrained('zonas');
            $table->string('nama_pelanggan', 255);
            $table->text('alamat');
            $table->string('nomor_sambungan', 50)->unique();
            $table->string('no_telepon', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    
    public function down(): void { 
        Schema::dropIfExists('pelanggans'); 
    }
};
