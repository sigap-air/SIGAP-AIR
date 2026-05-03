<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.kategori.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Edit Kategori Pengaduan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui data kategori: <strong>{{ $kategori->nama_kategori }}</strong></p>
        </div>
    </div>
</div>

<div class="max-w-2xl">
    {{-- SLA Warning Banner --}}
    <div class="flex items-start gap-3 p-4 mb-5 bg-amber-50 border border-amber-200 rounded-xl">
        <span class="material-symbols-outlined text-amber-500 text-xl flex-shrink-0 mt-0.5">info</span>
        <div>
            <p class="text-sm font-semibold text-amber-800">Perubahan SLA Hanya Berlaku untuk Pengaduan Baru</p>
            <p class="text-xs text-amber-700 mt-0.5">
                Pengaduan yang sudah berjalan <strong>tidak akan terpengaruh</strong> oleh perubahan batas waktu SLA ini.
                SLA yang sedang aktif tetap menggunakan nilai saat pengaduan dibuat.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="bg-navy-gradient px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-xl">edit</span>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white">Edit: {{ $kategori->nama_kategori }}</h2>
                    <p class="text-xs text-blue-200">Kode: <code class="bg-white/10 px-1.5 rounded">{{ $kategori->kode_kategori }}</code></p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Kategori --}}
            <div>
                <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">label</span>
                    <input type="text" id="nama_kategori" name="nama_kategori"
                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                           class="w-full h-12 pl-11 pr-4 bg-gray-50 border @error('nama_kategori') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200">
                </div>
                @error('nama_kategori')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Kode Kategori --}}
            <div>
                <label for="kode_kategori" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Kode Kategori <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">tag</span>
                    <input type="text" id="kode_kategori" name="kode_kategori"
                           value="{{ old('kode_kategori', $kategori->kode_kategori) }}"
                           oninput="this.value = this.value.toUpperCase()"
                           class="w-full h-12 pl-11 pr-4 bg-gray-50 border @error('kode_kategori') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl text-gray-900 font-mono focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200">
                </div>
                <p class="mt-1 text-xs text-gray-500">Hanya huruf kapital, angka, dan tanda hubung (-).</p>
                @error('kode_kategori')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- SLA Jam --}}
            <div>
                <label for="sla_jam" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Batas Waktu SLA <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">timer</span>
                    <input type="number" id="sla_jam" name="sla_jam"
                           value="{{ old('sla_jam', $kategori->sla_jam) }}"
                           min="1" max="720"
                           class="w-full h-12 pl-11 pr-16 bg-gray-50 border @error('sla_jam') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-medium">jam</span>
                </div>
                @error('sla_jam')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          class="w-full px-4 py-3 bg-gray-50 border @error('deskripsi') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 resize-none">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Is Active --}}
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', $kategori->is_active) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div onclick="document.getElementById('is_active').click()"
                         class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#022448] cursor-pointer"></div>
                </div>
                <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                    Kategori aktif
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.kategori.index') }}"
                   class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-navy-gradient text-white text-sm font-semibold rounded-xl shadow-md shadow-[#022448]/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>

</x-app-admin-layout>
