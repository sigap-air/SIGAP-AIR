<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Manajemen Petugas Teknis</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data petugas dan pemetaan wilayah zona.</p>
        </div>
        <a href="{{ route('admin.petugas.create') }}"
           id="btn-tambah-petugas"
           class="inline-flex items-center gap-2 px-6 py-3 bg-navy-gradient text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
            <span class="material-symbols-outlined text-xl">person_add</span>
            Tambah Petugas
        </a>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm shadow-sm">
        <span class="material-symbols-outlined text-emerald-500 flex-shrink-0">check_circle</span>
        {{ session('success') }}
    </div>
@endif
@if($errors->has('error'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm shadow-sm">
        <span class="material-symbols-outlined text-red-500 flex-shrink-0">error</span>
        {{ $errors->first('error') }}
    </div>
@endif

{{-- Summary Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-[#022448] text-xl">badge</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Petugas</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Tersedia</p>
            <p class="text-xl font-bold text-emerald-700">{{ $stats['tersedia'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-amber-600 text-xl">pending</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Sibuk</p>
            <p class="text-xl font-bold text-amber-700">{{ $stats['sibuk'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-gray-500 text-xl">cancel</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Tidak Aktif</p>
            <p class="text-xl font-bold text-gray-600">{{ $stats['tidak_aktif'] }}</p>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('admin.petugas.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Petugas</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   id="input-search-petugas"
                   placeholder="Nama, email, atau NIP..."
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448]">
        </div>
        <div class="min-w-[160px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <select name="status" id="filter-status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448]">
                <option value="">Semua Status</option>
                <option value="tersedia"    {{ request('status') === 'tersedia'    ? 'selected' : '' }}>Tersedia</option>
                <option value="sibuk"       {{ request('status') === 'sibuk'       ? 'selected' : '' }}>Sibuk</option>
                <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>
        <div class="min-w-[180px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Zona</label>
            <select name="zona_id" id="filter-zona" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448]">
                <option value="">Semua Zona</option>
                <option value="tanpa_zona" {{ request('zona_id') === 'tanpa_zona' ? 'selected' : '' }}>— Tanpa Zona</option>
                @foreach($zonas as $zona)
                    <option value="{{ $zona->id }}" {{ request('zona_id') == $zona->id ? 'selected' : '' }}>
                        {{ $zona->nama_zona }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" id="btn-filter-petugas"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#022448] text-white text-sm font-semibold rounded-lg hover:bg-[#033466] transition">
                <span class="material-symbols-outlined text-base">filter_list</span>
                Filter
            </button>
            @if(request()->hasAny(['search','status','zona_id']))
                <a href="{{ route('admin.petugas.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                    <span class="material-symbols-outlined text-base">close</span>
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Data Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
        <p class="text-xs text-gray-500">
            Menampilkan <strong class="text-gray-700">{{ $petugas->firstItem() ?? 0 }}–{{ $petugas->lastItem() ?? 0 }}</strong>
            dari <strong class="text-gray-700">{{ $petugas->total() }}</strong> petugas
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Petugas</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">NIP</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Zona Wilayah</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($petugas as $index => $p)
                    <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                        <td class="px-6 py-4 text-gray-500 font-medium">
                            {{ $petugas->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($p->user?->foto_profil)
                                    <img src="{{ asset('storage/' . $p->user->foto_profil) }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 flex-shrink-0" alt="Foto">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-[#022448]/10 flex items-center justify-center flex-shrink-0 border border-transparent">
                                        <span class="material-symbols-outlined text-[#022448] text-base">person</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $p->user?->name ?? '(tanpa nama)' }}</p>
                                    <p class="text-xs text-gray-400">{{ $p->user?->email ?? '—' }}</p>
                                    @if($p->user?->no_telepon)
                                        <p class="text-xs text-gray-400">{{ $p->user->no_telepon }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($p->nip)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-[#022448] rounded-lg text-xs font-mono font-semibold">
                                    <span class="material-symbols-outlined text-sm">tag</span>
                                    {{ $p->nip }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($p->zones->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach($p->zones as $z)
                                        <a href="{{ route('admin.zona.show', $z->id) }}"
                                           class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-violet-50 text-violet-700 hover:bg-violet-100 rounded-lg text-xs font-semibold transition-colors">
                                            <span class="material-symbols-outlined text-sm">map</span>
                                            {{ $z->nama_zona }}
                                        </a>
                                    @endforeach
                                </div>
                            @elseif($p->zona)
                                <a href="{{ route('admin.zona.show', $p->zona->id) }}"
                                   class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-violet-50 text-violet-700 hover:bg-violet-100 rounded-lg text-xs font-semibold transition-colors">
                                    <span class="material-symbols-outlined text-sm">map</span>
                                    {{ $p->zona->nama_zona }}
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-400 rounded-lg text-xs font-medium">
                                    <span class="material-symbols-outlined text-sm">location_off</span>
                                    Belum dipetakan
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusConfig = [
                                    'tersedia'    => ['bg-emerald-50 text-emerald-700', 'check_circle'],
                                    'sibuk'       => ['bg-amber-50 text-amber-700',     'pending'],
                                    'tidak_aktif' => ['bg-gray-100 text-gray-500',      'cancel'],
                                ];
                                $statusLabel = [
                                    'tersedia'    => 'Tersedia',
                                    'sibuk'       => 'Sibuk',
                                    'tidak_aktif' => 'Tidak Aktif',
                                ];
                                [$statusClass, $statusIcon] = $statusConfig[$p->status_tersedia] ?? ['bg-gray-100 text-gray-500', 'help'];
                                $label = $statusLabel[$p->status_tersedia] ?? ucwords(str_replace('_', ' ', $p->status_tersedia ?? '-'));
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 {{ $statusClass }} rounded-full text-xs font-semibold">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">{{ $statusIcon }}</span>
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.petugas.show', $p) }}"
                                   id="btn-detail-petugas-{{ $p->id }}"
                                   class="p-2 text-[#022448] hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </a>
                                <a href="{{ route('admin.petugas.edit', $p) }}"
                                   id="btn-edit-petugas-{{ $p->id }}"
                                   class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                @if($p->status_tersedia !== 'tidak_aktif')
                                    <form id="form-nonaktifkan-{{ $p->id }}"
                                          action="{{ route('admin.petugas.destroy', $p) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="if(confirm('Nonaktifkan \'{{ $p->user?->name ?? 'petugas ini' }}\'? Petugas tidak akan bisa login dan tidak akan menerima tugas baru.')) { document.getElementById('form-nonaktifkan-{{ $p->id }}').submit(); }"
                                                class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors cursor-pointer"
                                                title="Nonaktifkan">
                                            <span class="material-symbols-outlined text-xl" style="pointer-events:none;">person_off</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="p-2 text-gray-300 cursor-not-allowed" title="Sudah nonaktif">
                                        <span class="material-symbols-outlined text-xl">person_off</span>
                                    </span>
                                @endif
                                <form id="form-hapus-{{ $p->id }}"
                                      action="{{ route('admin.petugas.hapus-permanen', $p) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="if(confirm('⚠️ HAPUS PERMANEN \'{{ $p->user?->name ?? 'petugas ini' }}\'?\n\nTindakan ini tidak dapat dibatalkan!\nPetugas hanya bisa dihapus jika tidak memiliki riwayat tugas.')) { document.getElementById('form-hapus-{{ $p->id }}').submit(); }"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                                            title="Hapus Permanen">
                                        <span class="material-symbols-outlined text-xl" style="pointer-events:none;">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-gray-300 text-3xl">badge</span>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada data petugas</p>
                                <p class="text-gray-400 text-sm mt-1">
                                    @if(request()->hasAny(['search','status','zona_id']))
                                        Tidak ada petugas yang cocok dengan filter saat ini.
                                    @else
                                        Klik tombol "Tambah Petugas" untuk mendaftarkan petugas teknis.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($petugas->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $petugas->withQueryString()->links() }}
        </div>
    @endif
</div>

    @include('petugas-manajemen._index-content')
</x-app-admin-layout>
