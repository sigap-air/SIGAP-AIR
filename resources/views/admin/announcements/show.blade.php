<x-app-admin-layout>

<div class="mb-6">
    <a href="{{ route('admin.announcements.index') }}"
       class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-[#022448] transition-colors">
        <span class="material-symbols-outlined text-base">arrow_back</span>
        Kembali ke Daftar Pengumuman
    </a>
</div>

@php
    $color = $announcement->typeColor();
    $colorMap = [
        'red'   => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'text-red-500'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'text-amber-500'],
        'blue'  => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => 'text-blue-500'],
    ];
    $c = $colorMap[$color] ?? $colorMap['blue'];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Konten Utama --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Header Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $c['bg'] }} {{ $c['text'] }}">
                            {{ $announcement->typeLabelText() }}
                        </span>
                        @if($announcement->isCurrentlyActive())
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                Kadaluarsa
                            </span>
                        @endif
                    </div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $announcement->title }}</h1>
                    <p class="text-xs text-gray-400 mt-1">
                        Dibuat {{ $announcement->created_at->diffForHumans() }}
                        &bull;
                        Berlaku: {{ $announcement->start_date->format('d M Y') }} s/d {{ $announcement->end_date->format('d M Y') }}
                    </p>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ route('admin.announcements.edit', $announcement->id) }}"
                       class="inline-flex items-center gap-1 px-4 py-2 text-amber-600 border border-amber-200 rounded-xl text-sm font-semibold hover:bg-amber-50 transition-colors">
                        <span class="material-symbols-outlined text-base">edit</span>
                        Edit
                    </a>
                    <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" id="del-form">
                        @csrf @method('DELETE')
                        <button type="button"
                                onclick="if(confirm('Yakin ingin menghapus pengumuman ini?')) document.getElementById('del-form').submit();"
                                class="inline-flex items-center gap-1 px-4 py-2 text-red-600 border border-red-200 rounded-xl text-sm font-semibold hover:bg-red-50 transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-base">delete</span>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Isi Pengumuman --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Isi Pengumuman</h2>
            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $announcement->content }}</div>
        </div>
    </div>

    {{-- Sidebar Info --}}
    <div class="space-y-6">

        {{-- Dampak Masyarakat --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Estimasi Dampak</h2>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-orange-500 text-2xl">groups</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTerdampak) }}</p>
                    <p class="text-xs text-gray-500">Masyarakat Terdampak</p>
                </div>
            </div>
        </div>

        {{-- Zona Terdampak --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Zona Terdampak</h2>
            @if($announcement->zones->isEmpty())
                <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-xl text-sm text-blue-700">
                    <span class="material-symbols-outlined text-base">public</span>
                    Berlaku untuk semua zona
                </div>
            @else
                <div class="space-y-2">
                    @foreach($announcement->zones as $zona)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $zona->nama_zona }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $zona->kode_zona }}</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $zona->pelanggan->count() }} pelanggan</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

</x-app-admin-layout>
