{{-- PBI-06 Form Assignment Petugas --}}
<x-app-supervisor-layout>
    <x-slot name="title">Tugaskan Petugas</x-slot>

    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tugaskan Petugas</h1>
                <p class="text-sm text-gray-500 mt-1">Pilih petugas yang tersedia sesuai zona pengaduan.</p>
            </div>
            <a href="{{ route('supervisor.verifikasi.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Kembali ke antrean
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-xl bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-700 text-sm">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Info Pengaduan --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="font-semibold text-gray-800 mb-4">Info Pengaduan</h2>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-gray-500">No. Tiket</dt>
                        <dd class="font-mono font-bold text-[#022448]">{{ $pengaduan->nomor_tiket }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Kategori</dt>
                        <dd class="font-semibold">{{ $pengaduan->kategori->nama_kategori }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Zona</dt>
                        <dd class="font-semibold">{{ $pengaduan->zona->nama_zona }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Lokasi</dt>
                        <dd>{{ $pengaduan->lokasi }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">SLA</dt>
                        <dd class="font-semibold text-amber-600">{{ $pengaduan->kategori->sla_jam }} jam</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Form Assignment --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                @if ($petugas->isEmpty())
                <div class="text-center py-10 text-gray-400 border border-dashed border-gray-200 rounded-xl">
                    <div class="text-4xl mb-3">-</div>
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
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-[#022448] has-[:checked]:bg-[#022448]/5">
                                <input type="radio" name="petugas_id" value="{{ $p->id }}" required {{ old('petugas_id') == $p->id ? 'checked' : '' }}
                                       class="accent-blue-600">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $p->user->name }}</p>
                                    <p class="text-xs text-gray-500">No. Pegawai: {{ $p->nomor_pegawai ?? '-' }} ·
                                        <span class="text-green-600 font-medium">{{ ucwords(str_replace('_', ' ', $p->status_ketersediaan ?? 'tersedia')) }}</span>
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
                               class="w-full h-11 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white" required>
                        @error('jadwal_penanganan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Instruksi --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Instruksi Khusus <span class="text-gray-400">(opsional)</span>
                        </label>
                        <textarea name="instruksi" rows="3"
                            placeholder="Contoh: Bawa alat ukur tekanan, koordinasi dengan RT setempat..."
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white">{{ old('instruksi') }}</textarea>
                        @error('instruksi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 h-11 bg-[#022448] text-white rounded-xl font-semibold hover:bg-[#1e3a5f] transition">
                            Tugaskan Petugas
                        </button>
                        <a href="{{ route('supervisor.verifikasi.index') }}"
                           class="flex-1 h-11 inline-flex items-center justify-center bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                            Batal
                        </a>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app-supervisor-layout>
