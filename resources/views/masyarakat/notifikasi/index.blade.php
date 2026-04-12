{{-- PBI-12 Daftar Notifikasi --}}
<x-app-layout>
    <x-slot name="title">Notifikasi</x-slot>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🔔 Notifikasi</h1>
        @if ($notifikasis->where('is_read', false)->count() > 0)
        <form method="POST" action="{{ route('masyarakat.notifikasi.baca-semua') }}">
            @csrf @method('PATCH')
            <button type="submit" class="text-sm text-blue-600 hover:underline">Tandai semua dibaca</button>
        </form>
        @endif
    </div>

    <div class="space-y-3">
        @forelse ($notifikasis as $notif)
        <div class="bg-white rounded-xl shadow p-4 flex items-start gap-4
            {{ !$notif->is_read ? 'border-l-4 border-blue-500' : 'opacity-70' }}">
            <div class="text-2xl mt-1">🔔</div>
            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $notif->judul }}</p>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $notif->pesan }}</p>
                        @if ($notif->pengaduan)
                        <a href="{{ route('masyarakat.riwayat.show', $notif->pengaduan) }}"
                           class="text-xs text-blue-600 hover:underline mt-1 inline-block">Lihat Pengaduan →</a>
                        @endif
                    </div>
                    <div class="text-right ml-4 flex-shrink-0">
                        <p class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</p>
                        @if (!$notif->is_read)
                        <form method="POST" action="{{ route('masyarakat.notifikasi.baca', $notif->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs text-blue-600 hover:underline mt-1">Tandai dibaca</button>
                        </form>
                        @else
                        <span class="text-xs text-gray-300">✓ Dibaca</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow p-16 text-center text-gray-400">
            <div class="text-5xl mb-3">🔕</div>
            <p>Belum ada notifikasi</p>
        </div>
        @endforelse
    </div>

    @if ($notifikasis->hasPages())
    <div class="mt-4">{{ $notifikasis->links() }}</div>
    @endif
</x-app-layout>
