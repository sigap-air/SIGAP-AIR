<nav class="bg-brand text-white h-16 flex items-center justify-between px-6 shadow-lg fixed top-0 left-0 right-0 z-50">
    {{-- Brand --}}
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
            <span class="text-brand font-black text-sm">💧</span>
        </div>
        <span class="font-bold text-lg tracking-tight">SIGAP-AIR</span>
    </div>

    {{-- Right: Notif + User --}}
    <div class="flex items-center gap-4">
        {{-- Bell Notifikasi --}}
        @auth
        <div class="relative">
            <a href="{{ auth()->user()->isMasyarakat() ? route('masyarakat.notifikasi.index') : '#' }}"
               class="relative text-white hover:text-blue-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @php
                    $unreadCount = \App\Models\Notifikasi::where('user_id', auth()->id())->where('is_read', false)->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-bold">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </a>
        </div>

        {{-- User Info + Logout --}}
        <div class="flex items-center gap-2">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold leading-none">{{ auth()->user()->name }}</p>
                <p class="text-xs text-white/80 capitalize">{{ auth()->user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" data-confirm="Yakin ingin logout dari akun ini?">
                @csrf
                <button type="submit"
                    class="text-xs bg-brand-dark hover:opacity-90 px-3 py-1.5 rounded-lg transition">
                    Keluar
                </button>
            </form>
        </div>
        @endauth
    </div>
</nav>