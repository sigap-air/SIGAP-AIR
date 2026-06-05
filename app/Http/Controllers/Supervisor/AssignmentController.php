<?php
/**
 * PBI-06 — Assignment Petugas ke Pengaduan
 * TANGGUNG JAWAB: Sanitra Savitri
 *
 * Fitur:
 * - Pilih petugas berdasarkan zona wilayah pengaduan
 * - Monitoring status Available / On-Duty / Off sebelum assignment
 * - Input instruksi khusus + jadwal penanganan
 * - Trigger notifikasi ke petugas + pelapor setelah assignment
 */
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\{Pengaduan, Petugas};
use App\Services\AssignmentService;
use App\Services\PetugasMonitoringService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    public function __construct(
        private AssignmentService $assignmentService,
        private PetugasMonitoringService $monitoringService,
    ) {}

    public function create(Pengaduan $pengaduan)
    {
        $pengaduan->load(['kategori', 'zona']);

        $petugasRows = $this->monitoringService
            ->getMonitorList($pengaduan->zona_id)
            ->values();

        $petugasTersedia = $petugasRows->where('dapat_dipilih', true)->count();
        $monitorSummary = $this->monitoringService->getSummary($pengaduan->zona_id);

        return view('supervisor.assignment.create', compact(
            'pengaduan',
            'petugasRows',
            'petugasTersedia',
            'monitorSummary',
        ));
    }

    public function store(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'petugas_id'        => 'required|exists:petugas,id',
            'instruksi'         => 'nullable|string|max:500',
            'jadwal_penanganan' => 'required|date|after:now',
        ]);

        $petugas = Petugas::with('user')->findOrFail($request->petugas_id);

        // Cek apakah petugas terpetakan ke zona pengaduan (via many-to-many zones() atau fallback zona_id)
        $isMappedToZone = $petugas->zones()->where('zona_wilayah.id', $pengaduan->zona_id)->exists()
            || $petugas->zona_id === $pengaduan->zona_id;

        if (!$isMappedToZone) {
            throw ValidationException::withMessages([
                'petugas_id' => 'Petugas harus berada di zona yang sama dengan pengaduan.',
            ]);
        }

        $this->monitoringService->syncOperationalStatuses($pengaduan->zona_id);

        if (! $this->monitoringService->isSelectableForAssignment($petugas->fresh())) {
            throw ValidationException::withMessages([
                'petugas_id' => 'Petugas tidak Available. Hanya petugas berstatus Available yang dapat dipilih (Off dan On-Duty tidak dapat dipilih).',
            ]);
        }

        $this->assignmentService->tugaskan($pengaduan, $validated, auth()->user());

        return redirect()->route('supervisor.verifikasi.index')
            ->with('success', 'Petugas berhasil ditugaskan.');
    }
}
