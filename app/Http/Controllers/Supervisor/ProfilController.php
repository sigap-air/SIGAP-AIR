<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * PBI #27 — Kelola Profil Supervisor
 *
 * Supervisor menggunakan akun pada tabel users yang sama,
 * sehingga controller ini hanya membaca dan memperbarui data user aktif.
 */
class ProfilController extends Controller
{
    /**
     * Tampilkan halaman profil supervisor.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        abort_if(!$user || $user->role !== 'supervisor', 403, 'Akun bukan supervisor.');

        return view('supervisor.profil.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update data profil supervisor.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_if(!$user || $user->role !== 'supervisor', 403, 'Akun bukan supervisor.');

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'no_telepon'  => ['nullable', 'string', 'regex:/^[0-9\+\-\(\)\s]+$/', 'max:20'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah digunakan akun lain.',
            'no_telepon.regex'  => 'Format nomor telepon tidak valid.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
            'foto_profil.max'   => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $validated['foto_profil'] = $request->file('foto_profil')->store('foto-profil', 'public');
        }

        if ($request->boolean('hapus_foto')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $validated['foto_profil'] = null;
        }

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route('supervisor.profil.edit')->with('status', 'profile-updated');
    }
}