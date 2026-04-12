{{-- PBI-05 Detail Verifikasi --}}
<x-app-layout>
    <x-slot name="title">Detail Verifikasi #{{ $pengaduan->nomor_tiket }}</x-slot>

    <div class="mb-4">
        <a href="{{ route('supervisor.verifikasi.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Antrean</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Info Pengaduan --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-gray-800">{{ $pengaduan->nomor_tiket }}</h1>
                    <span class="bg-yellow-100 text-yellow-700 text-sm px-3 py-1 rounded-full font-semibold">Menunggu Verifikasi</span>
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

                <form method="POST" action="{{ route('supervisor.verifikasi.update', $pengaduan) }}" id="formVerifikasi">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="keputusan" id="inputKeputusan" value="">

                    {{-- Alasan Penolakan --}}
                    <div id="fieldAlasan" class="hidden mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan_penolakan" rows="4"
                            placeholder="Jelaskan alasan penolakan..."
                            class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('alasan_penolakan') }}</textarea>
                        @error('alasan_penolakan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <button type="button" onclick="setKeputusan('disetujui')"
                            class="w-full bg-green-600 text-white py-2.5 rounded-lg font-semibold hover:bg-green-700 transition">
                            ✅ Setujui Pengaduan
                        </button>
                        <button type="button" onclick="setKeputusan('ditolak')"
                            class="w-full bg-red-600 text-white py-2.5 rounded-lg font-semibold hover:bg-red-700 transition">
                            ❌ Tolak Pengaduan
                        </button>
                    </div>
                </form>

                @if (session('success'))
                <div class="mt-3 p-3 bg-green-50 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function setKeputusan(nilai) {
            document.getElementById('inputKeputusan').value = nilai;
            if (nilai === 'ditolak') {
                document.getElementById('fieldAlasan').classList.remove('hidden');
            } else {
                document.getElementById('fieldAlasan').classList.add('hidden');
                if (confirm('Apakah Anda yakin menyetujui pengaduan ini?')) {
                    document.getElementById('formVerifikasi').submit();
                }
                return;
            }
            document.getElementById('formVerifikasi').submit();
        }
    </script>
    @endpush
</x-app-layout>
