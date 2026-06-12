<x-app-admin-layout>

{{-- Breadcrumb --}}
<div class="mb-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.user.index') }}" class="hover:text-[#022448] transition">Manajemen User</a>
        <span class="material-symbols-outlined text-base text-gray-300">chevron_right</span>
        <span class="text-gray-700 font-medium">{{ isset($user) ? 'Edit User' : 'Tambah User' }}</span>
    </nav>
</div>

{{-- Page Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 font-headline">
        {{ isset($user) ? 'Edit Data User' : 'Tambah User Baru' }}
    </h1>
    <p class="text-sm text-gray-500 mt-1">
        {{ isset($user) ? 'Perbarui informasi akun pengguna di bawah ini.' : 'Isi formulir di bawah ini untuk mendaftarkan pengguna baru ke dalam sistem.' }}
    </p>
</div>

<form method="POST" action="{{ isset($user) ? route('admin.user.update', $user) : route('admin.user.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf
    @if (isset($user)) @method('PUT') @endif

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#022448] text-xl">manage_accounts</span>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-800">Informasi Akun</h2>
                    <p class="text-xs text-gray-400">Data login dan profil pengguna</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user?->name) }}" class="w-full border {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl px-4 py-2.5 focus:ring-[#022448]/20 focus:border-[#022448] transition" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user?->email) }}" class="w-full border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl px-4 py-2.5 focus:ring-[#022448]/20 focus:border-[#022448] transition" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $user?->no_telepon) }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#022448]/20 focus:border-[#022448] transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full border {{ $errors->has('role') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl px-4 py-2.5 focus:ring-[#022448]/20 focus:border-[#022448] transition" required>
                        @foreach (['admin','supervisor','petugas','masyarakat'] as $r)
                        <option value="{{ $r }}" {{ old('role', $user?->role) === $r ? 'selected':'' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password {{ isset($user) ? '(kosongkan jika tidak diubah)':'' }} <span class="text-red-500">{{ !isset($user) ? '*':'' }}</span></label>
                    <input type="password" name="password" class="w-full border {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl px-4 py-2.5 focus:ring-[#022448]/20 focus:border-[#022448] transition" {{ !isset($user) ? 'required':'' }}>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-[#022448]/20 focus:border-[#022448] transition">
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Pengaturan Status</h3>
            <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $user?->is_active ?? true) ? 'checked':'' }}
                       class="w-5 h-5 text-[#022448] rounded border-gray-300 focus:ring-[#022448]">
                <div>
                    <span class="block text-sm font-semibold text-gray-800">Akun Aktif</span>
                    <span class="block text-xs text-gray-500">Bisa login dan akses sistem</span>
                </div>
            </label>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-navy-gradient text-white font-semibold rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                <span class="material-symbols-outlined text-xl">save</span>
                {{ isset($user) ? 'Simpan Perubahan' : 'Tambah User' }}
            </button>
            <a href="{{ route('admin.user.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
                Batal
            </a>
        </div>
    </div>
</form>

</x-app-admin-layout>
