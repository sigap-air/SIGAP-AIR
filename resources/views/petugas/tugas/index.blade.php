{{-- PBI-07 Daftar Tugas Petugas --}}
<x-app-layout>
    <x-slot name="title">Tugas Aktif Saya</x-slot>

    <h1 class="text-2xl font-bold text-gray-800 mb-6">🔧 Tugas Aktif Saya</h1>

    @if ($tugasAktif->isEmpty())
    <div class="bg-white rounded-xl shadow p-10 text-center text-gray-400">
        <div class="text-5xl mb-3">✅</div>
        <p class="font-semibold">Tidak ada tugas aktif</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach ($tugasAktif as $tugas)
        @php
            $sla = $tugas->pengaduan->sla;
            $isOverdue = $sla && $sla->is_overdue;
        @endphp
        <div class="bg-white rounded-xl shadow p-5 border-l-4 {{ $isOverdue ? 'border-red-500' : ($tugas->status_assignment === 'diproses' ? 'border-indigo-500' : 'border-orange-400') }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-mono font-bold text-blue-700">{{ $tugas->pengaduan->nomor_tiket }}</span>
                        @if ($isOverdue)
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full font-semibold">🚨 OVERDUE</span>
                        @endif
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">
                            {{ ucwords(str_replace('_', ' ', $tugas->status_assignment)) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-700">{{ $tugas->pengaduan->kategori->nama_kategori }} · {{ $tugas->pengaduan->zona->nama_zona }}</p>
                    <p class="text-xs text-gray-500 mt-1">📍 {{ $tugas->pengaduan->lokasi }}</p>
                    @if ($sla)
                    <p class="text-xs mt-1 {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-400' }}">
                        ⏱️ Deadline: {{ $sla->deadline->translatedFormat('d M Y H:i') }} WIB
                        ({{ $sla->deadline->diffForHumans() }})
                    </p>
                    @endif
                </div>
                <a href="{{ route('petugas.tugas.show', $tugas) }}"
                   class="ml-4 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition whitespace-nowrap">
                    Update Status
                </a>
            </div>
            @if ($tugas->instruksi)
            <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg px-3 py-2 text-xs text-yellow-800">
                📋 <strong>Instruksi:</strong> {{ $tugas->instruksi }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    @if ($tugasSelesai->isNotEmpty())
    <h2 class="text-lg font-bold text-gray-700 mt-8 mb-4">✅ Riwayat Selesai (5 Terakhir)</h2>
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">No. Tiket</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Kategori</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Zona</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($tugasSelesai as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-blue-700">{{ $t->pengaduan->nomor_tiket }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $t->pengaduan->kategori->nama_kategori }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $t->pengaduan->zona->nama_zona }}</td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $t->tanggal_selesai?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</x-app-layout>
