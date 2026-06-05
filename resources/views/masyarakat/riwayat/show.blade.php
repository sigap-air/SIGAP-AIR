{{-- PBI-10 Detail Riwayat Pengaduan --}}
<x-masyarakat-form-layout title="Detail Pengaduan" :back-url="route('masyarakat.pengaduan.riwayat')">

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
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6" x-data="{ openBukti: false }">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Foto Bukti Laporan</h3>
                <img src="{{ asset('storage/' . $pengaduan->foto_bukti) }}"
                     alt="Foto Bukti"
                     class="w-full max-h-80 object-cover rounded-xl border border-gray-100 cursor-pointer hover:opacity-90 transition-opacity"
                     @click="openBukti = true">
                <p class="text-xs text-gray-400 mt-2 text-center">Klik untuk memperbesar</p>

                <!-- Lightbox Bukti -->
                <div x-show="openBukti" style="display: none;" 
                     class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm"
                     @click.self="openBukti = false" 
                     @keydown.escape.window="openBukti = false">
                    <button @click="openBukti = false" class="absolute top-4 right-6 text-white text-5xl hover:text-gray-300 transition-colors">&times;</button>
                    <img src="{{ asset('storage/' . $pengaduan->foto_bukti) }}" class="max-w-full max-h-full rounded-xl object-contain">
                </div>
            </div>
            @endif

            @if ($pengaduan->assignment?->instruksi)
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Instruksi Penanganan</h3>
                <x-instruksi-supervisor :assignment="$pengaduan->assignment" compact />
            </div>
            @endif

            {{-- Card: Foto Hasil Penanganan --}}
            @if ($pengaduan->assignment && $pengaduan->assignment->foto_hasil)
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6" x-data="{ openHasil: false }">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Dokumentasi Penanganan</h3>
                <img src="{{ asset('storage/' . $pengaduan->assignment->foto_hasil) }}"
                     alt="Foto Hasil"
                     class="w-full max-h-80 object-cover rounded-xl border border-gray-100 cursor-pointer hover:opacity-90 transition-opacity"
                     @click="openHasil = true">
                     
                <!-- Lightbox Hasil -->
                <div x-show="openHasil" style="display: none;" 
                     class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm"
                     @click.self="openHasil = false" 
                     @keydown.escape.window="openHasil = false">
                    <button @click="openHasil = false" class="absolute top-4 right-6 text-white text-5xl hover:text-gray-300 transition-colors">&times;</button>
                    <img src="{{ asset('storage/' . $pengaduan->assignment->foto_hasil) }}" class="max-w-full max-h-full rounded-xl object-contain">
                </div>

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
                        'menunggu_verifikasi' => [
                            'label' => 'Pengajuan Dikirim', 
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>'
                        ],
                        'disetujui' => [
                            'label' => 'Diverifikasi', 
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                        ],
                        'ditugaskan' => [
                            'label' => 'Petugas Ditugaskan', 
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>'
                        ],
                        'diproses' => [
                            'label' => 'Sedang Diproses', 
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" /></svg>'
                        ],
                        'selesai' => [
                            'label' => 'Selesai', 
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" /></svg>'
                        ],
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
                        <div class="flex gap-4 relative items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 relative z-10
                                {{ $idx <= $currentIdx ? 'bg-[#022448] text-white' : 'bg-gray-100 text-gray-400' }}">
                                {{ $idx < $currentIdx ? '✓' : ($idx == $currentIdx ? '●' : '○') }}
                            </div>
                            <div class="pb-4 flex items-center gap-2">
                                <span class="{{ $idx <= $currentIdx ? 'text-blue-600' : 'text-gray-400' }}">
                                    {!! $step['icon'] !!}
                                </span>
                                <p class="text-sm font-semibold {{ $idx <= $currentIdx ? 'text-gray-900' : 'text-gray-400' }}">
                                    {{ $step['label'] }}
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
                    <span class="text-xl {{ $i <= $pengaduan->rating->rating ? 'text-amber-400' : 'text-gray-200' }}">★</span>
                    @endfor
                    <span class="ml-2 text-sm font-semibold text-gray-700">{{ $pengaduan->rating->rating }}/5</span>
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
