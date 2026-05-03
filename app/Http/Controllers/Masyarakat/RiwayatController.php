<?php
/**
 * PBI-10 — Riwayat Pengaduan Pelanggan
 * TANGGUNG JAWAB: Amanda Zuhra Azis
 *
 * Fitur:
 * - Daftar semua pengaduan milik pelanggan yang login
 * - Filter berdasarkan status, kategori, rentang tanggal
 * - Detail pengaduan + timeline status (kapan diajukan, diverifikasi, ditugaskan, selesai)
 *
 * Routes (dalam group middleware('role:masyarakat')->prefix('masyarakat')->name('masyarakat.')):
 * GET /masyarakat/pengaduan/riwayat               → index()  → name: masyarakat.pengaduan.riwayat
 * GET /masyarakat/pengaduan/riwayat/{nomor_tiket} → show()   → name: masyarakat.pengaduan.riwayat.show
 */
namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\Kategori;          // Model Kategori (table: kategori_pengaduan)
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RiwayatController extends Controller
{
    /**
     * Daftar riwayat pengaduan milik pelanggan yang sedang login,
     * dengan filter multi-kriteria sesuai spesifikasi PBI-10.
     */
    public function index(Request $request)
    {
        $pengaduan = Pengaduan::where('user_id', auth()->id())
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->kategori_id, fn ($q, $v) => $q->where('kategori_id', $v))
            ->when($request->tanggal_dari, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->tanggal_sampai, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->with(['kategori', 'zona', 'assignment.petugas.user', 'rating'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $kategoris = Kategori::where('is_active', true)->get();

        return view('masyarakat.riwayat.index', compact('pengaduan', 'kategoris'));

    }

    /**
     * Detail satu pengaduan milik pelanggan.
     * Route model binding menggunakan nomor_tiket (lihat getRouteKeyName() di model).
     */
    public function show(Pengaduan $pengaduan)
    {
        // Pastikan hanya bisa lihat pengaduan milik sendiri
        abort_if($pengaduan->user_id !== auth()->id(), 403);

        $pengaduan->load([
            'kategori',
            'zona',
            'assignment.petugas.user',
            'statusLog.user',   // untuk timeline (relasi placeholder sampai model StatusLog dibuat)
            'rating',
            'sla',
        ]);
        return view('masyarakat.riwayat.show', compact('pengaduan'));
    }
}
