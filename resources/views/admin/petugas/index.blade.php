<x-app-admin-layout>
    <x-slot name="title">Manajemen Petugas</x-slot>

    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Petugas Teknis</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data petugas teknis, status ketersediaan, dan pemetaan zona.</p>
        </div>
        <a href="{{ route('admin.petugas.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#022448] to-[#0A3D73] text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
            <span class="material-symbols-outlined text-xl">person_add</span>
            Tambah Petugas
        </a>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[#022448] text-xl">badge</span>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Petugas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Tersedia</p>
                <p class="text-2xl font-bold text-emerald-700">{{ $stats['tersedia'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-amber-600 text-xl">pending</span>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Sibuk</p>
                <p class="text-2xl font-bold text-amber-700">{{ $stats['sibuk'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-gray-500 text-xl">cancel</span>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Tidak Aktif</p>
                <p class="text-2xl font-bold text-gray-600">{{ $stats['tidak_aktif'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.petugas.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Cari Petugas</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 text-lg">search</span>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama, email, atau NIP..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] outline-none transition-all">
                </div>
            </div>
            <div class="md:w-48 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] outline-none transition-all bg-white">
                    <option value="">Semua Status</option>
                    <option value="tersedia"    {{ request('status') === 'tersedia'    ? 'selected' : '' }}>Tersedia</option>
                    <option value="sibuk"       {{ request('status') === 'sibuk'       ? 'selected' : '' }}>Sibuk</option>
                    <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="md:w-56 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Zona Wilayah</label>
                <select name="zona_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] outline-none transition-all bg-white">
                    <option value="">Semua Zona</option>
                    <option value="tanpa_zona" {{ request('zona_id') === 'tanpa_zona' ? 'selected' : '' }}>— Belum Dipetakan —</option>
                    @foreach($zonas as $zona)
                        <option value="{{ $zona->id }}" {{ request('zona_id') == $zona->id ? 'selected' : '' }}>
                            {{ $zona->nama_zona }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto mt-2 md:mt-0">
                <button type="submit"
                        class="flex-1 md:flex-none h-[42px] px-6 bg-[#022448] text-white rounded-xl text-sm font-semibold hover:bg-[#0A3D73] transition-colors shadow-sm">
                    Filter
                </button>
                @if(request()->hasAny(['search','status','zona_id']))
                    <a href="{{ route('admin.petugas.index') }}"
                       class="h-[42px] px-4 flex items-center justify-center text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Petugas</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIP</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Zona Wilayah</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($petugas as $index => $p)
                        <tr class="hover:bg-blue-50/30 transition-colors duration-150 {{ $p->status_tersedia === 'tidak_aktif' ? 'opacity-75' : '' }}">
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                {{ $petugas->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($p->user?->foto_profil)
                                        <img src="{{ asset('storage/' . $p->user->foto_profil) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 flex-shrink-0 bg-white" alt="Foto">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center flex-shrink-0 border border-blue-200">
                                            <span class="font-bold text-blue-700 text-sm">{{ substr($p->user?->name ?? 'P', 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $p->user?->name ?? '(tanpa nama)' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $p->user?->email ?? '—' }}</p>
                                        @if($p->user?->no_telepon)
                                            <p class="text-[11px] text-gray-400 mt-0.5"><span class="material-symbols-outlined text-[12px] align-middle">call</span> {{ $p->user->no_telepon }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->nip)
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md font-mono font-medium border border-gray-200">
                                        {{ $p->nip }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($p->zona)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-violet-50 text-violet-700 rounded-md text-xs font-semibold border border-violet-100">
                                        <span class="material-symbols-outlined text-sm">map</span>
                                        {{ $p->zona->nama_zona }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 text-gray-500 rounded-md text-xs font-medium border border-gray-200">
                                        <span class="material-symbols-outlined text-sm">location_off</span>
                                        Belum dipetakan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusConfig = [
                                        'tersedia'    => ['bg-emerald-50 text-emerald-700 border-emerald-100', 'check_circle', 'Tersedia'],
                                        'sibuk'       => ['bg-amber-50 text-amber-700 border-amber-100',     'pending', 'Sibuk'],
                                        'tidak_aktif' => ['bg-gray-100 text-gray-500 border-gray-200',      'cancel', 'Tidak Aktif'],
                                    ];
                                    [$statusClass, $statusIcon, $label] = $statusConfig[$p->status_tersedia] ?? ['bg-gray-100 text-gray-500', 'help', 'Unknown'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 border {{ $statusClass }} rounded-full text-xs font-bold">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">{{ $statusIcon }}</span>
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.petugas.show', $p) }}"
                                       class="w-8 h-8 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-colors border border-transparent hover:border-blue-200" title="Lihat Detail">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.petugas.edit', $p) }}"
                                       class="w-8 h-8 flex items-center justify-center text-amber-600 bg-amber-50 hover:bg-amber-100 hover:text-amber-700 rounded-lg transition-colors border border-transparent hover:border-amber-200" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    @if($p->status_tersedia !== 'tidak_aktif')
                                        <form action="{{ route('admin.petugas.destroy', $p) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="if(confirm('Nonaktifkan \'{{ $p->user?->name ?? 'petugas ini' }}\'? Petugas tidak akan bisa login dan tidak akan menerima tugas baru.')) { this.closest('form').submit(); }"
                                                    class="w-8 h-8 flex items-center justify-center text-orange-600 bg-orange-50 hover:bg-orange-100 hover:text-orange-700 rounded-lg transition-colors cursor-pointer border border-transparent hover:border-orange-200"
                                                    title="Nonaktifkan">
                                                <span class="material-symbols-outlined text-lg" style="pointer-events:none;">person_off</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="w-8 h-8 flex items-center justify-center text-gray-300 bg-gray-50 rounded-lg cursor-not-allowed border border-gray-100" title="Sudah nonaktif">
                                            <span class="material-symbols-outlined text-lg">person_off</span>
                                        </span>
                                    @endif
                                    <form action="{{ route('admin.petugas.hapus-permanen', $p) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="if(confirm('⚠️ HAPUS PERMANEN \'{{ $p->user?->name ?? 'petugas ini' }}\'?\n\nTindakan ini tidak dapat dibatalkan!\nPetugas hanya bisa dihapus jika tidak memiliki riwayat tugas.')) { this.closest('form').submit(); }"
                                                class="w-8 h-8 flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 rounded-lg transition-colors cursor-pointer border border-transparent hover:border-red-200"
                                                title="Hapus Permanen">
                                            <span class="material-symbols-outlined text-lg" style="pointer-events:none;">delete_forever</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                        <span class="material-symbols-outlined text-gray-300 text-3xl">engineering</span>
                                    </div>
                                    <p class="text-gray-600 font-bold">Belum ada data petugas</p>
                                    @if(request()->hasAny(['search','status','zona_id']))
                                        <p class="text-gray-400 text-sm mt-1">Coba sesuaikan filter pencarian.</p>
                                        <a href="{{ route('admin.petugas.index') }}" class="mt-3 inline-block px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">Reset Filter</a>
                                    @else
                                        <p class="text-gray-400 text-sm mt-1 mb-4">Klik tombol di bawah ini untuk mendaftarkan petugas teknis.</p>
                                        <a href="{{ route('admin.petugas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 bg-white text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                                            <span class="material-symbols-outlined text-lg">person_add</span>
                                            Tambah Petugas
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($petugas->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $petugas->links() }}
            </div>
        @endif
    </div>

    {{-- Summary --}}
    <p class="mt-3 text-xs font-semibold text-gray-400 text-right">
        Menampilkan {{ $petugas->count() }} dari {{ $petugas->total() }} petugas
    </p>

</x-app-admin-layout>
