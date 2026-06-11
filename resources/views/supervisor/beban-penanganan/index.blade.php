<x-app-supervisor-layout>
    <x-slot name="title">Monitoring Beban Penanganan Pengaduan</x-slot>

    <div class="mx-auto w-full max-w-6xl">

        {{-- Page Header --}}
        <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Monitoring Beban Penanganan</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Pantau distribusi tugas setiap petugas agar penugasan lebih seimbang dan efektif.
                </p>
            </div>
            <a href="{{ route('supervisor.assignment.index') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-[#022448] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1e3a5f]">
                <span class="material-symbols-outlined text-base">assignment_ind</span>
                Buat Assignment
            </a>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Ringkasan Beban --}}
        <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Petugas</p>
                <p class="mt-2 text-3xl font-black text-[#022448]">{{ $ringkasan['total_petugas'] }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50/40 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-700">Tugas Aktif</p>
                <p class="mt-2 text-3xl font-black text-sky-600">{{ $ringkasan['total_aktif'] }}</p>
            </div>
            <div class="rounded-2xl border border-blue-100 bg-blue-50/40 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Rata-rata Beban</p>
                <p class="mt-2 text-3xl font-black text-blue-600">{{ number_format($ringkasan['rata_beban'], 1) }}</p>
                <p class="mt-0.5 text-[10px] text-blue-500">tugas/petugas</p>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-rose-700">Overload (>3)</p>
                <p class="mt-2 text-3xl font-black text-rose-600">{{ $ringkasan['petugas_overload'] }}</p>
                <p class="mt-0.5 text-[10px] text-rose-500">petugas</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/40 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Tersedia & Idle</p>
                <p class="mt-2 text-3xl font-black text-emerald-600">{{ $ringkasan['petugas_idle'] }}</p>
                <p class="mt-0.5 text-[10px] text-emerald-500">petugas</p>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('supervisor.beban-penanganan.index') }}"
              class="mb-5 flex flex-wrap items-end gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            {{-- Search --}}
            <div class="flex-1 min-w-48">
                <label for="search" class="mb-1 block text-xs font-semibold text-gray-600">Cari Petugas</label>
                <input
                    type="text" id="search" name="search"
                    value="{{ $filters['search'] ?? '' }}"
                    placeholder="Nama atau NIP..."
                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#022448] focus:outline-none focus:ring-2 focus:ring-[#022448]/20"
                >
            </div>
            {{-- Filter Zona --}}
            <div class="min-w-44">
                <label for="zona_id" class="mb-1 block text-xs font-semibold text-gray-600">Zona Wilayah</label>
                <select id="zona_id" name="zona_id"
                        class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#022448] focus:outline-none focus:ring-2 focus:ring-[#022448]/20">
                    <option value="">Semua Zona</option>
                    @foreach ($zonas as $zona)
                        <option value="{{ $zona->id }}" {{ ($filters['zona_id'] ?? '') == $zona->id ? 'selected' : '' }}>
                            {{ $zona->nama_zona }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Filter Status --}}
            <div class="min-w-44">
                <label for="status" class="mb-1 block text-xs font-semibold text-gray-600">Status Petugas</label>
                <select id="status" name="status"
                        class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#022448] focus:outline-none focus:ring-2 focus:ring-[#022448]/20">
                    <option value="">Semua Status</option>
                    <option value="tersedia"    {{ ($filters['status'] ?? '') === 'tersedia'    ? 'selected' : '' }}>Tersedia</option>
                    <option value="sibuk"       {{ ($filters['status'] ?? '') === 'sibuk'       ? 'selected' : '' }}>Sibuk</option>
                    <option value="tidak_aktif" {{ ($filters['status'] ?? '') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-[#022448] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1e3a5f]">
                    <span class="material-symbols-outlined text-base">search</span>
                    Filter
                </button>
                @if (array_filter($filters))
                    <a href="{{ route('supervisor.beban-penanganan.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        <span class="material-symbols-outlined text-base">close</span>
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Tabel Beban Petugas --}}
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50/80">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Petugas</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Zona</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold uppercase tracking-wide text-gray-500">Status</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold uppercase tracking-wide text-gray-500">Tugas Aktif</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold uppercase tracking-wide text-gray-500">Selesai</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold uppercase tracking-wide text-gray-500">Total Ditangani</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold uppercase tracking-wide text-gray-500">Beban</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold uppercase tracking-wide text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($petugas as $p)
                            @php
                                $statusMeta = match($p->status_tersedia) {
                                    'tersedia'    => ['label' => 'Tersedia',    'badge' => 'bg-emerald-50 text-emerald-700', 'dot' => 'bg-emerald-500'],
                                    'sibuk'       => ['label' => 'Sibuk',       'badge' => 'bg-amber-50 text-amber-700',    'dot' => 'bg-amber-500'],
                                    'tidak_aktif' => ['label' => 'Tidak Aktif', 'badge' => 'bg-gray-100 text-gray-600',     'dot' => 'bg-gray-400'],
                                    default       => ['label' => 'Tidak Aktif', 'badge' => 'bg-gray-100 text-gray-600',     'dot' => 'bg-gray-400'],
                                };
                                $bebanMeta = \App\Services\BebanPenangananService::bebanMeta((int) $p->total_aktif);

                                // Bar width: maks 100%, 1 tugas = 20%
                                $barWidth = min(100, (int) $p->total_aktif * 20);
                                $barColor = match(true) {
                                    (int) $p->total_aktif === 0  => 'bg-gray-200',
                                    (int) $p->total_aktif <= 2   => 'bg-emerald-400',
                                    (int) $p->total_aktif <= 4   => 'bg-amber-400',
                                    default                      => 'bg-rose-500',
                                };
                            @endphp
                            <tr class="group transition hover:bg-blue-50/30" id="row-petugas-{{ $p->id }}">
                                {{-- Petugas --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($p->user?->name ?? '-') }}&background=022448&color=fff&size=40"
                                             alt="{{ $p->user?->name }}"
                                             class="h-9 w-9 rounded-full ring-2 ring-white shadow-sm">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $p->user?->name ?? '—' }}</p>
                                            <p class="text-xs text-gray-400">NIP: {{ $p->nip ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                {{-- Zona --}}
                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $p->zona?->nama_zona ?? '<span class="italic text-gray-400">Tanpa zona</span>' }}
                                </td>
                                {{-- Status --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusMeta['badge'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusMeta['dot'] }}"></span>
                                        {{ $statusMeta['label'] }}
                                    </span>
                                </td>
                                {{-- Tugas Aktif --}}
                                <td class="px-5 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-lg font-black
                                            {{ $p->total_aktif > 4 ? 'text-rose-600' : ($p->total_aktif > 2 ? 'text-amber-600' : 'text-gray-800') }}">
                                            {{ $p->total_aktif }}
                                        </span>
                                        {{-- Progress bar --}}
                                        <div class="h-1.5 w-20 overflow-hidden rounded-full bg-gray-100">
                                            <div class="h-full rounded-full transition-all {{ $barColor }}"
                                                 style="width: {{ $barWidth }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                {{-- Selesai --}}
                                <td class="px-5 py-4 text-center font-semibold text-emerald-700">
                                    {{ $p->total_selesai }}
                                </td>
                                {{-- Total Ditangani --}}
                                <td class="px-5 py-4 text-center font-bold text-gray-700">
                                    {{ $p->total_ditangani }}
                                </td>
                                {{-- Badge Beban --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold {{ $bebanMeta['badge'] }}">
                                        {{ $bebanMeta['label'] }}
                                    </span>
                                </td>
                                {{-- Aksi --}}
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('supervisor.petugas.show', $p) }}"
                                       class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-[#022448] ring-1 ring-[#022448]/20 transition hover:bg-[#022448] hover:text-white">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center text-sm text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="material-symbols-outlined text-4xl text-gray-300">manage_accounts</span>
                                        <p>Tidak ada data petugas ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Legend Beban --}}
            @if ($petugas->isNotEmpty())
                <div class="border-t border-gray-100 bg-gray-50/60 px-5 py-3">
                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                        <span class="font-semibold">Kategori Beban:</span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-gray-300"></span> Kosong (0)
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-400"></span> Ringan (1–2)
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-amber-400"></span> Sedang (3–4)
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-rose-500"></span> Berat (>4)
                        </span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Rekomendasi Distribusi --}}
        @if ($ringkasan['petugas_overload'] > 0 && $ringkasan['petugas_idle'] > 0)
            <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-xl text-amber-600" style="font-variation-settings: 'FILL' 1;">warning</span>
                    <div>
                        <p class="font-semibold text-amber-900">Distribusi Tidak Seimbang</p>
                        <p class="mt-1 text-sm text-amber-800">
                            Terdapat <strong>{{ $ringkasan['petugas_overload'] }} petugas</strong> dengan beban berat (&gt;3 tugas aktif)
                            dan <strong>{{ $ringkasan['petugas_idle'] }} petugas</strong> yang tersedia tanpa tugas aktif.
                            Pertimbangkan redistribusi penugasan melalui halaman
                            <a href="{{ route('supervisor.assignment.index') }}" class="font-bold underline">Assignment</a>.
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($ringkasan['petugas_overload'] > 0)
            <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-5">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-xl text-rose-600" style="font-variation-settings: 'FILL' 1;">error</span>
                    <div>
                        <p class="font-semibold text-rose-900">Petugas dengan Beban Berat</p>
                        <p class="mt-1 text-sm text-rose-800">
                            <strong>{{ $ringkasan['petugas_overload'] }} petugas</strong> memiliki lebih dari 3 tugas aktif.
                            Segera koordinasikan penugasan untuk menjaga kualitas penanganan.
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($ringkasan['total_petugas'] > 0)
            <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-xl text-emerald-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <div>
                        <p class="font-semibold text-emerald-900">Distribusi Seimbang</p>
                        <p class="mt-1 text-sm text-emerald-800">
                            Tidak ada petugas dengan beban berlebih. Distribusi penugasan saat ini sudah seimbang.
                        </p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-app-supervisor-layout>
