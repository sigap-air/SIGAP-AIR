{{-- PBI-09 — Monitor SLA & Alert Overdue Otomatis --}}
<x-app-supervisor-layout>

    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-gradient-to-br from-[#0F4C81] to-[#1a6bb5] rounded-xl flex items-center justify-center shadow-lg shadow-[#0F4C81]/20">
                <span class="material-symbols-outlined text-white text-xl">timer</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-headline">Monitor SLA</h1>
                <p class="text-sm text-gray-500">Pantau status SLA semua pengaduan secara real-time</p>
            </div>
        </div>
    </div>

    {{-- SLA Status Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">SLA Berjalan</span>
                <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-sky-600 text-lg">schedule</span>
                </div>
            </div>
            <p class="text-3xl font-black text-sky-600">{{ $stats['total_berjalan'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Masih dalam batas waktu</p>
        </div>

        <div class="bg-white rounded-2xl border {{ $stats['total_overdue'] > 0 ? 'border-red-200 ring-2 ring-red-100' : 'border-gray-100' }} p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide {{ $stats['total_overdue'] > 0 ? 'text-red-400' : 'text-gray-400' }}">Overdue</span>
                <div class="w-8 h-8 {{ $stats['total_overdue'] > 0 ? 'bg-red-100 animate-pulse' : 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined {{ $stats['total_overdue'] > 0 ? 'text-red-600' : 'text-gray-400' }} text-lg">warning</span>
                </div>
            </div>
            <p class="text-3xl font-black {{ $stats['total_overdue'] > 0 ? 'text-red-600' : 'text-gray-300' }}">{{ $stats['total_overdue'] }}</p>
            <p class="text-xs {{ $stats['total_overdue'] > 0 ? 'text-red-400' : 'text-gray-400' }} mt-1">Melewati batas SLA</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Terpenuhi</span>
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-600 text-lg">check_circle</span>
                </div>
            </div>
            <p class="text-3xl font-black text-emerald-600">{{ $stats['total_terpenuhi'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Selesai tepat waktu</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Perlu Tindakan</span>
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-amber-600 text-lg">flag</span>
                </div>
            </div>
            <p class="text-3xl font-black text-amber-600">{{ $stats['flagged'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Ditandai untuk eskalasi</p>
        </div>
    </div>

    {{-- Overdue Alert Banner --}}
    @if($stats['total_overdue'] > 0)
    <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-2xl p-5 mb-6 flex items-start gap-4" id="alert-overdue-banner">
        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0 animate-pulse">
            <span class="material-symbols-outlined text-red-600 text-xl">crisis_alert</span>
        </div>
        <div>
            <h3 class="text-sm font-bold text-red-800 mb-1">⚠️ {{ $stats['total_overdue'] }} Pengaduan Melewati Batas SLA!</h3>
            <p class="text-xs text-red-600 leading-relaxed">
                Terdapat pengaduan yang belum ditangani melewati batas waktu SLA.
                Segera tindak lanjuti untuk menghindari eskalasi lebih lanjut.
            </p>
        </div>
    </div>
    @endif

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 overflow-hidden">
        <div class="p-5">
            <form method="GET" action="{{ route('supervisor.monitor-sla.index') }}" class="flex flex-wrap items-end gap-4" id="form-filter-sla">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Cari Tiket</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Cari nomor tiket..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0F4C81]/20 focus:border-[#0F4C81]">
                    </div>
                </div>
                <div class="w-48">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Status SLA</label>
                    <select name="status_sla" class="w-full py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0F4C81]/20 focus:border-[#0F4C81]">
                        <option value="semua" {{ $filterSla === 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="berjalan" {{ $filterSla === 'berjalan' ? 'selected' : '' }}>🟢 Berjalan</option>
                        <option value="overdue" {{ $filterSla === 'overdue' ? 'selected' : '' }}>🔴 Overdue</option>
                        <option value="terpenuhi" {{ $filterSla === 'terpenuhi' ? 'selected' : '' }}>✅ Terpenuhi</option>
                    </select>
                </div>
                <div class="w-48">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Kategori</label>
                    <select name="kategori_id" class="w-full py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0F4C81]/20 focus:border-[#0F4C81]">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ $filterKategori == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#0F4C81] text-white rounded-xl text-sm font-semibold hover:bg-[#0D3F6E] transition shadow-sm">
                        <span class="material-symbols-outlined text-sm">filter_alt</span>
                        Filter
                    </button>
                    <a href="{{ route('supervisor.monitor-sla.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Pengaduan + SLA --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-gray-400 text-lg">table_chart</span>
                Daftar Pengaduan & Status SLA
            </h2>
            <span class="text-xs text-gray-400">{{ $pengaduans->total() }} pengaduan</span>
        </div>

        @if($pengaduans->isEmpty())
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-gray-300 text-3xl">check_circle</span>
                </div>
                <p class="text-gray-500 font-medium mb-1">Tidak ada data</p>
                <p class="text-xs text-gray-400">Coba ubah filter pencarian Anda</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="tabel-monitor-sla">
                <thead class="bg-gray-50/80 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">No. Tiket</th>
                        <th class="text-left px-6 py-3 font-semibold">Pelapor</th>
                        <th class="text-left px-6 py-3 font-semibold">Kategori</th>
                        <th class="text-left px-6 py-3 font-semibold">Zona</th>
                        <th class="text-center px-6 py-3 font-semibold">Status SLA</th>
                        <th class="text-center px-6 py-3 font-semibold">Batas Waktu</th>
                        <th class="text-center px-6 py-3 font-semibold">Sisa Waktu</th>
                        <th class="text-center px-6 py-3 font-semibold">Status Pengaduan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($pengaduans as $p)
                    @php
                        $sla = $p->sla;
                        $isOverdue = $sla && $sla->status_sla === 'overdue';
                        $isTerpenuhi = $sla && $sla->status_sla === 'terpenuhi';
                        $isBerjalan = $sla && $sla->status_sla === 'berjalan';
                        $sisaWaktu = null;
                        $sisaNegatif = false;
                        if ($sla && $sla->batas_waktu) {
                            $sisaWaktu = now()->diff($sla->batas_waktu);
                            $sisaNegatif = now()->greaterThan($sla->batas_waktu);
                        }
                    @endphp
                    <tr class="hover:bg-{{ $isOverdue ? 'red' : 'blue' }}-50/30 transition-colors {{ $isOverdue ? 'bg-red-50/20' : '' }}" id="sla-row-{{ $p->id }}">
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm font-bold {{ $isOverdue ? 'text-red-700' : 'text-[#0F4C81]' }}">{{ $p->nomor_tiket }}</span>
                            @if($isOverdue && $sla->is_flagged)
                                <span class="inline-block ml-1 text-red-500 animate-pulse" title="Ditandai untuk eskalasi">🚩</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700 text-xs">{{ $p->pelapor->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700 text-xs">{{ $p->kategori->nama_kategori ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700 text-xs">{{ $p->zona->nama_zona ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($isOverdue)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold ring-1 ring-red-200 animate-pulse">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> OVERDUE
                                </span>
                            @elseif($isTerpenuhi)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold ring-1 ring-emerald-200">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Terpenuhi
                                </span>
                            @elseif($isBerjalan)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-sky-100 text-sky-700 rounded-full text-xs font-semibold ring-1 ring-sky-200">
                                    <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span> Berjalan
                                </span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-gray-600">
                            @if($sla && $sla->batas_waktu)
                                {{ $sla->batas_waktu->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}
                                <p class="text-[10px] text-gray-400 mt-0.5">WIB</p>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($isTerpenuhi)
                                <span class="text-xs text-emerald-600 font-semibold">✅ Selesai</span>
                            @elseif($sla && $sla->batas_waktu)
                                @if($sisaNegatif)
                                    <span class="text-xs font-bold text-red-600">
                                        -{{ $sisaWaktu->d > 0 ? $sisaWaktu->d . 'h ' : '' }}{{ $sisaWaktu->h }}j {{ $sisaWaktu->i }}m
                                    </span>
                                    <p class="text-[10px] text-red-400 mt-0.5">Terlampaui</p>
                                @else
                                    @php $totalJamSisa = $sisaWaktu->d * 24 + $sisaWaktu->h; @endphp
                                    <span class="text-xs font-bold {{ $totalJamSisa <= 2 ? 'text-amber-600' : 'text-sky-600' }}">
                                        {{ $sisaWaktu->d > 0 ? $sisaWaktu->d . 'h ' : '' }}{{ $sisaWaktu->h }}j {{ $sisaWaktu->i }}m
                                    </span>
                                    @if($totalJamSisa <= 2)
                                        <p class="text-[10px] text-amber-400 mt-0.5">⚠️ Hampir habis</p>
                                    @endif
                                @endif
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <x-badge-status :status="$p->status" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($pengaduans->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">{{ $pengaduans->links() }}</div>
        @endif
        @endif
    </div>

    {{-- Info Footer --}}
    <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3">
        <span class="material-symbols-outlined text-blue-500 flex-shrink-0 mt-0.5">info</span>
        <div class="text-xs text-blue-700">
            <p class="font-semibold mb-1">Sistem Otomatis SLA</p>
            <p>Sistem memeriksa status SLA setiap <strong>15 menit</strong>. Pengaduan yang melewati batas waktu akan otomatis ditandai overdue dan notifikasi dikirim ke semua supervisor.</p>
        </div>
    </div>

</x-app-supervisor-layout>
