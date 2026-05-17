<x-app-admin-layout>

{{-- Breadcrumb --}}
<div class="mb-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.petugas.index') }}" class="hover:text-[#022448] transition">Petugas Teknis</a>
        <span class="material-symbols-outlined text-base text-gray-300">chevron_right</span>
        <span class="text-gray-700 font-medium">Edit: {{ $petugas->user?->name ?? '(tanpa nama)' }}</span>
    </nav>
</div>

{{-- Page Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 font-headline">Edit Data Petugas</h1>
    <p class="text-sm text-gray-500 mt-1">Perbarui informasi petugas teknis yang sudah terdaftar.</p>
</div>

<form method="POST" action="{{ route('admin.petugas.update', $petugas) }}" id="form-edit-petugas"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                    {{-- Preview --}}
                    <div class="w-24 h-24 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if($petugas->user?->foto_profil)
                            <img id="foto-preview"
                                 src="{{ asset('storage/' . $petugas->user->foto_profil) }}"
                                 alt="Foto {{ $petugas->user?->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <img id="foto-preview" src="" alt="" class="w-full h-full object-cover hidden">
                            <span class="material-symbols-outlined text-gray-300 text-4xl" id="foto-placeholder">person</span>
                        @endif
                    </div>

                    <div class="flex-1 space-y-2">
                        <label for="foto_profil"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-[#022448] text-white text-sm font-semibold rounded-xl cursor-pointer hover:bg-[#033466] transition">
                            <span class="material-symbols-outlined text-base">upload</span>
                            {{ $petugas->user?->foto_profil ? 'Ganti Foto' : 'Pilih Foto' }}
                        </label>
                        <input type="file" name="foto_profil" id="foto_profil"
                               accept="image/jpg,image/jpeg,image/png,image/webp"
                               class="hidden" onchange="previewFoto(this)">

                        @if($petugas->user?->foto_profil)
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="hapus_foto" id="hapus_foto" value="1" class="accent-red-500">
                                <label for="hapus_foto" class="text-xs text-red-600 cursor-pointer">Hapus foto saat ini</label>
                            </div>
                        @endif
                        <p class="text-xs text-gray-400">Format: JPG, PNG, atau WebP. Maks. 2 MB.</p>
                        @error('foto_profil')
                            <p class="text-red-500 text-xs flex items-center gap-1">
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
                               value="{{ old('name', $petugas->user?->name) }}"
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
                               value="{{ old('email', $petugas->user?->email) }}"
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
                               value="{{ old('username', $petugas->user?->username) }}"
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
                               value="{{ old('no_telepon', $petugas->user?->no_telepon) }}"
                               placeholder="08xxxxxxxxxx"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition {{ $errors->has('no_telepon') ? 'border-red-400 bg-red-50' : '' }}">
                        @error('no_telepon')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div x-data="{ show: false }">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password Baru
                            <span class="text-xs text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
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
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                                   placeholder="Ulangi password baru"
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
                    {{-- NIP --}}
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-1.5">NIP</label>
                        <input type="text" name="nip" id="nip"
                               value="{{ old('nip', $petugas->nip) }}"
                               placeholder="Contoh: PEG-2024-001"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition {{ $errors->has('nip') ? 'border-red-400 bg-red-50' : '' }}">
                        @error('nip')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status Tersedia --}}
                    <div>
                        <label for="status_tersedia" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Status Ketersediaan <span class="text-red-500">*</span>
                        </label>
                        <select name="status_tersedia" id="status_tersedia"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] transition">
                            <option value="tersedia"    {{ old('status_tersedia', $petugas->status_tersedia) === 'tersedia'    ? 'selected':'' }}>✅ Tersedia</option>
                            <option value="sibuk"       {{ old('status_tersedia', $petugas->status_tersedia) === 'sibuk'       ? 'selected':'' }}>🕐 Sibuk</option>
                            <option value="tidak_aktif" {{ old('status_tersedia', $petugas->status_tersedia) === 'tidak_aktif' ? 'selected':'' }}>❌ Tidak Aktif</option>
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
                                <option value="{{ $zona->id }}"
                                    {{ old('zona_id', $petugas->zona_id) == $zona->id ? 'selected':'' }}>
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

        {{-- Kolom Kanan: Aksi & Info --}}
        <div class="space-y-6">
            {{-- Tombol Aksi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
                <button type="submit" id="btn-update-petugas"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-navy-gradient text-white font-semibold rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                    <span class="material-symbols-outlined text-xl">save</span>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.petugas.show', $petugas) }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-50 text-[#022448] font-semibold rounded-xl hover:bg-blue-100 transition">
                    <span class="material-symbols-outlined text-xl">visibility</span>
                    Lihat Detail
                </a>
                <a href="{{ route('admin.petugas.index') }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
                    <span class="material-symbols-outlined text-xl">arrow_back</span>
                    Kembali
                </a>
            </div>

            {{-- Info Akun Saat Ini --}}
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-gray-500 text-xl">info</span>
                    <h3 class="text-sm font-semibold text-gray-700">Info Saat Ini</h3>
                </div>
                <dl class="space-y-2 text-xs text-gray-600">
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Terdaftar</dt>
                        <dd class="font-medium">{{ $petugas->created_at->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Zona</dt>
                        <dd class="font-medium">{{ $petugas->zona?->nama_zona ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Total Tugas</dt>
                        <dd class="font-medium">{{ $petugas->assignments()->count() }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Danger Zone: Nonaktifkan --}}
            @if($petugas->status_tersedia !== 'tidak_aktif')
                <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-red-500 text-xl">warning</span>
                        <h3 class="text-sm font-semibold text-red-700">Nonaktifkan Petugas</h3>
                    </div>
                    <p class="text-xs text-red-600 mb-3">Petugas tidak dapat dinonaktifkan jika masih memiliki tugas aktif.</p>
                    <form action="{{ route('admin.petugas.destroy', $petugas) }}" method="POST"
                          onsubmit="return confirm('Yakin nonaktifkan petugas ini? Status akan berubah menjadi Tidak Aktif.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="btn-nonaktifkan-dari-edit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition">
                            <span class="material-symbols-outlined text-base">person_off</span>
                            Nonaktifkan
                        </button>
                    </form>
                </div>
            @endif
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
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</x-app-admin-layout>
