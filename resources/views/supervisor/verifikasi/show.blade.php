{{-- PBI-05 Detail Verifikasi --}}
<x-app-supervisor-layout>
    <x-slot name="title">Detail Verifikasi #{{ $pengaduan->nomor_tiket }}</x-slot>

    <div class="mb-4 flex flex-wrap items-center gap-3">
        <a href="{{ route('supervisor.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-[#022448]">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white">←</span>
            <span>Dashboard Supervisor</span>
        </a>
        <a href="{{ route('supervisor.verifikasi.index') }}" class="text-sm text-blue-600 hover:underline">Kembali ke Antrean</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Info Pengaduan --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-gray-800">{{ $pengaduan->nomor_tiket }}</h1>
                    @php
                        $statusClass = match($pengaduan->status) {
                            'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-700',
                            'disetujui' => 'bg-green-100 text-green-700',
                            'ditolak' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700',
                        };

                        $statusLabel = match($pengaduan->status) {
                            'menunggu_verifikasi' => 'Menunggu Verifikasi',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                            default => ucfirst(str_replace('_', ' ', $pengaduan->status)),
                        };
                    @endphp
                    <span class="{{ $statusClass }} text-sm px-3 py-1 rounded-full font-semibold">{{ $statusLabel }}</span>
                </div>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500 mb-1">Pelapor</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->pelapor->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 mb-1">Email</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->pelapor->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 mb-1">No. Telepon</dt>
                        <dd class="font-semibold text-gray-800 font-mono">{{ $pengaduan->pelapor?->no_telepon ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 mb-1">Kategori</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->kategori->nama_kategori }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 mb-1">Zona</dt>
                        <dd class="font-semibold text-gray-800 flex items-center flex-wrap gap-2">
                            {{ $pengaduan->zona->nama_zona }}
                            @if($pengaduan->is_zona_valid === 1)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs text-emerald-700" title="Validasi otomatis: Sesuai">
                                    <span class="material-symbols-outlined text-[14px]">verified</span> Valid
                                </span>
                            @elseif($pengaduan->is_zona_valid === 0)
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-xs text-amber-700" title="Validasi otomatis: Peringatan (Mungkin tidak sesuai)">
                                    <span class="material-symbols-outlined text-[14px]">warning</span> Warning
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-500 mb-1">Lokasi</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->lokasi }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-500 mb-1">Deskripsi</dt>
                        <dd class="text-gray-800 leading-relaxed">{{ $pengaduan->deskripsi }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 mb-1">Tanggal Pengajuan</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->tanggal_pengajuan->translatedFormat('d F Y, H:i') }} WIB</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 mb-1">SLA</dt>
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->kategori->sla_jam }} jam sejak pengajuan</dd>
                    </div>
                </dl>
            </div>

            {{-- Foto Bukti --}}
            @if ($pengaduan->foto_bukti)
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Foto Bukti Laporan</h3>
                <div class="mx-auto w-full max-w-sm">
                    <img src="{{ asset('storage/' . $pengaduan->foto_bukti) }}"
                         alt="Foto Bukti"
                         class="w-full aspect-square object-cover rounded-xl border border-gray-100 cursor-pointer hover:opacity-90 transition-opacity"
                         onclick="this.requestFullscreen()">
                </div>
                <p class="text-xs text-gray-400 mt-2 text-center">Klik untuk fullscreen</p>
            </div>
            @endif

            @if ($pengaduan->assignment)
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-base font-semibold text-gray-900">Penugasan & Instruksi</h3>
                    <dl class="mb-4 grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <dt class="text-gray-500">Petugas</dt>
                            <dd class="font-semibold">{{ $pengaduan->assignment->petugas?->user?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Supervisor</dt>
                            <dd class="font-semibold">{{ $pengaduan->assignment->supervisor?->name ?? '—' }}</dd>
                        </div>
                    </dl>
                    <x-instruksi-supervisor :assignment="$pengaduan->assignment" />
                    @unless ($pengaduan->assignment->instruksi)
                        <p class="mt-3 text-sm text-gray-500">Belum ada catatan assignment.</p>
                    @endunless
                    <a href="{{ route('supervisor.pengaduan.show', $pengaduan) }}"
                       class="mt-4 inline-block text-sm font-semibold text-[#0F4C81] hover:underline">
                        Lihat detail lengkap pengaduan →
                    </a>
                </div>
            @endif
        </div>

        {{-- Form Keputusan --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow p-6 sticky top-24">
                <h2 class="font-bold text-gray-700 mb-5">📝 Keputusan Verifikasi</h2>

                @if (session('error'))
                    <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
                @endif

                @if ($pengaduan->status === 'menunggu_verifikasi')
                    @if (!empty($isRevisiUlang))
                    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                        <p class="font-semibold">Pengaduan hasil revisi pelapor</p>
                        <p class="mt-1 text-amber-800">Tiket ini pernah ditolak dan telah diperbaiki oleh pelapor. Silakan verifikasi ulang data terbaru.</p>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('supervisor.verifikasi.update', $pengaduan) }}" id="formVerifikasi">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-2 mb-4">
                            <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-green-50 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                <input type="radio" name="keputusan" value="disetujui" class="mt-1 accent-green-600" {{ old('keputusan') === 'disetujui' ? 'checked' : '' }}>
                                <div>
                                    <p class="font-semibold text-gray-800">Setujui Pengaduan</p>
                                    <p class="text-xs text-gray-500">Pengaduan akan dilanjutkan ke tahap assignment petugas.</p>
                                </div>
                            </label>
                            <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-red-50 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                                <input type="radio" name="keputusan" value="ditolak" class="mt-1 accent-red-600" {{ old('keputusan') === 'ditolak' ? 'checked' : '' }}>
                                <div>
                                    <p class="font-semibold text-gray-800">Tolak Pengaduan</p>
                                    <p class="text-xs text-gray-500">Pelapor akan menerima notifikasi alasan penolakan.</p>
                                </div>
                            </label>
                        </div>

                        @error('keputusan')
                            <p class="text-red-500 text-xs mb-4">{{ $message }}</p>
                        @enderror

                        {{-- Alasan Penolakan --}}
                        <div id="fieldAlasan" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alasan_penolakan" rows="4"
                                placeholder="Wajib diisi jika memilih Tolak Pengaduan."
                                class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('alasan_penolakan') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Kolom ini akan dipakai saat keputusan <strong>ditolak</strong>.</p>
                            @error('alasan_penolakan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Simpan Keputusan
                        </button>
                    </form>
                @elseif ($pengaduan->status === 'disetujui' && ! $pengaduan->assignment)
                    <a href="{{ route('supervisor.assignment.create', $pengaduan) }}"
                       class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#022448] py-2.5 text-sm font-semibold text-white hover:bg-[#1e3a5f]">
                        Tugaskan Petugas
                    </a>
                    <p class="mt-2 text-xs text-gray-500">Tambahkan catatan assignment sebelum memilih petugas.</p>
                @else
                    <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700">
                        Pengaduan ini sudah diproses dan tidak bisa diverifikasi ulang.
                    </div>
                    @if ($pengaduan->assignment)
                        <a href="{{ route('supervisor.pengaduan.show', $pengaduan) }}"
                           class="mt-3 inline-block text-sm font-semibold text-[#0F4C81] hover:underline">
                            Detail pengaduan & instruksi →
                        </a>
                    @endif
                @endif

                @if ($pengaduan->status === 'ditolak' && $pengaduan->alasan_penolakan)
                    <div class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm">
                        <p class="font-semibold mb-1">Alasan penolakan</p>
                        <p>{{ $pengaduan->alasan_penolakan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-app-supervisor-layout>
