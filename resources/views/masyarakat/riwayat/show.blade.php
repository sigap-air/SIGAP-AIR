{{-- PBI-10 Detail Riwayat + Timeline --}}
<x-app-layout>
    <x-slot name="title">Detail #{{ $pengaduan->nomor_tiket }}</x-slot>

    <div class="mb-4">
        <a href="{{ route('masyarakat.riwayat.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Riwayat</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Info Pengaduan --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-gray-800">{{ $pengaduan->nomor_tiket }}</h1>
                    <x-badge-status :status="$pengaduan->status" />
                </div>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Kategori</dt><dd class="font-semibold mt-0.5">{{ $pengaduan->kategori->nama_kategori }}</dd></div>
                    <div><dt class="text-gray-500">Zona</dt><dd class="font-semibold mt-0.5">{{ $pengaduan->zona->nama_zona }}</dd></div>
                    <div class="col-span-2"><dt class="text-gray-500">Lokasi</dt><dd class="font-semibold mt-0.5">{{ $pengaduan->lokasi }}</dd></div>
                    <div class="col-span-2"><dt class="text-gray-500">Deskripsi</dt><dd class="leading-relaxed text-gray-700 mt-0.5">{{ $pengaduan->deskripsi }}</dd></div>
                    <div><dt class="text-gray-500">Tanggal Pengajuan</dt><dd class="font-semibold mt-0.5">{{ $pengaduan->tanggal_pengajuan->translatedFormat('d F Y, H:i') }}</dd></div>
                    @if ($pengaduan->status === 'ditolak')
                    <div class="col-span-2 bg-red-50 border border-red-200 rounded-lg p-3">
                        <dt class="text-red-600 font-semibold text-xs">Alasan Penolakan</dt>
                        <dd class="text-red-700 text-sm mt-1">{{ $pengaduan->alasan_penolakan }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Foto Bukti --}}
            @if ($pengaduan->foto_bukti)
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="font-bold text-gray-700 mb-3">📸 Foto Bukti</h2>
                <img src="{{ asset('storage/' . $pengaduan->foto_bukti) }}" alt="Foto Bukti" class="w-full max-h-72 object-cover rounded-lg">
            </div>
            @endif

            {{-- Hasil Penanganan (jika ada) --}}
            @if ($pengaduan->assignment && $pengaduan->assignment->foto_hasil)
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="font-bold text-gray-700 mb-3">🛠️ Foto Hasil Penanganan</h2>
                <img src="{{ asset('storage/' . $pengaduan->assignment->foto_hasil) }}" alt="Foto Hasil" class="w-full max-h-72 object-cover rounded-lg">
                @if ($pengaduan->assignment->catatan_penanganan)
                <p class="text-sm text-gray-600 mt-3 bg-gray-50 rounded p-3">{{ $pengaduan->assignment->catatan_penanganan }}</p>
                @endif
            </div>
            @endif
        </div>

        {{-- Timeline & Info --}}
        <div class="lg:col-span-1 space-y-4">
            {{-- Timeline Progress --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="font-bold text-gray-700 mb-4">📍 Progress Status</h2>
                @php
                    $steps = [
                        'menunggu_verifikasi' => ['label' => 'Pengajuan Dikirim', 'icon' => '📥'],
                        'disetujui'           => ['label' => 'Diverifikasi', 'icon' => '✅'],
                        'ditugaskan'          => ['label' => 'Petugas Ditugaskan', 'icon' => '👷'],
                        'diproses'            => ['label' => 'Sedang Diproses', 'icon' => '🔧'],
                        'selesai'             => ['label' => 'Selesai', 'icon' => '🎉'],
                    ];
                    $statusOrder = array_keys($steps);
                    $currentIdx  = array_search($pengaduan->status, $statusOrder);
                @endphp
                @if ($pengaduan->status === 'ditolak')
                <div class="text-center py-4">
                    <div class="text-3xl mb-2">❌</div>
                    <p class="text-red-600 font-semibold">Pengaduan Ditolak</p>
                </div>
                @else
                <div class="relative pl-6">
                    @foreach ($steps as $key => $step)
                    @php $idx = array_search($key, $statusOrder); @endphp
                    <div class="flex items-start gap-3 mb-4 relative">
                        <div class="absolute -left-6 top-0 w-4 h-4 rounded-full border-2 flex items-center justify-center
                            {{ $idx <= $currentIdx ? 'bg-blue-600 border-blue-600' : 'bg-white border-gray-300' }}">
                            @if ($idx < $currentIdx)
                            <svg class="w-2 h-2 text-white" viewBox="0 0 8 8"><path d="M1 4l2 2 4-4" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
                            @endif
                        </div>
                        @if (!$loop->last)
                        <div class="absolute -left-4 top-4 w-0.5 h-6 {{ $idx < $currentIdx ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                        @endif
                        <div>
                            <p class="text-sm font-semibold {{ $idx <= $currentIdx ? 'text-gray-800' : 'text-gray-400' }}">
                                {{ $step['icon'] }} {{ $step['label'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- SLA Info --}}
            @if ($pengaduan->sla)
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="font-bold text-gray-700 mb-3">⏱️ SLA</h2>
                <p class="text-sm text-gray-600">Batas: <strong>{{ $pengaduan->sla->deadline->translatedFormat('d M Y H:i') }}</strong></p>
                @if ($pengaduan->sla->is_fulfilled)
                <p class="text-sm text-green-600 font-semibold mt-1">✅ SLA Terpenuhi</p>
                @elseif ($pengaduan->sla->is_overdue)
                <p class="text-sm text-red-600 font-semibold mt-1">🚨 SLA Terlampaui</p>
                @else
                <p class="text-sm text-orange-600 mt-1">Sisa: {{ $pengaduan->sla->deadline->diffForHumans() }}</p>
                @endif
            </div>
            @endif

            {{-- Info Petugas --}}
            @if ($pengaduan->assignment)
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="font-bold text-gray-700 mb-3">👷 Petugas Penanganan</h2>
                <p class="font-semibold text-gray-800">{{ $pengaduan->assignment->petugas->user->name }}</p>
                <p class="text-xs text-gray-500 mt-1">Jadwal: {{ $pengaduan->assignment->jadwal_penanganan?->translatedFormat('d M Y H:i') }}</p>
            </div>
            @endif

            {{-- Tombol Rating --}}
            @if ($pengaduan->status === 'selesai' && !$pengaduan->rating)
            <a href="{{ route('masyarakat.rating.create', $pengaduan) }}"
               class="block w-full text-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-xl transition">
                ⭐ Beri Penilaian Layanan
            </a>
            @elseif ($pengaduan->rating)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
                <p class="text-yellow-700 font-semibold">Rating Anda</p>
                <p class="text-2xl mt-1">{{ str_repeat('⭐', $pengaduan->rating->bintang) }}</p>
                @if ($pengaduan->rating->komentar)
                <p class="text-xs text-gray-500 mt-2 italic">"{{ $pengaduan->rating->komentar }}"</p>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
