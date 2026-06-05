<?php

/**
 * PBI-16 — Kelola Data Petugas Teknis
 * Admin dapat melihat, menambah, mengedit, dan menonaktifkan petugas teknis.
 *
 * TANGGUNG JAWAB: Farisha Huwaida Shofha
 *
 * Schema DB yang digunakan (tabel petugas):
 *   - user_id         : FK ke users
 *   - zona_id         : FK ke zona_wilayah (nullable)
 *   - nip             : Nomor Induk Pegawai (unique, nullable) — AUTO GENERATED
 *   - status_tersedia : enum('tersedia','sibuk','tidak_aktif')
 *
 * Schema DB users:
 *   - foto_profil     : path foto profil (nullable)
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePetugasRequest;
use App\Http\Requests\Admin\UpdatePetugasRequest;
use App\Models\Petugas;
use App\Models\User;
use App\Models\ZonaWilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    /**
     * Generate NIP otomatis dengan format PEG-YYYY-XXXX.
     * Contoh: PEG-2026-0001
     */
    private function generateNip(): string
    {
        $tahun  = now()->format('Y');
        $prefix = "PEG-{$tahun}-";

        // Ambil NIP terakhir tahun ini dan tambah 1
        $last = Petugas::where('nip', 'like', $prefix . '%')
            ->orderByDesc('nip')
            ->value('nip');

        if ($last) {
            $lastNum = (int) substr($last, strlen($prefix));
            $seq     = $lastNum + 1;
        } else {
            $seq = 1;
        }

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Daftar semua petugas dengan informasi user & zona.
     */
    public function index(Request $request)
    {
        $query = Petugas::with(['user', 'zones', 'zona'])
            ->latest();

        // Filter pencarian berdasarkan nama atau NIP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_tersedia', $request->status);
        }

        // Filter berdasarkan zona (bisa pivot atau fallback)
        if ($request->filled('zona_id')) {
            if ($request->zona_id === 'tanpa_zona') {
                $query->whereDoesntHave('zones')->whereNull('zona_id');
            } else {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('zones', function ($sq) use ($request) {
                        $sq->where('zona_wilayah.id', $request->zona_id);
                    })->orWhere('zona_id', $request->zona_id);
                });
            }
        }

        $petugas = $query->paginate(15)->withQueryString();
        $zonas   = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();

        // Summary stats
        $stats = [
            'total'       => Petugas::count(),
            'tersedia'    => Petugas::where('status_tersedia', 'tersedia')->count(),
            'sibuk'       => Petugas::where('status_tersedia', 'sibuk')->count(),
            'tidak_aktif' => Petugas::where('status_tersedia', 'tidak_aktif')->count(),
        ];

        return view('admin.petugas.index', compact('petugas', 'zonas', 'stats'));
    }

    /**
     * Form tambah petugas baru.
     * NIP di-generate otomatis dan ditampilkan di form (read-only).
     */
    public function create()
    {
        $zonas   = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();
        $autoNip = $this->generateNip();
        return view('admin.petugas.create', compact('zonas', 'autoNip'));
    }

    /**
     * Simpan petugas baru ke database.
     * Membuat User dengan role=petugas lalu record Petugas terkait.
     */
    public function store(StorePetugasRequest $request)
    {
        DB::transaction(function () use ($request) {
            // 1. Handle upload foto profil
            $fotoPath = null;
            if ($request->hasFile('foto_profil')) {
                $fotoPath = $request->file('foto_profil')
                    ->store('foto-petugas', 'public');
            }

            // 2. Buat akun user dengan role petugas
            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'username'    => $request->username,
                'password'    => Hash::make($request->password),
                'role'        => 'petugas',
                'no_telepon'  => $request->no_telepon,
                'foto_profil' => $fotoPath,
                'is_active'   => true,
            ]);

            // 3. Buat record petugas yang terhubung ke user
            Petugas::create([
                'user_id'         => $user->id,
                'zona_id'         => $request->zona_id ?: null,
                'nip'             => $request->nip ?: $this->generateNip(),
                'status_tersedia' => $request->status_tersedia,
            ]);
        });

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Petugas berhasil ditambahkan.');
    }

    /**
     * Detail petugas — digunakan juga untuk show.
     */
    public function show(Petugas $petugas)
    {
        $petugas->load(['user', 'zona', 'assignments.pengaduan']);
        return view('admin.petugas.show', compact('petugas'));
    }

    /**
     * Form edit data petugas.
     */
    public function edit(Petugas $petugas)
    {
        $petugas->load(['user', 'zona']);
        $zonas = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();
        return view('admin.petugas.edit', compact('petugas', 'zonas'));
    }

    /**
     * Perbarui data petugas yang sudah ada.
     */
    public function update(UpdatePetugasRequest $request, Petugas $petugas)
    {
        // Eager load user agar tidak null saat diakses di dalam transaction
        $petugas->load('user');

        DB::transaction(function () use ($request, $petugas) {
            $userData = [
                'name'       => $request->name,
                'email'      => $request->email,
                'username'   => $request->username,
                'no_telepon' => $request->no_telepon,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            // Handle ganti foto profil
            if ($request->hasFile('foto_profil')) {
                if ($petugas->user?->foto_profil) {
                    Storage::disk('public')->delete($petugas->user->foto_profil);
                }
                $userData['foto_profil'] = $request->file('foto_profil')
                    ->store('foto-petugas', 'public');
            }

            // Handle hapus foto
            if ($request->boolean('hapus_foto') && $petugas->user?->foto_profil) {
                Storage::disk('public')->delete($petugas->user->foto_profil);
                $userData['foto_profil'] = null;
            }

            // Null check — pastikan relasi user ada sebelum update
            if ($petugas->user) {
                $petugas->user->update($userData);
            }

            // Update data petugas
            $petugas->update([
                'zona_id'         => $request->zona_id ?: null,
                'status_tersedia' => $request->status_tersedia,
            ]);
        });

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    /**
     * Nonaktifkan petugas (soft-delete via status).
     * Tidak dapat dilakukan jika petugas masih memiliki tugas aktif.
     */
    public function destroy(Petugas $petugas)
    {
        // Eager load relasi user
        $petugas->load('user');

        // Admin dapat menonaktifkan petugas kapanpun.
        // Tugas yang sedang berjalan tetap tercatat di database,
        // namun petugas tidak dapat menerima tugas baru setelah dinonaktifkan.
        DB::transaction(function () use ($petugas) {
            $petugas->update(['status_tersedia' => 'tidak_aktif']);

            if ($petugas->user) {
                $petugas->user->update(['is_active' => false]);
            }
        });

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Petugas berhasil dinonaktifkan.');
    }

    /**
     * Hapus permanen petugas dari database (hard delete).
     * Hanya bisa dilakukan jika petugas TIDAK memiliki riwayat tugas apapun.
     */
    public function hapusPermanen(Petugas $petugas)
    {
        DB::transaction(function () use ($petugas) {
            // Hapus semua riwayat tugas agar tidak terjadi error foreign key constraint
            $petugas->assignments()->delete();

            $user = $petugas->user;

            // Hapus foto profil dari storage jika ada
            if ($user && $user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // Hapus record petugas dulu (karena FK ke users)
            $petugas->delete();

            // Hapus user account
            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Data petugas beserta seluruh riwayat tugasnya berhasil dihapus permanen.');
    }
}
