{{--
    PBI-04 — Form Pengajuan Pengaduan
    TANGGUNG JAWAB: Sanitra Savitri
--}}
<x-app-layout>
    <x-slot name="title">Pengaduan Baru</x-slot>
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-xl font-bold text-gray-800 mb-6">📋 Ajukan Pengaduan Baru</h1>
            <form action="{{ route('masyarakat.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori_id" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">— Pilih Kategori —</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }} (SLA: {{ $k->sla_jam }} jam)</option>
                        @endforeach
                    </select>
                    @error('kategori_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zona Wilayah <span class="text-red-500">*</span></label>
                    <select name="zona_id" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">— Pilih Zona —</option>
                        @foreach ($zonas as $z)
                            <option value="{{ $z->id }}">{{ $z->nama_zona }}</option>
                        @endforeach
                    </select>
                    @error('zona_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Jl. Merdeka No. 10, RT 02/RW 04" class="w-full border rounded-lg px-3 py-2" required>
                    @error('lokasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Masalah <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="4" placeholder="Jelaskan masalah secara detail..." class="w-full border rounded-lg px-3 py-2" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bukti <span class="text-gray-400">(opsional, maks 5MB)</span></label>
                    <input type="file" name="foto_bukti" accept="image/jpeg,image/png" class="w-full border rounded-lg px-3 py-2">
                    @error('foto_bukti') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700">📤 Kirim Pengaduan</button>
                    <a href="{{ route('masyarakat.dashboard') }}" class="flex-1 text-center bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
