<?php
/**
 * PBI-13 — Filter & Pencarian Lanjutan Pengaduan
 * Supervisor dapat memfilter pengaduan berdasarkan status, zona, kategori,
 * rentang tanggal, dan nomor tiket secara real-time.
 */
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\{Pengaduan, Zona, Kategori};
use Illuminate\Http\Request;

class FilterPengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with(['pelapor', 'kategori', 'zona', 'sla', 'assignment'])
            ->latest('tanggal_pengajuan');

        // Filter nomor tiket
        if ($request->filled('nomor_tiket')) {
            $query->where('nomor_tiket', 'like', '%' . $request->nomor_tiket . '%');
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter zona
        if ($request->filled('zona_id')) {
            $query->where('zona_id', $request->zona_id);
        }

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter rentang tanggal
        if ($request->filled('dari')) {
            $query->where('tanggal_pengajuan', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal_pengajuan', '<=', \Carbon\Carbon::parse($request->sampai)->endOfDay());
        }

        // Filter overdue
        if ($request->boolean('overdue')) {
            $query->whereHas('sla', fn($q) => $q->where('is_overdue', true));
        }

        $pengaduans = $query->paginate(15)->withQueryString();
        $zonas      = Zona::where('is_active', true)->get();
        $kategoris  = Kategori::where('is_active', true)->get();
        $statuses   = ['menunggu_verifikasi', 'disetujui', 'ditolak', 'ditugaskan', 'diproses', 'selesai'];

        return view('supervisor.filter.index', compact('pengaduans', 'zonas', 'kategoris', 'statuses'));
    }
}
