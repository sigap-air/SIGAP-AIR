{{-- PBI-14 Laporan Rekap Pengaduan --}}
<x-app-layout>
    <x-slot name="title">Laporan Rekap Pengaduan</x-slot>

    <div class="mb-4">
        <a href="{{ route('supervisor.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-[#022448]">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white">←</span>
            <span>Kembali ke Dashboard Supervisor</span>
        </a>
    </div>

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-2xl font-bold text-gray-800">📄 Laporan Rekap Pengaduan</h1>
        <a href="{{ route('supervisor.laporan.export-pdf', request()->all()) }}"
           target="_blank"
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
            🖨️ Export / Print PDF
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="bg-white rounded-xl shadow p-4 mb-5 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-600 mb-1">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ request('dari') }}" class="border rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="border rounded-lg px-3 py-2 text-sm">
        </div>
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
            <label class="block text-xs text-gray-600 mb-1">Kategori</label>
            <select name="kategori_id" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Kategori</option>
                @foreach ($data['kategoris'] as $k)
                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected':'' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition">Filter</button>
            <a href="{{ route('supervisor.laporan.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <div class="text-2xl font-black text-gray-800">{{ $data['total'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Pengaduan</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <div class="text-2xl font-black text-green-700">{{ $data['per_status']['selesai'] ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Selesai</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <div class="text-2xl font-black text-red-700">{{ $data['total_overdue'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Overdue</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <div class="text-2xl font-black text-blue-700">{{ $data['rata_waktu_jam'] ?? '—' }}</div>
            <div class="text-xs text-gray-500 mt-1">Rata-rata Waktu (Jam)</div>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-3 text-gray-600 font-semibold">No. Tiket</th>
                    <th class="text-left px-4 py-3 text-gray-600 font-semibold">Kategori</th>
                    <th class="text-left px-4 py-3 text-gray-600 font-semibold">Zona</th>
                    <th class="text-center px-4 py-3 text-gray-600 font-semibold">Status</th>
                    <th class="text-center px-4 py-3 text-gray-600 font-semibold">SLA</th>
                    <th class="text-left px-4 py-3 text-gray-600 font-semibold">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($data['pengaduans'] as $p)
                <tr class="{{ $p->sla?->is_overdue ? 'bg-red-50': '' }} hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-blue-700 text-xs font-semibold">{{ $p->nomor_tiket }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $p->kategori->nama_kategori }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->zona->nama_zona }}</td>
                    <td class="px-4 py-3 text-center"><x-badge-status :status="$p->status" /></td>
                    <td class="px-4 py-3 text-center text-xs">
                        @if ($p->sla?->is_overdue) <span class="text-red-600 font-bold">Overdue</span>
                        @elseif ($p->sla?->is_fulfilled) <span class="text-green-600">Terpenuhi</span>
                        @else <span class="text-orange-500">Aktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-400">{{ $p->tanggal_pengajuan->translatedFormat('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-400">Belum ada data untuk periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
