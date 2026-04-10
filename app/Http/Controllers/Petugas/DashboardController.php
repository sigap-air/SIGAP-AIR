<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Assignment;

class DashboardController extends Controller
{
    public function index()
    {
        $petugasId = auth()->user()->petugas?->id;
        abort_if(!$petugasId, 403, 'Akun belum terdaftar sebagai petugas.');

        $tugasAktif   = Assignment::with(['pengaduan.kategori', 'pengaduan.zona', 'pengaduan.sla'])
            ->where('petugas_id', $petugasId)
            ->whereIn('status_assignment', ['ditugaskan', 'diproses'])
            ->latest()
            ->get();

        $totalSelesai = Assignment::where('petugas_id', $petugasId)
            ->where('status_assignment', 'selesai')
            ->whereMonth('updated_at', now()->month)
            ->count();

        $totalSemua = Assignment::where('petugas_id', $petugasId)->count();

        return view('petugas.dashboard', compact('tugasAktif', 'totalSelesai', 'totalSemua'));
    }
}
