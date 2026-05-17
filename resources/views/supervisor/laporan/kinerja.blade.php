{{-- PBI-18 Laporan Kinerja Petugas --}}
<x-app-supervisor-layout>
    <x-slot name="title">Laporan Kinerja Petugas</x-slot>

    @php
        $kinerjaIndexRoute = auth()->user()->isAdmin() ? 'admin.kinerja.index' : 'supervisor.kinerja.index';
        $kinerjaExportRoute = auth()->user()->isAdmin() ? 'admin.kinerja.export-excel' : 'supervisor.kinerja.export-excel';
    @endphp

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-2xl font-bold text-gray-800">📊 Laporan Kinerja Petugas</h1>
        <a href="{{ route($kinerjaExportRoute, request()->all()) }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
            📥 Export CSV
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="bg-white rounded-xl shadow p-4 mb-5 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-600 mb-1">Zona</label>
            <select name="zona_id" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Zona</option>
                @foreach ($data['zonas'] as $z)
                <option value="{{ $z->id }}" {{ request('zona_id') == $z->id ? 'selected':'' }}>{{ $z->nama_zona }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ request('dari') }}" class="border rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="border rounded-lg px-3 py-2 text-sm">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition">Filter</button>
        <a href="{{ route($kinerjaIndexRoute) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition">Reset</a>
    </form>

    {{-- Tabel Kinerja --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Nama Petugas</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">No. Pegawai</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Total Tugas</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Selesai</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Rata Waktu (Jam)</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Rata Rating</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Kinerja</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($data['kinerja'] as $row)
                @php
                    $pct = $row['total_tugas'] > 0 ? round($row['total_selesai'] / $row['total_tugas'] * 100) : 0;
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $row['petugas']->user->name }}</td>
                    <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $row['petugas']->nip }}</td>
                    <td class="px-5 py-3 text-center font-semibold">{{ $row['total_tugas'] }}</td>
                    <td class="px-5 py-3 text-center text-green-700 font-semibold">{{ $row['total_selesai'] }}</td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $row['rata_waktu_jam'] ?? '—' }}</td>
                    <td class="px-5 py-3 text-center">
                        @if ($row['rata_rating'])
                            <span class="text-yellow-500 font-semibold">{{ $row['rata_rating'] }} ⭐</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="text-xs text-center mt-0.5 text-gray-500">{{ $pct }}%</p>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-10 text-gray-400">Tidak ada data petugas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-supervisor-layout>
