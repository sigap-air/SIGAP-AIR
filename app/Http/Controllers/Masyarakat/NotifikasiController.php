<?php
/**
 * PBI-12 — Notifikasi In-App
 * TANGGUNG JAWAB: Amanda Zuhra Azis
 */
namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
        return view('masyarakat.notifikasi.index', compact('notifikasis'));
    }

    public function markRead($id)
    {
        $notif = Notifikasi::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $notif->update(['is_read' => true, 'dibaca_pada' => now()]);
        return back();
    }

    public function markAllRead()
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'dibaca_pada' => now()]);
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
