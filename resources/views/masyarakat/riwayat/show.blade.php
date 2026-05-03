{{-- PBI-10 Detail Riwayat Pengaduan --}}
<x-masyarakat-form-layout title="Detail Pengaduan" :back-url="route('masyarakat.riwayat.index')">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Detail & Foto (2/3) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card: Informasi Pengaduan --}}
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-bold text-gray-900">{{ $pengaduan->nomor_tiket }}</h2>
                    <x-badge-status :status="$pengaduan->status" />
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    {{-- Kategori --}}
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</dt>
                        <dd class="mt-1.5 font-semibold text-gray-900">{{ $pengaduan->kategori->nama_kategori }}</dd>
                    </div>

                    {{-- Zona --}}
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Zona Wilayah</dt>
                        <dd class="mt-1.5 font-semibold text-gray-900">{{ $pengaduan->zona->nama_zona }}</dd>
                    </div>

                    {{-- Lokasi --}}
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi Detail</dt>
                        <dd class="mt-1.5 font-semibold text-gray-900">{{ $pengaduan->lokasi }}</dd>
                    </div>

                    {{-- Tanggal Pengajuan --}}
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</dt>
                        <dd class="mt-1.5 font-semibold text-gray-900">{{ $pengaduan->tanggal_pengajuan->timezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB</dd>
                    </div>

                    {{-- Nomor Telepon --}}
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Telepon</dt>
                        <dd class="mt-1.5 font-semibold text-gray-900 font-mono">{{ $pengaduan->pelapor?->no_telepon ?? '-' }}</dd>
                    </div>
                </dl>

                {{-- Deskripsi Masalah --}}
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Masalah</p>
                    <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 rounded-xl p-4">{{ $pengaduan->deskripsi }}</p>
                </div>
            </div>

            {{-- Card: Foto Bukti --}}
            @if ($pengaduan->foto_bukti)
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Foto Bukti Laporan</h3>
                <img src="{{ asset('storage/' . $pengaduan->foto_bukti) }}"
                     alt="Foto Bukti"
                     class="w-full max-h-80 object-cover rounded-xl border border-gray-100 cursor-pointer hover:opacity-90 transition-opacity"
                     onclick="this.requestFullscreen()">
                <p class="text-xs text-gray-400 mt-2 text-center">Klik untuk fullscreen</p>
            </div>
            @endif

            {{-- Card: Foto Hasil Penanganan --}}
            @if ($pengaduan->assignment && $pengaduan->assignment->foto_hasil)
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Dokumentasi Penanganan</h3>
                <img src="{{ asset('storage/' . $pengaduan->assignment->foto_hasil) }}"
                     alt="Foto Hasil"
                     class="w-full max-h-80 object-cover rounded-xl border border-gray-100 cursor-pointer hover:opacity-90 transition-opacity"
                     onclick="this.requestFullscreen()">
                @if ($pengaduan->assignment->catatan_penanganan)
                <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs font-semibold text-gray-600 mb-1">Catatan Petugas</p>
                    <p class="text-sm text-gray-700">{{ $pengaduan->assignment->catatan_penanganan }}</p>
                </div>
                @endif
            </div>
            @endif

        </div>

        {{-- Kolom Kanan: Timeline & Info (1/3) --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Card: Timeline Progress --}}
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-5">Riwayat Status</h3>

                @php
                    $statusSteps = [
                        'menunggu_verifikasi' => ['label' => 'Pengajuan Dikirim', 'icon' => '📥'],
                        'disetujui'           => ['label' => 'Diverifikasi', 'icon' => '✅'],
                        'ditugaskan'          => ['label' => 'Petugas Ditugaskan', 'icon' => '👷'],
                        'diproses'            => ['label' => 'Sedang Diproses', 'icon' => '🔧'],
                        'selesai'             => ['label' => 'Selesai', 'icon' => '🎉'],
                    ];
                    $statusArray = array_keys($statusSteps);
                    $currentIdx = array_search($pengaduan->status, $statusArray);
                @endphp

                @if ($pengaduan->status === 'ditolak')
                    <div class="py-6 px-4 bg-red-50 rounded-xl text-center border border-red-100">
                        <div class="text-3xl mb-2">❌</div>
                        <p class="text-red-700 font-semibold text-sm">Pengaduan Ditolak</p>
                        @if ($pengaduan->alasan_penolakan)
                        <p class="text-xs text-red-600 mt-2 leading-relaxed">{{ $pengaduan->alasan_penolakan }}</p>
                        @endif
                    </div>
                @else
                    <div class="space-y-4 relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-100"></div>
                        @foreach ($statusSteps as $key => $step)
                        @php $idx = array_search($key, $statusArray); @endphp
                        <div class="flex gap-4 relative">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 relative z-10
                                {{ $idx <= $currentIdx ? 'bg-[#022448] text-white' : 'bg-gray-100 text-gray-400' }}">
                                {{ $idx < $currentIdx ? '✓' : ($idx == $currentIdx ? '●' : '○') }}
                            </div>
                            <div class="pb-4">
                                <p class="text-sm font-semibold {{ $idx <= $currentIdx ? 'text-gray-900' : 'text-gray-400' }}">
                                    {{ $step['icon'] }} {{ $step['label'] }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Card: SLA Info --}}
            @if ($pengaduan->sla)
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Batas Waktu (SLA)</h3>
                @php
                    $deadlineSla = $pengaduan->sla->deadline?->copy();
                    $nowLocal = now();
                    $sisaDetik = $deadlineSla ? $nowLocal->diffInSeconds($deadlineSla, false) : null;
                    $teksSisaSla = '—';

                    if (!is_null($sisaDetik)) {
                        if ($sisaDetik > 0) {
                            $sisaHari = (int) ceil($sisaDetik / 86400);
                            $teksSisaSla = $sisaHari . ' hari lagi';
                        } elseif ($sisaDetik === 0) {
                            $teksSisaSla = 'hari ini';
                        } else {
                            $lewatHari = (int) ceil(abs($sisaDetik) / 86400);
                            $teksSisaSla = 'terlambat ' . $lewatHari . ' hari';
                        }
                    }
                @endphp
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Deadline:</span>
                        <span class="font-semibold text-gray-900">{{ $pengaduan->sla->deadline->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>

                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        @if (($pengaduan->sla->status_sla ?? null) === 'terpenuhi')
                        <div class="flex items-center gap-2 text-emerald-700 bg-emerald-50 px-3 py-2 rounded-lg text-xs font-semibold">
                            <span>✅</span>
                            <span>SLA Terpenuhi</span>
                        </div>
                        @elseif (($pengaduan->sla->status_sla ?? null) === 'overdue')
                        <div class="flex items-center gap-2 text-red-700 bg-red-50 px-3 py-2 rounded-lg text-xs font-semibold">
                            <span>🚨</span>
                            <span>SLA Terlampaui</span>
                        </div>
                        @else
                        <div class="flex items-center gap-2 text-amber-700 bg-amber-50 px-3 py-2 rounded-lg text-xs font-semibold">
                            <span>⏳</span>
                            <span>{{ $teksSisaSla }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Card: Petugas Penanganan --}}
            @if ($pengaduan->assignment && $pengaduan->assignment->petugas)
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Petugas Penanganan</h3>
                <p class="font-semibold text-gray-900 text-sm">{{ $pengaduan->assignment->petugas->user->name }}</p>
                <p class="text-xs text-gray-500 mt-1">Jadwal: {{ $pengaduan->assignment->jadwal_penanganan?->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
            </div>
            @endif

            {{-- Card: Rating --}}
            @if ($pengaduan->rating)
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Penilaian Anda</h3>
                <div class="flex items-center gap-1 mb-3">
                    @for ($i = 1; $i <= 5; $i++)
                    <span class="text-xl {{ $i <= $pengaduan->rating->bintang ? 'text-amber-400' : 'text-gray-200' }}">★</span>
                    @endfor
                    <span class="ml-2 text-sm font-semibold text-gray-700">{{ $pengaduan->rating->bintang }}/5</span>
                </div>
                @if ($pengaduan->rating->komentar)
                <p class="text-sm text-gray-700 italic">{{ $pengaduan->rating->komentar }}</p>
                @endif
            </div>
            @elseif ($pengaduan->status === 'selesai')
            <div class="rounded-2xl border border-amber-200 bg-amber-50 shadow-sm p-6 text-center">
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-lg">⭐</span>
                </div>
                <h3 class="font-semibold text-amber-900 mb-1">Berikan Penilaian</h3>
                <p class="text-xs text-amber-700 mb-4 leading-relaxed">Pengaduan sudah selesai! Bantu kami dengan memberikan penilaian.</p>
                <a href="{{ route('masyarakat.rating.create', $pengaduan->nomor_tiket) }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600 transition-colors text-sm w-full">
                    <span>⭐</span>
                    Nilai Sekarang
                </a>
            </div>
            @endif

        </div>
    </div>

</x-masyarakat-form-layout>
