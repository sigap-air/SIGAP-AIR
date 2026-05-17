<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.zona.show', $zona->id) }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Edit Zona Wilayah</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui data zona: <strong>{{ $zona->nama_zona }}</strong></p>
        </div>
    </div>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="bg-navy-gradient px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-xl">edit</span>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white">Edit: {{ $zona->nama_zona }}</h2>
                    <p class="text-xs text-blue-200">Kode: <code class="bg-white/10 px-1.5 rounded">{{ $zona->kode_zona }}</code></p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.zona.update', $zona->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Zona --}}
            <div>
                <label for="nama_zona" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Zona <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">map</span>
                    <input type="text" id="nama_zona" name="nama_zona"
                           value="{{ old('nama_zona', $zona->nama_zona) }}"
                           class="w-full h-12 pl-11 pr-4 bg-gray-50 border @error('nama_zona') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200">
                </div>
                @error('nama_zona')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Kode Zona --}}
            <div>
                <label for="kode_zona" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Kode Zona <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">tag</span>
                    <input type="text" id="kode_zona" name="kode_zona"
                           value="{{ old('kode_zona', $zona->kode_zona) }}"
                           oninput="this.value = this.value.toUpperCase()"
                           class="w-full h-12 pl-11 pr-4 bg-gray-50 border @error('kode_zona') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl text-gray-900 font-mono focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200">
                </div>
                <p class="mt-1 text-xs text-gray-500">Hanya huruf kapital, angka, dan tanda hubung (-).</p>
                @error('kode_zona')
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
                          class="w-full px-4 py-3 bg-gray-50 border @error('deskripsi') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 resize-none">{{ old('deskripsi', $zona->deskripsi) }}</textarea>
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
                           {{ old('is_active', $zona->is_active) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div onclick="document.getElementById('is_active').click()"
                         class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#022448] cursor-pointer"></div>
                </div>
                <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                    Zona aktif
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.zona.show', $zona->id) }}"
                   class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-navy-gradient text-white text-sm font-semibold rounded-xl shadow-md shadow-[#022448]/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Perbarui Zona
                </button>
            </div>
        </form>
    </div>
</div>

</x-app-admin-layout>
