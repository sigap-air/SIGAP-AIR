{{--
    Komponen Badge Status Pengaduan
    Penggunaan: <x-badge-status :status="$pengaduan->status" />
    TANGGUNG JAWAB: Digunakan oleh semua developer
--}}
@props(['status'])

@php
    $config = [
        'menunggu_verifikasi' => ['bg-yellow-100 text-yellow-800',  '⏳ Menunggu Verifikasi'],
        'disetujui'           => ['bg-blue-100 text-blue-800',      '✅ Disetujui'],
        'ditolak'             => ['bg-red-100 text-red-800',        '❌ Ditolak'],
        'ditugaskan'          => ['bg-indigo-100 text-indigo-800',  '👷 Ditugaskan'],
        'diproses'            => ['bg-orange-100 text-orange-800',  '🔧 Sedang Diproses'],
        'selesai'             => ['bg-green-100 text-green-800',    '✔️ Selesai'],
    ];
    [$class, $label] = $config[$status] ?? ['bg-gray-100 text-gray-800', $status];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}">
    {{ $label }}
</span>
