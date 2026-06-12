<x-app-layout>
    <x-slot name="title">Tambah User Baru</x-slot>

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.users.index') }}" class="hover:text-[#022448] transition-colors">Manajemen User</a>
        <span class="material-symbols-outlined text-base">chevron_right</span>
        <span class="text-gray-900 font-medium">Tambah User Baru</span>
    </div>

    @if ($errors->any())
        <div class="flex items-start gap-3 p-4 mb-5 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
            <span class="material-symbols-outlined text-red-500 flex-shrink-0 mt-0.5">error</span>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="bg-gradient-to-r from-[#022448] to-[#0A3D73] px-6 py-5 flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-xl">person_add</span>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white tracking-wide">Tambah User Baru</h1>
                    <p class="text-xs text-blue-200">Isi data pengguna sistem SIGAP-AIR</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}"
                  x-data="userForm()"
                  class="p-6 space-y-5"
                  enctype="multipart/form-data">
                @csrf

                {{-- Foto Profil --}}
                <div class="flex flex-col items-center mb-2">
                    <div class="relative w-28 h-28 rounded-full bg-gray-50 border-2 border-dashed border-gray-300 overflow-hidden mb-3 flex items-center justify-center">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!photoPreview">
                            <span class="material-symbols-outlined text-4xl text-gray-400">add_a_photo</span>
                        </template>
                    </div>
                    <label for="foto_profil" class="cursor-pointer bg-blue-50 text-[#022448] px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-100 transition-colors shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">upload</span>
                        Pilih Foto Profil
                    </label>
                    <input type="file" id="foto_profil" name="foto_profil" class="hidden"
                           accept="image/png, image/jpeg, image/jpg, image/webp"
                           @change="updatePreview($event)">
                    <p class="text-xs text-gray-400 mt-2">Format: JPG/PNG/WebP, Maks 10MB</p>
                    @error('foto_profil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                           placeholder="Contoh: Budi Santoso"
                           class="w-full border {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition"
                           required>
                    @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email & Username --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               placeholder="contoh@pdam.go.id"
                               class="w-full border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition"
                               required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}"
                               placeholder="tanpa_spasi"
                               class="w-full border {{ $errors->has('username') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition"
                               required>
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Role & No. Telepon --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role" x-model="role" required
                                class="w-full border {{ $errors->has('role') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition bg-white">
                            <option value="">-- Pilih Role --</option>
                            @foreach (['admin', 'supervisor', 'petugas', 'masyarakat'] as $r)
                                <option value="{{ $r }}" {{ old('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                            @endforeach
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="no_telepon" class="block text-sm font-semibold text-gray-700 mb-1.5">No. Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                               placeholder="08xx-xxxx-xxxx"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition">
                        @error('no_telepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- NIP Preview & Zona --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            NIP <span class="text-xs text-emerald-700 ml-1 font-bold bg-emerald-100 px-2 py-0.5 rounded-full">✨ Auto-Generate</span>
                        </label>
                        <input type="text" :value="nipPreview" disabled
                               class="w-full border border-gray-300 bg-gray-100 text-gray-500 rounded-xl px-4 py-2.5 text-sm font-mono font-medium tracking-wide">
                        <p class="text-xs text-gray-400 mt-1.5" x-text="'Format: ' + (role ? nipPreview : 'Pilih role terlebih dahulu')"></p>
                    </div>
                    <div x-show="role === 'petugas'" x-transition style="display: none;">
                        <label for="zona_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Zona Wilayah <span class="text-red-500">*</span>
                            <span class="text-gray-400 font-normal text-xs ml-1">(wajib untuk petugas)</span>
                        </label>
                        <select id="zona_id" name="zona_id"
                                class="w-full border {{ $errors->has('zona_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition bg-white">
                            <option value="">-- Pilih Zona --</option>
                            @foreach ($zonas as $zona)
                                <option value="{{ $zona->id }}" {{ old('zona_id') == $zona->id ? 'selected' : '' }}>
                                    {{ $zona->nama_zona }}{{ $zona->kode_zona ? ' (' . $zona->kode_zona . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('zona_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                                   placeholder="Min. 8 karakter"
                                   class="w-full border {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition"
                                   required>
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <span class="material-symbols-outlined text-base" x-text="showPassword ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showPasswordConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                                   placeholder="Ulangi password"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition"
                                   required>
                            <button type="button" @click="showPasswordConfirm = !showPasswordConfirm"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <span class="material-symbols-outlined text-base" x-text="showPasswordConfirm ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-[#022448] to-[#0A3D73] text-white py-3 rounded-xl font-bold text-sm shadow hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">person_add</span>
                        Simpan & Daftarkan User
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="px-6 text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-bold text-sm hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
    function userForm() {
        return {
            role: '{{ old('role') }}',
            showPassword: false,
            showPasswordConfirm: false,
            photoPreview: null,
            counters: @json($counters),
            year: '{{ $year }}',

            get nipPreview() {
                if (!this.role) return 'Pilih role terlebih dahulu';
                const prefix = { 'admin': 'ADM', 'supervisor': 'SPV', 'petugas': 'PEG', 'masyarakat': 'MSY' }[this.role] || 'ROLE';
                const count  = (this.counters[this.role] || 1).toString().padStart(4, '0');
                return `${prefix}-${this.year}-${count}`;
            },

            updatePreview(event) {
                const file = event.target.files[0];
                this.photoPreview = file ? URL.createObjectURL(file) : null;
            }
        }
    }
    </script>
</x-app-layout>
