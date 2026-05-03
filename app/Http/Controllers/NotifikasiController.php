<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->when($request->filter === 'unread', fn($q) => $q->where('is_read', false))
            ->when($request->filter === 'read', fn($q) => $q->where('is_read', true))
            ->orderByDesc('created_at')
            ->paginate(15);
            
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function count()
    {
        // Untuk AJAX polling — tidak perlu middleware khusus, sudah di group auth
        $count = Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)->count();
            
        return response()->json(['count' => $count]);
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
                return redirect()->route('pengaduan.riwayat.show', $pengaduan->nomor_tiket);
            }
        }
        
        return redirect()->route('notifikasi.index');
    }
}
