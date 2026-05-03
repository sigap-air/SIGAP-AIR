<x-app-supervisor-layout>
    <x-slot name="title">Dashboard Supervisor</x-slot>

    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Supervisor</h1>
            <p class="mt-1 text-sm text-gray-500">Pantau verifikasi tiket, distribusi zona, dan progres penanganan pengaduan.</p>
        </div>

        {{-- KPI Utama --}}
        <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-gray-500">Total Pengaduan</div>
                <div class="mt-2 text-3xl font-black text-[#022448]">{{ $kpi['total_masuk'] ?? 0 }}</div>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-gray-500">Menunggu Verifikasi</div>
                <div class="mt-2 text-3xl font-black text-amber-600">{{ $kpi['menunggu_verifikasi'] ?? 0 }}</div>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-gray-500">Sedang Diproses</div>
                <div class="mt-2 text-3xl font-black text-sky-600">{{ $kpi['diproses'] ?? 0 }}</div>
            </div>
        </div>

        {{-- KPI Tambahan --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <div class="text-xs text-gray-500">Selesai</div>
                <div class="mt-1 text-2xl font-extrabold text-emerald-600">{{ $kpi['selesai'] ?? 0 }}</div>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <div class="text-xs text-gray-500">Overdue</div>
                <div class="mt-1 text-2xl font-extrabold text-rose-600">{{ $kpi['overdue'] ?? 0 }}</div>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <div class="text-xs text-gray-500">Petugas Aktif</div>
                <div class="mt-1 text-2xl font-extrabold text-violet-600">{{ $kpi['total_petugas'] ?? 0 }}</div>
            </div>
        </div>

        {{-- Quick Action + Antrean --}}
        <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
            <a href="{{ route('supervisor.verifikasi.index') }}"
               class="rounded-2xl bg-[#022448] p-5 text-white shadow-sm transition hover:bg-[#1e3a5f]">
                <p class="text-xs uppercase tracking-wide text-blue-200">Aksi Cepat</p>
                <p class="mt-2 text-lg font-bold">Verifikasi Pengaduan</p>
                <p class="mt-1 text-sm text-blue-100">Periksa tiket masuk dan putuskan approve atau tolak.</p>
            </a>

            <a href="{{ route('supervisor.filter.index') }}"
               class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:bg-gray-50">
                <p class="text-xs uppercase tracking-wide text-gray-500">Aksi Cepat</p>
                <p class="mt-2 text-lg font-bold text-gray-800">Filter Pengaduan</p>
                <p class="mt-1 text-sm text-gray-500">Cari data lintas status, zona, dan kategori.</p>
            </a>

            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800">Antrean Verifikasi</h2>
                    <a href="{{ route('supervisor.verifikasi.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
                </div>
                <div class="space-y-2">
                    @forelse ($antrean as $p)
                    <a href="{{ route('supervisor.verifikasi.show', $p) }}"
                       class="flex items-center justify-between rounded-lg border border-transparent p-2.5 transition hover:border-blue-100 hover:bg-blue-50">
                        <div>
                            <p class="text-xs font-semibold text-gray-800">{{ $p->nomor_tiket }}</p>
                            <p class="text-xs text-gray-500">{{ $p->kategori?->nama_kategori ?? '-' }} · {{ $p->zona?->nama_zona ?? '-' }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $p->tanggal_pengajuan->diffForHumans() }}</span>
                    </a>
                    @empty
                    <p class="py-4 text-center text-sm text-gray-400">Tidak ada antrean 🎉</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Chart Section --}}
        <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-bold text-gray-700">Per Kategori</h2>
                <canvas id="chartKategori" height="170"></canvas>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-bold text-gray-700">Per Zona</h2>
                <canvas id="chartZona" height="170"></canvas>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-bold text-gray-700">Ringkasan Prioritas</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between rounded-lg bg-amber-50 px-3 py-2">
                        <span class="text-sm text-amber-800">Perlu diverifikasi</span>
                        <span class="text-sm font-bold text-amber-700">{{ $kpi['menunggu_verifikasi'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-rose-50 px-3 py-2">
                        <span class="text-sm text-rose-800">Overdue</span>
                        <span class="text-sm font-bold text-rose-700">{{ $kpi['overdue'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-sky-50 px-3 py-2">
                        <span class="text-sm text-sky-800">Sedang diproses</span>
                        <span class="text-sm font-bold text-sky-700">{{ $kpi['diproses'] ?? 0 }}</span>
                    </div>
                    <a href="{{ route('supervisor.laporan.index') }}"
                       class="mt-2 inline-flex w-full items-center justify-center rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Lihat Laporan
                    </a>
                </div>
            </div>
        </div>

        {{-- Tren Bulanan --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-bold text-gray-700">Tren Pengaduan 12 Bulan</h2>
            <canvas id="chartTren" height="70"></canvas>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data dari PHP
        const perKategori = @json($perKategori);
        const perZona     = @json($perZona);
        const trenBulanan = @json($trenBulanan);

        const colors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#14b8a6','#f97316','#ec4899'];

        new Chart(document.getElementById('chartKategori'), {
            type: 'bar',
            data: {
                labels: perKategori.map(d => d.label),
                datasets: [{ data: perKategori.map(d => d.count), backgroundColor: colors }],
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });

        new Chart(document.getElementById('chartZona'), {
            type: 'doughnut',
            data: {
                labels: perZona.map(d => d.label),
                datasets: [{ data: perZona.map(d => d.count), backgroundColor: colors }],
            },
            options: { plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
        });

        new Chart(document.getElementById('chartTren'), {
            type: 'line',
            data: {
                labels: trenBulanan.map(d => d.label),
                datasets: [{
                    label: 'Jumlah Pengaduan',
                    data: trenBulanan.map(d => d.count),
                    fill: true,
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    borderColor: '#3b82f6',
                    tension: 0.4,
                }],
            },
            options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    </script>
    @endpush
</x-app-supervisor-layout>
