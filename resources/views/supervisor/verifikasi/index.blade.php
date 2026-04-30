{{-- PBI-05 Antrean Verifikasi --}}
<x-app-layout>
    <x-slot name="title">Verifikasi Pengaduan</x-slot>

    <div class="mb-4">
        <a href="{{ route('supervisor.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-[#022448]">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white">←</span>
            <span>Kembali ke Dashboard Supervisor</span>
        </a>
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">✅ Antrean Verifikasi</h1>
        <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full">
            {{ $pengaduans->total() }} menunggu
        </span>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        @if ($pengaduans->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <div class="text-5xl mb-3">🎉</div>
                <p class="font-semibold">Tidak ada pengaduan yang menunggu verifikasi</p>
            </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">No. Tiket</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Pelapor</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">No. Telepon</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Kategori</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Zona</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Foto Bukti</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Waktu</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($pengaduans as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-mono text-blue-700 font-semibold">{{ $p->nomor_tiket }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $p->pelapor?->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600 font-mono text-xs">{{ $p->pelapor?->no_telepon ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $p->kategori?->nama_kategori ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $p->zona?->nama_zona ?? '-' }}</td>
                    <td class="px-5 py-3">
                        @if($p->foto_bukti)
                            <a href="{{ asset('storage/' . $p->foto_bukti) }}" target="_blank" class="inline-block">
                                <img src="{{ asset('storage/' . $p->foto_bukti) }}"
                                     alt="Foto bukti {{ $p->nomor_tiket }}"
                                     class="h-10 w-14 rounded-lg border border-gray-200 object-cover hover:opacity-90">
                            </a>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $p->tanggal_pengajuan->diffForHumans() }}</td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('supervisor.verifikasi.show', $p) }}"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                           title="Lihat Detail">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3C5 3 1.73 7.11.46 9.29a1.35 1.35 0 000 1.42C1.73 12.89 5 17 10 17s8.27-4.11 9.54-6.29a1.35 1.35 0 000-1.42C18.27 7.11 15 3 10 3zm0 11a4 4 0 110-8 4 4 0 010 8zm0-2.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-4 border-t">
            {{ $pengaduans->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
