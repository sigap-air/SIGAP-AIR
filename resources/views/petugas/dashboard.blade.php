{{-- PBI-07 Dashboard Petugas — styling identik admin --}}
<x-app-petugas-layout>

{{-- Page Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 font-headline">Dashboard</h1>
    <p class="text-sm text-gray-500 mt-1">Pantau tugas dan progres penanganan pengaduan Anda.</p>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 gap-4 md:grid-cols-3">
    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600 text-xl">pending_actions</span>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tugas Aktif</p>
                <p class="mt-1 text-3xl font-black text-amber-600">{{ $tugasAktif->count() }}</p>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Selesai Bulan Ini</p>
                <p class="mt-1 text-3xl font-black text-emerald-600">{{ $totalSelesai }}</p>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-sky-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-sky-600 text-xl">leaderboard</span>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Dikerjakan</p>
                <p class="mt-1 text-3xl font-black text-sky-600">{{ $totalSemua }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Tugas Aktif Table --}}
<div class="mt-6 rounded-2xl border border-gray-100 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
        <h2 class="text-base font-semibold text-gray-800">Tugas Aktif Saya</h2>
        <a href="{{ route('petugas.tugas.index') }}" class="text-sm text-[#022448] hover:text-[#1e3a5f] font-medium">Lihat Semua →</a>
    </div>

    @if ($tugasAktif->isEmpty())
        <div class="p-12 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#022448]/5">
                <span class="material-symbols-outlined text-[#022448]/30 text-4xl">celebration</span>
            </div>
            <p class="text-sm text-gray-500">Tidak ada tugas aktif. Selamat beristirahat!</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">No Tiket</th>
                        <th class="px-6 py-3 text-left">Kategori</th>
                        <th class="px-6 py-3 text-left">Zona</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">SLA Deadline</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($tugasAktif as $tugas)
                    @php
                        $sla = $tugas->pengaduan->sla;
                        $isOverdue = $sla && $sla->is_overdue;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $isOverdue ? 'bg-red-50/50' : '' }}">
                        <td class="px-6 py-3 font-mono text-xs font-semibold text-[#022448]">{{ $tugas->pengaduan->nomor_tiket }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $tugas->pengaduan->kategori->nama_kategori }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $tugas->pengaduan->zona->nama_zona }}</td>
                        <td class="px-6 py-3">
                            @if ($isOverdue)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                    <span class="material-symbols-outlined text-xs">warning</span> Overdue
                                </span>
                            @elseif ($tugas->status_assignment === 'diproses')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-700 border border-sky-200">
                                    Sedang Diproses
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200">
                                    Ditugaskan
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            @if ($sla)
                                <span class="text-xs {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                    {{ $sla->deadline->translatedFormat('d M Y H:i') }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <a href="{{ route('petugas.tugas.show', $tugas) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#022448] text-white text-xs font-semibold rounded-lg hover:bg-[#1e3a5f] transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-sm">edit_note</span>
                                Update
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

</x-app-petugas-layout>
