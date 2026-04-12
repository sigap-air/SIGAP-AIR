<?php
/**
 * PBI-17 — Manajemen Petugas Teknis
 * Admin dapat membuat akun petugas, assign ke zona, dan mengatur status ketersediaan.
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Petugas, Zona};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::with(['user', 'zonas'])
            ->latest()
            ->paginate(15);

        return view('admin.petugas.index', compact('petugas'));
    }

    public function create()
    {
        $zonas = Zona::where('is_active', true)->get();
        return view('admin.petugas.form', compact('zonas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email',
            'password'             => 'required|string|min:8|confirmed',
            'no_telepon'           => 'nullable|string|max:20',
            'nomor_pegawai'        => 'required|string|unique:petugas,nomor_pegawai',
            'status_ketersediaan'  => 'required|in:tersedia,sibuk,tidak_aktif',
            'zona_ids'             => 'array',
            'zona_ids.*'           => 'exists:zonas,id',
        ], [
            'email.unique'          => 'Email sudah digunakan.',
            'nomor_pegawai.unique'  => 'Nomor pegawai sudah terdaftar.',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat user dengan role petugas
            $user = User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => 'petugas',
                'no_telepon' => $request->no_telepon,
                'is_active'  => true,
            ]);

            // 2. Buat record petugas
            $petugas = Petugas::create([
                'user_id'              => $user->id,
                'nomor_pegawai'        => $request->nomor_pegawai,
                'status_ketersediaan'  => $request->status_ketersediaan,
            ]);

            // 3. Sync zona assignment
            if ($request->filled('zona_ids')) {
                $petugas->zonas()->sync($request->zona_ids);
            }
        });

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit(Petugas $petugas)
    {
        $petugas->load(['user', 'zonas']);
        $zonas = Zona::where('is_active', true)->get();
        return view('admin.petugas.form', compact('petugas', 'zonas'));
    }

    public function update(Request $request, Petugas $petugas)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email,' . $petugas->user_id,
            'no_telepon'          => 'nullable|string|max:20',
            'nomor_pegawai'       => 'required|string|unique:petugas,nomor_pegawai,' . $petugas->id,
            'status_ketersediaan' => 'required|in:tersedia,sibuk,tidak_aktif',
            'zona_ids'            => 'array',
            'zona_ids.*'          => 'exists:zonas,id',
            'password'            => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request, $petugas) {
            $userData = [
                'name'       => $request->name,
                'email'      => $request->email,
                'no_telepon' => $request->no_telepon,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $petugas->user->update($userData);
            $petugas->update([
                'nomor_pegawai'       => $request->nomor_pegawai,
                'status_ketersediaan' => $request->status_ketersediaan,
            ]);

            $petugas->zonas()->sync($request->zona_ids ?? []);
        });

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy(Petugas $petugas)
    {
        // Cegah hapus petugas yang punya tugas aktif
        $adalahAktif = $petugas->assignments()
            ->whereIn('status_assignment', ['ditugaskan', 'diproses'])
            ->exists();

        if ($adalahAktif) {
            return back()->withErrors(['error' => 'Petugas tidak dapat dihapus karena masih memiliki tugas aktif.']);
        }

        DB::transaction(function () use ($petugas) {
            $petugas->zonas()->detach();
            $petugas->user->update(['is_active' => false, 'role' => 'petugas']);
            $petugas->update(['status_ketersediaan' => 'tidak_aktif']);
        });

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Petugas berhasil dinonaktifkan.');
    }
}
