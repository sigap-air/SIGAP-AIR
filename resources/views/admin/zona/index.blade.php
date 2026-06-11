<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Zona Wilayah</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola zona wilayah layanan PDAM beserta pemetaan petugas</p>
        </div>
        <a href="{{ route('admin.zona.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-navy-gradient text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
            <span class="material-symbols-outlined text-xl">add_circle</span>
            Tambah Zona
        </a>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
        <span class="material-symbols-outlined text-emerald-500 flex-shrink-0">check_circle</span>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
        <span class="material-symbols-outlined text-red-500 flex-shrink-0">error</span>
        {{ session('error') }}
    </div>
@endif
@if(session('warning'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-sm">
        <span class="material-symbols-outlined text-amber-500 flex-shrink-0">warning</span>
        {{ session('warning') }}
    </div>
@endif

{{-- Stats Bar --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-[#022448] text-xl">map</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Zona</p>
            <p class="text-xl font-bold text-gray-900">{{ $zonas->total() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Aktif</p>
            <p class="text-xl font-bold text-gray-900">{{ $zonas->getCollection()->where('is_active', true)->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-violet-600 text-xl">badge</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Petugas</p>
            <p class="text-xl font-bold text-gray-900">{{ $zonas->getCollection()->sum('petugas_count') }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-red-500 text-xl">pending_actions</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Pengaduan</p>
            <p class="text-xl font-bold text-gray-900">{{ $zonas->getCollection()->sum('pengaduan_count') }}</p>
        </div>
    </div>
</div>

{{-- Data Table Card --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Zona</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Kode</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Petugas</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Pengaduan</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($zonas as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                        <td class="px-6 py-4 text-gray-500 font-medium">{{ $zonas->firstItem() + $index }}</td>

                        {{-- Nama Zona --}}
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->nama_zona }}</p>
                                @if($item->deskripsi)
                                    <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $item->deskripsi }}</p>
                                @endif
                            </div>
                        </td>

                        {{-- Kode Zona --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-[#022448] rounded-lg text-xs font-mono font-semibold">
                                <span class="material-symbols-outlined text-sm">tag</span>
                                {{ $item->kode_zona }}
                            </span>
                        </td>

                        {{-- Petugas --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->petugas_count > 0)
                                <span class="inline-flex items-center justify-center gap-1 px-3 py-1 bg-violet-50 text-violet-700 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm">badge</span>
                                    {{ $item->petugas_count }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Pengaduan --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->pengaduan_count > 0)
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-red-100 text-red-700 rounded-full text-sm font-bold">
                                    {{ $item->pengaduan_count }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->is_active)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Detail --}}
                                <a href="{{ route('admin.zona.show', $item->id) }}"
                                   class="p-2 text-[#022448] hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('admin.zona.edit', $item->id) }}"
                                   class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                {{-- Hapus --}}
                                <form id="delete-zona-{{ $item->id }}"
                                      action="{{ route('admin.zona.destroy', $item->id) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="if(confirm('Yakin ingin menghapus zona \'{{ $item->nama_zona }}\'? Zona tidak dapat dihapus jika masih ada pengaduan aktif.')) { document.getElementById('delete-zona-{{ $item->id }}').submit(); }"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                                            title="Hapus">
                                        <span class="material-symbols-outlined text-xl" style="pointer-events:none;">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-gray-300 text-3xl">map</span>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada zona wilayah</p>
                                <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Zona" untuk menambahkan data baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($zonas->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $zonas->withQueryString()->links() }}
        </div>
    @endif
</div>

</x-app-admin-layout>
