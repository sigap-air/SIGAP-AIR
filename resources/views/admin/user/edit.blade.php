<x-app-admin-layout>

{{-- Breadcrumb --}}
<div class="mb-5 flex items-center gap-2 text-sm text-gray-500">
    <a href="{{ route('admin.users.index') }}" class="hover:text-[#022448] transition-colors">Manajemen User</a>
    <span class="material-symbols-outlined text-base">chevron_right</span>
    <span class="text-gray-900 font-medium">Edit User</span>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="bg-[#022448] px-6 py-5 flex items-center gap-4">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1e3a5f&color=fff&size=80"
                 alt="{{ $user->name }}" class="w-12 h-12 rounded-full ring-2 ring-white/20">
            <div>
                <h1 class="text-lg font-bold text-white font-headline">Edit User</h1>
                <p class="text-xs text-blue-200">{{ $user->email }}</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.users.update', $user) }}" x-data="userEditForm()" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama & Email --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $user->name) }}"
                           placeholder="Nama lengkap"
                           class="w-full border {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition"
                           required>
                    @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition"
                           required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                           placeholder="tanpa spasi"
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
                            class="w-full border {{ $errors->has('role') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition">
                        @foreach (['admin', 'supervisor', 'petugas', 'masyarakat'] as $r)
                            <option value="{{ $r }}" {{ old('role', $user->role) === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="no_telepon" class="block text-sm font-semibold text-gray-700 mb-1.5">No. Telepon</label>
                    <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}"
                           placeholder="08xx-xxxx-xxxx"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition">
                    @error('no_telepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Zona (hanya muncul jika role = petugas) --}}
            <div x-show="role === 'petugas'" x-transition>
                <label for="zona_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Zona Wilayah <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal text-xs ml-1">(wajib untuk petugas)</span>
                </label>
                <select id="zona_id" name="zona_id"
                        class="w-full border {{ $errors->has('zona_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none transition">
                    <option value="">-- Pilih Zona --</option>
                    @foreach ($zonas as $zona)
                        <option value="{{ $zona->id }}"
                            {{ old('zona_id', $user->petugas?->zona_id) == $zona->id ? 'selected' : '' }}>
                            {{ $zona->nama_zona }}
                            @if ($zona->kode_zona) ({{ $zona->kode_zona }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('zona_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Password (opsional saat edit) --}}
            <div class="rounded-xl border border-dashed border-gray-300 p-4 bg-gray-50">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Ubah Password (kosongkan jika tidak ingin diubah)</p>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                                   placeholder="Min. 8 karakter"
                                   class="w-full border {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none bg-white transition">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <span class="material-symbols-outlined text-base" x-text="showPassword ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input :type="showPasswordConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                                   placeholder="Ulangi password baru"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none bg-white transition">
                            <button type="button" @click="showPasswordConfirm = !showPasswordConfirm"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <span class="material-symbols-outlined text-base" x-text="showPasswordConfirm ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Meta --}}
            <div class="rounded-xl bg-gray-50 border border-gray-100 p-4 text-xs text-gray-500 flex flex-wrap gap-4">
                <span><span class="font-semibold text-gray-700">ID:</span> #{{ $user->id }}</span>
                <span><span class="font-semibold text-gray-700">Terdaftar:</span> {{ $user->created_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</span>
                <span><span class="font-semibold text-gray-700">Terakhir diperbarui:</span> {{ $user->updated_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</span>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-1">
                <button type="submit"
                        class="flex-1 bg-[#022448] text-white py-2.5 rounded-xl font-semibold text-sm hover:bg-[#1e3a5f] transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">save</span>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="flex-1 text-center bg-gray-100 text-gray-700 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function userEditForm() {
    return {
        role: '{{ old('role', $user->role) }}',
        showPassword: false,
        showPasswordConfirm: false,
    }
}
</script>

</x-app-admin-layout>
