<x-app-layout>
    <x-slot name="title">Edit SLA — {{ $kategori->nama_kategori }}</x-slot>

    <div class="mb-4">
        <a href="{{ route('admin.sla.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Daftar SLA</a>
    </div>

    <div class="max-w-lg mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-5">⏱️ Edit SLA: {{ $kategori->nama_kategori }}</h1>

        <form method="POST" action="{{ route('admin.sla.update', $kategori) }}">
            @csrf @method('PATCH')

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Batas Waktu SLA (Jam) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="sla_jam" value="{{ old('sla_jam', $kategori->sla_jam) }}"
                       min="1" max="720" class="w-full border rounded-lg px-3 py-2" required>
                <p class="text-xs text-gray-400 mt-1">Minimal 1 jam, maksimal 720 jam (30 hari)</p>
                @error('sla_jam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $kategori->is_active) ? 'checked' : '' }}
                           class="accent-blue-600">
                    <span class="text-sm font-medium text-gray-700">Kategori Aktif</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.sla.index') }}" class="flex-1 text-center bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
