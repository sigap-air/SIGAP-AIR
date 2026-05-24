<x-app-supervisor-layout>
    <x-slot name="title">Dashboard Supervisor</x-slot>

    <div
        class="mx-auto w-full max-w-6xl"
        x-data="supervisorDashboard({
            statsUrl: @js(route('supervisor.dashboard.stats')),
            initial: @js([
                'kpi' => $kpi,
                'per_kategori' => $perKategori,
                'per_zona' => $perZona,
                'tren_bulanan' => $trenBulanan,
            ]),
        })"
    >
        <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Supervisor</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Pantau kondisi operasional pengaduan secara langsung — KPI dan grafik diperbarui otomatis.
                </p>
            </div>
            <div class="flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50/60 px-3 py-2 text-xs">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                </span>
                <span class="font-semibold text-emerald-800">Live</span>
                <span class="text-emerald-700" x-text="'· ' + lastUpdated"></span>
            </div>
        </div>

        {{-- Widget KPI real-time --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <a href="{{ route('supervisor.filter.index') }}"
               class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:border-blue-100 hover:shadow-md">
                <div class="text-xs uppercase tracking-wide text-gray-500">Total Masuk</div>
                <div class="mt-2 text-3xl font-black text-[#022448]" x-text="kpi.total_masuk">{{ $kpi['total_masuk'] ?? 0 }}</div>
            </a>
            <a href="{{ route('supervisor.verifikasi.index') }}"
               class="rounded-2xl border border-amber-100 bg-amber-50/40 p-5 shadow-sm transition hover:shadow-md">
                <div class="text-xs uppercase tracking-wide text-amber-700">Tiket Menunggu Verifikasi</div>
                <div class="mt-2 text-3xl font-black text-amber-600" x-text="kpi.menunggu_verifikasi">{{ $kpi['menunggu_verifikasi'] ?? 0 }}</div>
            </a>
            <a href="{{ route('supervisor.filter.index', ['status' => 'diproses']) }}"
               class="rounded-2xl border border-sky-100 bg-sky-50/40 p-5 shadow-sm transition hover:shadow-md">
                <div class="text-xs uppercase tracking-wide text-sky-700">Tiket Sedang Diproses</div>
                <div class="mt-2 text-3xl font-black text-sky-600" x-text="kpi.diproses">{{ $kpi['diproses'] ?? 0 }}</div>
            </a>
            <a href="{{ route('supervisor.filter.index', ['status' => 'selesai']) }}"
               class="rounded-2xl border border-emerald-100 bg-emerald-50/40 p-5 shadow-sm transition hover:shadow-md">
                <div class="text-xs uppercase tracking-wide text-emerald-700">Tiket Selesai</div>
                <div class="mt-2 text-3xl font-black text-emerald-600" x-text="kpi.selesai">{{ $kpi['selesai'] ?? 0 }}</div>
            </a>
            <a href="{{ route('supervisor.monitor-sla.index') }}"
               class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm transition hover:shadow-md">
                <div class="text-xs uppercase tracking-wide text-rose-700">Overdue</div>
                <div class="mt-2 text-3xl font-black text-rose-600" x-text="kpi.overdue">{{ $kpi['overdue'] ?? 0 }}</div>
            </a>
        </div>

        {{-- Monitor Petugas (realtime) --}}
        <div class="mb-6">
            <x-supervisor-petugas-monitor
                :petugas-list="$petugasMonitorList"
                :summary="$petugasMonitorSummary"
                :compact="true"
            />
        </div>

        {{-- Grafik statistik --}}
        <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="mb-1 font-bold text-gray-800">Pengaduan per Kategori</h2>
                <p class="mb-4 text-xs text-gray-500">Distribusi volume berdasarkan jenis masalah</p>
                <canvas id="chartKategori" height="200"></canvas>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="mb-1 font-bold text-gray-800">Pengaduan per Zona Wilayah</h2>
                <p class="mb-4 text-xs text-gray-500">Sebaran tiket menurut area layanan</p>
                <canvas id="chartZona" height="200"></canvas>
            </div>
        </div>

        <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="mb-1 font-bold text-gray-800">Tren Bulanan</h2>
            <p class="mb-4 text-xs text-gray-500">Volume pengaduan 12 bulan terakhir</p>
            <canvas id="chartTren" height="90"></canvas>
        </div>

        {{-- Quick Action + Antrean --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
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
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('supervisorDashboard', ({ statsUrl, initial }) => ({
                statsUrl,
                kpi: initial.kpi,
                lastUpdated: 'baru saja',
                timer: null,
                charts: { kategori: null, zona: null, tren: null },
                colors: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#14b8a6','#f97316','#ec4899'],

                init() {
                    this.initCharts(initial);
                    this.refresh();
                    this.timer = setInterval(() => this.refresh(), 30000);
                },

                destroy() {
                    if (this.timer) clearInterval(this.timer);
                },

                initCharts(data) {
                    const commonBarOpts = {
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                        responsive: true,
                        maintainAspectRatio: true,
                    };

                    this.charts.kategori = new Chart(document.getElementById('chartKategori'), {
                        type: 'bar',
                        data: {
                            labels: data.per_kategori.map(d => d.label),
                            datasets: [{ data: data.per_kategori.map(d => d.count), backgroundColor: this.colors }],
                        },
                        options: commonBarOpts,
                    });

                    this.charts.zona = new Chart(document.getElementById('chartZona'), {
                        type: 'doughnut',
                        data: {
                            labels: data.per_zona.map(d => d.label),
                            datasets: [{ data: data.per_zona.map(d => d.count), backgroundColor: this.colors }],
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
                        },
                    });

                    this.charts.tren = new Chart(document.getElementById('chartTren'), {
                        type: 'line',
                        data: {
                            labels: data.tren_bulanan.map(d => d.label),
                            datasets: [{
                                label: 'Jumlah Pengaduan',
                                data: data.tren_bulanan.map(d => d.count),
                                fill: true,
                                backgroundColor: 'rgba(59,130,246,0.1)',
                                borderColor: '#3b82f6',
                                tension: 0.4,
                            }],
                        },
                        options: {
                            responsive: true,
                            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                        },
                    });
                },

                updateCharts(data) {
                    const { kategori, zona, tren } = this.charts;
                    if (!kategori || !zona || !tren) return;

                    kategori.data.labels = data.per_kategori.map(d => d.label);
                    kategori.data.datasets[0].data = data.per_kategori.map(d => d.count);
                    kategori.update('none');

                    zona.data.labels = data.per_zona.map(d => d.label);
                    zona.data.datasets[0].data = data.per_zona.map(d => d.count);
                    zona.update('none');

                    tren.data.labels = data.tren_bulanan.map(d => d.label);
                    tren.data.datasets[0].data = data.tren_bulanan.map(d => d.count);
                    tren.update('none');
                },

                async refresh() {
                    try {
                        const response = await fetch(this.statsUrl, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        });
                        if (!response.ok) return;
                        const data = await response.json();
                        this.kpi = data.kpi;
                        this.updateCharts(data);
                        const at = new Date(data.updated_at);
                        this.lastUpdated = at.toLocaleTimeString('id-ID', {
                            hour: '2-digit', minute: '2-digit', second: '2-digit',
                        });
                    } catch (e) {
                        console.error('Dashboard stats:', e);
                    }
                },
            }));
        });
    </script>
    @endpush
</x-app-supervisor-layout>
