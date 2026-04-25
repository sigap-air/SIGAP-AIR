<?php
/**
 * PBI-10 — Riwayat Pengaduan Pelanggan
 * TANGGUNG JAWAB: Amanda Zuhra Azis
 *
 * Fitur:
 * - Daftar semua pengaduan milik pelanggan yang login
 * - Filter berdasarkan status, kategori, rentang tanggal
 * - Detail pengaduan + timeline status (kapan diajukan, diverifikasi, ditugaskan, selesai)
 */
namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\{Pengaduan, Kategori};
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        // TODO AMANDA: Implementasi filter multi-kriteria
        $query = Pengaduan::with(['kategori', 'zona', 'rating'])
            ->where('user_id', auth()->id())
            ->latest();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pengajuan', [$request->dari, $request->sampai]);
        }

        $pengaduans = $query->paginate(10)->withQueryString();
        $kategoris  = Kategori::where('is_active', true)->get();
        return view('masyarakat.riwayat.index', compact('pengaduans', 'kategoris'));
    }

    public function show(Pengaduan $pengaduan)
    {
        // Pastikan hanya bisa lihat pengaduan milik sendiri
        abort_if($pengaduan->user_id !== auth()->id(), 403);
        $pengaduan->load(['pelapor', 'kategori', 'zona', 'assignment.petugas.user', 'rating', 'sla']);
        return view('masyarakat.riwayat.show', compact('pengaduan'));
    }
}
