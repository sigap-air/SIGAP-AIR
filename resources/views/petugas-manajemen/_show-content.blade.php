@php
    $readOnly = $readOnly ?? false;
    $routePrefix = $routePrefix ?? 'admin.petugas';
@endphp

<div class="mb-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route($routePrefix . '.index') }}" class="hover:text-[#022448]">Petugas Teknis</a>
        <span class="material-symbols-outlined text-base text-gray-300">chevron_right</span>
        <span class="text-gray-700 font-medium">{{ $petugas->user?->name ?? '(tanpa nama)' }}</span>
    </nav>
</div>

@if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
        <span class="material-symbols-outlined text-emerald-500">check_circle</span>
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Kiri: Info + Kinerja --}}
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start gap-4 mb-6">
                @if($petugas->user?->foto_profil)
                    <img src="{{ asset('storage/' . $petugas->user->foto_profil) }}" class="w-16 h-16 rounded-2xl object-cover" alt="">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-[#022448]/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#022448] text-3xl">person</span>
                    </div>
                @endif
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $petugas->user?->name ?? '(tanpa nama)' }}</h1>
                    <p class="text-sm text-gray-500">{{ $petugas->user?->email }}</p>
                    @if($petugas->nip)
                        <p class="text-xs font-mono text-[#022448] mt-1">NIP: {{ $petugas->nip }}</p>
                    @endif
                </div>
            </div>

            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-xs text-gray-400 uppercase">Zona</dt>
                    <dd class="font-medium">{{ $petugas->zona?->nama_zona ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase">Status</dt>
                    <dd>
                        @php $lbl = ['tersedia'=>'Tersedia','sibuk'=>'Sibuk','tidak_aktif'=>'Tidak Aktif']; @endphp
                        <span class="font-semibold">{{ $lbl[$petugas->status_tersedia] ?? $petugas->status_tersedia }}</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase">Telepon</dt>
                    <dd>{{ $petugas->user?->no_telepon ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase">Terdaftar</dt>
                    <dd>{{ $petugas->created_at->translatedFormat('d M Y') }}</dd>
                </div>
            </dl>

            <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-gray-100">
                @unless($readOnly)
                    <a href="{{ route('admin.petugas.edit', $petugas) }}"
                       class="px-4 py-2 bg-[#022448] text-white text-sm font-semibold rounded-xl">Edit Data</a>
                @endunless
                @php
                    $isAktif = in_array($petugas->status_tersedia, ['tersedia', 'sibuk']);
                    $toggleStatus = $isAktif ? 'tidak_aktif' : 'tersedia';
                    $toggleLabel  = $isAktif ? 'Nonaktifkan' : 'Aktifkan';
                    $toggleClass  = $isAktif
                        ? 'bg-red-50 text-red-700 border border-red-200'
                        : 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                @endphp
                <form action="{{ route($routePrefix . '.update-status', $petugas) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_tersedia" value="{{ $toggleStatus }}">
                    <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-xl {{ $toggleClass }}">
                        {{ $toggleLabel }} Petugas
                    </button>
                </form>
                <a href="{{ route($routePrefix . '.index') }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl">Kembali</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Statistik Kinerja</h2>
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500">Total Ditangani</p>
                    <p class="text-xl font-bold text-gray-900">{{ $kinerja['total_ditangani'] }}</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <p class="text-xs text-emerald-600">Total Selesai</p>
                    <p class="text-xl font-bold text-emerald-700">{{ $kinerja['total_selesai'] }}</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl">
                    <p class="text-xs text-amber-600">Tugas Aktif</p>
                    <p class="text-xl font-bold text-amber-700">{{ $kinerja['total_aktif'] }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl">
                    <p class="text-xs text-blue-600">Rata-rata Waktu</p>
                    <p class="text-xl font-bold text-blue-700">
                        {{ $kinerja['rata_waktu_jam'] !== null ? $kinerja['rata_waktu_jam'] . ' jam' : '—' }}
                    </p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 mb-2">Rata-rata Rating</p>
                @if($kinerja['rata_rating'])
                    <x-star-rating :rating="$kinerja['rata_rating']" size="md" :label="number_format($kinerja['rata_rating'], 1) . '/5'" />
                @else
                    <span class="text-sm text-gray-400">Belum ada rating</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Kanan: Histori penugasan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Histori Penugasan</h2>
            <span class="text-xs text-gray-400">{{ $histori->total() }} tugas</span>
        </div>

        @if($histori->isEmpty())
            <p class="text-sm text-gray-400 text-center py-8">Belum ada riwayat penugasan.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 uppercase border-b border-gray-100">
                            <th class="py-2 text-left">Tiket</th>
                            <th class="py-2 text-left">Kategori</th>
                            <th class="py-2 text-center">Status</th>
                            <th class="py-2 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($histori as $assignment)
                            @php
                                $asnCfg = [
                                    'ditugaskan' => 'bg-blue-50 text-blue-700',
                                    'diproses'   => 'bg-amber-50 text-amber-700',
                                    'selesai'    => 'bg-emerald-50 text-emerald-700',
                                ];
                                $asnLbl = [
                                    'ditugaskan' => 'Ditugaskan',
                                    'diproses'   => 'Diproses',
                                    'selesai'    => 'Selesai',
                                ];
                            @endphp
                            <tr>
                                <td class="py-3 font-mono text-xs font-semibold text-[#022448]">
                                    {{ $assignment->pengaduan?->nomor_tiket ?? '—' }}
                                </td>
                                <td class="py-3 text-gray-600">
                                    {{ $assignment->pengaduan?->kategori?->nama_kategori ?? '—' }}
                                </td>
                                <td class="py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $asnCfg[$assignment->status_assignment] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $asnLbl[$assignment->status_assignment] ?? $assignment->status_assignment }}
                                    </span>
                                </td>
                                <td class="py-3 text-gray-500 text-xs">
                                    {{ $assignment->created_at->translatedFormat('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($histori->hasPages())
                <div class="mt-4 pt-4 border-t border-gray-100">
                    {{ $histori->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
