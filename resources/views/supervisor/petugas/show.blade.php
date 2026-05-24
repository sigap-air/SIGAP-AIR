<x-app-supervisor-layout>
    <x-slot name="title">Detail Petugas — {{ $petugas->user?->name }}</x-slot>

    @if ($petugas->status_tersedia === 'tersedia' && $pengaduanMenungguTugas->isNotEmpty())
        @include('supervisor.petugas._assign-form')
    @elseif ($petugas->status_tersedia === 'tersedia')
        <div class="mb-6 rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-5 py-4 text-sm text-gray-600">
            <p class="font-semibold text-gray-800">Belum ada pengaduan siap ditugaskan di zona ini.</p>
            <p class="mt-1">Setujui pengaduan terlebih dahulu di menu <a href="{{ route('supervisor.verifikasi.index') }}" class="font-semibold text-[#0F4C81] hover:underline">Verifikasi Tiket</a>.</p>
        </div>
    @elseif ($petugas->status_tersedia !== 'tersedia')
        <div class="mb-6 rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4 text-sm text-amber-800">
            Petugas berstatus <strong>{{ $petugas->status_tersedia === 'sibuk' ? 'Sibuk' : 'Tidak Aktif' }}</strong>.
            Hanya petugas <strong>Tersedia</strong> yang dapat ditugaskan dengan catatan baru.
        </div>
    @endif

    @include('petugas-manajemen._show-content')
</x-app-supervisor-layout>
