<?php
/**
 * PBI-11 — Rating & Feedback Penyelesaian
 * TANGGUNG JAWAB: Amanda Zuhra Azis
 *
 * Fitur:
 * - Form rating bintang 1-5 muncul otomatis setelah status = selesai
 * - Input komentar opsional
 * - Satu pengaduan hanya bisa dirating satu kali
 */
namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\{Pengaduan, Rating};
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function create(Pengaduan $pengaduan)
    {
        abort_if($pengaduan->user_id !== auth()->id(), 403);
        abort_if($pengaduan->status !== 'selesai', 400, 'Pengaduan belum selesai.');
        abort_if($pengaduan->rating()->exists(), 400, 'Sudah memberikan rating.');
        return view('masyarakat.rating.create', compact('pengaduan'));
    }

    public function store(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'bintang'  => 'required|integer|between:1,5',
            'komentar' => 'nullable|string|max:500',
        ]);

        // TODO AMANDA: Simpan rating + link ke pengaduan
        Rating::create([
            'pengaduan_id'  => $pengaduan->id,
            'user_id'       => auth()->id(),
            'rating'        => $request->bintang,
            'komentar'      => $request->komentar,
            'tanggal_rating'=> now(),
        ]);

        return redirect()->route('masyarakat.riwayat.show', $pengaduan)
                         ->with('success', 'Terima kasih atas penilaian Anda!');
    }
}
