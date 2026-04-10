<?php
/**
 * PBI-08 — Manajemen Profil Pengguna (semua role)
 * TANGGUNG JAWAB: Falah Adhi Chandra
 *
 * Fitur:
 * - Edit nama, nomor telepon
 * - Upload / ganti foto profil
 * - Ganti password dengan validasi kekuatan
 */
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfilRequest;
use Illuminate\Support\Facades\{Hash, Storage};

class ProfilController extends Controller
{
    public function edit()
    {
        return view('petugas.profil.edit', ['user' => auth()->user()]);
    }

    public function update(UpdateProfilRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        // TODO FALAH: Handle upload foto profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('uploads/profil', 'public');
        }

        // TODO FALAH: Handle ganti password
        if ($request->filled('password_baru')) {
            $data['password'] = Hash::make($request->password_baru);
        }

        $user->update($data);
        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
