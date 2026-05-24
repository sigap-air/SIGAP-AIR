@props([
    'petugasList' => collect(),
    'summary' => ['tersedia' => 0, 'sibuk' => 0, 'tidak_aktif' => 0, 'total' => 0],
    'zonaId' => null,
    'pollUrl' => null,
    'compact' => false,
])

@php
    $pollUrl = $pollUrl ?? route('supervisor.petugas.status', $zonaId ? ['zona_id' => $zonaId] : []);
@endphp

<div
    x-data="petugasMonitor({
        pollUrl: @js($pollUrl),
        initial: @js(['summary' => $summary, 'petugas' => $petugasList->values()]),
    })"
    class="rounded-2xl border border-gray-100 bg-white shadow-sm {{ $compact ? 'p-4' : 'p-5' }}"
>
    <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
        <div>
            <h2 class="font-bold text-gray-800">Monitor Status Petugas</h2>
            <p class="mt-0.5 text-xs text-gray-500">
                Pantau ketersediaan sebelum assignment pengaduan.
                <span class="font-medium text-emerald-600">Live</span>
                <span x-text="' · ' + lastUpdated" class="text-gray-400"></span>
            </p>
        </div>
        <a href="{{ route('supervisor.petugas.index', $zonaId ? ['zona_id' => $zonaId] : []) }}"
           class="text-xs font-semibold text-[#0F4C81] hover:underline">Lihat data petugas</a>
    </div>

    <div class="mb-4 grid grid-cols-3 gap-2">
        <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 px-3 py-2 text-center">
            <p class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700">Tersedia</p>
            <p class="text-xl font-black text-emerald-700" x-text="summary.tersedia">{{ $summary['tersedia'] ?? 0 }}</p>
        </div>
        <div class="rounded-xl border border-amber-100 bg-amber-50/60 px-3 py-2 text-center">
            <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-700">Sibuk</p>
            <p class="text-xl font-black text-amber-700" x-text="summary.sibuk">{{ $summary['sibuk'] ?? 0 }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-center">
            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-600">Tidak Aktif</p>
            <p class="text-xl font-black text-gray-600" x-text="summary.tidak_aktif">{{ $summary['tidak_aktif'] ?? 0 }}</p>
        </div>
    </div>

    <div class="max-h-64 space-y-2 overflow-y-auto">
        <template x-if="petugas.length === 0">
            <p class="py-6 text-center text-sm text-gray-400">Belum ada data petugas.</p>
        </template>
        <template x-for="row in petugas" :key="row.id">
            <div class="flex items-center justify-between rounded-xl border border-gray-100 px-3 py-2.5">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-gray-800" x-text="row.nama"></p>
                    <p class="text-xs text-gray-500">
                        <span x-text="row.zona_nama"></span>
                        <span x-show="row.tugas_aktif > 0"> · <span x-text="row.tugas_aktif + ' tugas aktif'"></span></span>
                    </p>
                </div>
                <span
                    class="inline-flex shrink-0 items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold"
                    :class="row.status_badge"
                >
                    <span class="h-1.5 w-1.5 rounded-full" :class="row.status_dot"></span>
                    <span x-text="row.status_label"></span>
                </span>
            </div>
        </template>
    </div>

    <p class="mt-3 text-[11px] text-gray-400">
        Hanya petugas <strong class="text-emerald-700">Tersedia</strong> yang dapat dipilih saat assignment.
        Petugas <strong class="text-gray-600">Tidak Aktif</strong> tidak dapat dipilih.
    </p>
</div>

@include('components.partials.petugas-monitor-script')
