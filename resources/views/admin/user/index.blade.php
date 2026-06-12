<x-app-admin-layout>
    <x-slot name="title">Manajemen User</x-slot>

    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen User</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data akun pengguna, role, dan hak akses ke sistem.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#022448] to-[#0A3D73] text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
            <span class="material-symbols-outlined text-xl">person_add</span>
            Tambah User
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center gap-2">
            <div class="flex items-center gap-2 text-gray-500">
                <span class="material-symbols-outlined text-sm">groups</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Total User</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center gap-2">
            <div class="flex items-center gap-2 text-purple-600">
                <span class="material-symbols-outlined text-sm">shield_person</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Admin</span>
            </div>
            <p class="text-3xl font-bold text-purple-700">{{ $stats['admin'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center gap-2">
            <div class="flex items-center gap-2 text-blue-600">
                <span class="material-symbols-outlined text-sm">manage_accounts</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Supervisor</span>
            </div>
            <p class="text-3xl font-bold text-blue-700">{{ $stats['supervisor'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center gap-2">
            <div class="flex items-center gap-2 text-sky-600">
                <span class="material-symbols-outlined text-sm">engineering</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Petugas</span>
            </div>
            <p class="text-3xl font-bold text-sky-700">{{ $stats['petugas'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center gap-2">
            <div class="flex items-center gap-2 text-emerald-600">
                <span class="material-symbols-outlined text-sm">person</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Masyarakat</span>
            </div>
            <p class="text-3xl font-bold text-emerald-700">{{ $stats['masyarakat'] }}</p>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="p-4 flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Cari User</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 text-lg">search</span>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] outline-none transition-all"
                           placeholder="Nama, email, atau username...">
                </div>
            </div>
            <div class="lg:w-48">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Role</label>
                <select name="role" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] outline-none transition-all bg-white">
                    <option value="">Semua Role</option>
                    @foreach(['admin', 'supervisor', 'petugas', 'masyarakat'] as $role)
                        <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:w-48">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                <select name="is_active" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] outline-none transition-all bg-white">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-[42px] px-6 bg-[#022448] text-white rounded-xl text-sm font-semibold hover:bg-[#0A3D73] transition-colors shadow-sm">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'role', 'is_active']))
                    <a href="{{ route('admin.users.index') }}" class="h-[42px] px-4 flex items-center text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Main Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Role</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Zona</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Terdaftar</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($users as $index => $user)
                        @php
                            $roleConfig = [
                                'admin'      => ['bg-purple-100 text-purple-700', 'shield_person'],
                                'supervisor' => ['bg-blue-100 text-blue-700', 'manage_accounts'],
                                'petugas'    => ['bg-sky-100 text-sky-700', 'engineering'],
                                'masyarakat' => ['bg-emerald-100 text-emerald-700', 'person'],
                            ];
                            $rc = $roleConfig[$user->role] ?? ['bg-gray-100 text-gray-700', 'person'];
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors duration-150 {{ !$user->is_active ? 'opacity-60' : '' }}">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $users->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($user->foto_profil)
                                        <img src="{{ asset('storage/' . $user->foto_profil) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 flex-shrink-0 bg-white" alt="Foto">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center flex-shrink-0 border border-gray-200">
                                            <span class="material-symbols-outlined text-gray-400 text-xl">person</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md font-mono font-medium border border-gray-200">{{ $user->username ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 {{ $rc[0] }} rounded-full text-xs font-bold capitalize">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">{{ $rc[1] }}</span>
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-xs font-medium">
                                @if ($user->role === 'petugas' && $user->petugas?->zona)
                                    <span class="inline-flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100">
                                        <span class="material-symbols-outlined text-gray-400 text-[14px]">location_on</span>
                                        {{ $user->petugas->zona->nama_zona }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($user->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-100">
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 rounded-full text-xs font-bold border border-red-100">
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">cancel</span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs font-medium">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="w-8 h-8 flex items-center justify-center text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.user.reset-password', $user) }}" class="inline-block">
                                        @csrf
                                        <button type="button"
                                                onclick="if(confirm('Reset password user ini ke \'password\'?')) { this.closest('form').submit(); }"
                                                class="w-8 h-8 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors cursor-pointer"
                                                title="Reset Password">
                                            <span class="material-symbols-outlined text-lg" style="pointer-events:none;">lock_reset</span>
                                        </button>
                                    </form>
                                    @if ($user->id !== auth()->id() && $user->is_active)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="if(confirm('Nonaktifkan user ini? User tidak akan bisa login ke sistem.')) { this.closest('form').submit(); }"
                                                    class="w-8 h-8 flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors cursor-pointer"
                                                    title="Nonaktifkan">
                                                <span class="material-symbols-outlined text-lg" style="pointer-events:none;">person_off</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="w-8 h-8 flex items-center justify-center text-gray-300 bg-gray-50 rounded-lg cursor-not-allowed"
                                              title="{{ $user->id === auth()->id() ? 'Tidak dapat menonaktifkan akun sendiri' : 'Sudah nonaktif' }}">
                                            <span class="material-symbols-outlined text-lg">person_off</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-gray-300 text-3xl">groups</span>
                                    </div>
                                    <p class="text-gray-600 font-bold">Belum ada data user</p>
                                    @if (request()->anyFilled(['search', 'role', 'is_active']))
                                        <p class="text-gray-400 text-sm mt-1">Coba sesuaikan filter pencarian.</p>
                                        <a href="{{ route('admin.users.index') }}" class="mt-3 inline-block px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">Reset Filter</a>
                                    @else
                                        <p class="text-gray-400 text-sm mt-1 mb-4">Klik tombol di bawah untuk mendaftarkan akun baru.</p>
                                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                                            <span class="material-symbols-outlined text-lg">person_add</span>
                                            Tambah User
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Summary --}}
    <p class="mt-3 text-xs font-semibold text-gray-400 text-right">
        Menampilkan {{ $users->count() }} dari {{ $users->total() }} pengguna
    </p>

</x-app-admin-layout>
