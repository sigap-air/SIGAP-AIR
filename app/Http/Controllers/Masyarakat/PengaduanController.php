<?php
/**
 * PBI-04 — Pengajuan Pengaduan Digital
 * TANGGUNG JAWAB: Sanitra Savitri
 *
 * Fitur:
 * - Form pengaduan dengan kategori, lokasi, deskripsi, upload foto
 * - Auto-generate nomor tiket unik (SIGAP-YYYYMMDD-XXXX)
 * - Auto-set SLA berdasarkan kategori yang dipilih
 */

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengaduan\StorePengaduanRequest;
use App\Models\{Pengaduan, KategoriPengaduan, Zona};  // FIX ERR-3: pakai KategoriPengaduan
use App\Services\PengaduanService;

class PengaduanController extends Controller
{
    public function __construct(private PengaduanService $pengaduanService) {}

    public function create()
    {
        // FIX ERR-3: pakai KategoriPengaduan (model PBI-02) bukan Kategori lama
        $kategoris = KategoriPengaduan::where('is_active', true)->get();
        $zonas     = Zona::where('is_active', true)->get();

        return view('masyarakat.pengaduan.create', compact('kategoris', 'zonas'));
    }

    // ✅ Halaman sukses setelah submit
    public function sukses(Pengaduan $pengaduan)
    {
        abort_unless($pengaduan->user_id === auth()->id(), 403);

        return view('masyarakat.pengaduan.tiket-sukses', compact('pengaduan'));
    }

    // ✅ Simpan pengaduan
    public function store(StorePengaduanRequest $request)
    {
        $pengaduan = $this->pengaduanService->buat(
            $request->validated(),
            auth()->user()
        );

        return redirect()->route('masyarakat.pengaduan.sukses', $pengaduan);
    }
}