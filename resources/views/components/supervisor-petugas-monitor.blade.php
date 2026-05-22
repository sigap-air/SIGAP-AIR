@props([
    'petugasList' => collect(),
    'summary' => ['available' => 0, 'on_duty' => 0, 'off' => 0, 'total' => 0],
    'zonaId' => null,
    'pollUrl' => null,
    'compact' => false,
])

@php
    $pollUrl = $pollUrl ?? route('supervisor.monitor-petugas.status', $zonaId ? ['zona_id' => $zonaId] : []);
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
        <a href="{{ route('supervisor.monitor-petugas.index', $zonaId ? ['zona_id' => $zonaId] : []) }}"
           class="text-xs font-semibold text-[#0F4C81] hover:underline">Lihat detail</a>
    </div>

    <div class="mb-4 grid grid-cols-3 gap-2">
        <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 px-3 py-2 text-center">
            <p class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700">Available</p>
            <p class="text-xl font-black text-emerald-700" x-text="summary.available">{{ $summary['available'] }}</p>
        </div>
        <div class="rounded-xl border border-amber-100 bg-amber-50/60 px-3 py-2 text-center">
            <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-700">On-Duty</p>
            <p class="text-xl font-black text-amber-700" x-text="summary.on_duty">{{ $summary['on_duty'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-center">
            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-600">Off</p>
            <p class="text-xl font-black text-gray-600" x-text="summary.off">{{ $summary['off'] }}</p>
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
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2 py-0.5 text-xs font-semibold"
                    :class="row.status_badge"
                >
                    <span class="h-2 w-2 rounded-full animate-pulse" :class="row.status_dot"></span>
                    <span x-text="row.status_label"></span>
                </span>
            </div>
        </template>
    </div>

    <p class="mt-3 text-[11px] text-gray-400">
        Hanya petugas <strong class="text-emerald-700">Available</strong> yang dapat dipilih saat assignment.
        Petugas <strong class="text-gray-600">Off</strong> dan <strong class="text-amber-700">On-Duty</strong> tidak dapat dipilih.
    </p>
</div>

@once
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('petugasMonitor', ({ pollUrl, initial }) => ({
                pollUrl,
                summary: initial.summary,
                petugas: initial.petugas,
                lastUpdated: 'baru saja',
                timer: null,
                init() {
                    this.refresh();
                    this.timer = setInterval(() => this.refresh(), 10000);
                },
                destroy() {
                    if (this.timer) clearInterval(this.timer);
                },
                async refresh() {
                    try {
                        const response = await fetch(this.pollUrl, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        });
                        if (!response.ok) return;
                        const data = await response.json();
                        this.summary = data.summary;
                        this.petugas = data.petugas;
                        const at = new Date(data.updated_at);
                        this.lastUpdated = at.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    } catch (e) {
                        console.error('Monitor petugas:', e);
                    }
                },
            }));
        });
    </script>
    @endpush
@endonce
