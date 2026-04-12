@props([
    'backUrl' => null,
])

@php
    $back = $backUrl ?? route('masyarakat.dashboard');
@endphp

<header class="sticky top-0 z-50 flex h-14 items-center justify-between bg-brand px-3 text-white shadow-md">
    <a href="{{ $back }}" class="flex h-10 w-10 items-center justify-center rounded-lg hover:bg-white/10 transition" aria-label="Kembali">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </a>
    <span class="absolute left-1/2 -translate-x-1/2 font-bold text-sm tracking-wide">SIGAP-AIR</span>
    <div class="flex items-center gap-1">
        @auth
            <a href="{{ auth()->user()->isMasyarakat() ? route('masyarakat.notifikasi.index') : '#' }}"
               class="flex h-10 w-10 items-center justify-center rounded-lg hover:bg-white/10 transition relative"
               aria-label="Notifikasi">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @php
                    $unreadCount = \App\Models\Notifikasi::where('user_id', auth()->id())->where('is_read', false)->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring-brand"></span>
                @endif
            </a>
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-xs font-bold uppercase" title="{{ auth()->user()->name }}" aria-hidden="true">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
        @endauth
    </div>
</header>
