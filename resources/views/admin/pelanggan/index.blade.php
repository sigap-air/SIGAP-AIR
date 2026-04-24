<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Data Pelanggan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data pelanggan PDAM yang terdaftar di sistem</p>
        </div>
        <a href="{{ route('admin.pelanggan.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-navy-gradient text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
            <span class="material-symbols-outlined text-xl">person_add</span>
            Tambah Pelanggan
        </a>
    </div>
</div>

{{-- Filter & Search Card --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <form method="GET" action="{{ route('admin.pelanggan.index') }}" class="flex flex-col md:flex-row gap-4">
        {{-- Search --}}
        <div class="flex-1 relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari nama atau no tiket..." class="w-full h-12 pl-12 pr-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 placeholder:text-gray-400">
        </div>

        {{-- Filter Zona --}}
        <div class="w-full md:w-56">
            <select name="zona_id" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200">
                <option value="">Semua Zona</option>
                @foreach($zonas as $zona)
                    <option value="{{ $zona->id }}" {{ ($filters['zona_id'] ?? '') == $zona->id ? 'selected' : '' }}>{{ $zona->nama_zona }}</option>
                @endforeach
            </select>
        </div>

        {{-- Filter Status --}}
        <div class="w-full md:w-44">
            <select name="is_active" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200">
                <option value="">Semua Status</option>
                <option value="1" {{ (isset($filters['is_active']) && $filters['is_active'] == '1') ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ (isset($filters['is_active']) && $filters['is_active'] == '0') ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-2">
            <button type="submit" class="h-12 px-6 bg-[#022448] text-white font-medium rounded-xl hover:bg-[#1e3a5f] transition-colors duration-200 flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">filter_list</span>
                Filter
            </button>
            <a href="{{ route('admin.pelanggan.index') }}" class="h-12 px-4 bg-gray-100 text-gray-600 font-medium rounded-xl hover:bg-gray-200 transition-colors duration-200 flex items-center">
                <span class="material-symbols-outlined text-lg">refresh</span>
            </a>
        </div>
    </form>
</div>

{{-- Data Table Card --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Nama Pelapor / Pelanggan</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No Tiket</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Zona</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Lokasi</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No. Telepon</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Kategori Pengaduan</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Deskripsi Masalah</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Bukti Foto</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pelanggan as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                        <td class="px-6 py-4 text-gray-500 font-medium">{{ $pelanggan->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-[#022448]/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-[#022448] text-lg">person</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $item->nama_pelanggan }}</p>
                                    @if($item->user)
                                        <p class="text-xs text-gray-400">{{ $item->user->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-[#022448] rounded-lg text-xs font-mono font-semibold">
                                <span class="material-symbols-outlined text-sm">tag</span>
                                {{ $item->latestPengaduan?->nomor_tiket ?? $item->nomor_sambungan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $item->zona->nama_zona ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ \Illuminate\Support\Str::limit($item->alamat, 45) }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->no_telepon ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $item->latestPengaduan?->kategori->nama_kategori ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-600 max-w-[14rem]">{{ $item->latestPengaduan ? \Illuminate\Support\Str::limit($item->latestPengaduan->deskripsi, 80) : '—' }}</td>
                        <td class="px-6 py-4">
                            @if($item->latestPengaduan?->foto_bukti)
                                <a href="{{ asset('storage/' . $item->latestPengaduan->foto_bukti) }}" target="_blank" class="inline-block">
                                    <img src="{{ asset('storage/' . $item->latestPengaduan->foto_bukti) }}" alt="" class="h-10 w-14 rounded-lg border border-gray-200 object-cover hover:opacity-90">
                                </a>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
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
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Detail --}}
                                <a href="{{ route('admin.pelanggan.show', $item->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('admin.pelanggan.edit', $item->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                {{-- Hapus --}}
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.pelanggan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="if(confirm('Yakin ingin menghapus data pelanggan ini?')){document.getElementById('delete-form-{{ $item->id }}').submit();}" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" title="Hapus">
                                        <span class="material-symbols-outlined text-xl" style="pointer-events:none;">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-gray-300 text-3xl">group_off</span>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada data pelanggan</p>
                                <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Pelanggan" untuk menambahkan data baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pelanggan->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $pelanggan->withQueryString()->links() }}
        </div>
    @endif
</div>

</x-app-admin-layout>
