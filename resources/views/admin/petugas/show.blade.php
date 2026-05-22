<x-app-admin-layout>

{{-- Breadcrumb --}}
<div class="mb-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.petugas.index') }}" class="hover:text-[#022448] transition">Petugas Teknis</a>
        <span class="material-symbols-outlined text-base text-gray-300">chevron_right</span>
        <span class="text-gray-700 font-medium">{{ $petugas->user?->name ?? '(tanpa nama)' }}</span>
    </nav>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
        <span class="material-symbols-outlined text-emerald-500 flex-shrink-0">check_circle</span>
        {{ session('success') }}
    </div>
@endif

{{-- Header Profil Petugas --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @if($petugas->user?->foto_profil)
                <img src="{{ asset('storage/' . $petugas->user->foto_profil) }}" class="w-16 h-16 rounded-2xl object-cover border border-gray-200 flex-shrink-0" alt="Foto">
            @else
                <div class="w-16 h-16 rounded-2xl bg-[#022448]/10 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[#022448] text-3xl">person</span>
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $petugas->user?->name ?? '(tanpa nama)' }}</h1>
                <p class="text-sm text-gray-500">{{ $petugas->user?->email }}</p>
                @if($petugas->nip)
                    <span class="inline-flex items-center gap-1 mt-1 px-2.5 py-0.5 bg-blue-50 text-[#022448] rounded-lg text-xs font-mono font-semibold">
                        <span class="material-symbols-outlined text-sm">tag</span>{{ $petugas->nip }}
                    </span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            {{-- Status Badge --}}
            @php
                $statusConfig = [
                    'tersedia'    => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'bg-emerald-500', 'Tersedia'],
                    'sibuk'       => ['bg-amber-50 text-amber-700 border-amber-200',       'bg-amber-500',   'Sibuk'],
                    'tidak_aktif' => ['bg-gray-100 text-gray-600 border-gray-200',         'bg-gray-400',    'Tidak Aktif'],
                ];
                [$cls, $dot, $label] = $statusConfig[$petugas->status_tersedia] ?? ['bg-gray-100 text-gray-600 border-gray-200','bg-gray-400','—'];
            @endphp
            <span class="inline-flex items-center gap-1.5 px-4 py-2 {{ $cls }} border rounded-xl text-sm font-semibold">
                <span class="w-2 h-2 {{ $dot }} rounded-full"></span>
                {{ $label }}
            </span>

            {{-- Tombol Edit --}}
            <a href="{{ route('admin.petugas.edit', $petugas) }}"
               id="btn-edit-dari-show"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#022448] text-white text-sm font-semibold rounded-xl hover:bg-[#033466] transition">
                <span class="material-symbols-outlined text-base">edit</span>
                Edit Data
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kolom Kiri: Detail Info --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Card: Informasi Lengkap --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-5 pb-4 border-b border-gray-100">Informasi Lengkap</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Nama Lengkap</dt>
                    <dd class="text-sm font-semibold text-gray-800">{{ $petugas->user?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Email</dt>
                    <dd class="text-sm text-gray-700">{{ $petugas->user?->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Username</dt>
                    <dd class="text-sm text-gray-700 font-mono">{{ $petugas->user?->username ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">No. Telepon</dt>
                    <dd class="text-sm text-gray-700">{{ $petugas->user?->no_telepon ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">NIP</dt>
                    <dd class="text-sm font-mono font-semibold text-[#022448]">{{ $petugas->nip ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Zona Wilayah</dt>
                    <dd class="text-sm text-gray-700">
                        @if($petugas->zona)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-violet-50 text-violet-700 rounded-lg text-xs font-semibold">
                                <span class="material-symbols-outlined text-sm">map</span>
                                {{ $petugas->zona->nama_zona }}
                            </span>
                        @else
                            <span class="text-gray-400">Belum ditentukan</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Tanggal Daftar</dt>
                    <dd class="text-sm text-gray-700">{{ $petugas->created_at->translatedFormat('d F Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Terakhir Diperbarui</dt>
                    <dd class="text-sm text-gray-700">{{ $petugas->updated_at->translatedFormat('d F Y') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Card: Riwayat Tugas Terkini --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Riwayat Tugas</h2>
                <span class="text-xs text-gray-400">{{ $petugas->assignments->count() }} total tugas</span>
            </div>

            @if($petugas->assignments->isEmpty())
                <div class="flex flex-col items-center py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-gray-300 text-2xl">assignment</span>
                    </div>
                    <p class="text-sm text-gray-400">Belum ada riwayat tugas</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($petugas->assignments->take(5) as $assignment)
                        @php
                            $statusAsn = [
                                'ditugaskan'      => ['bg-blue-50 text-blue-700',    'Ditugaskan'],
                                'sedang_diproses' => ['bg-amber-50 text-amber-700',  'Diproses'],
                                'selesai'         => ['bg-emerald-50 text-emerald-700','Selesai'],
                                'dibatalkan'      => ['bg-gray-100 text-gray-500',   'Dibatalkan'],
                            ];
                            [$asnCls, $asnLabel] = $statusAsn[$assignment->status_assignment] ?? ['bg-gray-100 text-gray-500','—'];
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $assignment->pengaduan->nomor_tiket ?? '#' . $assignment->id }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $assignment->created_at->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 {{ $asnCls }} rounded-full text-xs font-semibold">
                                {{ $asnLabel }}
                            </span>
                        </div>
                    @endforeach
                    @if($petugas->assignments->count() > 5)
                        <p class="text-center text-xs text-gray-400 pt-2">
                            + {{ $petugas->assignments->count() - 5 }} tugas lainnya
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Kolom Kanan: Statistik & Aksi --}}
    <div class="space-y-6">

        {{-- Stats --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Statistik Tugas</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <span class="text-xs text-gray-500">Total Tugas</span>
                    <span class="text-lg font-bold text-gray-900">{{ $petugas->assignments->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl">
                    <span class="text-xs text-emerald-600">Selesai</span>
                    <span class="text-lg font-bold text-emerald-700">
                        {{ $petugas->assignments->where('status_assignment', 'selesai')->count() }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-amber-50 rounded-xl">
                    <span class="text-xs text-amber-600">Sedang Diproses</span>
                    <span class="text-lg font-bold text-amber-700">
                        {{ $petugas->assignments->where('status_assignment', 'sedang_diproses')->count() }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                    <span class="text-xs text-blue-600">Ditugaskan</span>
                    <span class="text-lg font-bold text-blue-700">
                        {{ $petugas->assignments->where('status_assignment', 'ditugaskan')->count() }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Aksi Cepat --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Aksi</h3>
            <a href="{{ route('admin.petugas.edit', $petugas) }}"
               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#022448] text-white text-sm font-semibold rounded-xl hover:bg-[#033466] transition">
                <span class="material-symbols-outlined text-base">edit</span>
                Edit Data Petugas
            </a>
            <a href="{{ route('admin.petugas.index') }}"
               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Kembali ke Daftar
            </a>
            {{-- Nonaktifkan --}}
            @if($petugas->status_tersedia !== 'tidak_aktif')
                <form action="{{ route('admin.petugas.destroy', $petugas) }}" method="POST"
                      onsubmit="return confirm('Nonaktifkan {{ $petugas->user?->name ?? 'petugas ini' }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-50 text-orange-600 text-sm font-semibold rounded-xl hover:bg-orange-100 transition border border-orange-200">
                        <span class="material-symbols-outlined text-base">person_off</span>
                        Nonaktifkan Petugas
                    </button>
                </form>
            @endif
            {{-- Hapus Permanen --}}
            <form id="form-hapus-show-{{ $petugas->id }}"
                  action="{{ route('admin.petugas.hapus-permanen', $petugas) }}" method="POST"
                  onsubmit="return confirm('⚠️ HAPUS PERMANEN {{ $petugas->user->name }}?\n\nTindakan ini tidak dapat dibatalkan!\nPetugas hanya bisa dihapus jika tidak memiliki riwayat tugas.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-100 transition border border-red-200">
                    <span class="material-symbols-outlined text-base">delete</span>
                    Hapus Permanen
                </button>
            </form>
        </div>
    </div>
</div>

</x-app-admin-layout>
