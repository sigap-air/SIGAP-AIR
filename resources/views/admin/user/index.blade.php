<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 font-headline">Manajemen User & Role</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola akun pengguna sistem SIGAP-AIR.</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 bg-[#022448] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#1e3a5f] transition-colors shadow-sm">
        <span class="material-symbols-outlined text-base">person_add</span>
        Tambah User
    </a>
</div>

{{-- Flash: Password Baru (resetPassword) --}}
@if (session('password_baru'))
<div x-data="{ copied: false }" class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4">
    <div class="flex items-start gap-3">
        <span class="material-symbols-outlined text-amber-600 mt-0.5" style="font-variation-settings:'FILL' 1;">key</span>
        <div class="flex-1">
            <p class="text-sm font-semibold text-amber-800">Password baru telah dibuat</p>
            <p class="text-xs text-amber-700 mt-1">Salin dan sampaikan kepada user. Password ini tidak akan ditampilkan lagi.</p>
            <div class="mt-2 flex items-center gap-2">
                <code class="bg-white border border-amber-300 text-amber-900 font-mono text-sm px-3 py-1.5 rounded-lg select-all">
                    {{ session('password_baru') }}
                </code>
                <button @click="navigator.clipboard.writeText('{{ session('password_baru') }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="flex items-center gap-1 text-xs text-amber-700 hover:text-amber-900 border border-amber-300 bg-white px-2 py-1.5 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-sm" x-text="copied ? 'check' : 'content_copy'">content_copy</span>
                    <span x-text="copied ? 'Tersalin!' : 'Salin'">Salin</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Filter Form --}}
<div class="mb-5 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-end">
        {{-- Search --}}
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Cari User</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">search</span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama, email, atau username..."
                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none">
            </div>
        </div>

        {{-- Filter Role --}}
        <div class="min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Role</label>
            <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none">
                <option value="">Semua Role</option>
                @foreach (['admin', 'supervisor', 'petugas', 'masyarakat'] as $r)
                    <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>

        {{-- Filter Status --}}
        <div class="min-w-[130px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <select name="is_active" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#022448]/30 focus:border-[#022448] outline-none">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="bg-[#022448] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#1e3a5f] transition-colors">
                Filter
            </button>
            @if (request()->anyFilled(['search', 'role', 'is_active']))
                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Table --}}
<div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Pengguna</th>
                    <th class="px-6 py-3 text-left font-semibold">Username</th>
                    <th class="px-6 py-3 text-left font-semibold">Role</th>
                    <th class="px-6 py-3 text-left font-semibold">Zona</th>
                    <th class="px-6 py-3 text-center font-semibold">Status</th>
                    <th class="px-6 py-3 text-left font-semibold">Terdaftar</th>
                    <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                @php
                    $roleConfig = [
                        'admin'      => ['label' => 'Admin',      'class' => 'bg-purple-100 text-purple-700'],
                        'supervisor' => ['label' => 'Supervisor', 'class' => 'bg-indigo-100 text-indigo-700'],
                        'petugas'    => ['label' => 'Petugas',   'class' => 'bg-emerald-100 text-emerald-700'],
                        'masyarakat' => ['label' => 'Masyarakat','class' => 'bg-sky-100 text-sky-700'],
                    ];
                    $rc = $roleConfig[$user->role] ?? ['label' => $user->role, 'class' => 'bg-gray-100 text-gray-600'];
                @endphp
                <tr class="hover:bg-gray-50/70 transition-colors {{ !$user->is_active ? 'opacity-60' : '' }}">
                    {{-- Pengguna: Avatar + Nama + Email --}}
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=022448&color=fff&size=64"
                                 alt="{{ $user->name }}" class="w-9 h-9 rounded-full flex-shrink-0">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                @if ($user->no_telepon)
                                    <p class="text-xs text-gray-400">{{ $user->no_telepon }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Username --}}
                    <td class="px-6 py-3">
                        <code class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded font-mono">{{ $user->username ?? '—' }}</code>
                    </td>

                    {{-- Role Badge --}}
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $rc['class'] }}">
                            {{ $rc['label'] }}
                        </span>
                    </td>

                    {{-- Zona (hanya untuk petugas) --}}
                    <td class="px-6 py-3 text-gray-600 text-xs">
                        @if ($user->role === 'petugas' && $user->petugas?->zona)
                            <span class="inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-gray-400" style="font-size:14px">location_on</span>
                                {{ $user->petugas->zona->nama_zona }}
                            </span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>

                    {{-- Status Badge --}}
                    <td class="px-6 py-3 text-center">
                        @if ($user->is_active)
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs px-2.5 py-0.5 rounded-full font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 text-xs px-2.5 py-0.5 rounded-full font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>Nonaktif
                            </span>
                        @endif
                    </td>

                    {{-- Terdaftar --}}
                    <td class="px-6 py-3 text-xs text-gray-500">
                        {{ $user->created_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            {{-- Edit --}}
                            <a href="{{ route('admin.users.edit', $user) }}"
                               title="Edit User"
                               class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2.5 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-100 transition-colors">
                                <span class="material-symbols-outlined text-sm">edit</span>Edit
                            </a>

                            {{-- Toggle Active --}}
                            @if ($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}"
                                  onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?')">
                                @csrf
                                <button type="submit" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                        class="inline-flex items-center gap-1 {{ $user->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }} px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors">
                                    <span class="material-symbols-outlined text-sm">{{ $user->is_active ? 'toggle_off' : 'toggle_on' }}</span>
                                    {{ $user->is_active ? 'Nonaktif' : 'Aktifkan' }}
                                </button>
                            </form>
                            @endif

                            {{-- Reset Password --}}
                            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}"
                                  onsubmit="return confirm('Reset password {{ $user->name }}? Password baru akan ditampilkan sekali.')">
                                @csrf
                                <button type="submit" title="Reset Password"
                                        class="inline-flex items-center gap-1 bg-orange-50 text-orange-700 px-2.5 py-1.5 rounded-lg text-xs font-semibold hover:bg-orange-100 transition-colors">
                                    <span class="material-symbols-outlined text-sm">lock_reset</span>Reset PW
                                </button>
                            </form>

                            {{-- Delete (Nonaktifkan) --}}
                            @if ($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Nonaktifkan akun {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Nonaktifkan User"
                                        class="inline-flex items-center gap-1 bg-red-50 text-red-700 px-2.5 py-1.5 rounded-lg text-xs font-semibold hover:bg-red-100 transition-colors">
                                    <span class="material-symbols-outlined text-sm">person_remove</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <span class="material-symbols-outlined text-5xl">group_off</span>
                            <p class="text-sm font-medium">Belum ada user yang ditemukan</p>
                            @if (request()->anyFilled(['search', 'role', 'is_active']))
                                <a href="{{ route('admin.users.index') }}" class="text-xs text-[#022448] hover:underline">Hapus filter</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Summary --}}
<p class="mt-3 text-xs text-gray-400 text-right">
    Menampilkan {{ $users->count() }} dari {{ $users->total() }} pengguna
</p>

</x-app-admin-layout>
