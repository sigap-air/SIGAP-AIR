<x-app-layout>
    <x-slot name="title">{{ isset($user) ? 'Edit User' : 'Tambah User' }}</x-slot>

    <div class="mb-4">
        <a href="{{ route('admin.user.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Daftar User</a>
    </div>

    <div class="max-w-lg mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-5">
            {{ isset($user) ? '✏️ Edit User' : '➕ Tambah User Baru' }}
        </h1>

        <form method="POST" action="{{ isset($user) ? route('admin.user.update', $user) : route('admin.user.store') }}">
            @csrf
            @if (isset($user)) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user?->name) }}" class="w-full border rounded-lg px-3 py-2" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user?->email) }}" class="w-full border rounded-lg px-3 py-2" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $user?->no_telepon) }}" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full border rounded-lg px-3 py-2" required>
                        @foreach (['admin','supervisor','petugas','masyarakat'] as $r)
                        <option value="{{ $r }}" {{ old('role', $user?->role) === $r ? 'selected':'' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password {{ isset($user) ? '(kosongkan jika tidak diubah)':'' }} <span class="text-red-500">{{ !isset($user) ? '*':'' }}</span></label>
                    <input type="password" name="password" class="w-full border rounded-lg px-3 py-2" {{ !isset($user) ? 'required':'' }}>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $user?->is_active ?? true) ? 'checked':'' }}
                           class="accent-blue-600">
                    <span class="text-sm font-medium text-gray-700">Akun Aktif</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition">
                    {{ isset($user) ? '💾 Simpan' : '➕ Tambah User' }}
                </button>
                <a href="{{ route('admin.user.index') }}" class="flex-1 text-center bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 transition">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
