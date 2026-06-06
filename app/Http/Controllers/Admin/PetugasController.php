<?php

/**
 * PBI-16 — Kelola Data Petugas Teknis
 * PBI-17 — Manajemen Petugas Teknis (status ketersediaan & histori penugasan)
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
use App\Http\Requests\Admin\UpdatePetugasStatusRequest;
use App\Models\Petugas;
use App\Models\User;
use App\Models\ZonaWilayah;
use App\Services\PetugasManajemenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    public function __construct(
        private PetugasManajemenService $manajemenService
    ) {}

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
        $data = $this->manajemenService->indexData($request);

        return view('admin.petugas.index', array_merge($data, [
            'readOnly'    => false,
            'routePrefix' => 'admin.petugas',
        ]));
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
        $petugas->load(['user', 'zona']);

        $histori = $petugas->assignments()
            ->with(['pengaduan.kategori', 'pengaduan.rating'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kinerja = $this->manajemenService->getKinerjaPetugas($petugas);

        return view('admin.petugas.show', compact('petugas', 'histori', 'kinerja') + [
            'readOnly'    => false,
            'routePrefix' => 'admin.petugas',
        ]);
    }

    /**
     * Ubah status ketersediaan petugas (PBI-17).
     */
    public function updateStatus(UpdatePetugasStatusRequest $request, Petugas $petugas)
    {
        $petugas->load('user');

        DB::transaction(function () use ($request, $petugas) {
            $petugas->update(['status_tersedia' => $request->status_tersedia]);

            if ($petugas->user) {
                $petugas->user->update([
                    'is_active' => $request->status_tersedia !== 'tidak_aktif',
                ]);
            }
        });

        $redirectRoute = auth()->user()->isSupervisor()
            ? 'supervisor.petugas.index'
            : 'admin.petugas.index';

        return redirect()
            ->back()
            ->with('success', 'Status ketersediaan petugas berhasil diperbarui.');
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
     * Tidak dapat dilakukan jika petugas masih memiliki tugas yang belum terselesaikan.
     */
    public function destroy(Petugas $petugas)
    {
        $petugas->load('user');

        // Cek tugas aktif (belum selesai) sebelum nonaktifkan
        $tugasAktif = $petugas->assignmentsAktif()->count();
        if ($tugasAktif > 0) {
            return redirect()
                ->back()
                ->with('error', "Petugas {$petugas->user?->name} tidak dapat dinonaktifkan karena masih memiliki {$tugasAktif} tugas yang belum terselesaikan.");
        }

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
     * Diblokir jika petugas masih memiliki tugas yang belum terselesaikan.
     */
    public function hapusPermanen(Petugas $petugas)
    {
        $petugas->load('user');

        // Cek tugas aktif — hapus permanen DILARANG jika masih ada tugas belum selesai
        $tugasAktif = $petugas->assignmentsAktif()->count();
        if ($tugasAktif > 0) {
            return redirect()
                ->back()
                ->with('error', "Petugas {$petugas->user?->name} tidak dapat dihapus karena masih memiliki {$tugasAktif} tugas yang belum terselesaikan.");
        }

        DB::transaction(function () use ($petugas) {
            // Hapus semua riwayat tugas (yang sudah selesai) agar tidak ada FK conflict
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
