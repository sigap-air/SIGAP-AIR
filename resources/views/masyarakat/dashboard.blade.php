<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="mx-auto w-full max-w-5xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Halo, {{ auth()->user()->name }} 👋</h1>
            <p class="mt-1 text-sm text-gray-500">Selamat datang di portal pengaduan masyarakat SIGAP-AIR.</p>
        </div>

        {{-- Stats --}}
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="text-3xl font-black text-[#022448]">{{ $totalPengaduan }}</div>
                <div class="mt-1 text-sm text-gray-500">Total Pengaduan</div>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="text-3xl font-black text-emerald-600">{{ $totalSelesai }}</div>
                <div class="mt-1 text-sm text-gray-500">Selesai Ditangani</div>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="text-3xl font-black text-amber-600">{{ $unreadNotif }}</div>
                <div class="mt-1 text-sm text-gray-500">Notifikasi Baru</div>
            </div>
        </div>

        {{-- Quick Action --}}
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <a href="{{ route('masyarakat.pengaduan.create') }}"
               class="rounded-2xl bg-[#022448] p-5 text-white shadow-sm transition hover:bg-[#1e3a5f]">
                <p class="text-xs uppercase tracking-wide text-blue-200">Aksi Cepat</p>
                <p class="mt-2 text-lg font-bold">Buat Pengaduan Baru</p>
                <p class="mt-1 text-sm text-blue-100">Laporkan masalah air Anda sekarang.</p>
            </a>
            <a href="{{ route('masyarakat.pengaduan.riwayat') }}"
               class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:bg-gray-50">
                <p class="text-xs uppercase tracking-wide text-gray-500">Aksi Cepat</p>
                <p class="mt-2 text-lg font-bold text-gray-800">Riwayat Pengaduan</p>
                <p class="mt-1 text-sm text-gray-500">Pantau status pengaduan yang sudah dikirim.</p>
            </a>
        </div>

        {{-- Pengaduan Terakhir --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-bold text-gray-700">Pengaduan Terakhir</h2>
            @forelse ($pengaduanTerakhir as $p)
            <a href="{{ route('masyarakat.pengaduan.riwayat.show', $p->nomor_tiket) }}"
               class="flex items-center justify-between rounded-lg border-b p-3 transition last:border-b-0 hover:bg-gray-50">
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $p->nomor_tiket }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $p->kategori?->nama_kategori ?? '-' }}
                        · {{ $p->tanggal_pengajuan->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                <x-badge-status :status="$p->status" />
            </a>
            @empty
            <div class="py-8 text-center text-gray-400">
                <p class="text-sm">Belum ada pengaduan. <a href="{{ route('masyarakat.pengaduan.create') }}" class="text-blue-600 hover:underline">Buat sekarang?</a></p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
