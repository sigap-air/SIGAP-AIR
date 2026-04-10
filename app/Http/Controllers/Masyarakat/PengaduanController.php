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
use App\Models\{Pengaduan, Kategori, Zona, Sla};
use App\Services\PengaduanService;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function __construct(private PengaduanService $pengaduanService) {}

    public function create()
    {
        $kategoris = Kategori::where('is_active', true)->get();
        $zonas     = Zona::where('is_active', true)->get();
        return view('masyarakat.pengaduan.create', compact('kategoris', 'zonas'));
    }

    public function store(StorePengaduanRequest $request)
    {
        // TODO SANITRA: Delegasikan ke PengaduanService
        $pengaduan = $this->pengaduanService->buat($request->validated(), auth()->user());
        return redirect()->route('masyarakat.riwayat.show', $pengaduan)
                         ->with('success', "Pengaduan berhasil dikirim! Nomor tiket: {$pengaduan->nomor_tiket}");
    }
}
