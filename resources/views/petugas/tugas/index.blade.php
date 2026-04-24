{{-- PBI-07 Daftar Tugas Petugas — styling identik admin --}}
<x-app-petugas-layout>

{{-- Page Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 font-headline">Tugas Aktif</h1>
    <p class="text-sm text-gray-500 mt-1">Daftar pengaduan yang ditugaskan kepada Anda dan perlu ditindaklanjuti.</p>
</div>

{{-- Tugas Aktif Cards --}}
@if ($tugasAktif->isEmpty())
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-12 text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#022448]/5">
            <span class="material-symbols-outlined text-[#022448]/30 text-4xl">task_alt</span>
        </div>
        <p class="text-sm font-semibold text-gray-700">Tidak ada tugas aktif</p>
        <p class="text-xs text-gray-400 mt-1">Semua pengaduan sudah ditangani. Kerja bagus!</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($tugasAktif as $tugas)
        @php
            $sla = $tugas->pengaduan->sla;
            $isOverdue = $sla && $sla->is_overdue;
        @endphp
        <div class="rounded-2xl border bg-white shadow-sm overflow-hidden transition-all hover:shadow-md
            {{ $isOverdue ? 'border-red-200' : 'border-gray-100' }}">

            {{-- Colored top accent --}}
            <div class="h-1 {{ $isOverdue ? 'bg-red-500' : ($tugas->status_assignment === 'diproses' ? 'bg-sky-500' : 'bg-amber-400') }}"></div>

            <div class="p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        {{-- Title row --}}
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <span class="font-mono font-bold text-[#022448] text-sm">{{ $tugas->pengaduan->nomor_tiket }}</span>
                            @if ($isOverdue)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                    <span class="material-symbols-outlined text-xs">warning</span> OVERDUE
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                {{ $tugas->status_assignment === 'diproses'
                                    ? 'bg-sky-50 text-sky-700 border-sky-200'
                                    : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                {{ ucwords(str_replace('_', ' ', $tugas->status_assignment)) }}
                            </span>
                        </div>

                        {{-- Metadata --}}
                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-1">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm text-gray-400">category</span>
                                {{ $tugas->pengaduan->kategori->nama_kategori }}
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm text-gray-400">location_on</span>
                                {{ $tugas->pengaduan->zona->nama_zona }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">
                            <span class="material-symbols-outlined text-xs text-gray-400 align-text-bottom">pin_drop</span>
                            {{ $tugas->pengaduan->lokasi }}
                        </p>

                        {{-- SLA Deadline --}}
                        @if ($sla)
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="material-symbols-outlined text-sm {{ $isOverdue ? 'text-red-500' : 'text-gray-400' }}">timer</span>
                            <span class="text-xs {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                Deadline: {{ $sla->deadline->translatedFormat('d M Y H:i') }} WIB
                                ({{ $sla->deadline->diffForHumans() }})
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Action --}}
                    <a href="{{ route('petugas.tugas.show', $tugas) }}"
                       class="flex-shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#022448] text-white text-sm font-semibold rounded-xl hover:bg-[#1e3a5f] transition-all shadow-sm hover:shadow-md">
                        <span class="material-symbols-outlined text-lg">edit_note</span>
                        Update Status
                    </a>
                </div>

                {{-- Instruksi Supervisor --}}
                @if ($tugas->instruksi)
                <div class="mt-3 bg-amber-50 border border-amber-100 rounded-xl px-4 py-2.5 flex items-start gap-2">
                    <span class="material-symbols-outlined text-amber-600 text-lg mt-0.5">description</span>
                    <div>
                        <p class="text-xs font-semibold text-amber-700">Instruksi Supervisor</p>
                        <p class="text-xs text-amber-800 mt-0.5">{{ $tugas->instruksi }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- Riwayat Selesai --}}
@if ($tugasSelesai->isNotEmpty())
<div class="mt-8 rounded-2xl border border-gray-100 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
        <h2 class="text-base font-semibold text-gray-800">Riwayat Selesai (5 Terakhir)</h2>
        <a href="{{ route('petugas.riwayat') }}" class="text-sm text-[#022448] hover:text-[#1e3a5f] font-medium">Lihat Semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-6 py-3 text-left">No. Tiket</th>
                    <th class="px-6 py-3 text-left">Kategori</th>
                    <th class="px-6 py-3 text-left">Zona</th>
                    <th class="px-6 py-3 text-left">Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($tugasSelesai as $t)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3 font-mono text-xs font-semibold text-[#022448]">{{ $t->pengaduan->nomor_tiket }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $t->pengaduan->kategori->nama_kategori }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $t->pengaduan->zona->nama_zona }}</td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $t->tanggal_selesai?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

</x-app-petugas-layout>
