<?php
/**
 * ZonaValidationService — Layanan Validasi Wilayah Otomatis
 * TANGGUNG JAWAB: PBI-23
 *
 * Menganalisis teks lokasi untuk menentukan kesesuaian dengan zona yang dipilih.
 */
namespace App\Services;

use App\Models\Zona;
use Illuminate\Support\Str;

class ZonaValidationService
{
    /**
     * Memvalidasi apakah teks lokasi mengandung kata kunci dari zona.
     * Mengembalikan true jika sesuai, false jika tidak.
     */
    public function validateLokasi(string $lokasi, int $zonaId): bool
    {
        $zona = Zona::find($zonaId);
        if (!$zona) {
            return false;
        }

        // Ambil kata kunci dari nama zona (hilangkan kata umum jika perlu)
        $namaZona = strtolower($zona->nama_zona);
        $lokasiLower = strtolower($lokasi);

        // Pencocokan kata dasar (contoh: "Zona Utara" -> cek "utara" ada di lokasi)
        $keywords = explode(' ', $namaZona);
        
        // Buang kata 'zona' atau 'wilayah' dari keywords
        $filteredKeywords = array_filter($keywords, function($word) {
            return !in_array($word, ['zona', 'wilayah', 'area']);
        });

        // Jika array kosong (misalnya nama zona hanya "Zona"), fallback ke string full
        if (empty($filteredKeywords)) {
            $filteredKeywords = [$namaZona];
        }

        // Jika salah satu keyword penting ada di dalam teks lokasi, dianggap valid
        foreach ($filteredKeywords as $keyword) {
            if (Str::contains($lokasiLower, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
