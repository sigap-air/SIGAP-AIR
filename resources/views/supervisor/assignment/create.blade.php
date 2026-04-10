{{-- PBI-06 Form Assignment Petugas --}}
<x-app-layout>
    <x-slot name="title">Tugaskan Petugas</x-slot>

    <div class="mb-4">
        <a href="{{ route('supervisor.verifikasi.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali</a>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 mb-6">👷 Tugaskan Petugas</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Info Pengaduan --}}
        <div class="lg:col-span-1">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                <h2 class="font-bold text-blue-800 mb-3">📋 Info Pengaduan</h2>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-blue-600">No. Tiket</dt>
                        <dd class="font-mono font-bold text-blue-900">{{ $pengaduan->nomor_tiket }}</dd>
                    </div>
                    <div>
                        <dt class="text-blue-600">Kategori</dt>
                        <dd class="font-semibold">{{ $pengaduan->kategori->nama_kategori }}</dd>
                    </div>
                    <div>
                        <dt class="text-blue-600">Zona</dt>
                        <dd class="font-semibold">{{ $pengaduan->zona->nama_zona }}</dd>
                    </div>
                    <div>
                        <dt class="text-blue-600">Lokasi</dt>
                        <dd>{{ $pengaduan->lokasi }}</dd>
                    </div>
                    <div>
                        <dt class="text-blue-600">SLA</dt>
                        <dd class="font-semibold text-orange-700">{{ $pengaduan->kategori->sla_jam }} jam</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Form Assignment --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow p-6">
                @if ($petugas->isEmpty())
                <div class="text-center py-10 text-gray-400">
                    <div class="text-4xl mb-3">😔</div>
                    <p class="font-semibold">Tidak ada petugas tersedia di zona {{ $pengaduan->zona->nama_zona }}.</p>
                    <p class="text-sm mt-1">Tambahkan petugas ke zona ini terlebih dahulu.</p>
                </div>
                @else
                <form method="POST" action="{{ route('supervisor.assignment.store', $pengaduan) }}">
                    @csrf
                    <input type="hidden" name="pengaduan_id" value="{{ $pengaduan->id }}">

                    {{-- Pilih Petugas --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Petugas <span class="text-red-500">*</span></label>
                        <div class="space-y-2">
                            @foreach ($petugas as $p)
                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="petugas_id" value="{{ $p->id }}" required
                                       class="accent-blue-600">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $p->user->name }}</p>
                                    <p class="text-xs text-gray-500">No. Pegawai: {{ $p->nomor_pegawai }} ·
                                        <span class="text-green-600 font-medium">{{ ucwords(str_replace('_', ' ', $p->status_ketersediaan)) }}</span>
                                    </p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('petugas_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jadwal --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Jadwal Penanganan <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="jadwal_penanganan"
                               value="{{ old('jadwal_penanganan') }}"
                               min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}"
                               class="w-full border rounded-lg px-3 py-2" required>
                        @error('jadwal_penanganan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Instruksi --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Instruksi Khusus <span class="text-gray-400">(opsional)</span>
                        </label>
                        <textarea name="instruksi" rows="3"
                            placeholder="Contoh: Bawa alat ukur tekanan, koordinasi dengan RT setempat..."
                            class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('instruksi') }}</textarea>
                        @error('instruksi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition">
                            🚀 Tugaskan Petugas
                        </button>
                        <a href="{{ route('supervisor.verifikasi.index') }}"
                           class="flex-1 text-center bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 transition">
                            Batal
                        </a>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
