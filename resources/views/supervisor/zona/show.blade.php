<x-app-supervisor-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('supervisor.zona.index') }}"
               class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-headline">Detail Zona Wilayah</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <a href="{{ route('supervisor.zona.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Zona Wilayah</a>
                    <span class="text-gray-300 text-xs">/</span>
                    <span class="text-xs text-gray-600 font-medium">{{ $zona->nama_zona }}</span>
                </div>
            </div>
        </div>
        {{-- Supervisor: Hanya lihat, tidak ada tombol Edit/Hapus --}}
        <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-[#022448] text-xs font-semibold rounded-xl border border-blue-100">
            <span class="material-symbols-outlined text-base">visibility</span>
            Mode Lihat Saja
        </span>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
        <span class="material-symbols-outlined text-emerald-500 flex-shrink-0">check_circle</span>
        {{ session('success') }}
    </div>
@endif

{{-- ====================================== --}}
{{-- SECTION A: Statistik Pengaduan per Zona --}}
{{-- ====================================== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Total Pengaduan --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[#022448] text-xl">pending_actions</span>
            </div>
            <span class="text-xs text-gray-400 font-medium">Total</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Pengaduan</p>
    </div>

    {{-- Pengaduan Aktif --}}
    <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600 text-xl">hourglass_top</span>
            </div>
            <span class="text-xs text-amber-600 font-semibold bg-amber-50 px-2 py-0.5 rounded-full">Aktif</span>
        </div>
        <p class="text-3xl font-bold text-amber-700">{{ $stats['aktif'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Sedang Berjalan</p>
    </div>

    {{-- Pengaduan Selesai --}}
    <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
            </div>
            <span class="text-xs text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full">Selesai</span>
        </div>
        <p class="text-3xl font-bold text-emerald-700">{{ $stats['selesai'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Sudah Ditangani</p>
    </div>

    {{-- Pengaduan Overdue --}}
    <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-5 {{ $stats['overdue'] > 0 ? 'ring-1 ring-red-300' : '' }}">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600 text-xl">alarm_off</span>
            </div>
            @if($stats['overdue'] > 0)
                <span class="text-xs text-red-600 font-bold bg-red-100 px-2 py-0.5 rounded-full animate-pulse">Overdue!</span>
            @else
                <span class="text-xs text-gray-400 font-medium">Overdue</span>
            @endif
        </div>
        <p class="text-3xl font-bold {{ $stats['overdue'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $stats['overdue'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Melewati Batas SLA</p>
    </div>
</div>

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

        {{-- ======================================= --}}
        {{-- Beban Kerja (Workload) Card             --}}
        {{-- ======================================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#022448] text-xl">speed</span>
                    <h3 class="text-base font-semibold text-gray-900">Beban Kerja</h3>
                </div>
                <p class="text-xs text-gray-400 mt-0.5 ml-7">Kapasitas penanganan zona ini</p>
            </div>

            <div class="p-5">
                {{-- Progress Bar --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Utilisasi Kapasitas</span>
                        <span class="text-sm font-bold
                            @if($bebanPersen >= 80) text-red-600
                            @elseif($bebanPersen >= 50) text-amber-600
                            @else text-emerald-600
                            @endif">
                            {{ $bebanPersen }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="h-3 rounded-full transition-all duration-500
                            @if($bebanPersen >= 80) bg-red-500
                            @elseif($bebanPersen >= 50) bg-amber-400
                            @else bg-emerald-500
                            @endif"
                             style="width: {{ $bebanPersen }}%">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-500 mb-1">Aktif</p>
                        <p class="text-lg font-bold text-amber-600">{{ $stats['aktif'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-500 mb-1">Kapasitas</p>
                        <p class="text-lg font-bold text-gray-700">{{ $kapasitas }}</p>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-3 text-center">
                    Kapasitas = {{ $zona->petugas->count() }} petugas × 5 pengaduan
                </p>
            </div>
        </div>

    </div>

    {{-- ============================== --}}
    {{-- KOLOM KANAN: Petugas & Pengaduan --}}
    {{-- ============================== --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Daftar Petugas di Zona Ini (read-only) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#022448] text-xl">badge</span>
                    <h2 class="text-base font-semibold text-gray-900">Petugas di Zona Ini</h2>
                    <span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 bg-[#022448] text-white rounded-full text-xs font-bold">
                        {{ $zona->petugas->count() }}
                    </span>
                </div>
            </div>

            @if($zona->petugas->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($zona->petugas as $petugas)
                        <div class="flex items-center px-6 py-4 hover:bg-gray-50/50 transition-colors">
                            <div class="flex items-center gap-4 flex-1">
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
                            {{-- Supervisor: tidak ada tombol lepas --}}
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-semibold">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Aktif
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center py-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 border-2 border-dashed border-gray-200">
                        <span class="material-symbols-outlined text-gray-300 text-3xl">group_off</span>
                    </div>
                    <p class="text-gray-600 font-semibold">Belum ada petugas di zona ini</p>
                    <p class="text-gray-400 text-sm mt-1">Hubungi Admin untuk memetakan petugas</p>
                </div>
            @endif
        </div>

        {{-- ================================== --}}
        {{-- Pengaduan Terbaru di Zona Ini       --}}
        {{-- ================================== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#022448] text-xl">receipt_long</span>
                    <h2 class="text-base font-semibold text-gray-900">Pengaduan Terbaru</h2>
                    <span class="text-xs text-gray-400 ml-auto">5 terakhir</span>
                </div>
            </div>

            @if($pengaduanTerbaru->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($pengaduanTerbaru as $p)
                        @php
                            $isOverdue = $p->sla && $p->sla->status_sla === 'overdue';
                            $statusColor = match($p->status) {
                                'selesai'          => 'bg-emerald-100 text-emerald-700',
                                'ditolak'          => 'bg-gray-100 text-gray-500',
                                'sedang_diproses'  => 'bg-blue-100 text-blue-700',
                                'ditugaskan'       => 'bg-violet-100 text-violet-700',
                                default            => 'bg-amber-100 text-amber-700',
                            };
                            $statusLabel = match($p->status) {
                                'selesai'          => 'Selesai',
                                'ditolak'          => 'Ditolak',
                                'sedang_diproses'  => 'Diproses',
                                'ditugaskan'       => 'Ditugaskan',
                                'menunggu_verifikasi' => 'Menunggu',
                                default            => ucfirst($p->status),
                            };
                        @endphp
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/50 transition-colors {{ $isOverdue ? 'border-l-2 border-red-400' : '' }}">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="text-xs text-gray-400 font-mono">{{ $p->nomor_tiket ?? '#' . $p->id }}</span>
                                    @if($isOverdue)
                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-red-100 text-red-600 rounded text-xs font-bold">
                                            <span class="material-symbols-outlined" style="font-size:11px;">warning</span>
                                            OD
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $p->judul ?? $p->deskripsi ?? '(Tanpa Judul)' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $p->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor }} flex-shrink-0">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center py-10 text-center">
                    <span class="material-symbols-outlined text-gray-300 text-3xl mb-2">inbox</span>
                    <p class="text-gray-500 text-sm">Belum ada pengaduan di zona ini</p>
                </div>
            @endif
        </div>

    </div>
</div>

</x-app-supervisor-layout>
