{{--
    PBI-04 — Konfirmasi tiket berhasil dibuat
    TANGGUNG JAWAB: Sanitra Savitri
--}}
<x-masyarakat-form-layout title="Pengaduan terkirim">
    <div class="rounded-2xl bg-gray-100 p-6 text-center">
        <p class="text-lg font-semibold text-gray-800">Pengaduan berhasil dikirim</p>
        <p class="mt-2 text-sm text-gray-600">Simpan nomor tiket berikut untuk memantau progres.</p>
        <p class="mt-4 break-all font-mono text-xl font-bold tracking-tight text-brand">{{ $pengaduan->nomor_tiket }}</p>
    </div>

    <div class="mt-6 flex flex-col gap-3">
        <x-sigap-action-button variant="primary" href="{{ route('masyarakat.riwayat.show', $pengaduan) }}">Lihat detail pengaduan</x-sigap-action-button>
        <x-sigap-action-button variant="secondary" href="{{ route('masyarakat.dashboard') }}">Kembali ke beranda</x-sigap-action-button>
    </div>
</x-masyarakat-form-layout>
