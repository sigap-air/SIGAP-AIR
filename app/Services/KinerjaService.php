<?php

namespace App\Services;

use App\Models\{Pengaduan, Zona, Kategori};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KinerjaService
{
    public function __construct(private LaporanService $laporanService) {}

    public function getAll(array $filter = []): array
    {
        return $this->laporanService->getKinerja($filter);
    }

    public function exportExcel(array $filter = [])
    {
        // Fallback: export CSV (tanpa dependency eksternal)
        $data    = $this->laporanService->getKinerja($filter);
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="kinerja-petugas.csv"',
        ];

        $callback = function () use ($data) {
            $fp = fopen('php://output', 'w');
            fputs($fp, "\xEF\xBB\xBF"); // BOM for Excel UTF-8
            fputcsv($fp, ['Nama Petugas', 'No. Pegawai', 'Total Tugas', 'Selesai', 'Rata Waktu (Jam)', 'Rata Rating']);

            foreach ($data['kinerja'] as $row) {
                fputcsv($fp, [
                    $row['petugas']->user->name ?? '-',
                    $row['petugas']->nip ?? '-',
                    $row['total_tugas'],
                    $row['total_selesai'],
                    $row['rata_waktu_jam'] ?? '-',
                    $row['rata_rating'] ?? '-',
                ]);
            }
            fclose($fp);
        };

        return response()->stream($callback, 200, $headers);
    }
}
