<?php

/**
 * Migrasi penyesuaian kolom tabel assignment.
 *
 * Kolom-kolom di migrasi awal menggunakan nama yang berbeda dari
 * yang dipakai di model/controller. Migrasi ini menyeragamkan:
 *  - assigned_by      → supervisor_id
 *  - catatan_petugas  → catatan_penanganan
 *  - foto_penanganan  → foto_hasil
 *  - timestamp_mulai  → (tidak dipakai, tetap)
 *  - timestamp_selesai → tanggal_selesai
 *  - enum sedang_diproses → diproses
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{Schema, DB};

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignment', function (Blueprint $table) {
            $table->renameColumn('assigned_by', 'supervisor_id');
            $table->renameColumn('catatan_petugas', 'catatan_penanganan');
            $table->renameColumn('foto_penanganan', 'foto_hasil');
            $table->renameColumn('timestamp_selesai', 'tanggal_selesai');
        });

        // Update enum value: sedang_diproses → diproses
        // MySQL requires ALTER to change enum values
        DB::statement("ALTER TABLE assignment MODIFY COLUMN status_assignment ENUM('ditugaskan', 'diproses', 'selesai') DEFAULT 'ditugaskan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE assignment MODIFY COLUMN status_assignment ENUM('ditugaskan', 'sedang_diproses', 'selesai') DEFAULT 'ditugaskan'");

        Schema::table('assignment', function (Blueprint $table) {
            $table->renameColumn('supervisor_id', 'assigned_by');
            $table->renameColumn('catatan_penanganan', 'catatan_petugas');
            $table->renameColumn('foto_hasil', 'foto_penanganan');
            $table->renameColumn('tanggal_selesai', 'timestamp_selesai');
        });
    }
};
