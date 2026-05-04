<?php
/**
 * Filter Dashboard — Filter untuk Supervisor dan Admin
 * TANGGUNG JAWAB: Imanuel Karmelio V. Liuw (PBI 13)
 */
namespace App\Services;

use App\Models\{Kategori, Pengaduan, Petugas, Zona};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PengaduanFilterService
{
    /** Status nilai DB + label filter (termasuk alias diproses). */
    public function statusOptions(): array
    {
        return [
            'menunggu_verifikasi',
            'disetujui',
            'ditolak',
            'ditugaskan',
            'diproses',
            'sedang_diproses',
            'selesai',
        ];
    }

    public function sortableColumns(): array
    {
        return [
            'tanggal_pengajuan' => 'tanggal_pengajuan',
            'nomor_tiket'       => 'nomor_tiket',
            'status'            => 'status',
            'kategori'          => 'kategori_id',
            'zona'              => 'zona_id',
        ];
    }

    public function baseQuery(): Builder
    {
        return Pengaduan::query()
            ->with(['pelapor', 'kategori', 'zona', 'sla', 'assignment.petugas.user']);
    }

    /**
     * Terapkan filter dari query string (nomor tiket, status, zona, kategori, petugas, tanggal, overdue).
     */
    public function applyFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('nomor_tiket')) {
            $query->where('nomor_tiket', 'like', '%' . $request->nomor_tiket . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'diproses') {
                $query->whereIn('status', ['diproses', 'sedang_diproses']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('zona_id')) {
            $query->where('zona_id', $request->zona_id);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('petugas_id')) {
            $query->whereHas('assignment', fn ($q) => $q->where('petugas_id', $request->petugas_id));
        }

        $submittedAt = Schema::hasColumn('pengaduan', 'tanggal_pengajuan')
            ? DB::raw('COALESCE(tanggal_pengajuan, created_at)')
            : 'created_at';

        if ($request->filled('dari')) {
            $query->whereDate($submittedAt, '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate($submittedAt, '<=', $request->sampai);
        }

        if ($request->boolean('overdue')) {
            $query->whereHas('sla', fn ($q) => $q->where('status_sla', 'overdue'));
        }

        return $query;
    }

    public function applySorting(Builder $query, Request $request): Builder
    {
        $map   = $this->sortableColumns();
        $sort  = $request->input('sort', 'tanggal_pengajuan');
        $dir   = strtolower($request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        if (! isset($map[$sort])) {
            $sort = 'tanggal_pengajuan';
        }

        $column = $map[$sort];

        if ($column === 'tanggal_pengajuan') {
            if (Schema::hasColumn('pengaduan', 'tanggal_pengajuan')) {
                return $query->orderByRaw('COALESCE(tanggal_pengajuan, created_at) ' . $dir);
            }

            return $query->orderBy('created_at', $dir);
        }

        return $query->orderBy($column, $dir);
    }

    /**
     * Query lengkap untuk daftar / export (tanpa paginate).
     */
    public function filteredQuery(Request $request): Builder
    {
        $q = $this->baseQuery();
        $this->applyFilters($q, $request);
        $this->applySorting($q, $request);

        return $q;
    }

    public function dropdownData(): array
    {
        return [
            'zonas'     => Zona::where('is_active', true)->orderBy('nama_zona')->get(),
            'kategoris' => Kategori::where('is_active', true)->orderBy('nama_kategori')->get(),
            'statuses'  => $this->statusOptions(),
            'petugas'   => Petugas::with('user')->orderBy('id')->get()
                ->sortBy(fn ($p) => $p->user?->name ?? '')
                ->values(),
        ];
    }

    public function csvExportResponse(Request $request): StreamedResponse
    {
        $query = $this->filteredQuery($request);
        $filename = 'pengaduan-filter-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, [
                'Nomor Tiket',
                'Tanggal Pengajuan',
                'Pelapor',
                'Kategori',
                'Zona',
                'Status',
                'Petugas',
                'SLA',
                'Lokasi',
            ]);

            foreach ($query->cursor() as $p) {
                $petugasNama = $p->assignment?->petugas?->user?->name ?? '';
                $slaLabel    = $p->sla?->status_sla ?? '';

                fputcsv($out, [
                    $p->nomor_tiket,
                    optional($p->tanggal_pengajuan)->format('Y-m-d H:i'),
                    $p->pelapor?->name,
                    $p->kategori?->nama_kategori,
                    $p->zona?->nama_zona,
                    $p->status,
                    $petugasNama,
                    $slaLabel,
                    $p->lokasi,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
