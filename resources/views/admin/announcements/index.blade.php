<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Pengumuman Layanan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pengumuman gangguan, pemeliharaan, dan informasi layanan PDAM</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-navy-gradient text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
            <span class="material-symbols-outlined text-xl">add_circle</span>
            Buat Pengumuman
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

{{-- Stats Bar --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-[#022448] text-xl">campaign</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Pengumuman</p>
            <p class="text-xl font-bold text-gray-900">{{ $announcements->total() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Sedang Aktif</p>
            <p class="text-xl font-bold text-gray-900">
                {{ $announcements->getCollection()->filter(fn($a) => $a->isCurrentlyActive())->count() }}
            </p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-gray-400 text-xl">schedule</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Kadaluarsa</p>
            <p class="text-xl font-bold text-gray-900">
                {{ $announcements->getCollection()->reject(fn($a) => $a->isCurrentlyActive())->count() }}
            </p>
        </div>
    </div>
</div>

{{-- Data Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Judul</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Jenis</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Periode</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Zona</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($announcements as $index => $item)
                    @php
                        $color = $item->typeColor();
                        $colorMap = [
                            'red'   => 'bg-red-50 text-red-700',
                            'amber' => 'bg-amber-50 text-amber-700',
                            'blue'  => 'bg-blue-50 text-blue-700',
                        ];
                        $badgeClass = $colorMap[$color] ?? 'bg-gray-50 text-gray-700';
                    @endphp
                    <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                        <td class="px-6 py-4 text-gray-500 font-medium">{{ $announcements->firstItem() + $index }}</td>

                        {{-- Judul + isi singkat --}}
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.announcements.show', $item->id) }}" class="font-semibold text-gray-900 hover:text-[#022448]">
                                {{ $item->title }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ Str::limit($item->content, 60) }}</p>
                        </td>

                        {{-- Jenis --}}
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                {{ $item->typeLabelText() }}
                            </span>
                        </td>

                        {{-- Periode --}}
                        <td class="px-6 py-4 text-center text-xs text-gray-500">
                            <div>{{ $item->start_date->format('d M Y') }}</div>
                            <div class="text-gray-400">s/d {{ $item->end_date->format('d M Y') }}</div>
                        </td>

                        {{-- Jumlah Zona --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->zones_count > 0)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-violet-50 text-violet-700 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm">map</span>
                                    {{ $item->zones_count }} zona
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">Semua zona</span>
                            @endif
                        </td>

                        {{-- Status Badge --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->isCurrentlyActive())
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                    Kadaluarsa
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.announcements.show', $item->id) }}"
                                   class="p-2 text-[#022448] hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </a>
                                <a href="{{ route('admin.announcements.edit', $item->id) }}"
                                   class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <form id="del-announcement-{{ $item->id }}"
                                      action="{{ route('admin.announcements.destroy', $item->id) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="if(confirm('Yakin ingin menghapus pengumuman \'{{ $item->title }}\'?')) { document.getElementById('del-announcement-{{ $item->id }}').submit(); }"
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
                                    <span class="material-symbols-outlined text-gray-300 text-3xl">campaign</span>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada pengumuman</p>
                                <p class="text-gray-400 text-sm mt-1">Klik "Buat Pengumuman" untuk menambahkan informasi layanan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($announcements->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $announcements->withQueryString()->links() }}
        </div>
    @endif
</div>

</x-app-admin-layout>
