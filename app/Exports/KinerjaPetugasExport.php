<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KinerjaPetugasExport implements FromCollection, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(): Collection
    {
        return $this->data->map(fn($p) => [
            $p->user->nama ?? '-',
            $p->zona->nama_zona ?? 'Belum ada zona',
            $p->total_selesai ?? 0,
            $p->total_aktif ?? 0,
            round($p->rata_waktu ?? 0, 1) . ' jam',
            $p->rata_rating ? round($p->rata_rating, 1) . '/5' : '-',
        ]);
    }

    public function headings(): array
    {
        return [
            'Nama Petugas',
            'Zona',
            'Selesai (Total)',
            'Aktif Sekarang',
            'Rata-rata Waktu',
            'Rata-rata Rating'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
