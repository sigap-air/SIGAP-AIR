<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PBI-03 Fix: Jadikan kolom zona_id pada tabel petugas bersifat nullable.
 *
 * Alasan: PBI-03 mewajibkan fitur "lepas petugas dari zona" (removePetugas)
 * yang men-set zona_id = null. Migration awal (000003) membuat kolom ini
 * NOT NULL + FK constraint, sehingga operasi tersebut akan gagal di DB level.
 *
 * CATATAN: Relasi FK ke zona_wilayah tetap dipertahankan (RESTRICT on delete),
 * hanya sifatnya yang diubah menjadi nullable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('petugas', function (Blueprint $table) {
            // Drop FK lama dulu (nama constraint: petugas_zona_id_foreign)
            $table->dropForeign(['zona_id']);

            // Ubah kolom menjadi nullable
            $table->foreignId('zona_id')
                ->nullable()
                ->change();

            // Tambah kembali FK constraint (nullable, restrict on delete)
            $table->foreign('zona_id')
                ->references('id')
                ->on('zona_wilayah')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->dropForeign(['zona_id']);

            $table->foreignId('zona_id')
                ->nullable(false)
                ->change();

            $table->foreign('zona_id')
                ->references('id')
                ->on('zona_wilayah')
                ->cascadeOnDelete();
        });
    }
};
