<?php
/**
 * PBI-06 — Assignment Petugas ke Pengaduan
 * TANGGUNG JAWAB: Sanitra Savitri
 *
 * Fitur:
 * - Catatan / instruksi perbaikan sebelum assignment
 * - Pilih petugas berdasarkan zona wilayah pengaduan
 * - Monitoring status Tersedia / Sibuk / Tidak Aktif sebelum assignment
 * - Jadwal penanganan + notifikasi ke petugas & pelapor
 */
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supervisor\StoreAssignmentRequest;
use App\Models\{Pengaduan, Petugas};
use App\Services\AssignmentService;
use App\Services\PetugasMonitoringService;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    public function __construct(
        private AssignmentService $assignmentService,
        private PetugasMonitoringService $monitoringService,
    ) {}

    public function create(Pengaduan $pengaduan)
    {
        if ($pengaduan->assignment) {
            return redirect()
                ->route('supervisor.pengaduan.show', $pengaduan)
                ->with('error', 'Pengaduan ini sudah memiliki petugas yang ditugaskan.');
        }

        if ($pengaduan->status !== 'disetujui') {
            return redirect()
                ->route('supervisor.pengaduan.show', $pengaduan)
                ->with('error', 'Pengaduan tidak dalam status yang dapat ditugaskan.');
        }

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

    public function store(StoreAssignmentRequest $request, Pengaduan $pengaduan)
    {
        if ($pengaduan->assignment) {
            throw ValidationException::withMessages([
                'petugas_id' => 'Pengaduan ini sudah memiliki petugas yang ditugaskan.',
            ]);
        }

        $data = $request->validated();
        $petugas = Petugas::with('user')->findOrFail($data['petugas_id']);

        if ($petugas->zona_id !== $pengaduan->zona_id) {
            throw ValidationException::withMessages([
                'petugas_id' => 'Petugas harus berada di zona yang sama dengan pengaduan.',
            ]);
        }

        $this->monitoringService->syncOperationalStatuses($pengaduan->zona_id);

        if (! $this->monitoringService->isSelectableForAssignment($petugas->fresh())) {
            throw ValidationException::withMessages([
                'petugas_id' => 'Petugas tidak Tersedia. Hanya petugas berstatus Tersedia yang dapat dipilih. Petugas Tidak Aktif tidak dapat dipilih.',
            ]);
        }

        $this->assignmentService->tugaskan($pengaduan, $data, auth()->user());

        return redirect()
            ->route('supervisor.pengaduan.show', $pengaduan)
            ->with('success', 'Petugas berhasil ditugaskan. Catatan assignment telah disimpan.');
    }
}
