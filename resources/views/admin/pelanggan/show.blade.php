<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.pelanggan.index') }}" class="hover:text-[#022448] transition-colors">Data Pelanggan</a>
        <span class="material-symbols-outlined text-base">chevron_right</span>
        <span class="text-gray-900 font-medium">Detail Pelanggan</span>
    </nav>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Detail Pelanggan</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi lengkap pelanggan dan riwayat pengaduan</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pelanggan.edit', $pelanggan->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-50 text-amber-700 font-semibold rounded-xl hover:bg-amber-100 transition-colors duration-200 border border-amber-200">
                <span class="material-symbols-outlined text-lg">edit</span>
                Edit
            </a>
            <a href="{{ route('admin.pelanggan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors duration-200">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Profil Pelanggan --}}
    <div class="lg:col-span-1 space-y-6">

        {{-- Kartu Profil --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-navy-gradient px-6 py-8 text-center">
                <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm ring-4 ring-white/20">
                    <span class="material-symbols-outlined text-white text-4xl">person</span>
                </div>
                <h2 class="text-xl font-bold text-white font-headline">{{ $pelanggan->nama_pelanggan }}</h2>
                <p class="text-blue-300/80 text-xs mt-2 uppercase tracking-wide">No Tiket</p>
                <p class="text-blue-100 text-sm font-mono">{{ $pelanggan->latestPengaduan?->nomor_tiket ?? $pelanggan->nomor_sambungan }}</p>
                <div class="mt-3">
                    @if($pelanggan->is_active)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 text-emerald-200 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-500/20 text-red-200 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                            Nonaktif
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6 space-y-4">
                {{-- Zona --}}
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[#022448] text-lg">location_on</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Zona Wilayah</p>
                        <p class="text-sm text-gray-900 font-semibold mt-0.5">{{ $pelanggan->zona->nama_zona ?? '-' }}</p>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[#022448] text-lg">home</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Alamat</p>
                        <p class="text-sm text-gray-900 mt-0.5">{{ $pelanggan->alamat }}</p>
                    </div>
                </div>

                {{-- No Telepon --}}
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[#022448] text-lg">phone</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Nomor Telepon</p>
                        <p class="text-sm text-gray-900 font-semibold mt-0.5">{{ $pelanggan->no_telepon ?? '-' }}</p>
                    </div>
                </div>

                {{-- Akun User --}}
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[#022448] text-lg">account_circle</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Akun Terhubung</p>
                        @if($pelanggan->user)
                            <p class="text-sm text-gray-900 font-semibold mt-0.5">{{ $pelanggan->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $pelanggan->user->email }}</p>
                        @else
                            <p class="text-sm text-gray-400 mt-0.5 italic">Belum terhubung</p>
                        @endif
                    </div>
                </div>

                {{-- Tanggal Daftar --}}
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[#022448] text-lg">calendar_today</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Terdaftar Sejak</p>
                        <p class="text-sm text-gray-900 font-semibold mt-0.5">{{ $pelanggan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Riwayat Pengaduan --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#022448]/10 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#022448]">history</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Riwayat Pengaduan</h3>
                        <p class="text-xs text-gray-500">Daftar pengaduan yang diajukan oleh pelanggan ini</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 bg-[#022448]/10 text-[#022448] rounded-full text-sm font-bold">
                    {{ $pelanggan->pengaduan->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80">
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No. Tiket</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Kategori</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pelanggan->pengaduan as $aduan)
                            <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-semibold text-[#022448]">{{ $aduan->nomor_tiket }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $aduan->kategori->nama_kategori ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColors = [
                                            'menunggu_verifikasi' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                            'ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                            'disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'ditugaskan' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'sedang_diproses' => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        ];
                                        $statusLabels = [
                                            'menunggu_verifikasi' => 'Menunggu',
                                            'ditolak' => 'Ditolak',
                                            'disetujui' => 'Disetujui',
                                            'ditugaskan' => 'Ditugaskan',
                                            'sedang_diproses' => 'Diproses',
                                            'selesai' => 'Selesai',
                                        ];
                                    @endphp
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$aduan->status] ?? 'bg-gray-50 text-gray-600' }}">
                                        {{ $statusLabels[$aduan->status] ?? $aduan->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-sm">{{ $aduan->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                            <span class="material-symbols-outlined text-gray-300 text-2xl">inbox</span>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada riwayat pengaduan</p>
                                        <p class="text-gray-400 text-xs mt-1">Pelanggan ini belum pernah mengajukan pengaduan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

</x-app-admin-layout>
