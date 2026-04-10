<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\{Pengaduan, Notifikasi};

class DashboardController extends Controller
{
    public function index()
    {
        $pengaduanTerakhir = Pengaduan::with(['kategori', 'zona', 'sla'])
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $totalPengaduan = Pengaduan::where('user_id', auth()->id())->count();
        $totalSelesai   = Pengaduan::where('user_id', auth()->id())->where('status', 'selesai')->count();
        $unreadNotif    = Notifikasi::where('user_id', auth()->id())->where('is_read', false)->count();

        return view('masyarakat.dashboard', compact(
            'pengaduanTerakhir', 'totalPengaduan', 'totalSelesai', 'unreadNotif'
        ));
    }
}
