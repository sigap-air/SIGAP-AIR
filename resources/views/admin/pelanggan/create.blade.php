<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.pelanggan.index') }}" class="hover:text-[#022448] transition-colors">Data Pelanggan</a>
        <span class="material-symbols-outlined text-base">chevron_right</span>
        <span class="text-gray-900 font-medium">Tambah Pelanggan</span>
    </nav>
    <h1 class="text-2xl font-bold text-gray-900 font-headline">Tambah Pelanggan Baru</h1>
    <p class="text-sm text-gray-500 mt-1">Isi form di bawah ini untuk menambahkan data pelanggan baru</p>
</div>

{{-- Form Card --}}
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#022448]/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#022448]">person_add</span>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">Informasi Pelanggan</h2>
                    <p class="text-xs text-gray-500">Lengkapi informasi data pelanggan PDAM</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.pelanggan.store') }}" enctype="multipart/form-data" class="p-8 space-y-6" data-confirm="Yakin ingin menyimpan data pelanggan ini?">
            @csrf

            {{-- Nama Pelanggan --}}
            <div>
                <label for="nama_pelanggan" class="block text-sm font-semibold text-gray-700 mb-2">Nama Pelapor / Nama Pelanggan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required placeholder="Masukkan nama lengkap pelanggan" class="w-full h-14 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 placeholder:text-gray-400">
                @error('nama_pelanggan')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- No Tiket --}}
            <div>
                <label for="nomor_sambungan" class="block text-sm font-semibold text-gray-700 mb-2">No Tiket <span class="text-red-500">*</span></label>
                <input type="text" name="nomor_sambungan" id="nomor_sambungan" value="{{ old('nomor_sambungan') }}" required placeholder="Contoh: SIGAP-20260419-0001" class="w-full h-14 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 placeholder:text-gray-400 font-mono">
                @error('nomor_sambungan')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Zona Wilayah --}}
            <div>
                <label for="zona_id" class="block text-sm font-semibold text-gray-700 mb-2">Zona Wilayah <span class="text-red-500">*</span></label>
                <select name="zona_id" id="zona_id" required class="w-full h-14 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200">
                    <option value="">-- Pilih Zona Wilayah --</option>
                    @foreach($zonas as $zona)
                        <option value="{{ $zona->id }}" {{ old('zona_id') == $zona->id ? 'selected' : '' }}>{{ $zona->nama_zona }} ({{ $zona->kode_zona }})</option>
                    @endforeach
                </select>
                @error('zona_id')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Lokasi (alamat) --}}
            <div>
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" required placeholder="Alamat atau patokan lokasi kejadian" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 placeholder:text-gray-400 resize-none">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Nomor Telepon (sebelum deskripsi, sama seperti gform masyarakat) --}}
            <div>
            
                <label for="no_telepon" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                <input type="tel" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 08123456789" inputmode="numeric" pattern="[0-9]*" maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,'')" class="w-full h-14 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 placeholder:text-gray-400">
                
                @error('no_telepon')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Kategori Pengaduan --}}
            <div>
                <label for="kategori_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori Pengaduan <span class="text-red-500">*</span></label>
                <select name="kategori_id" id="kategori_id" required class="w-full h-14 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ (string) old('kategori_id') === (string) $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Deskripsi Masalah --}}
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Masalah <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" id="deskripsi" rows="4" required minlength="20" placeholder="Jelaskan kendala secara detail (minimal 20 karakter)" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 placeholder:text-gray-400 resize-y">{{ old('deskripsi') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Minimal 20 karakter.</p>
                @error('deskripsi')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Bukti Foto --}}
            <div>
                <label for="foto_bukti" class="block text-sm font-semibold text-gray-700 mb-2">Bukti Foto <span class="text-red-500">*</span></label>
                <input type="file" name="foto_bukti" id="foto_bukti" accept="image/jpeg,image/png,image/jpg" required class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-[#022448] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                <p class="mt-1 text-xs text-gray-500">JPG atau PNG, maks. 10MB.</p>
                @error('foto_bukti')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Status Aktif Toggle --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Status Pelanggan</label>
                <label for="is_active" class="relative inline-flex items-center cursor-pointer group">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-12 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#022448]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:shadow-sm after:transition-all peer-checked:bg-[#022448] transition-colors duration-200"></div>
                    <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">
                        Pelanggan Aktif
                    </span>
                </label>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3.5 bg-navy-gradient text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                    <span class="material-symbols-outlined text-xl">save</span>
                    Simpan Data
                </button>
                <a href="{{ route('admin.pelanggan.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors duration-200">
                    <span class="material-symbols-outlined text-xl">close</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

</x-app-admin-layout>
