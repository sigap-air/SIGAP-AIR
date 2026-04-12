<x-app-layout>
    <x-slot name="title">Dashboard Petugas</x-slot>

    <h1 class="text-2xl font-bold text-gray-800 mb-6">👷 Dashboard Petugas</h1>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-orange-500">
            <div class="text-3xl font-black text-gray-800">{{ $tugasAktif->count() }}</div>
            <div class="text-sm text-gray-500 mt-1">Tugas Aktif</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
            <div class="text-3xl font-black text-gray-800">{{ $totalSelesai }}</div>
            <div class="text-sm text-gray-500 mt-1">Selesai Bulan Ini</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
            <div class="text-3xl font-black text-gray-800">{{ $totalSemua }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Dikerjakan</div>
        </div>
    </div>

    {{-- Tugas Aktif --}}
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-gray-700">🔧 Tugas Aktif Saya</h2>
            <a href="{{ route('petugas.tugas.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
        </div>

        @forelse ($tugasAktif as $tugas)
        @php
            $sla = $tugas->pengaduan->sla;
            $isOverdue = $sla && $sla->is_overdue;
            $deadline = $sla ? $sla->deadline : null;
        @endphp
        <div class="border rounded-xl p-4 mb-3 {{ $isOverdue ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold text-gray-800">{{ $tugas->pengaduan->nomor_tiket }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $tugas->pengaduan->kategori->nama_kategori }} · {{ $tugas->pengaduan->zona->nama_zona }}
                    </p>
                    @if ($deadline)
                    <p class="text-xs mt-1 {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-400' }}">
                        {{ $isOverdue ? '🚨 Overdue!' : '⏱️ Deadline:' }}
                        {{ $deadline->translatedFormat('d M Y H:i') }} WIB
                    </p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $tugas->status_assignment === 'diproses' ? 'bg-indigo-100 text-indigo-700' : 'bg-orange-100 text-orange-700' }}">
                        {{ ucwords(str_replace('_', ' ', $tugas->status_assignment)) }}
                    </span>
                    <a href="{{ route('petugas.tugas.show', $tugas) }}"
                       class="text-xs bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition">
                        Update
                    </a>
                </div>
            </div>
            @if($tugas->instruksi)
            <p class="text-xs text-gray-600 mt-2 bg-gray-50 rounded p-2">📋 {{ $tugas->instruksi }}</p>
            @endif
        </div>
        @empty
        <div class="text-center py-10 text-gray-400">
            <div class="text-4xl mb-2">🎉</div>
            <p class="text-sm">Tidak ada tugas aktif. Selamat beristirahat!</p>
        </div>
        @endforelse
    </div>
</x-app-layout>
