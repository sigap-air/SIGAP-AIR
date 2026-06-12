<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $notifikasis = Notifikasi::where('user_id', auth()->id())
            ->when($request->filter === 'unread', fn($q) => $q->where('is_read', false))
            ->when($request->filter === 'read', fn($q) => $q->where('is_read', true))
            ->orderByDesc('created_at')
            ->paginate(15);
            
        return view('notifikasi.index', compact('notifikasis'));
    }

    public function count()
    {
        // Untuk AJAX polling — tidak perlu middleware khusus, sudah di group auth
        $unreadCount = Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)->count();
            
        $notifications = Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
            
        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    public function markAllRead()
    {
        Notifikasi::where('user_id', auth()->id())->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function markRead($id)
    {
        $notif = Notifikasi::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $notif->update(['is_read' => true]);
        
        // Redirect ke pengaduan terkait jika ada, atau kembali
        if ($notif->pengaduan_id) {
            $pengaduan = Pengaduan::find($notif->pengaduan_id);
            if ($pengaduan) {
                $role = auth()->user()->role;
                if ($role === 'masyarakat') {
                    return redirect()->route('masyarakat.pengaduan.riwayat.show', $pengaduan->nomor_tiket);
                } elseif ($role === 'petugas') {
                    return redirect()->route('petugas.tugas.show', $pengaduan->nomor_tiket);
                } elseif ($role === 'supervisor') {
                    return redirect()->route('supervisor.pengaduan.show', $pengaduan->nomor_tiket);
                } else {
                    return redirect()->route('admin.pengaduan.index');
                }
            }
        }
        
        return redirect()->route('notifikasi.index');
    }
}
