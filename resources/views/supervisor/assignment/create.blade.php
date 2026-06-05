{{-- PBI-06 Form Assignment Petugas + Monitoring Status --}}
<x-app-supervisor-layout>
    <x-slot name="title">Tugaskan Petugas</x-slot>

    <div class="mb-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tugaskan Petugas</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Zona {{ $pengaduan->zona->nama_zona }} — hanya petugas <strong class="text-emerald-700">Available</strong> yang dapat dipilih.
                </p>
            </div>
            <a href="{{ route('supervisor.verifikasi.index') }}" class="inline-flex items-center rounded-xl bg-gray-100 px-4 py-2 text-gray-700 transition hover:bg-gray-200">
                Kembali ke antrean
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6">
        <x-supervisor-petugas-monitor
            :petugas-list="$petugasRows"
            :summary="$monitorSummary"
            :zona-id="$pengaduan->zona_id"
            :compact="true"
        />
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-semibold text-gray-800">Info Pengaduan</h2>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-gray-500">No. Tiket</dt>
                        <dd class="font-mono font-bold text-[#022448]">{{ $pengaduan->nomor_tiket }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Kategori</dt>
                        <dd class="font-semibold">{{ $pengaduan->kategori->nama_kategori }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Zona</dt>
                        <dd class="font-semibold flex items-center flex-wrap gap-2">
                            {{ $pengaduan->zona->nama_zona }}
                            @if($pengaduan->is_zona_valid === 1)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs text-emerald-700" title="Validasi otomatis: Sesuai">
                                    <span class="material-symbols-outlined text-[14px]">verified</span> Valid
                                </span>
                            @elseif($pengaduan->is_zona_valid === 0)
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-xs text-amber-700" title="Validasi otomatis: Peringatan (Mungkin tidak sesuai)">
                                    <span class="material-symbols-outlined text-[14px]">warning</span> Warning
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Lokasi</dt>
                        <dd>{{ $pengaduan->lokasi }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">SLA</dt>
                        <dd class="font-semibold text-amber-600">{{ $pengaduan->kategori->sla_jam }} jam</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                @if ($petugasTersedia === 0)
                    <div class="rounded-xl border border-dashed border-amber-200 bg-amber-50 py-10 text-center text-amber-800">
                        <p class="font-semibold">Tidak ada petugas Available di zona ini.</p>
                        <p class="mt-1 text-sm">Tunggu petugas selesai tugas (On-Duty → Available) atau hubungi admin untuk mengaktifkan petugas Off.</p>
                        <a href="{{ route('supervisor.monitor-petugas.index', ['zona_id' => $pengaduan->zona_id]) }}"
                           class="mt-4 inline-block text-sm font-semibold text-[#0F4C81] hover:underline">
                            Buka monitor petugas
                        </a>
                    </div>
                @else
                    <form method="POST" action="{{ route('supervisor.assignment.store', $pengaduan) }}" data-confirm="Yakin ingin menugaskan petugas ini?">
                        @csrf

                        <div class="mb-5">
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Pilih Petugas <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                @foreach ($petugasRows as $row)
                                    @php
                                        $selectable = $row['dapat_dipilih'];
                                        $inputId = 'petugas_' . $row['id'];
                                    @endphp
                                    <label
                                        for="{{ $inputId }}"
                                        class="flex items-center gap-3 rounded-xl border p-3 transition
                                            {{ $selectable ? 'cursor-pointer border-gray-200 hover:bg-gray-50 has-[:checked]:border-[#022448] has-[:checked]:bg-[#022448]/5' : 'cursor-not-allowed border-gray-100 bg-gray-50 opacity-70' }}"
                                    >
                                        <input
                                            type="radio"
                                            id="{{ $inputId }}"
                                            name="petugas_id"
                                            value="{{ $row['id'] }}"
                                            class="accent-blue-600"
                                            @disabled(! $selectable)
                                            @checked(old('petugas_id') == $row['id'])
                                            @required($selectable && $loop->first && ! old('petugas_id'))
                                        >
                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-gray-800">{{ $row['nama'] }}</p>
                                            <p class="text-xs text-gray-500">
                                                NIP: {{ $row['nip'] }}
                                                @if ($row['tugas_aktif'] > 0)
                                                    · {{ $row['tugas_aktif'] }} tugas aktif
                                                @endif
                                            </p>
                                        </div>
                                        <x-petugas-status-badge :status-key="$row['status_key']" />
                                    </label>
                                @endforeach
                            </div>
                            @error('petugas_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                Petugas <strong>Off</strong> dan <strong>On-Duty</strong> ditampilkan untuk monitoring tetapi tidak dapat dipilih.
                            </p>
                        </div>

                        <div class="mb-5">
                            <label class="mb-1 block text-sm font-semibold text-gray-700">
                                Jadwal Penanganan <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="jadwal_penanganan"
                                value="{{ old('jadwal_penanganan') }}"
                                min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}"
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 focus:border-transparent focus:bg-white focus:ring-2 focus:ring-[#1e3a5f]"
                                required>
                            @error('jadwal_penanganan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="mb-1 block text-sm font-semibold text-gray-700">
                                Instruksi Khusus <span class="text-gray-400">(opsional)</span>
                            </label>
                            <textarea name="instruksi" rows="3"
                                placeholder="Contoh: Bawa alat ukur tekanan, koordinasi dengan RT setempat..."
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-transparent focus:bg-white focus:ring-2 focus:ring-[#1e3a5f]">{{ old('instruksi') }}</textarea>
                            @error('instruksi')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                class="h-11 flex-1 rounded-xl bg-[#022448] font-semibold text-white transition hover:bg-[#1e3a5f]">
                                Tugaskan Petugas
                            </button>
                            <a href="{{ route('supervisor.verifikasi.index') }}"
                                class="inline-flex h-11 flex-1 items-center justify-center rounded-xl bg-gray-100 text-gray-700 transition hover:bg-gray-200">
                                Batal
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.confirm = function () { return true; };
        });
    </script>
    @endpush
</x-app-supervisor-layout>
