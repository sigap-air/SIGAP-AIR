<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <h1 class="text-2xl font-bold text-gray-800 mb-6">👋 Selamat datang, {{ auth()->user()->name }}!</h1>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
            <div class="text-3xl font-black text-gray-800">{{ $totalPengaduan }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Pengaduan</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
            <div class="text-3xl font-black text-gray-800">{{ $totalSelesai }}</div>
            <div class="text-sm text-gray-500 mt-1">Selesai Ditangani</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-yellow-500">
            <div class="text-3xl font-black text-gray-800">{{ $unreadNotif }}</div>
            <div class="text-sm text-gray-500 mt-1">Notifikasi Baru</div>
        </div>
    </div>

    {{-- Quick Action --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <a href="{{ route('masyarakat.pengaduan.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-5 flex items-center gap-4 transition">
            <span class="text-4xl">📋</span>
            <div>
                <div class="font-bold text-lg">Buat Pengaduan Baru</div>
                <div class="text-sm text-blue-200">Laporkan masalah air Anda</div>
            </div>
        </a>
        <a href="{{ route('masyarakat.riwayat.index') }}"
           class="bg-white shadow rounded-xl p-5 flex items-center gap-4 hover:bg-gray-50 transition">
            <span class="text-4xl">📜</span>
            <div>
                <div class="font-bold text-lg text-gray-800">Riwayat Pengaduan</div>
                <div class="text-sm text-gray-500">Pantau status pengaduan Anda</div>
            </div>
        </a>
    </div>

    {{-- Pengaduan Terakhir --}}
    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="font-bold text-gray-700 mb-4">📬 Pengaduan Terakhir</h2>
        @forelse ($pengaduanTerakhir as $p)
        <a href="{{ route('masyarakat.riwayat.show', $p) }}"
           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition border-b last:border-b-0">
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $p->nomor_tiket }}</p>
                <p class="text-xs text-gray-500">{{ $p->kategori->nama_kategori }} · {{ $p->tanggal_pengajuan->translatedFormat('d M Y') }}</p>
            </div>
            <x-badge-status :status="$p->status" />
        </a>
        @empty
        <div class="text-center py-8 text-gray-400">
            <p class="text-sm">Belum ada pengaduan. <a href="{{ route('masyarakat.pengaduan.create') }}" class="text-blue-600 hover:underline">Buat sekarang?</a></p>
        </div>
        @endforelse
    </div>
</x-app-layout>
