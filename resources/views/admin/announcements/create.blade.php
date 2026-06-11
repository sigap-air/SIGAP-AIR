<x-app-admin-layout>

<div class="mb-6">
    <a href="{{ route('admin.announcements.index') }}"
       class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-[#022448] transition-colors">
        <span class="material-symbols-outlined text-base">arrow_back</span>
        Kembali ke Daftar Pengumuman
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Buat Pengumuman Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Informasikan kepada masyarakat mengenai kondisi layanan PDAM</p>
        </div>

        @if($errors->any())
            <div class="p-4 mb-6 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Judul --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Pengumuman <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="Contoh: Gangguan Air di Zona Utara"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] @error('title') border-red-300 @enderror">
            </div>

            {{-- Jenis --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Pengumuman <span class="text-red-500">*</span></label>
                <select name="type" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] @error('type') border-red-300 @enderror">
                    <option value="">— Pilih Jenis —</option>
                    <option value="disruption" {{ old('type') === 'disruption' ? 'selected' : '' }}>Gangguan</option>
                    <option value="maintenance" {{ old('type') === 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
                    <option value="info" {{ old('type') === 'info' ? 'selected' : '' }}>Informasi Umum</option>
                </select>
            </div>

            {{-- Isi Pengumuman --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Isi Pengumuman <span class="text-red-500">*</span></label>
                <textarea name="content" rows="5" required
                          placeholder="Jelaskan detail pengumuman secara lengkap..."
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] @error('content') border-red-300 @enderror">{{ old('content') }}</textarea>
            </div>

            {{-- Periode --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] @error('start_date') border-red-300 @enderror">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Berakhir <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] @error('end_date') border-red-300 @enderror">
                </div>
            </div>

            {{-- Zona Terdampak (multi-select) --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Zona yang Terdampak
                    <span class="text-xs text-gray-400 font-normal ml-1">(Opsional — kosongkan jika berlaku untuk semua zona)</span>
                </label>
                <div class="border border-gray-200 rounded-xl p-3 max-h-48 overflow-y-auto space-y-2">
                    @foreach($zonas as $zona)
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 rounded-lg px-2 py-1.5 transition-colors">
                            <input type="checkbox" name="zone_ids[]" value="{{ $zona->id }}"
                                   {{ in_array($zona->id, old('zone_ids', [])) ? 'checked' : '' }}
                                   class="w-4 h-4 text-[#022448] border-gray-300 rounded focus:ring-[#022448]">
                            <span class="text-sm text-gray-700">{{ $zona->nama_zona }}</span>
                            <span class="text-xs text-gray-400 font-mono">{{ $zona->kode_zona }}</span>
                        </label>
                    @endforeach
                    @if($zonas->isEmpty())
                        <p class="text-sm text-gray-400 px-2">Tidak ada zona aktif yang tersedia.</p>
                    @endif
                </div>
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', '1') === '1' ? 'checked' : '' }}
                       class="w-4 h-4 text-[#022448] border-gray-300 rounded focus:ring-[#022448]">
                <label for="is_active" class="text-sm text-gray-700 cursor-pointer">Aktifkan pengumuman ini</label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-navy-gradient text-white font-semibold rounded-xl hover:-translate-y-0.5 transition-all duration-200 shadow-md shadow-[#022448]/20">
                    Simpan Pengumuman
                </button>
                <a href="{{ route('admin.announcements.index') }}"
                   class="px-6 py-2.5 text-gray-600 border border-gray-200 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

</x-app-admin-layout>
