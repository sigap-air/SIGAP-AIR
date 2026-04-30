@if (!empty($backToDashboardRoute))
<div class="mb-4">
    <a href="{{ route($backToDashboardRoute) }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-[#022448]">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white">←</span>
        <span>{{ $backToDashboardLabel ?? 'Kembali ke Dashboard' }}</span>
    </a>
</div>
@endif

<h1 class="text-2xl font-bold text-gray-800 mb-5">🔍 {{ $pageTitle }}</h1>

@php
    $currentSort = request('sort', 'tanggal_pengajuan');
    $currentDir = request('dir', 'desc');
    $sortLink = function (string $col) use ($currentSort, $currentDir, $indexRoute) {
        $active = $currentSort === $col;
        $nextDir = $active && $currentDir === 'desc' ? 'asc' : 'desc';

        return route($indexRoute, array_merge(request()->except('page'), ['sort' => $col, 'dir' => $nextDir]));
    };
    $sortIndicator = function (string $col) use ($currentSort, $currentDir) {
        if ($currentSort !== $col) {
            return '';
        }

        return $currentDir === 'asc' ? ' ↑' : ' ↓';
    };
@endphp

<form method="GET" action="{{ route($indexRoute) }}" class="bg-white rounded-xl shadow p-5 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-3">
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
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $s)) }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Zona</label>
            <select name="zona_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Zona</option>
                @foreach ($zonas as $z)
                <option value="{{ $z->id }}" {{ (string) request('zona_id') === (string) $z->id ? 'selected' : '' }}>{{ $z->nama_zona }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Kategori</label>
            <select name="kategori_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach ($kategoris as $k)
                <option value="{{ $k->id }}" {{ (string) request('kategori_id') === (string) $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Petugas</label>
            <select name="petugas_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Petugas</option>
                @foreach ($petugasList as $pt)
                <option value="{{ $pt->id }}" {{ (string) request('petugas_id') === (string) $pt->id ? 'selected' : '' }}>
                    {{ $pt->user?->name ?? ('Petugas #' . $pt->id) }}
                </option>
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
    <input type="hidden" name="sort" value="{{ request('sort', 'tanggal_pengajuan') }}">
    <input type="hidden" name="dir" value="{{ request('dir', 'desc') }}">
    <div class="flex flex-wrap gap-2 mt-3 items-center">
        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
            <input type="checkbox" name="overdue" value="1" {{ request('overdue') ? 'checked' : '' }} class="accent-red-600">
            Tampilkan hanya yang Overdue
        </label>
        <div class="ml-auto flex flex-wrap gap-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition">🔍 Terapkan</button>
            <a href="{{ route($indexRoute) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition inline-flex items-center">Reset</a>
            <a href="{{ route($exportCsvRoute, request()->query()) }}"
                class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition inline-flex items-center">
                ⬇ Export CSV
            </a>
        </div>
    </div>
</form>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-5 py-3 border-b bg-gray-50 flex items-center justify-between flex-wrap gap-2">
        <span class="text-sm text-gray-600">
            Ditemukan <strong>{{ $pengaduans->total() }}</strong> pengaduan
        </span>
        <span class="text-xs text-gray-500">Klik judul kolom untuk mengurutkan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[900px]">
            <thead class="border-b bg-gray-50">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">
                        <a href="{{ $sortLink('nomor_tiket') }}" class="hover:text-blue-700">No. Tiket{{ $sortIndicator('nomor_tiket') }}</a>
                    </th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Pelapor</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">
                        <a href="{{ $sortLink('kategori') }}" class="hover:text-blue-700">Kategori{{ $sortIndicator('kategori') }}</a>
                    </th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">
                        <a href="{{ $sortLink('zona') }}" class="hover:text-blue-700">Zona{{ $sortIndicator('zona') }}</a>
                    </th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Petugas</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">
                        <a href="{{ $sortLink('status') }}" class="hover:text-blue-700">Status{{ $sortIndicator('status') }}</a>
                    </th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">SLA</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">
                        <a href="{{ $sortLink('tanggal_pengajuan') }}" class="hover:text-blue-700">Tanggal{{ $sortIndicator('tanggal_pengajuan') }}</a>
                    </th>
                    @if (!empty($showFotoBuktiColumn))
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Foto Bukti</th>
                    @endif
                    @if (!empty($showAksiEyeOnly))
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($pengaduans as $p)
                <tr class="{{ $p->sla?->is_overdue ? 'bg-red-50' : '' }} hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-mono text-blue-700 font-semibold text-xs">{{ $p->nomor_tiket }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $p->pelapor->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $p->kategori->nama_kategori }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $p->zona->nama_zona }}</td>
                    <td class="px-5 py-3 text-gray-600">
                        {{ $p->assignment?->petugas?->user?->name ?? '—' }}
                    </td>
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
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $p->tanggal_pengajuan->translatedFormat('d M Y') }}</td>
                    @if (!empty($showFotoBuktiColumn))
                    <td class="px-5 py-3">
                        @if($p->foto_bukti)
                            <a href="{{ asset('storage/' . $p->foto_bukti) }}" target="_blank" class="inline-block">
                                <img src="{{ asset('storage/' . $p->foto_bukti) }}"
                                     alt="Foto bukti {{ $p->nomor_tiket }}"
                                     class="h-10 w-14 rounded-lg border border-gray-200 object-cover hover:opacity-90">
                            </a>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    @endif
                    @if (!empty($showAksiEyeOnly))
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route($detailRouteName ?? 'supervisor.verifikasi.show', $p) }}"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                           title="Lihat Detail">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3C5 3 1.73 7.11.46 9.29a1.35 1.35 0 000 1.42C1.73 12.89 5 17 10 17s8.27-4.11 9.54-6.29a1.35 1.35 0 000-1.42C18.27 7.11 15 3 10 3zm0 11a4 4 0 110-8 4 4 0 010 8zm0-2.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                            </svg>
                        </a>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 8 + (!empty($showFotoBuktiColumn) ? 1 : 0) + (!empty($showAksiEyeOnly) ? 1 : 0) }}" class="text-center py-10 text-gray-400">
                        Tidak ditemukan data pengaduan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t">{{ $pengaduans->links() }}</div>
</div>
