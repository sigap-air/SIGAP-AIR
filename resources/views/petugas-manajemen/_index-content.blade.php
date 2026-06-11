@php
    $readOnly = $readOnly ?? false;
    $routePrefix = $routePrefix ?? 'admin.petugas';
@endphp

<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Manajemen Petugas Teknis</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data petugas teknis, status ketersediaan, dan histori penugasan.</p>
        </div>
        @unless($readOnly)
            <a href="{{ route('admin.petugas.create') }}"
               id="btn-tambah-petugas"
               class="inline-flex items-center gap-2 px-6 py-3 bg-navy-gradient text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <span class="material-symbols-outlined text-xl">person_add</span>
                Tambah Petugas
            </a>
        @endunless
    </div>
</div>

@if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
        <span class="material-symbols-outlined text-emerald-500 flex-shrink-0">check_circle</span>
        {{ session('success') }}
    </div>
@endif

@if (!empty($showCatatanInfo))
    <div class="mb-6 flex gap-4 rounded-2xl border border-blue-100 bg-blue-50/80 p-5">
        <span class="material-symbols-outlined shrink-0 text-3xl text-[#0F4C81]">info</span>
        <div class="text-sm text-gray-700">
            <p class="font-bold text-gray-900">Cara menambah catatan assignment</p>
            <p class="mt-1 leading-relaxed">
                Catatan untuk petugas <strong>tidak diisi di tabel ini</strong>, melainkan saat Anda menugaskan petugas ke suatu <strong>pengaduan</strong>.
            </p>
            <ol class="mt-2 list-decimal space-y-1 pl-5">
                <li>Klik ikon <strong>mata (Detail)</strong> pada baris petugas berstatus <span class="font-semibold text-emerald-700">Tersedia</span>.</li>
                <li>Di halaman detail, isi <strong>Catatan Assignment</strong>, pilih pengaduan, lalu klik <strong>Simpan & Tugaskan</strong>.</li>
            </ol>
            <p class="mt-2 text-xs text-gray-500">
                Alternatif: menu Verifikasi Tiket → setujui pengaduan → form Tugaskan Petugas.
            </p>
        </div>
    </div>
@endif

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-[#022448] text-xl">badge</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Petugas</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Tersedia</p>
            <p class="text-xl font-bold text-emerald-700">{{ $stats['tersedia'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-amber-600 text-xl">pending</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Sibuk</p>
            <p class="text-xl font-bold text-amber-700">{{ $stats['sibuk'] }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-gray-500 text-xl">cancel</span>
        </div>
        <div>
            <p class="text-xs text-gray-500">Tidak Aktif</p>
            <p class="text-xl font-bold text-gray-600">{{ $stats['tidak_aktif'] }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route($routePrefix . '.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Petugas</label>
            <input type="text" name="search" id="input-search-petugas" value="{{ request('search') }}" placeholder="Nama, email, atau NIP..."
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448]">
        </div>
        <div class="min-w-[160px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <select name="status" id="filter-status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="tersedia"    {{ request('status') === 'tersedia'    ? 'selected' : '' }}>Tersedia</option>
                <option value="sibuk"       {{ request('status') === 'sibuk'       ? 'selected' : '' }}>Sibuk</option>
                <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>
        <div class="min-w-[180px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Zona</label>
            <select name="zona_id" id="filter-zona" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Zona</option>
                @foreach($zonas as $zona)
                    <option value="{{ $zona->id }}" {{ request('zona_id') == $zona->id ? 'selected' : '' }}>{{ $zona->nama_zona }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" id="btn-filter-petugas" class="px-4 py-2 bg-[#022448] text-white text-sm font-semibold rounded-lg">Filter</button>
        @if(request()->hasAny(['search','status','zona_id']))
            <a href="{{ route($routePrefix . '.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 uppercase text-xs font-bold text-gray-500 tracking-wider">
                    <th class="px-6 py-5 text-left">NO</th>
                    <th class="px-6 py-5 text-left">PETUGAS</th>
                    <th class="px-6 py-5 text-left">NIP</th>
                    <th class="px-6 py-5 text-left">ZONA</th>
                    <th class="px-6 py-5 text-left">STATUS</th>
                    <th class="px-6 py-5 text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($petugas as $index => $p)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-500">
                            {{ $petugas->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                @if($p->user?->foto_profil)
                                    <img src="{{ asset('storage/' . $p->user->foto_profil) }}" alt="" class="w-12 h-12 rounded-full object-cover shadow-sm">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 shadow-sm">
                                        <span class="material-symbols-outlined text-2xl">person</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $p->user?->name ?? '(tanpa nama)' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $p->user?->email ?? '—' }}</div>
                                    <div class="text-xs text-gray-400">{{ $p->user?->no_telepon ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-[#F0F5FA] text-[#022448]">
                                <span class="mr-1 opacity-70">#</span> {{ $p->nip ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-purple-50 text-purple-700">
                                <span class="material-symbols-outlined text-[16px]">map</span>
                                {{ $p->zona?->nama_zona ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @php
                                $cfg = [
                                    'tersedia'    => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
                                    'sibuk'       => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500'],
                                    'tidak_aktif' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400'],
                                ];
                                $lbl = ['tersedia' => 'Tersedia', 'sibuk' => 'Sibuk', 'tidak_aktif' => 'Tidak Aktif'];
                                $c = $cfg[$p->status_tersedia] ?? $cfg['tidak_aktif'];
                            @endphp
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold {{ $c['bg'] }} {{ $c['text'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
                                {{ $lbl[$p->status_tersedia] ?? $p->status_tersedia }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route($routePrefix . '.show', $p) }}" class="text-[#022448] hover:text-blue-700 transition" title="{{ $routePrefix === 'supervisor.petugas' ? 'Detail & catatan assignment' : 'Detail' }}">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                                @if ($routePrefix === 'supervisor.petugas' && $p->status_tersedia === 'tersedia')
                                    <a href="{{ route($routePrefix . '.show', $p) }}#tugaskan-petugas" class="text-amber-600 hover:text-amber-800 transition" title="Tugaskan + catatan">
                                        <span class="material-symbols-outlined text-[20px]">edit_note</span>
                                    </a>
                                @endif
                                {{-- Toggle Status: tersedia untuk Admin dan Supervisor --}}
                                @php
                                    $isActive = in_array($p->status_tersedia, ['tersedia', 'sibuk']);
                                    $statusBtnColor = $isActive ? 'text-emerald-500 hover:text-emerald-700' : 'text-red-500 hover:text-red-700';
                                    $newStatus = $p->status_tersedia === 'tidak_aktif' ? 'tersedia' : 'tidak_aktif';
                                    $statusTitle = $p->status_tersedia === 'tidak_aktif' ? 'Aktifkan Petugas' : 'Nonaktifkan Petugas';
                                @endphp
                                <form action="{{ route($routePrefix . '.update-status', $p) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_tersedia" value="{{ $newStatus }}">
                                    <button type="submit" class="{{ $statusBtnColor }} transition" title="{{ $statusTitle }}">
                                        <span class="material-symbols-outlined text-[20px]">person_off</span>
                                    </button>
                                </form>
                                @unless($readOnly)
                                    <a href="{{ route('admin.petugas.edit', $p) }}" class="text-orange-500 hover:text-orange-700 transition" title="Edit Data">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.petugas.hapus-permanen', $p) }}" method="POST" class="inline-block" data-confirm="Yakin ingin menghapus permanen petugas ini beserta semua riwayatnya?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus Permanen">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-6 py-12 text-center text-gray-500">Belum ada data petugas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($petugas->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $petugas->withQueryString()->links() }}</div>
    @endif
</div>



<script>
    const statusRoutes = @json(
        $petugas->getCollection()->mapWithKeys(fn ($p) => [$p->id => route($routePrefix . '.update-status', $p)])
    );

    function openStatusModal(id, name, status) {
        document.getElementById('status-modal').classList.remove('hidden');
        document.getElementById('status-modal-name').textContent = name;
        document.getElementById('status-modal-select').value = status;
        document.getElementById('status-modal-form').action = statusRoutes[id];
    }
    function closeStatusModal() {
        document.getElementById('status-modal').classList.add('hidden');
    }
</script>
