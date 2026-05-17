{{-- PBI-08: Kelola Profil Masyarakat — Khusus role masyarakat --}}
@component('layouts.app-masyarakat')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 font-heading">Profil Saya</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola informasi akun dan keamanan Anda.</p>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="relative h-32 bg-gradient-to-r from-[#0F4C81] via-[#1a5f9e] to-[#2563EB]">
            <div class="absolute -bottom-12 left-6">
                @if($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil"
                         class="w-24 h-24 rounded-2xl border-4 border-white shadow-lg object-cover bg-white">
                @else
                    <div class="w-24 h-24 rounded-2xl border-4 border-white shadow-lg bg-gradient-to-br from-[#0F4C81] to-[#2563EB] flex items-center justify-center">
                        <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="pt-16 pb-6 px-6">
            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
            <div class="flex flex-wrap items-center gap-3 mt-1">
                <span class="inline-flex items-center gap-1.5 text-sm text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $user->email }}
                </span>
                @if($user->no_telepon)
                <span class="inline-flex items-center gap-1.5 text-sm text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $user->no_telepon }}
                </span>
                @endif
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">
                    Masyarakat
                </span>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: '{{ $errors->any() && old('current_password') ? 'password' : 'info' }}' }">
        <div class="flex gap-1 bg-gray-100 rounded-xl p-1 w-fit">
            <button @click="tab = 'info'" :class="tab === 'info' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                    class="px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200" id="tab-info">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Informasi Profil
                </span>
            </button>
            <button @click="tab = 'password'" :class="tab === 'password' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                    class="px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200" id="tab-password">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Ubah Password
                </span>
            </button>
        </div>

        {{-- Tab: Informasi Profil --}}
        <div x-show="tab === 'info'" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Informasi Profil</h3>
                    <p class="text-sm text-gray-500 mt-1">Perbarui data pribadi dan alamat email Anda.</p>
                </div>

                <form method="POST" action="{{ route('masyarakat.profil.update') }}" enctype="multipart/form-data" id="form-update-profile">
                    @csrf
                    @method('PATCH')

                    {{-- Foto Profil --}}
                    <div class="mb-6" x-data="photoUpload()">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Foto Profil</label>
                        <div class="flex items-center gap-5">
                            <div class="relative shrink-0">
                                <template x-if="previewUrl">
                                    <img :src="previewUrl" class="w-20 h-20 rounded-xl object-cover border-2 border-gray-200" alt="Preview">
                                </template>
                                <template x-if="!previewUrl">
                                    @if($user->foto_profil)
                                        <img src="{{ asset('storage/' . $user->foto_profil) }}" class="w-20 h-20 rounded-xl object-cover border-2 border-gray-200" alt="Foto">
                                    @else
                                        <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-[#0F4C81] to-[#2563EB] flex items-center justify-center border-2 border-gray-200">
                                            <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </template>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="foto_profil" class="btn-secondary text-sm cursor-pointer inline-flex items-center gap-2 w-fit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Pilih Foto
                                </label>
                                <input type="file" id="foto_profil" name="foto_profil" accept="image/jpeg,image/png,image/webp" class="hidden" @change="handleFile($event)">
                                @if($user->foto_profil)
                                <label class="text-xs text-red-500 cursor-pointer hover:text-red-700 flex items-center gap-1">
                                    <input type="checkbox" name="hapus_foto" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500"> Hapus foto
                                </label>
                                @endif
                                <p class="text-xs text-gray-400">JPG, PNG, WebP. Maks 2MB.</p>
                            </div>
                        </div>
                        @error('foto_profil')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Nama --}}
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="input-field" placeholder="Masukkan nama lengkap">
                        @error('name')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="input-field" placeholder="Masukkan alamat email">
                        @error('email')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Username (readonly) --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Username</label>
                        <input type="text" value="{{ $user->username }}" disabled class="input-field bg-gray-50 text-gray-500 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-400">Username tidak dapat diubah.</p>
                    </div>

                    {{-- No Telepon --}}
                    <div class="mb-5">
                        <label for="no_telepon" class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" class="input-field" placeholder="Contoh: 08123456789">
                        @error('no_telepon')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Role (readonly) --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Role</label>
                        <input type="text" value="Masyarakat" disabled class="input-field bg-gray-50 text-gray-500 cursor-not-allowed">
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                        <button type="submit" class="btn-primary flex items-center gap-2" id="btn-save-profile">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>
                        @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                           class="text-sm font-medium text-emerald-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Profil berhasil diperbarui!
                        </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Tab: Ubah Password --}}
        <div x-show="tab === 'password'" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Ubah Password</h3>
                    <p class="text-sm text-gray-500 mt-1">Pastikan akun Anda menggunakan password yang kuat dan unik.</p>
                </div>

                <form method="POST" action="{{ route('masyarakat.profil.update-password') }}" id="form-update-password">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password Saat Ini <span class="text-red-500">*</span></label>
                        <input type="password" id="current_password" name="current_password" required class="input-field" placeholder="Masukkan password saat ini" autocomplete="current-password">
                        @error('current_password')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-5">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" required class="input-field" placeholder="Minimal 8 karakter" autocomplete="new-password">
                        @error('password')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="input-field" placeholder="Ulangi password baru" autocomplete="new-password">
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                        <button type="submit" class="btn-primary flex items-center gap-2" id="btn-save-password">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Ubah Password
                        </button>
                        @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                           class="text-sm font-medium text-emerald-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Password berhasil diubah!
                        </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function photoUpload() {
    return {
        previewUrl: null,
        handleFile(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto maksimal 2MB.');
                    event.target.value = '';
                    return;
                }
                this.previewUrl = URL.createObjectURL(file);
            }
        }
    }
}
</script>
@endcomponent
