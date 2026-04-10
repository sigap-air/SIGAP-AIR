{{-- PBI-07 Detail Tugas + Update Status --}}
<x-app-layout>
    <x-slot name="title">Detail Tugas #{{ $tugas->pengaduan->nomor_tiket }}</x-slot>

    <div class="mb-4">
        <a href="{{ route('petugas.tugas.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Daftar Tugas</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Detail Pengaduan --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow p-6">
                <h1 class="text-xl font-bold text-gray-800 mb-4">{{ $tugas->pengaduan->nomor_tiket }}</h1>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Kategori</dt>
                        <dd class="font-semibold mt-0.5">{{ $tugas->pengaduan->kategori->nama_kategori }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Zona</dt>
                        <dd class="font-semibold mt-0.5">{{ $tugas->pengaduan->zona->nama_zona }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-500">Lokasi</dt>
                        <dd class="font-semibold mt-0.5">{{ $tugas->pengaduan->lokasi }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-500">Deskripsi Masalah</dt>
                        <dd class="mt-0.5 leading-relaxed text-gray-700">{{ $tugas->pengaduan->deskripsi }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Pelapor</dt>
                        <dd class="font-semibold mt-0.5">{{ $tugas->pengaduan->pelapor->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">No. Telepon</dt>
                        <dd class="font-semibold mt-0.5">{{ $tugas->pengaduan->pelapor->no_telepon ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Foto Bukti --}}
            @if ($tugas->pengaduan->foto_bukti)
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="font-bold text-gray-700 mb-3">📸 Foto Masalah dari Pelapor</h2>
                <img src="{{ asset('storage/' . $tugas->pengaduan->foto_bukti) }}"
                     alt="Foto Bukti" class="w-full max-h-64 object-cover rounded-lg border">
            </div>
            @endif

            {{-- Foto Hasil sebelumnya --}}
            @if ($tugas->foto_hasil)
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="font-bold text-gray-700 mb-3">🖼️ Foto Penanganan Sebelumnya</h2>
                <img src="{{ asset('storage/' . $tugas->foto_hasil) }}"
                     alt="Foto Hasil" class="w-full max-h-64 object-cover rounded-lg border">
            </div>
            @endif
        </div>

        {{-- Form Update Status --}}
        <div class="lg:col-span-1">
            @php $sla = $tugas->pengaduan->sla; @endphp

            {{-- SLA Info --}}
            @if ($sla)
            <div class="bg-{{ $sla->is_overdue ? 'red' : 'orange' }}-50 border border-{{ $sla->is_overdue ? 'red' : 'orange' }}-200 rounded-xl p-4 mb-4">
                <p class="font-bold text-{{ $sla->is_overdue ? 'red' : 'orange' }}-700 text-sm">
                    {{ $sla->is_overdue ? '🚨 SLA TERLAMPAUI!' : '⏱️ Batas Waktu SLA' }}
                </p>
                <p class="text-xs text-{{ $sla->is_overdue ? 'red' : 'orange' }}-600 mt-1">
                    {{ $sla->deadline->translatedFormat('d M Y H:i') }} WIB
                    ({{ $sla->deadline->diffForHumans() }})
                </p>
            </div>
            @endif

            {{-- Instruksi Supervisor --}}
            @if ($tugas->instruksi)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                <p class="font-bold text-yellow-700 text-sm mb-1">📋 Instruksi Supervisor</p>
                <p class="text-xs text-yellow-800">{{ $tugas->instruksi }}</p>
            </div>
            @endif

            {{-- Form Update --}}
            @if ($tugas->status_assignment !== 'selesai')
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="font-bold text-gray-700 mb-4">🔄 Update Status</h2>
                <form method="POST" action="{{ route('petugas.tugas.update', $tugas) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status_assignment" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                            @if ($tugas->status_assignment === 'ditugaskan')
                                <option value="diproses">🔧 Sedang Diproses</option>
                            @endif
                            <option value="selesai">✅ Selesai</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Dokumentasi</label>
                        <input type="file" name="foto_hasil" accept="image/jpeg,image/png"
                               class="w-full text-sm border rounded-lg px-3 py-2">
                        <p class="text-xs text-gray-400 mt-1">JPG/PNG, maks 5MB</p>
                        @error('foto_hasil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Penanganan</label>
                        <textarea name="catatan_penanganan" rows="3"
                            placeholder="Apa yang sudah dilakukan..."
                            class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('catatan_penanganan', $tugas->catatan_penanganan) }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Simpan Update
                    </button>
                </form>
            </div>
            @else
            <div class="bg-green-50 border border-green-200 rounded-xl p-5 text-center">
                <div class="text-3xl mb-2">✅</div>
                <p class="font-bold text-green-700">Tugas Selesai</p>
                <p class="text-xs text-green-600 mt-1">{{ $tugas->tanggal_selesai?->translatedFormat('d M Y H:i') }} WIB</p>
                @if ($tugas->catatan_penanganan)
                <p class="text-xs text-gray-600 mt-2">{{ $tugas->catatan_penanganan }}</p>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
