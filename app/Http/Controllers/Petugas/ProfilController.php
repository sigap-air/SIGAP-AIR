<?php

namespace App\Http\Controllers\Petugas;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Assignment;

/**
 * PBI #24 — Profil & Status Petugas Teknis
 *
 * Controller khusus untuk role petugas.
 * Menampilkan informasi profil, status ketersediaan,
 * zona penugasan, dan statistik penanganan.
 */
class ProfilController extends Controller
{
    /**
     * Tampilkan halaman profil petugas.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $petugas = $user->petugas;

        abort_if(!$petugas, 403, 'Akun belum terdaftar sebagai petugas.');

        // Eager load zona
        $petugas->load('zona');

        // Statistik penugasan
        $stats = [
            'total_tugas'    => Assignment::where('petugas_id', $petugas->id)->count(),
            'tugas_aktif'    => Assignment::where('petugas_id', $petugas->id)
                                    ->whereIn('status_assignment', ['ditugaskan', 'diproses'])
                                    ->count(),
            'tugas_selesai'  => Assignment::where('petugas_id', $petugas->id)
                                    ->where('status_assignment', 'selesai')
                                    ->count(),
            'selesai_bulan_ini' => Assignment::where('petugas_id', $petugas->id)
                                    ->where('status_assignment', 'selesai')
                                    ->whereMonth('updated_at', now()->month)
                                    ->whereYear('updated_at', now()->year)
                                    ->count(),
        ];

        return view('petugas.profil.edit', compact('user', 'petugas', 'stats'));
    }

    /**
     * Update data profil petugas (nama, email, no_telepon, foto_profil).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'no_telepon'  => ['nullable', 'string', 'regex:/^[0-9\+\-\(\)\s]+$/', 'max:20'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ], [
            'name.required'        => 'Nama lengkap wajib diisi.',
            'email.required'       => 'Alamat email wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'email.unique'         => 'Email sudah digunakan akun lain.',
            'no_telepon.regex'     => 'Format nomor telepon tidak valid.',
            'foto_profil.image'    => 'File harus berupa gambar.',
            'foto_profil.mimes'    => 'Format gambar harus JPG, PNG, atau WebP.',
            'foto_profil.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $path = $request->file('foto_profil')->store('foto-profil', 'public');
            $validated['foto_profil'] = $path;
        }

        // Handle hapus foto
        if ($request->boolean('hapus_foto')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $validated['foto_profil'] = null;
        }

        // Reset verifikasi email jika email berubah
        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route('petugas.profil.edit')->with('status', 'profile-updated');
    }

}
