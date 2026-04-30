<?php
/**
 * PBI-06 — Assignment Petugas ke Pengaduan
 * TANGGUNG JAWAB: Sanitra Savitri
 *
 * Fitur:
 * - Pilih petugas berdasarkan zona wilayah pengaduan
 * - Input instruksi khusus + jadwal penanganan
 * - Trigger notifikasi ke petugas + pelapor setelah assignment
 */
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\{Pengaduan, Petugas};
use App\Services\AssignmentService;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function __construct(private AssignmentService $assignmentService) {}

    public function create(Pengaduan $pengaduan)
    {
        $statusTersediaColumn = \Illuminate\Support\Facades\Schema::hasColumn('petugas', 'status_ketersediaan')
            ? 'status_ketersediaan'
            : 'status_tersedia';

        // Tampilkan hanya petugas di zona yang sama dengan pengaduan.
        // Fallback kolom status disesuaikan dengan skema DB yang aktif.
        $petugas = Petugas::with('user')
            ->where('zona_id', $pengaduan->zona_id)
            ->where($statusTersediaColumn, 'tersedia')
            ->get();

        return view('supervisor.assignment.create', compact('pengaduan', 'petugas'));
    }

    public function store(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'petugas_id'        => 'required|exists:petugas,id',
            'instruksi'         => 'nullable|string|max:500',
            'jadwal_penanganan' => 'required|date|after:now',
        ]);

        // TODO SANITRA: Delegasikan ke AssignmentService + kirim notifikasi
        $this->assignmentService->tugaskan($pengaduan, $request->validated(), auth()->user());
        return redirect()->route('supervisor.verifikasi.index')->with('success', 'Petugas berhasil ditugaskan.');
    }
}
