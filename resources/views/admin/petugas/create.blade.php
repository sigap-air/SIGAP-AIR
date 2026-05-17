<x-app-admin-layout>

{{-- Breadcrumb --}}
<div class="mb-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.petugas.index') }}" class="hover:text-[#022448] transition">Petugas Teknis</a>
        <span class="material-symbols-outlined text-base text-gray-300">chevron_right</span>
        <span class="text-gray-700 font-medium">Tambah Petugas</span>
    </nav>
</div>

{{-- Page Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 font-headline">Tambah Petugas Teknis</h1>
    <p class="text-sm text-gray-500 mt-1">Isi data di bawah untuk mendaftarkan petugas teknis baru ke dalam sistem.</p>
</div>

<form method="POST" action="{{ route('admin.petugas.store') }}"
      id="form-tambah-petugas" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Data Akun & Kepegawaian --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card: Foto Profil --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600 text-xl">photo_camera</span>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-800">Foto Profil</h2>
                        <p class="text-xs text-gray-400">Opsional — JPG, PNG, WebP, maks. 2 MB</p>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    {{-- Preview Avatar --}}
                    <div id="foto-preview-wrapper"
                         class="w-24 h-24 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        <span class="material-symbols-outlined text-gray-300 text-4xl" id="foto-placeholder">person</span>
                        <img id="foto-preview" src="" alt="Preview"
                             class="w-full h-full object-cover hidden">
                    </div>

                    <div class="flex-1">
                        <label for="foto_profil"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-[#022448] text-white text-sm font-semibold rounded-xl cursor-pointer hover:bg-[#033466] transition">
                            <span class="material-symbols-outlined text-base">upload</span>
                            Pilih Foto
                        </label>
                        <input type="file" name="foto_profil" id="foto_profil"
                               accept="image/jpg,image/jpeg,image/png,image/webp"
                               class="hidden"
                               onchange="previewFoto(this)">
                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, atau WebP. Maks. 2 MB.</p>
                        @error('foto_profil')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Card: Informasi Akun --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#022448] text-xl">manage_accounts</span>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-800">Informasi Akun</h2>
                        <p class="text-xs text-gray-400">Data login petugas ke sistem SIGAP-AIR</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Nama Lengkap --}}
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name') }}"
                               placeholder="Contoh: Budi Santoso"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition {{ $errors->has('name') ? 'border-red-400 bg-red-50' : '' }}">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email') }}"
                               placeholder="email@pdam.go.id"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" id="username"
                               value="{{ old('username') }}"
                               placeholder="username_unik"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition {{ $errors->has('username') ? 'border-red-400 bg-red-50' : '' }}">
                        @error('username')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- No. Telepon --}}
                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
                        <input type="tel" name="no_telepon" id="no_telepon"
                               value="{{ old('no_telepon') }}"
                               placeholder="08xxxxxxxxxx"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition {{ $errors->has('no_telepon') ? 'border-red-400 bg-red-50' : '' }}">
                        @error('no_telepon')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div x-data="{ show: false }">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" id="password"
                                   placeholder="Minimal 8 karakter"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}">
                            <button type="button" @click="show = !show" tabindex="-1" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <span class="material-symbols-outlined text-[20px]" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div x-data="{ show: false }">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                                   placeholder="Ulangi password"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition">
                            <button type="button" @click="show = !show" tabindex="-1" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <span class="material-symbols-outlined text-[20px]" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Data Kepegawaian --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="w-9 h-9 bg-violet-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-violet-600 text-xl">badge</span>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-800">Data Kepegawaian</h2>
                        <p class="text-xs text-gray-400">Informasi teknis dan penugasan petugas</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- NIP — AUTO GENERATED, read-only --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            NIP
                            <span class="ml-1 inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">
                                <span class="material-symbols-outlined text-xs">auto_awesome</span>
                                Auto-Generate
                            </span>
                        </label>
                        {{-- Hidden field agar NIP tetap terkirim via form --}}
                        <input type="hidden" name="nip" value="{{ $autoNip }}">
                        <div class="w-full border border-emerald-200 bg-emerald-50/50 rounded-xl px-4 py-2.5 text-sm font-mono text-emerald-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-500 text-base">tag</span>
                            <span>{{ $autoNip }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">NIP dibuat otomatis oleh sistem.</p>
                    </div>

                    {{-- Status Tersedia --}}
                    <div>
                        <label for="status_tersedia" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Status Ketersediaan <span class="text-red-500">*</span>
                        </label>
                        <select name="status_tersedia" id="status_tersedia"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition">
                            <option value="tersedia"    {{ old('status_tersedia','tersedia') === 'tersedia'    ? 'selected':'' }}>✅ Tersedia</option>
                            <option value="sibuk"       {{ old('status_tersedia') === 'sibuk'                  ? 'selected':'' }}>🕐 Sibuk</option>
                            <option value="tidak_aktif" {{ old('status_tersedia') === 'tidak_aktif'            ? 'selected':'' }}>❌ Tidak Aktif</option>
                        </select>
                        @error('status_tersedia')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Zona Wilayah --}}
                    <div class="sm:col-span-2">
                        <label for="zona_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Zona Wilayah
                            <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <select name="zona_id" id="zona_id"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition">
                            <option value="">— Belum ditentukan —</option>
                            @foreach($zonas as $zona)
                                <option value="{{ $zona->id }}" {{ old('zona_id') == $zona->id ? 'selected':'' }}>
                                    {{ $zona->nama_zona }} ({{ $zona->kode_zona }})
                                </option>
                            @endforeach
                        </select>
                        @error('zona_id')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Panduan & Aksi --}}
        <div class="space-y-6">
            {{-- Tombol Aksi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
                <button type="submit" id="btn-simpan-petugas"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-navy-gradient text-white font-semibold rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                    <span class="material-symbols-outlined text-xl">save</span>
                    Simpan Petugas
                </button>
                <a href="{{ route('admin.petugas.index') }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
                    <span class="material-symbols-outlined text-xl">arrow_back</span>
                    Batal
                </a>
            </div>

            {{-- Panduan --}}
            <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-[#022448] text-xl">info</span>
                    <h3 class="text-sm font-semibold text-[#022448]">Panduan Pengisian</h3>
                </div>
                <ul class="space-y-2 text-xs text-gray-600">
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full flex-shrink-0 mt-1.5"></span>
                        <span><strong>NIP dibuat otomatis</strong> oleh sistem — tidak perlu diisi.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#022448] rounded-full flex-shrink-0 mt-1.5"></span>
                        <span>Email dan username harus unik dalam sistem.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#022448] rounded-full flex-shrink-0 mt-1.5"></span>
                        <span>Foto profil opsional, format JPG/PNG/WebP, maks. 2 MB.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#022448] rounded-full flex-shrink-0 mt-1.5"></span>
                        <span>Zona wilayah dapat diatur kemudian setelah petugas terdaftar.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#022448] rounded-full flex-shrink-0 mt-1.5"></span>
                        <span>Password minimal 8 karakter dan harus dikonfirmasi.</span>
                    </li>
                </ul>
            </div>

            {{-- NIP Preview Box --}}
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-center">
                <p class="text-xs text-emerald-600 font-medium mb-1">NIP yang akan diberikan</p>
                <p class="text-xl font-black font-mono text-emerald-800">{{ $autoNip }}</p>
                <p class="text-xs text-emerald-500 mt-1">Format: PEG-TAHUN-URUTAN</p>
            </div>
        </div>

    </div>
</form>

{{-- Script Preview Foto --}}
<script>
function previewFoto(input) {
    const preview = document.getElementById('foto-preview');
    const placeholder = document.getElementById('foto-placeholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</x-app-admin-layout>
