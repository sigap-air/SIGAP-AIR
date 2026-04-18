<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 font-headline">Dashboard</h1>
    <p class="text-sm text-gray-500 mt-1">Pantau pengaduan masyarakat terbaru secara real-time (WIB).</p>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Pengaduan</p>
        <p class="mt-2 text-3xl font-black text-[#022448]">{{ $kpi['total_masuk'] ?? 0 }}</p>
    </div>
    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Menunggu Verifikasi</p>
        <p class="mt-2 text-3xl font-black text-amber-600">{{ $kpi['menunggu_verifikasi'] ?? 0 }}</p>
    </div>
    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Sedang Diproses</p>
        <p class="mt-2 text-3xl font-black text-sky-600">{{ $kpi['diproses'] ?? 0 }}</p>
    </div>
    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Selesai</p>
        <p class="mt-2 text-3xl font-black text-emerald-600">{{ $kpi['selesai'] ?? 0 }}</p>
    </div>
</div>

{{-- Pengaduan Terbaru --}}
<div class="mt-6 rounded-2xl border border-gray-100 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
        <h2 class="text-base font-semibold text-gray-800">Pengaduan Terbaru</h2>
        <span class="text-xs text-gray-500">Waktu ditampilkan dalam WIB</span>
    </div>

    @if(($pengaduanTerbaru ?? collect())->isEmpty())
        <div class="p-12 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#022448]/5">
                <span class="material-symbols-outlined text-[#022448]/30 text-4xl">inbox</span>
            </div>
            <p class="text-sm text-gray-500">Belum ada pengaduan masuk dari masyarakat.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">No Tiket</th>
                        <th class="px-6 py-3 text-left">Nama Pelapor / Pelanggan</th>
                        <th class="px-6 py-3 text-left">Kategori</th>
                        <th class="px-6 py-3 text-left">Zona</th>
                        <th class="px-6 py-3 text-left">No Telepon</th>
                        <th class="px-6 py-3 text-left">Lokasi</th>
                        <th class="px-6 py-3 text-left">Deskripsi Masalah</th>
                        <th class="px-6 py-3 text-left">Bukti Foto</th>
                        <th class="px-6 py-3 text-left">Waktu Masuk</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pengaduanTerbaru as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-mono text-xs font-semibold text-[#022448]">{{ $p->nomor_tiket }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $p->pelapor->name ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $p->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $p->zona->nama_zona ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $p->pelapor->no_telepon ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ \Illuminate\Support\Str::limit($p->lokasi, 40) }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ \Illuminate\Support\Str::limit($p->deskripsi, 70) }}</td>
                            <td class="px-6 py-3">
                                @if($p->foto_bukti)
                                    <a href="{{ asset('storage/' . $p->foto_bukti) }}" target="_blank" class="group block">
                                        <img src="{{ asset('storage/' . $p->foto_bukti) }}"
                                             alt="Bukti Foto {{ $p->nomor_tiket }}"
                                             class="h-12 w-16 rounded-lg border border-gray-200 object-cover transition group-hover:opacity-80">
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $p->created_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</td>
                            <td class="px-6 py-3">
                                <x-badge-status :status="$p->status" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

</x-app-admin-layout>
