{{-- PBI-13 Filter & Pencarian Pengaduan --}}
<x-app-layout>
    <x-slot name="title">Filter Pengaduan</x-slot>

    <h1 class="text-2xl font-bold text-gray-800 mb-5">🔍 Filter Pengaduan</h1>

    {{-- Filter Form --}}
    <form method="GET" class="bg-white rounded-xl shadow p-5 mb-5">
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3">
            <div>
                <label class="block text-xs text-gray-600 mb-1">No. Tiket</label>
                <input type="text" name="nomor_tiket" value="{{ request('nomor_tiket') }}"
                    placeholder="SIGAP-..." class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    @foreach ($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected':'' }}>
                        {{ ucwords(str_replace('_',' ',$s)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Zona</label>
                <select name="zona_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua Zona</option>
                    @foreach ($zonas as $z)
                    <option value="{{ $z->id }}" {{ request('zona_id') == $z->id ? 'selected':'' }}>{{ $z->nama_zona }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Kategori</label>
                <select name="kategori_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    @foreach ($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected':'' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Dari</label>
                <input type="date" name="dari" value="{{ request('dari') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Sampai</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
        <div class="flex gap-2 mt-3 items-center">
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                <input type="checkbox" name="overdue" value="1" {{ request('overdue') ? 'checked':'' }} class="accent-red-600">
                Tampilkan hanya yang Overdue
            </label>
            <div class="ml-auto flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition">🔍 Cari</button>
                <a href="{{ route('supervisor.filter.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition">Reset</a>
            </div>
        </div>
    </form>

    {{-- Hasil --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-5 py-3 border-b bg-gray-50 flex items-center justify-between">
            <span class="text-sm text-gray-600">
                Ditemukan <strong>{{ $pengaduans->total() }}</strong> pengaduan
            </span>
        </div>
        <table class="w-full text-sm">
            <thead class="border-b">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">No. Tiket</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Pelapor</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Kategori</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Zona</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Status</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">SLA</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($pengaduans as $p)
                <tr class="{{ $p->sla?->is_overdue ? 'bg-red-50' : '' }} hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-mono text-blue-700 font-semibold text-xs">{{ $p->nomor_tiket }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $p->pelapor->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $p->kategori->nama_kategori }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $p->zona->nama_zona }}</td>
                    <td class="px-5 py-3 text-center"><x-badge-status :status="$p->status" /></td>
                    <td class="px-5 py-3 text-center">
                        @if ($p->sla?->is_overdue)
                            <span class="text-xs text-red-600 font-bold">🚨 Overdue</span>
                        @elseif ($p->sla?->is_fulfilled)
                            <span class="text-xs text-green-600">✅ OK</span>
                        @elseif ($p->sla)
                            <span class="text-xs text-orange-600">⏱️ Aktif</span>
                        @else
                            <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $p->tanggal_pengajuan->translatedFormat('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-10 text-gray-400">Tidak ditemukan data pengaduan</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t">{{ $pengaduans->links() }}</div>
    </div>
</x-app-layout>
