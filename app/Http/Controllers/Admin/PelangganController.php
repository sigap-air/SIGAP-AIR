<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\ZonaWilayah;
use App\Http\Requests\Admin\StorePelangganRequest;
use App\Http\Requests\Admin\UpdatePelangganRequest;
use App\Services\PengaduanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['zona_id', 'is_active', 'search']);
        $pelanggan = Pelanggan::filter($filters)
            ->with(['zona', 'user', 'latestPengaduan.kategori'])
            ->paginate(15);
        $zonas = ZonaWilayah::where('is_active', true)->get();

        return view('admin.pelanggan.index', compact('pelanggan', 'zonas', 'filters'));
    }

    public function create()
    {
        $zonas = ZonaWilayah::where('is_active', true)->get();
        $kategoris = Kategori::where('is_active', true)->orderBy('nama_kategori')->get();
        return view('admin.pelanggan.create', compact('zonas', 'kategoris'));
    }

    public function store(StorePelangganRequest $request, PengaduanService $pengaduanService)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated, $pengaduanService) {
            // Akun masyarakat (diperlukan agar pengaduan punya user_id seperti gform).
            $email    = 'plg_' . Str::lower(Str::random(14)) . '@sigapair.local';
            $username = 'plg_' . Str::lower(Str::random(12));

            $user = User::create([
                'name'        => $validated['nama_pelanggan'],
                'username'    => $username,
                'email'       => $email,
                'password'    => Hash::make('password'),
                'role'        => 'masyarakat',
                'no_telepon'  => $validated['no_telepon'],
                'is_active'   => (bool) ($validated['is_active'] ?? true),
            ]);

            Pelanggan::create([
                'user_id'         => $user->id,
                'zona_id'         => $validated['zona_id'],
                'nama_pelanggan'  => $validated['nama_pelanggan'],
                'alamat'          => $validated['alamat'],
                'nomor_sambungan' => $validated['nomor_sambungan'],
                'no_telepon'      => $validated['no_telepon'],
                'is_active'       => (bool) ($validated['is_active'] ?? true),
            ]);

            // Satu entri pengaduan agar kolom di admin selaras dengan isi gform masyarakat.
            $pengaduanService->buat(
                [
                    'kategori_id' => $validated['kategori_id'],
                    'zona_id'     => $validated['zona_id'],
                    'lokasi'      => $validated['alamat'],
                    'deskripsi'   => $validated['deskripsi'],
                    'foto_bukti'  => $request->file('foto_bukti'),
                    'no_telepon'  => $validated['no_telepon'],
                ],
                $user,
                false
            );
        });

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Data pelanggan dan pengaduan awal berhasil ditambahkan (sinkron dengan form masyarakat).');
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with(['zona', 'user', 'pengaduan', 'latestPengaduan'])->findOrFail($id);
        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $zonas = ZonaWilayah::where('is_active', true)->get();
        return view('admin.pelanggan.edit', compact('pelanggan', 'zonas'));
    }

    public function update(UpdatePelangganRequest $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        $data = $request->validated();
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        }

        $pelanggan->update($data);

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $aktif = $pelanggan->pengaduan()
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->count();

        if ($aktif > 0) {
            return redirect()->back()
                ->with('error', "Pelanggan tidak dapat dihapus karena masih memiliki {$aktif} pengaduan aktif.");
        }

        $pelanggan->delete();
        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Data pelanggan dihapus.');
    }
}
