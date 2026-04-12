{{-- PBI-05 Antrean Verifikasi --}}
<x-app-layout>
    <x-slot name="title">Verifikasi Pengaduan</x-slot>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">✅ Antrean Verifikasi</h1>
        <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full">
            {{ $pengaduans->total() }} menunggu
        </span>
    </div>

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
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Kategori</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Zona</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Waktu</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($pengaduans as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-mono text-blue-700 font-semibold">{{ $p->nomor_tiket }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $p->pelapor->name }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $p->kategori->nama_kategori }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $p->zona->nama_zona }}</td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $p->tanggal_pengajuan->diffForHumans() }}</td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('supervisor.verifikasi.show', $p) }}"
                           class="inline-block bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                            Periksa
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
