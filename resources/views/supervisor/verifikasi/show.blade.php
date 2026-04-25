{{-- PBI-05 Detail Verifikasi --}}
<x-app-layout>
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
                        <dd class="font-semibold text-gray-800">{{ $pengaduan->zona->nama_zona }}</dd>
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
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="font-bold text-gray-700 mb-3">📸 Foto Bukti</h2>
                <img src="{{ asset('storage/' . $pengaduan->foto_bukti) }}"
                     alt="Foto Bukti"
                     class="w-full max-h-80 object-cover rounded-lg border">
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
                @else
                    <div class="p-3 bg-gray-50 text-gray-700 rounded-lg text-sm">
                        Pengaduan ini sudah diproses dan tidak bisa diverifikasi ulang.
                    </div>
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

</x-app-layout>
