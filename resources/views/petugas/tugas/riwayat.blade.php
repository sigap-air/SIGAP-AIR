{{-- PBI-07 Riwayat Tugas Selesai — styling identik admin --}}
<x-app-petugas-layout>

{{-- Page Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 font-headline">Riwayat Selesai</h1>
    <p class="text-sm text-gray-500 mt-1">Daftar pengaduan yang telah berhasil Anda selesaikan.</p>
</div>

{{-- Table --}}
<div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
    @if ($tugasSelesai->isEmpty())
        <div class="p-12 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#022448]/5">
                <span class="material-symbols-outlined text-[#022448]/30 text-4xl">history</span>
            </div>
            <p class="text-sm font-medium text-gray-600">Belum ada tugas yang diselesaikan</p>
            <p class="text-xs text-gray-400 mt-1">Tugas yang telah Anda selesaikan akan muncul di sini.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">No. Tiket</th>
                        <th class="px-6 py-3 text-left">Kategori</th>
                        <th class="px-6 py-3 text-left">Zona</th>
                        <th class="px-6 py-3 text-left">Tanggal Selesai</th>
                        <th class="px-6 py-3 text-left">SLA</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($tugasSelesai as $tugas)
                    @php
                        $sla = $tugas->pengaduan->sla;
                        $slaTerpenuhi = $sla && $sla->status_sla === 'terpenuhi';
                        $slaOverdue   = $sla && $sla->status_sla === 'overdue';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 font-mono text-xs font-semibold text-[#022448]">{{ $tugas->pengaduan->nomor_tiket }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $tugas->pengaduan->kategori->nama_kategori }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $tugas->pengaduan->zona->nama_zona }}</td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $tugas->tanggal_selesai?->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</td>
                        <td class="px-6 py-3">
                            @if ($slaTerpenuhi)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <span class="material-symbols-outlined text-xs">check</span> Terpenuhi
                                </span>
                            @elseif ($slaOverdue)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                    <span class="material-symbols-outlined text-xs">warning</span> Overdue
                                </span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <a href="{{ route('petugas.tugas.show', $tugas) }}" class="inline-flex items-center gap-1 text-sm text-[#022448] hover:text-[#1e3a5f] font-medium transition-colors">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($tugasSelesai->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $tugasSelesai->links() }}
        </div>
        @endif
    @endif
</div>

</x-app-petugas-layout>
