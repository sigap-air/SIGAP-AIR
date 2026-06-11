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
        // Gunakan raw SQL karena renameColumn() butuh MariaDB 10.5.2+ atau doctrine/dbal
        DB::statement("ALTER TABLE `assignment`
            CHANGE `assigned_by`      `supervisor_id`       BIGINT UNSIGNED NOT NULL,
            CHANGE `catatan_petugas`  `catatan_penanganan`  TEXT NULL,
            CHANGE `foto_penanganan`  `foto_hasil`          VARCHAR(255) NULL,
            CHANGE `timestamp_selesai` `tanggal_selesai`    TIMESTAMP NULL
        ");

        // Update enum value: sedang_diproses → diproses
        DB::statement("ALTER TABLE `assignment` MODIFY COLUMN `status_assignment` ENUM('ditugaskan', 'diproses', 'selesai') DEFAULT 'ditugaskan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `assignment` MODIFY COLUMN `status_assignment` ENUM('ditugaskan', 'sedang_diproses', 'selesai') DEFAULT 'ditugaskan'");

        // Kembalikan nama kolom menggunakan raw SQL
        DB::statement("ALTER TABLE `assignment`
            CHANGE `supervisor_id`       `assigned_by`       BIGINT UNSIGNED NOT NULL,
            CHANGE `catatan_penanganan`  `catatan_petugas`   TEXT NULL,
            CHANGE `foto_hasil`          `foto_penanganan`   VARCHAR(255) NULL,
            CHANGE `tanggal_selesai`     `timestamp_selesai` TIMESTAMP NULL
        ");
    }
};
