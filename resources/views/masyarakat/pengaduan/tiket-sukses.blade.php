{{--
    PBI-04 — Konfirmasi tiket berhasil dibuat
    TANGGUNG JAWAB: Sanitra Savitri
--}}
<x-masyarakat-form-layout title="Pengaduan terkirim">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pengaduan Berhasil Dikirim</h1>
        <p class="mt-1 text-sm text-gray-500">Tiket pengaduan sudah dibuat dan menunggu proses verifikasi.</p>
    </div>

    <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-2xl text-emerald-600">
            ✓
        </div>
        <p class="text-center text-lg font-semibold text-gray-800">Nomor tiket pengaduan kamu:</p>
        <p class="mt-3 break-all text-center font-mono text-2xl font-bold tracking-wide text-brand">{{ $pengaduan->nomor_tiket }}</p>
        <p class="mt-3 text-center text-sm text-gray-500">Simpan nomor tiket ini untuk pelacakan status pengaduan.</p>

        <div class="mt-8 grid gap-3 sm:grid-cols-2">
            <x-sigap-action-button variant="primary" href="{{ route('masyarakat.pengaduan.create') }}">Buat pengaduan baru</x-sigap-action-button>
            <x-sigap-action-button variant="secondary" href="{{ route('masyarakat.dashboard') }}">Kembali ke beranda</x-sigap-action-button>
        </div>
    </div>
</x-masyarakat-form-layout>
