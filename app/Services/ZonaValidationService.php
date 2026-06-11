<?php
/**
 * ZonaValidationService — Layanan Validasi Wilayah Otomatis
 * TANGGUNG JAWAB: PBI-23 + Fitur Peta (Arthur Budi Maharesi)
 *
 * Menganalisis lokasi untuk menentukan kesesuaian dengan zona yang dipilih.
 * Mendukung dua metode:
 * 1. Validasi berbasis KOORDINAT GPS (Point-in-Polygon) — lebih akurat
 * 2. Validasi berbasis TEKS (keyword matching) — fallback
 */
namespace App\Services;

use App\Models\Zona;
use App\Models\ZonaWilayah;
use Illuminate\Support\Str;

class ZonaValidationService
{
    /**
     * Memvalidasi apakah teks lokasi mengandung kata kunci dari zona.
     * Dipertahankan sebagai fallback jika koordinat tidak tersedia.
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
        $filteredKeywords = array_filter($keywords, function ($word) {
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

    /**
     * Validasi berbasis koordinat GPS menggunakan algoritma Point-in-Polygon (Ray Casting).
     * Jauh lebih akurat daripada pencocokan teks.
     *
     * @param  float $lat    Latitude titik pengaduan
     * @param  float $lng    Longitude titik pengaduan
     * @param  int   $zonaId ID zona yang dipilih pengguna
     * @return bool  true jika titik berada di dalam polygon zona
     */
    public function validateByCoordinates(float $lat, float $lng, int $zonaId): bool
    {
        $zona = ZonaWilayah::find($zonaId);
        if (!$zona || empty($zona->geo_boundary)) {
            // Fallback: jika zona tidak punya polygon, anggap valid
            return true;
        }

        $boundary = $zona->geo_boundary;

        // Pastikan format GeoJSON Polygon yang benar
        if (!isset($boundary['coordinates'][0])) {
            return true;
        }

        $polygon = $boundary['coordinates'][0];
        return $this->isPointInPolygon($lat, $lng, $polygon);
    }

    /**
     * Auto-detect zona berdasarkan koordinat GPS.
     * Mengecek titik [lat, lng] terhadap semua polygon zona yang ada.
     *
     * @return int|null ID zona yang cocok, atau null jika tidak ada
     */
    public function detectZonaByCoordinates(float $lat, float $lng): ?int
    {
        $zonas = ZonaWilayah::where('is_active', true)
            ->whereNotNull('geo_boundary')
            ->get();

        foreach ($zonas as $zona) {
            $boundary = $zona->geo_boundary;
            if (isset($boundary['coordinates'][0])) {
                if ($this->isPointInPolygon($lat, $lng, $boundary['coordinates'][0])) {
                    return $zona->id;
                }
            }
        }

        return null;
    }

    /**
     * Algoritma Ray Casting untuk Point-in-Polygon.
     * Menghitung berapa kali sinar horizontal dari titik memotong sisi-sisi polygon.
     * Jika ganjil → titik di dalam polygon. Jika genap → titik di luar.
     *
     * @param  float $lat      Latitude titik yang dicek
     * @param  float $lng      Longitude titik yang dicek
     * @param  array $polygon  Array koordinat GeoJSON [lng, lat] (GeoJSON pakai lng dulu!)
     * @return bool
     */
    private function isPointInPolygon(float $lat, float $lng, array $polygon): bool
    {
        $n = count($polygon);
        $inside = false;

        $j = $n - 1;
        for ($i = 0; $i < $n; $i++) {
            // GeoJSON memakai format [longitude, latitude] — kebalikan dari [lat, lng]
            $xi = $polygon[$i][0]; // longitude
            $yi = $polygon[$i][1]; // latitude
            $xj = $polygon[$j][0]; // longitude
            $yj = $polygon[$j][1]; // latitude

            // Ray casting check
            $intersect = (($yi > $lat) !== ($yj > $lat))
                && ($lng < ($xj - $xi) * ($lat - $yi) / ($yj - $yi) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }

            $j = $i;
        }

        return $inside;
    }
}
