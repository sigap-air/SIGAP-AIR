<x-app-layout>
    <x-slot name="title">{{ isset($petugas) ? 'Edit Petugas' : 'Tambah Petugas' }}</x-slot>

    <div class="mb-4">
        <a href="{{ route('admin.petugas.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Daftar Petugas</a>
    </div>

    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-5">
            {{ isset($petugas) ? '✏️ Edit Petugas' : '➕ Tambah Petugas Baru' }}
        </h1>

        <form method="POST" enctype="multipart/form-data" id="{{ isset($petugas) ? 'form-edit-petugas' : '' }}"
              action="{{ isset($petugas) ? route('admin.petugas.update', $petugas) : route('admin.petugas.store') }}">
            @csrf
            @if (isset($petugas)) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $petugas?->user->name) }}"
                           class="w-full border rounded-lg px-3 py-2" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $petugas?->user->email) }}"
                           class="w-full border rounded-lg px-3 py-2" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $petugas?->user->username) }}"
                           class="w-full border rounded-lg px-3 py-2" required>
                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $petugas?->user->no_telepon) }}"
                           class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP <span class="text-xs text-gray-400 font-normal">(Auto Generated)</span></label>
                    @if(isset($petugas))
                        <div class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed">{{ $petugas->nip ?? '—' }}</div>
                        <input type="hidden" name="nip" value="{{ $petugas->nip }}">
                    @else
                        <input type="text" name="nip" value="{{ old('nip', $autoNip ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed" readonly tabindex="-1">
                    @endif
                    @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                    <input type="file" name="foto_profil" accept="image/png, image/jpeg, image/jpg, image/webp"
                           class="w-full border rounded-lg px-3 py-2">
                    @error('foto_profil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @if(isset($petugas) && $petugas->user?->foto_profil)
                        <div class="mt-2 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $petugas->user->foto_profil) }}" alt="Foto Petugas" class="w-16 h-16 rounded-xl object-cover">
                            <label class="flex items-center gap-2 text-sm text-gray-600">
                                <input type="checkbox" name="hapus_foto" value="1" class="rounded"> Hapus Foto Saat Ini
                            </label>
                        </div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password {{ isset($petugas) ? '(kosongkan jika tidak diubah)' : '' }} <span class="text-red-500">{{ !isset($petugas) ? '*' : '' }}</span></label>
                    <input type="password" name="password" class="w-full border rounded-lg px-3 py-2"
                           {{ !isset($petugas) ? 'required' : '' }}>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Ketersediaan <span class="text-red-500">*</span></label>
                    <select name="status_ketersediaan" class="w-full border rounded-lg px-3 py-2" required>
                        @foreach (['tersedia'=>'Tersedia','sibuk'=>'Sibuk','tidak_aktif'=>'Tidak Aktif'] as $val => $lab)
                        <option value="{{ $val }}" {{ old('status_ketersediaan', $petugas?->status_ketersediaan) === $val ? 'selected':'' }}>{{ $lab }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Assignment Zona --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Zona Wilayah</label>
                <select name="zona_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">-- Pilih Zona Wilayah --</option>
                    @foreach ($zonas as $zona)
                        <option value="{{ $zona->id }}" {{ old('zona_id', $petugas?->zona_id) == $zona->id ? 'selected' : '' }}>
                            {{ $zona->nama_zona }}
                        </option>
                    @endforeach
                </select>
                @error('zona_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition">
                    {{ isset($petugas) ? '💾 Simpan Perubahan' : '➕ Tambah Petugas' }}
                </button>
                <a href="{{ route('admin.petugas.index') }}" class="flex-1 text-center bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
