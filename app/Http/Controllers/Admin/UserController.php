<?php
/**
 * PBI-16 — Manajemen User & Role
 * TANGGUNG JAWAB: Farisha Huwaida Shofha
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'required|in:admin,supervisor,petugas,masyarakat',
            'no_telepon' => 'nullable|string|max:20',
            'is_active'  => 'boolean',
        ], [
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'no_telepon' => $request->no_telepon,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.user.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'role'       => 'required|in:admin,supervisor,petugas,masyarakat',
            'no_telepon' => 'nullable|string|max:20',
            'is_active'  => 'boolean',
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'no_telepon' => $request->no_telepon,
            'is_active'  => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun sendiri.']);
        }

        // Cek apakah petugas masih punya tugas aktif
        $punyaTugasAktif = $user->isPetugas() &&
            $user->petugas?->assignments()
                ->whereIn('status_assignment', ['ditugaskan', 'diproses'])
                ->exists();

        if ($punyaTugasAktif) {
            return back()->withErrors(['error' => 'User tidak dapat dihapus karena masih memiliki tugas aktif.']);
        }

        $user->update(['is_active' => false]);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'Akun pengguna berhasil dinonaktifkan.');
    }

    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('password')]);
        return back()->with('success', "Password user \"{$user->name}\" berhasil direset ke \"password\".");
    }
}
