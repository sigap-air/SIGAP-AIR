<?php
/**
 * PBI-16 — Manajemen User & Role
 * TANGGUNG JAWAB: Farisha Huwaida Shofha
 * Sprint 2, Pekan 7
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Petugas;
use App\Models\User;
use App\Models\ZonaWilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * GET /admin/users
     * Daftar user dengan filter: search, role, is_active
     */
    public function index(Request $request)
    {
        $users = User::when($request->search, function ($q, $v) {
                $q->where('name', 'like', "%{$v}%")
                  ->orWhere('email', 'like', "%{$v}%")
                  ->orWhere('username', 'like', "%{$v}%");
            })
            ->when($request->role, fn ($q, $v) => $q->where('role', $v))
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->with('petugas.zona')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        $zonas = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();
        $year = date('Y');
        $counters = [
            'admin'      => User::where('role', 'admin')->whereYear('created_at', $year)->count() + 1,
            'supervisor' => User::where('role', 'supervisor')->whereYear('created_at', $year)->count() + 1,
            'petugas'    => User::where('role', 'petugas')->whereYear('created_at', $year)->count() + 1,
            'masyarakat' => User::where('role', 'masyarakat')->whereYear('created_at', $year)->count() + 1,
        ];

        return view('admin.user.create', compact('zonas', 'counters', 'year'));
    }

    /**
     * POST /admin/users
     */
    public function store(StoreUserRequest $request)
    {
        DB::transaction(function () use ($request) {
            $fotoPath = null;
            if ($request->hasFile('foto_profil')) {
                $fotoPath = $request->file('foto_profil')->store('profile_photos', 'public');
            }

            // Auto-generate NIP berdasarkan role
            $prefix = match($request->role) {
                'admin' => 'ADM',
                'supervisor' => 'SPV',
                'petugas' => 'PEG',
                default => 'MSY',
            };
            $year = date('Y');
            $count = User::where('role', $request->role)
                         ->whereYear('created_at', $year)
                         ->count() + 1;
            $nip = sprintf("%s-%s-%04d", $prefix, $year, $count);

            $user = User::create([
                'nip'        => $nip,
                'name'       => $request->nama,
                'email'      => $request->email,
                'username'   => $request->username,
                'password'   => Hash::make($request->password),
                'role'       => $request->role,
                'no_telepon' => $request->no_telepon,
                'foto_profil'=> $fotoPath,
                'is_active'  => true,
            ]);

            // Jika role petugas, otomatis buat record di tabel petugas
            if ($request->role === 'petugas') {
                Petugas::create([
                    'user_id'         => $user->id,
                    'zona_id'         => $request->zona_id,
                    'nip'             => $user->nip,
                    'status_tersedia' => 'tersedia',
                ]);
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * GET /admin/users/{id}/edit
     */
    public function edit(User $user)
    {
        $zonas = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();
        return view('admin.user.edit', compact('user', 'zonas'));
    }

    /**
     * PUT /admin/users/{id}
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {
            $oldRole = $user->role;

            $data = [
                'name'       => $request->nama,
                'email'      => $request->email,
                'username'   => $request->username,
                'role'       => $request->role,
                'no_telepon' => $request->no_telepon,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('foto_profil')) {
                if ($user->foto_profil) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profil);
                }
                $data['foto_profil'] = $request->file('foto_profil')->store('profile_photos', 'public');
            }

            $user->update($data);

            // Kelola record petugas jika role berubah ke/dari petugas
            if ($request->role === 'petugas') {
                if ($user->petugas) {
                    // Update zona jika sudah ada record petugas
                    $user->petugas->update(['zona_id' => $request->zona_id]);
                } else {
                    // Buat record petugas baru
                    Petugas::create([
                        'user_id'         => $user->id,
                        'zona_id'         => $request->zona_id,
                        'status_tersedia' => 'tersedia',
                    ]);
                }
            } elseif ($oldRole === 'petugas' && $request->role !== 'petugas') {
                // Role berubah dari petugas ke role lain — hapus record petugas
                // hanya jika tidak ada assignment aktif
                if ($user->petugas && $user->petugas->assignmentsAktif()->count() === 0) {
                    $user->petugas->delete();
                }
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * DELETE /admin/users/{id}
     *
     * BUSINESS RULE 1: admin tidak bisa hapus dirinya sendiri.
     * BUSINESS RULE 2: petugas dengan assignment aktif tidak bisa dihapus.
     * SOLUSI: nonaktifkan daripada hard delete untuk menjaga integritas data.
     */
    public function destroy(User $user)
    {
        // BUSINESS RULE 1
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // BUSINESS RULE 2
        if ($user->role === 'petugas' && $user->petugas) {
            $aktif = $user->petugas->assignmentsAktif()->count();
            if ($aktif > 0) {
                return redirect()->back()
                    ->with('error', 'Petugas masih memiliki tugas aktif. Nonaktifkan akun saja.');
            }
        }

        // Nonaktifkan daripada hard delete
        $user->update(['is_active' => false]);

        return redirect()
            ->route('admin.users.index')
            ->with('warning', 'Akun dinonaktifkan (bukan dihapus) untuk menjaga integritas data.');
    }

    /**
     * POST /admin/users/{id}/reset-password
     * Generate password acak 10 karakter — flash ke session (tidak dikirim email).
     */
    public function resetPassword(User $user)
    {
        $passwordBaru = Str::random(10);
        $user->update(['password' => Hash::make($passwordBaru)]);

        return redirect()->back()
            ->with('password_baru', $passwordBaru);
    }

    /**
     * POST /admin/users/{id}/toggle-active
     * Toggle status is_active user.
     */
    public function toggleActive(User $user)
    {
        // Cegah admin menonaktifkan dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Akun berhasil {$status}.");
    }
}
