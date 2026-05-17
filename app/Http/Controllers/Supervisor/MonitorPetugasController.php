<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\ZonaWilayah;
use App\Services\PetugasMonitoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Monitoring status petugas untuk supervisor (Available / On-Duty / Off).
 */
class MonitorPetugasController extends Controller
{
    public function __construct(private PetugasMonitoringService $monitoringService) {}

    public function index(Request $request)
    {
        $zonaId = $request->filled('zona_id') ? (int) $request->zona_id : null;
        $petugasList = $this->monitoringService->getMonitorList($zonaId);
        $summary = $this->monitoringService->getSummary($zonaId);
        $zonas = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get(['id', 'nama_zona']);

        return view('supervisor.monitor-petugas.index', compact('petugasList', 'summary', 'zonas', 'zonaId'));
    }

    public function status(Request $request): JsonResponse
    {
        $zonaId = $request->filled('zona_id') ? (int) $request->zona_id : null;
        $petugasList = $this->monitoringService->getMonitorList($zonaId);

        return response()->json([
            'updated_at' => now()->toIso8601String(),
            'summary'    => $this->monitoringService->getSummary($zonaId),
            'petugas'    => $petugasList->values(),
        ]);
    }
}
