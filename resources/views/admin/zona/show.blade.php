<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.zona.index') }}"
               class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-headline">Detail Zona Wilayah</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <a href="{{ route('admin.zona.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Zona Wilayah</a>
                    <span class="text-gray-300 text-xs">/</span>
                    <span class="text-xs text-gray-600 font-medium">{{ $zona->nama_zona }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.zona.edit', $zona->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 hover:bg-amber-100 text-sm font-semibold rounded-xl transition-colors duration-200">
                <span class="material-symbols-outlined text-lg">edit</span>
                Edit Zona
            </a>
            <form id="delete-zona-header" action="{{ route('admin.zona.destroy', $zona->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button"
                        onclick="if(confirm('Hapus zona {{ addslashes($zona->nama_zona) }}? Tidak dapat dihapus jika masih ada pengaduan aktif.')) { document.getElementById('delete-zona-header').submit(); }"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer">
                    <span class="material-symbols-outlined text-lg" style="pointer-events:none;">delete</span>
                    Hapus
                </button>
            </form>
        </div>
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ============================== --}}
    {{-- KOLOM KIRI: Info Zona          --}}
    {{-- ============================== --}}
    <div class="lg:col-span-1 space-y-5">

        {{-- Info Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-navy-gradient px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white text-3xl">map</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">{{ $zona->nama_zona }}</h2>
                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 bg-white/10 rounded-md text-xs text-blue-200 font-mono">
                            <span class="material-symbols-outlined text-sm">tag</span>
                            {{ $zona->kode_zona }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-5 space-y-4">
                {{-- Status --}}
                <div class="flex items-center justify-between py-1">
                    <span class="text-sm text-gray-500 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base text-gray-400">toggle_on</span>
                        Status
                    </span>
                    @if($zona->is_active)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                            Nonaktif
                        </span>
                    @endif
                </div>

                {{-- Jumlah Petugas --}}
                <div class="flex items-center justify-between py-1">
                    <span class="text-sm text-gray-500 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base text-gray-400">badge</span>
                        Jumlah Petugas
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-violet-50 text-violet-700 rounded-full text-xs font-semibold">
                        <span class="material-symbols-outlined text-sm">person</span>
                        {{ $zona->petugas->count() }} orang
                    </span>
                </div>

                {{-- Deskripsi --}}
                @if($zona->deskripsi)
                    <div class="pt-3 border-t border-gray-100">
                        <p class="text-xs font-medium text-gray-500 mb-1.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">description</span>
                            Deskripsi
                        </p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $zona->deskripsi }}</p>
                    </div>
                @endif

                {{-- Timestamps --}}
                <div class="pt-3 border-t border-gray-100 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">Dibuat</span>
                        <span class="text-xs text-gray-500 font-medium">{{ $zona->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">Diperbarui</span>
                        <span class="text-xs text-gray-500 font-medium">{{ $zona->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================================== --}}
        {{-- SECTION D: Form Assign Petugas ke Zona   --}}
        {{-- ======================================== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#022448] text-xl">person_add</span>
                    <h3 class="text-base font-semibold text-gray-900">Tambah Petugas</h3>
                </div>
                <p class="text-xs text-gray-400 mt-0.5 ml-7">Petakan petugas yang belum memiliki zona</p>
            </div>

            <div class="p-5">
                @if($petugasTanpaZona->count() > 0)
                    <form action="{{ route('admin.zona.assign-petugas', $zona->id) }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <div>
                                <label for="petugas_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Pilih Petugas <span class="text-red-500">*</span>
                                </label>
                                <select id="petugas_id" name="petugas_id"
                                        class="w-full h-10 px-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200">
                                    <option value="">-- Pilih Petugas --</option>
                                    @foreach($petugasTanpaZona as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->user->name ?? '(Tanpa Nama)' }}
                                            @if($p->nip) — {{ $p->nip }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-navy-gradient text-white text-sm font-semibold rounded-xl shadow-md shadow-[#022448]/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                <span class="material-symbols-outlined text-lg">person_add</span>
                                Petakan ke Zona Ini
                            </button>
                        </div>
                    </form>
                @else
                    <div class="flex flex-col items-center py-6 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-gray-300 text-xl">person_off</span>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Tidak ada petugas tersedia</p>
                        <p class="text-xs text-gray-400 mt-1">Semua petugas sudah dipetakan ke zona masing-masing</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ============================== --}}
    {{-- KOLOM KANAN: Daftar Petugas   --}}
    {{-- ============================== --}}
    <div class="lg:col-span-2">

        {{-- ======================================= --}}
        {{-- SECTION C: Daftar Petugas Dalam Zona   --}}
        {{-- ======================================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#022448] text-xl">badge</span>
                    <h2 class="text-base font-semibold text-gray-900">Petugas di Zona Ini</h2>
                    <span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 bg-[#022448] text-white rounded-full text-xs font-bold">
                        {{ $zona->petugas->count() }}
                    </span>
                </div>
                @if($zona->petugas->count() > 0)
                    <span class="text-xs text-gray-400">
                        {{ $zona->petugas->count() }} petugas terdaftar
                    </span>
                @endif
            </div>

            @if($zona->petugas->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($zona->petugas as $petugas)
                        <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition-colors group">
                            <div class="flex items-center gap-4">
                                {{-- Avatar --}}
                                <div class="w-10 h-10 bg-gradient-to-br from-[#022448] to-[#1e3a5f] rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <span class="text-white text-sm font-bold">
                                        {{ strtoupper(substr($petugas->user->name ?? 'P', 0, 1)) }}
                                    </span>
                                </div>

                                {{-- Info --}}
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $petugas->user->name ?? '(Tanpa Nama)' }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        @if($petugas->nip)
                                            <span class="text-xs text-gray-400 font-mono">NIP: {{ $petugas->nip }}</span>
                                            <span class="text-gray-200">·</span>
                                        @endif
                                        <span class="text-xs text-gray-400">{{ $petugas->user->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Lepas --}}
                            <form id="remove-petugas-{{ $petugas->id }}"
                                  action="{{ route('admin.zona.remove-petugas', [$zona->id, $petugas->id]) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        onclick="if(confirm('Lepas {{ addslashes($petugas->user->name ?? 'petugas ini') }} dari zona {{ addslashes($zona->nama_zona) }}?\n\nPetugas tidak bisa dilepas jika masih memiliki tugas aktif.')) { document.getElementById('remove-petugas-{{ $petugas->id }}').submit(); }"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors cursor-pointer opacity-0 group-hover:opacity-100 duration-200"
                                        title="Lepas petugas dari zona ini">
                                    <span class="material-symbols-outlined text-base" style="pointer-events:none;">link_off</span>
                                    Lepas
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                {{-- Catatan Business Rule --}}
                <div class="px-6 py-3 bg-amber-50 border-t border-amber-100">
                    <p class="text-xs text-amber-700 flex items-start gap-1.5">
                        <span class="material-symbols-outlined text-sm flex-shrink-0 mt-0.5">info</span>
                        Petugas yang masih memiliki tugas aktif tidak dapat dilepas dari zona. Selesaikan atau relokasi tugas terlebih dahulu.
                    </p>
                </div>

            @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center py-16 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 border-2 border-dashed border-gray-200">
                        <span class="material-symbols-outlined text-gray-300 text-4xl">group_off</span>
                    </div>
                    <p class="text-gray-600 font-semibold">Belum ada petugas di zona ini</p>
                    <p class="text-gray-400 text-sm mt-1 max-w-xs">
                        Gunakan form "Tambah Petugas" di sebelah kiri untuk memetakan petugas ke zona ini.
                    </p>
                    @if($petugasTanpaZona->count() > 0)
                        <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-[#022448] rounded-lg text-xs font-medium">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                            {{ $petugasTanpaZona->count() }} petugas tersedia untuk dipetakan
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Info Ringkasan Cepat --}}
        @if($zona->petugas->count() > 0)
            <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
                    <div class="w-9 h-9 bg-violet-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-violet-600 text-lg">badge</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Terdaftar</p>
                        <p class="text-lg font-bold text-gray-900">{{ $zona->petugas->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[#022448] text-lg">person_search</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tersedia</p>
                        <p class="text-lg font-bold text-gray-900">{{ $petugasTanpaZona->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 col-span-2 sm:col-span-1">
                    <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-emerald-600 text-lg">
                            {{ $zona->is_active ? 'check_circle' : 'cancel' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status Zona</p>
                        <p class="text-sm font-bold {{ $zona->is_active ? 'text-emerald-600' : 'text-gray-400' }}">
                            {{ $zona->is_active ? 'Aktif' : 'Nonaktif' }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

</x-app-admin-layout>
