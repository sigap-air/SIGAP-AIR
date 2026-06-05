@php
    $profileItems = [
        ['label' => 'Nama Lengkap', 'value' => $user->name],
        ['label' => 'Username', 'value' => $user->username],
        ['label' => 'Email', 'value' => $user->email],
        ['label' => 'Nomor Telepon', 'value' => $user->no_telepon ?: '-'],
        ['label' => 'Role', 'value' => 'Supervisor'],
        ['label' => 'Status Akun', 'value' => $user->is_active ? 'Aktif' : 'Nonaktif'],
    ];
@endphp

<x-app-supervisor-layout>
    <x-slot name="title">Profil Supervisor</x-slot>

    <div class="mx-auto w-full max-w-5xl space-y-6 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profil Supervisor</h1>
            <p class="mt-1 text-sm text-gray-500">Lihat dan perbarui data akun supervisor yang digunakan untuk mengakses panel.</p>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                Profil berhasil diperbarui.
            </div>
        @endif

        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
            <div class="relative h-36 bg-gradient-to-r from-[#022448] via-[#0F4C81] to-[#1e3a5f]">
                <div class="absolute -bottom-12 left-6">
                    @if($user->foto_profil)
                        <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil"
                             class="h-24 w-24 rounded-2xl border-4 border-white object-cover shadow-lg bg-white">
                    @else
                        <div class="flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-[#022448] to-[#0F4C81] shadow-lg">
                            <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="px-6 pb-6 pt-16 md:px-8">
                <div class="flex flex-wrap items-center gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Supervisor</span>
                    <span class="inline-flex items-center rounded-full {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }} px-3 py-1 text-xs font-semibold">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($profileItems as $item)
                        <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $item['label'] }}</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900">Form Edit Profil</h3>
                <p class="mt-1 text-sm text-gray-500">Ubah data identitas akun supervisor dan unggah foto baru bila diperlukan.</p>
            </div>

            <form method="POST" action="{{ route('supervisor.profil.update') }}" enctype="multipart/form-data" id="form-update-profile">
                @csrf
                @method('PATCH')

                <div class="mb-6" x-data="photoUpload()">
                    <label class="mb-3 block text-sm font-semibold text-gray-700">Foto Profil</label>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <div class="shrink-0">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" class="h-20 w-20 rounded-xl border-2 border-gray-200 object-cover" alt="Preview">
                            </template>
                            <template x-if="!previewUrl">
                                @if($user->foto_profil)
                                    <img src="{{ asset('storage/' . $user->foto_profil) }}" class="h-20 w-20 rounded-xl border-2 border-gray-200 object-cover" alt="Foto">
                                @else
                                    <div class="flex h-20 w-20 items-center justify-center rounded-xl border-2 border-gray-200 bg-gradient-to-br from-[#022448] to-[#0F4C81]">
                                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </template>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="foto_profil" class="btn-secondary inline-flex w-fit cursor-pointer items-center gap-2 text-sm">
                                Pilih Foto
                            </label>
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/jpeg,image/png,image/webp" class="hidden" @change="handleFile($event)">
                            @if($user->foto_profil)
                                <label class="inline-flex cursor-pointer items-center gap-2 text-xs text-red-500 hover:text-red-700">
                                    <input type="checkbox" name="hapus_foto" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500"> Hapus foto
                                </label>
                            @endif
                            <p class="text-xs text-gray-400">JPG, PNG, WebP. Maks 2MB.</p>
                        </div>
                    </div>
                    @error('foto_profil')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="mb-5">
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="input-field" placeholder="Masukkan nama lengkap">
                    @error('name')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="mb-5">
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="input-field" placeholder="Masukkan alamat email">
                    @error('email')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="mb-5">
                    <label class="mb-1.5 block text-sm font-semibold text-gray-700">Username</label>
                    <input type="text" value="{{ $user->username }}" disabled class="input-field bg-gray-50 text-gray-500 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-400">Username tidak dapat diubah.</p>
                </div>

                <div class="mb-6">
                    <label for="no_telepon" class="mb-1.5 block text-sm font-semibold text-gray-700">Nomor Telepon</label>
                    <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" class="input-field" placeholder="Contoh: 08123456789">
                    @error('no_telepon')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-wrap items-center gap-4 border-t border-gray-100 pt-4">
                    <button type="submit" class="btn-primary inline-flex items-center gap-2" id="btn-save-profile">
                        Simpan Perubahan
                    </button>
                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="flex items-center gap-1 text-sm font-medium text-emerald-600">
                            Profil berhasil diperbarui!
                        </p>
                    @endif
                </div>
            </form>
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
</x-app-supervisor-layout>