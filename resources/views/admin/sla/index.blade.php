<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-2">
        <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
            <span class="material-symbols-outlined text-white text-xl">timer</span>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Konfigurasi SLA</h1>
            <p class="text-sm text-gray-500">Atur batas waktu penanganan per kategori pengaduan</p>
        </div>
    </div>
</div>

{{-- Statistik Ringkas --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-blue-500 text-lg">category</span>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total</span>
        </div>
        <p class="text-2xl font-black text-gray-900">{{ $stats['total_kategori'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Kategori</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-emerald-500 text-lg">check_circle</span>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Aktif</span>
        </div>
        <p class="text-2xl font-black text-emerald-600">{{ $stats['kategori_aktif'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Kategori Aktif</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-amber-500 text-lg">avg_pace</span>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Rerata</span>
        </div>
        <p class="text-2xl font-black text-amber-600">{{ $stats['rata_sla'] ?? 0 }}</p>
        <p class="text-xs text-gray-400 mt-1">Jam (rata-rata)</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-sky-500 text-lg">speed</span>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Tercepat</span>
        </div>
        <p class="text-2xl font-black text-sky-600">{{ $stats['sla_terpendek'] ?? 0 }}</p>
        <p class="text-xs text-gray-400 mt-1">Jam (minimum)</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-indigo-500 text-lg">hourglass_bottom</span>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Terlama</span>
        </div>
        <p class="text-2xl font-black text-indigo-600">{{ $stats['sla_terpanjang'] ?? 0 }}</p>
        <p class="text-xs text-gray-400 mt-1">Jam (maksimum)</p>
    </div>
    <div class="bg-white rounded-2xl border border-red-100 p-4 shadow-sm hover:shadow-md transition-shadow {{ $stats['overdue_aktif'] > 0 ? 'ring-2 ring-red-200 animate-pulse' : '' }}">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-red-500 text-lg">warning</span>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Overdue</span>
        </div>
        <p class="text-2xl font-black text-red-600">{{ $stats['overdue_aktif'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Pengaduan Overdue</p>
    </div>
</div>

{{-- Info Banner --}}
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-5 mb-6 flex items-start gap-4">
    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
        <span class="material-symbols-outlined text-blue-600 text-xl">info</span>
    </div>
    <div>
        <h3 class="text-sm font-semibold text-blue-900 mb-1">Tentang Konfigurasi SLA</h3>
        <p class="text-xs text-blue-700 leading-relaxed">
            SLA (Service Level Agreement) menentukan batas waktu penanganan untuk setiap kategori pengaduan.
            Ketika pengaduan melewati batas waktu SLA, sistem akan <strong>otomatis menandai sebagai overdue</strong>
            dan mengirim alert ke Supervisor agar segera ditindaklanjuti.
        </p>
    </div>
</div>

{{-- Tabel Kategori & SLA --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-gray-400 text-lg">list_alt</span>
            Daftar Kategori & Batas SLA
        </h2>
        <span class="text-xs text-gray-400">{{ $kategoris->total() }} kategori terdaftar</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tabel-sla-kategori">
            <thead class="bg-gray-50/80 text-xs uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="text-left px-6 py-3 font-semibold">Kategori</th>
                    <th class="text-left px-6 py-3 font-semibold">Deskripsi</th>
                    <th class="text-center px-6 py-3 font-semibold">Batas SLA</th>
                    <th class="text-center px-6 py-3 font-semibold">Status</th>
                    <th class="text-center px-6 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($kategoris as $k)
                <tr class="hover:bg-blue-50/30 transition-colors group" id="kategori-row-{{ $k->id }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#022448] to-[#1e3a5f] rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-bold">{{ strtoupper(substr($k->nama_kategori, 0, 2)) }}</span>
                            </div>
                            <span class="font-semibold text-gray-800 group-hover:text-[#022448] transition-colors">{{ $k->nama_kategori }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs max-w-xs truncate">{{ $k->deskripsi ?? '—' }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full
                            {{ $k->sla_jam <= 12 ? 'bg-red-50 text-red-700 ring-1 ring-red-200' :
                               ($k->sla_jam <= 24 ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' :
                                'bg-sky-50 text-sky-700 ring-1 ring-sky-200') }}">
                            <span class="material-symbols-outlined text-sm">schedule</span>
                            <span class="font-bold text-sm">{{ $k->sla_jam }} jam</span>
                        </div>
                        @if($k->sla_jam <= 12)
                            <p class="text-[10px] text-red-400 mt-1 font-medium">Prioritas Tinggi</p>
                        @elseif($k->sla_jam <= 24)
                            <p class="text-[10px] text-amber-400 mt-1 font-medium">Prioritas Sedang</p>
                        @else
                            <p class="text-[10px] text-sky-400 mt-1 font-medium">Prioritas Normal</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if ($k->is_active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold ring-1 ring-emerald-200">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-50 text-gray-500 rounded-full text-xs font-semibold ring-1 ring-gray-200">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.sla.edit', $k) }}" id="edit-sla-{{ $k->id }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#022448] text-white rounded-lg text-xs font-semibold hover:bg-[#1e3a5f] transition-all duration-200 shadow-sm hover:shadow-md">
                            <span class="material-symbols-outlined text-sm">edit</span> Edit SLA
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-16">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-gray-300 text-3xl">folder_off</span>
                            </div>
                            <p class="text-gray-500 font-medium mb-1">Belum ada kategori</p>
                            <p class="text-xs text-gray-400">Tambahkan kategori pengaduan terlebih dahulu</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kategoris->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">{{ $kategoris->links() }}</div>
    @endif
</div>

</x-app-admin-layout>
