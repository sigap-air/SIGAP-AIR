<x-app-supervisor-layout>
    <x-slot name="title">Detail Pengaduan #{{ $pengaduan->nomor_tiket }}</x-slot>

    <div class="mb-4 flex flex-wrap items-center gap-3">
        <a href="{{ route('supervisor.filter.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-[#022448]">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white">←</span>
            <span>Semua Pengaduan</span>
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-xl bg-red-50 p-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <h1 class="font-mono text-xl font-bold text-[#022448]">{{ $pengaduan->nomor_tiket }}</h1>
                    <x-badge-status :status="$pengaduan->status" />
                </div>
                <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="mb-1 text-gray-500">Pelapor</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->pelapor->name }}</dd>
                    </div>
                    <div>
                        <dt class="mb-1 text-gray-500">Email</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->pelapor->email }}</dd>
                    </div>
                    <div>
                        <dt class="mb-1 text-gray-500">Kategori</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->kategori->nama_kategori }}</dd>
                    </div>
                    <div>
                        <dt class="mb-1 text-gray-500">Zona</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->zona->nama_zona }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="mb-1 text-gray-500">Lokasi</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->lokasi }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="mb-1 text-gray-500">Deskripsi</dt>
                        <dd class="leading-relaxed text-gray-800">{{ $pengaduan->deskripsi }}</dd>
                    </div>
                    <div>
                        <dt class="mb-1 text-gray-500">Tanggal Pengajuan</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->tanggal_pengajuan->translatedFormat('d F Y, H:i') }} WIB</dd>
                    </div>
                    <div>
                        <dt class="mb-1 text-gray-500">SLA</dt>
                        <dd class="font-semibold text-amber-600">{{ $pengaduan->kategori->sla_jam }} jam</dd>
                    </div>
                </dl>
            </div>

            @if ($pengaduan->foto_bukti)
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-base font-semibold text-gray-900">Foto Bukti Laporan</h3>
                    <img src="{{ asset('storage/' . $pengaduan->foto_bukti) }}"
                         alt="Foto bukti"
                         class="mx-auto max-h-80 w-full max-w-md cursor-pointer rounded-xl border border-gray-100 object-cover hover:opacity-90"
                         onclick="this.requestFullscreen()">
                </div>
            @endif

            @if ($pengaduan->assignment)
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-bold text-gray-800">Penugasan Petugas</h2>
                    <dl class="mb-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="text-gray-500">Petugas</dt>
                            <dd class="font-semibold text-gray-800">{{ $pengaduan->assignment->petugas?->user?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">NIP</dt>
                            <dd class="font-mono text-gray-800">{{ $pengaduan->assignment->petugas?->nip ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Supervisor</dt>
                            <dd class="font-semibold text-gray-800">{{ $pengaduan->assignment->supervisor?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Status Penugasan</dt>
                            <dd class="font-semibold capitalize text-gray-800">{{ str_replace('_', ' ', $pengaduan->assignment->status_assignment) }}</dd>
                        </div>
                    </dl>

                    <x-instruksi-supervisor :assignment="$pengaduan->assignment" class="mb-4" />

                    @unless ($pengaduan->assignment->instruksi)
                        <p class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-500">
                            Tidak ada catatan assignment dari supervisor.
                        </p>
                    @endunless

                    @if ($pengaduan->assignment->catatan_penanganan)
                        <div class="mt-4 rounded-xl border border-gray-100 bg-gray-50 p-4">
                            <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Catatan Penanganan Petugas</p>
                            <p class="whitespace-pre-wrap text-sm text-gray-700">{{ $pengaduan->assignment->catatan_penanganan }}</p>
                        </div>
                    @endif
                </div>
            @endif

            @if ($pengaduan->status === 'ditolak' && $pengaduan->alasan_penolakan)
                <div class="rounded-2xl border border-red-100 bg-red-50 p-5">
                    <p class="mb-1 font-semibold text-red-800">Alasan Penolakan</p>
                    <p class="text-sm text-red-700">{{ $pengaduan->alasan_penolakan }}</p>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-4">
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h2 class="mb-4 font-bold text-gray-800">Aksi</h2>
                    @if ($pengaduan->status === 'menunggu_verifikasi')
                        <a href="{{ route('supervisor.verifikasi.show', $pengaduan) }}"
                           class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#022448] py-2.5 text-sm font-semibold text-white hover:bg-[#1e3a5f]">
                            Verifikasi Pengaduan
                        </a>
                    @elseif ($pengaduan->status === 'disetujui' && ! $pengaduan->assignment)
                        <a href="{{ route('supervisor.assignment.create', $pengaduan) }}"
                           class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#022448] py-2.5 text-sm font-semibold text-white hover:bg-[#1e3a5f]">
                            Tugaskan Petugas
                        </a>
                        <p class="mt-2 text-xs text-gray-500">Anda dapat menambahkan catatan assignment sebelum memilih petugas.</p>
                    @else
                        <p class="text-sm text-gray-500">Pengaduan sedang dalam proses penanganan atau telah selesai.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-supervisor-layout>
