<x-app-supervisor-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Zona Wilayah</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau distribusi beban kerja antar wilayah secara real-time</p>
        </div>
        {{-- Supervisor: hanya lihat, tidak ada tombol Tambah --}}
        <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-[#022448] text-xs font-semibold rounded-xl border border-blue-100">
            <span class="material-symbols-outlined text-base">visibility</span>
            Mode Lihat Saja
        </span>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
        <span class="material-symbols-outlined text-emerald-500 flex-shrink-0">check_circle</span>
        {{ session('success') }}
    </div>
@endif

{{-- Stats Bar --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-[#022448] text-xl">map</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Zona</p>
            <p class="text-xl font-bold text-gray-900">{{ $zonas->total() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Zona Aktif</p>
            <p class="text-xl font-bold text-gray-900">{{ $zonas->getCollection()->where('is_active', true)->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-violet-600 text-xl">badge</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Petugas</p>
            <p class="text-xl font-bold text-gray-900">{{ $zonas->getCollection()->sum('petugas_count') }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-red-500 text-xl">warning</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Overdue</p>
            <p class="text-xl font-bold text-red-600">{{ $zonas->getCollection()->sum('pengaduan_overdue_count') }}</p>
        </div>
    </div>
</div>

{{-- Data Table Card --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Zona</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Kode</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Petugas</th>
                    <th class="px-6 py-4 text-center font-semibold text-emerald-600 uppercase tracking-wider text-xs">Selesai</th>
                    <th class="px-6 py-4 text-center font-semibold text-amber-600 uppercase tracking-wider text-xs">Aktif</th>
                    <th class="px-6 py-4 text-center font-semibold text-red-600 uppercase tracking-wider text-xs">Overdue</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($zonas as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                        <td class="px-6 py-4 text-gray-500 font-medium">{{ $zonas->firstItem() + $index }}</td>

                        {{-- Nama Zona --}}
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->nama_zona }}</p>
                                @if($item->deskripsi)
                                    <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $item->deskripsi }}</p>
                                @endif
                            </div>
                        </td>

                        {{-- Kode Zona --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-[#022448] rounded-lg text-xs font-mono font-semibold">
                                <span class="material-symbols-outlined text-sm">tag</span>
                                {{ $item->kode_zona }}
                            </span>
                        </td>

                        {{-- Petugas --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->petugas_count > 0)
                                <span class="inline-flex items-center justify-center gap-1 px-3 py-1 bg-violet-50 text-violet-700 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm">badge</span>
                                    {{ $item->petugas_count }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Pengaduan Selesai --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->pengaduan_selesai_count > 0)
                                <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">
                                    {{ $item->pengaduan_selesai_count }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Pengaduan Aktif --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->pengaduan_aktif_count > 0)
                                <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">
                                    {{ $item->pengaduan_aktif_count }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Pengaduan Overdue --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->pengaduan_overdue_count > 0)
                                <span class="inline-flex items-center justify-center gap-1 min-w-[28px] h-7 px-2 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                    <span class="material-symbols-outlined text-xs" style="font-size:12px;">warning</span>
                                    {{ $item->pengaduan_overdue_count }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->is_active)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        {{-- Detail --}}
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('supervisor.zona.show', $item->id) }}"
                               class="p-2 text-[#022448] hover:bg-blue-50 rounded-lg transition-colors inline-flex" title="Lihat Detail">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-gray-300 text-3xl">map</span>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada zona wilayah</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($zonas->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $zonas->withQueryString()->links() }}
        </div>
    @endif
</div>

</x-app-supervisor-layout>
