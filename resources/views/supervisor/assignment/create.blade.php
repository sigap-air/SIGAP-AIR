{{-- PBI-06 Form Assignment Petugas + Monitoring Status --}}
<x-app-supervisor-layout>
    <x-slot name="title">Tugaskan Petugas</x-slot>

    <div class="mb-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tugaskan Petugas</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Zona {{ $pengaduan->zona->nama_zona }} — isi <strong>catatan assignment</strong> terlebih dahulu, lalu pilih petugas <strong class="text-emerald-700">Tersedia</strong>.
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

    @php
        $pollUrl = route('supervisor.petugas.status', ['zona_id' => $pengaduan->zona_id]);
    @endphp

    <div
        class="mb-6"
        x-data="petugasMonitor({
            pollUrl: @js($pollUrl),
            initial: @js([
                'summary' => $monitorSummary,
                'petugas' => $petugasRows->values(),
                'selectedId' => old('petugas_id') ? (int) old('petugas_id') : null,
            ]),
        })"
    >
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-4">
            <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="font-bold text-gray-800">Monitor Status Petugas</h2>
                    <p class="mt-0.5 text-xs text-gray-500">
                        Zona {{ $pengaduan->zona->nama_zona }} ·
                        <span class="font-medium text-emerald-600">Live</span>
                        <span x-text="' · ' + lastUpdated" class="text-gray-400"></span>
                    </p>
                </div>
                <a href="{{ route('supervisor.petugas.index', ['zona_id' => $pengaduan->zona_id]) }}"
                   class="text-xs font-semibold text-[#0F4C81] hover:underline">Lihat data petugas</a>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 px-3 py-2 text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700">Tersedia</p>
                    <p class="text-xl font-black text-emerald-700" x-text="summary.tersedia"></p>
                </div>
                <div class="rounded-xl border border-amber-100 bg-amber-50/60 px-3 py-2 text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-700">Sibuk</p>
                    <p class="text-xl font-black text-amber-700" x-text="summary.sibuk"></p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-600">Tidak Aktif</p>
                    <p class="text-xl font-black text-gray-600" x-text="summary.tidak_aktif"></p>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
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
                            <dd class="font-semibold">{{ $pengaduan->zona->nama_zona }}</dd>
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
                    <template x-if="summary.tersedia === 0">
                        <div class="rounded-xl border border-dashed border-amber-200 bg-amber-50 py-10 text-center text-amber-800">
                            <p class="font-semibold">Tidak ada petugas Tersedia di zona ini.</p>
                            <p class="mt-1 text-sm">Tunggu petugas selesai tugas (Sibuk → Tersedia) atau hubungi admin untuk mengaktifkan petugas Tidak Aktif.</p>
                            <a href="{{ route('supervisor.petugas.index', ['zona_id' => $pengaduan->zona_id]) }}"
                               class="mt-4 inline-block text-sm font-semibold text-[#0F4C81] hover:underline">
                                Buka data petugas
                            </a>
                        </div>
                    </template>

                    <template x-if="summary.tersedia > 0">
                        <form method="POST" action="{{ route('supervisor.assignment.store', $pengaduan) }}" data-confirm="Yakin ingin menugaskan petugas ini?">
                            @csrf

                            <div class="mb-5 rounded-xl border border-amber-100 bg-amber-50/40 p-4">
                                <label class="mb-1 flex items-center gap-2 text-sm font-semibold text-gray-800">
                                    <span class="material-symbols-outlined text-lg text-amber-600">edit_note</span>
                                    Catatan Assignment
                                    <span class="text-gray-400 font-normal">(instruksi perbaikan untuk petugas)</span>
                                </label>
                                <p class="mb-2 text-xs text-gray-500">
                                    Informasi tambahan terkait penanganan pengaduan ini akan ditampilkan kepada petugas yang ditugaskan.
                                </p>
                                <textarea name="instruksi" rows="4"
                                    placeholder="Contoh: Periksa tekanan pipa utama, bawa alat ukur, koordinasi dengan ketua RT setempat sebelum mulai perbaikan..."
                                    class="w-full rounded-xl border border-amber-200/80 bg-white px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-amber-400">{{ old('instruksi') }}</textarea>
                                @error('instruksi')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
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

                            <div class="mb-5">
                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Pilih Petugas <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-2">
                                    <template x-for="row in petugas" :key="row.id">
                                        <label
                                            :for="'petugas_' + row.id"
                                            class="flex items-center gap-3 rounded-xl border p-3 transition"
                                            :class="row.dapat_dipilih
                                                ? 'cursor-pointer border-gray-200 hover:bg-gray-50 has-[:checked]:border-[#022448] has-[:checked]:bg-[#022448]/5'
                                                : 'cursor-not-allowed border-gray-100 bg-gray-50 opacity-70'"
                                        >
                                            <input
                                                type="radio"
                                                :id="'petugas_' + row.id"
                                                name="petugas_id"
                                                :value="row.id"
                                                class="accent-blue-600"
                                                :disabled="!row.dapat_dipilih"
                                                :checked="selectedId === row.id"
                                            >
                                            <div class="min-w-0 flex-1">
                                                <p class="font-semibold text-gray-800" x-text="row.nama"></p>
                                                <p class="text-xs text-gray-500">
                                                    <span x-text="'NIP: ' + row.nip"></span>
                                                    <span x-show="row.tugas_aktif > 0" x-text="' · ' + row.tugas_aktif + ' tugas aktif'"></span>
                                                </p>
                                            </div>
                                            <span
                                                class="inline-flex shrink-0 items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold"
                                                :class="row.status_badge"
                                            >
                                                <span class="h-1.5 w-1.5 rounded-full" :class="row.status_dot"></span>
                                                <span x-text="row.status_label"></span>
                                            </span>
                                        </label>
                                    </template>
                                </div>
                                @error('petugas_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">
                                    Petugas <strong>Tidak Aktif</strong> ditampilkan untuk monitoring tetapi tidak dapat dipilih.
                                </p>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit"
                                    class="h-11 flex-1 rounded-xl bg-[#022448] font-semibold text-white transition hover:bg-[#1e3a5f]">
                                    Tugaskan Petugas
                                </button>
                                <a href="{{ route('supervisor.pengaduan.show', $pengaduan) }}"
                                    class="inline-flex h-11 flex-1 items-center justify-center rounded-xl bg-gray-100 text-gray-700 transition hover:bg-gray-200">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @include('components.partials.petugas-monitor-script')

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.confirm = function () { return true; };
        });
    </script>
    @endpush
</x-app-supervisor-layout>
