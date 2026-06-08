<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

/**
 * PBI #8 — Kelola Profil Masyarakat
 *
 * Controller ini khusus untuk role masyarakat.
 * Role lain (petugas, supervisor, admin) akan punya controller sendiri
 * sesuai PBI masing-masing (PBI-24, PBI-25, PBI-26).
 */
class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil masyarakat.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        abort_unless(in_array($user->role, ['masyarakat', 'admin', 'supervisor'], true), 403, 'Akun belum memiliki akses ke halaman profil ini.');

        return view($this->profileView($user->role), $this->profileViewData($user));
    }

    /**
     * Update data profil (nama, email, no_telepon, foto_profil).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $path = $request->file('foto_profil')->store('foto-profil', 'public');
            $validated['foto_profil'] = $path;
        }

        // Handle hapus foto profil
        if ($request->boolean('hapus_foto')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $validated['foto_profil'] = null;
        }

        // Jika email berubah, reset email_verified_at
        if (isset($validated['email']) && $user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route($this->profileRoute($user->role))->with('status', 'profile-updated');
    }

    /**
     * Update password pengguna.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::min(8), 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route($this->profileRoute($request->user()->role))->with('status', 'password-updated');
    }

    /**
     * Hapus akun masyarakat.
     */
    public function destroy(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'masyarakat', 403, 'Akun ini tidak dapat dihapus dari halaman profil.');

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Hapus foto profil jika ada
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function profileView(string $role): string
    {
        return 'profile.edit';
    }

    private function profileRoute(string $role): string
    {
        return match ($role) {
            'admin' => 'admin.profil.edit',
            'supervisor' => 'supervisor.profil.edit',
            default => 'masyarakat.profil.edit',
        };
    }

    private function profileViewData($user): array
    {
        $roleLabel = match ($user->role) {
            'admin' => 'Admin',
            'supervisor' => 'Supervisor',
            default => 'Masyarakat',
        };

        $routePrefix = $user->role;

        return [
            'user' => $user,
            'roleLabel' => $roleLabel,
            'profileUpdateRoute' => $this->profileRoute($user->role),
            'passwordUpdateRoute' => $routePrefix . '.profil.update-password',
        ];
    }
}
