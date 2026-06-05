<x-app-admin-layout>

<div
    class="mx-auto w-full max-w-7xl"
    x-data="adminDashboard({
        statsUrl: @js(route('admin.dashboard.stats')),
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
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Dashboard Admin</h1>
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
        <a href="{{ route('admin.pengaduan.index') }}"
           class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:border-blue-100 hover:shadow-md">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Masuk</div>
            <div class="mt-2 text-3xl font-black text-[#022448]" x-text="kpi.total_masuk">{{ $kpi['total_masuk'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.pengaduan.index', ['status' => 'menunggu_verifikasi']) }}"
           class="rounded-2xl border border-amber-100 bg-amber-50/40 p-5 shadow-sm transition hover:shadow-md">
            <div class="text-xs font-semibold uppercase tracking-wide text-amber-700">Tiket Menunggu Verifikasi</div>
            <div class="mt-2 text-3xl font-black text-amber-600" x-text="kpi.menunggu_verifikasi">{{ $kpi['menunggu_verifikasi'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.pengaduan.index', ['status' => 'diproses']) }}"
           class="rounded-2xl border border-sky-100 bg-sky-50/40 p-5 shadow-sm transition hover:shadow-md">
            <div class="text-xs font-semibold uppercase tracking-wide text-sky-700">Tiket Sedang Diproses</div>
            <div class="mt-2 text-3xl font-black text-sky-600" x-text="kpi.diproses">{{ $kpi['diproses'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.pengaduan.index', ['status' => 'selesai']) }}"
           class="rounded-2xl border border-emerald-100 bg-emerald-50/40 p-5 shadow-sm transition hover:shadow-md">
            <div class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Tiket Selesai</div>
            <div class="mt-2 text-3xl font-black text-emerald-600" x-text="kpi.selesai">{{ $kpi['selesai'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.pengaduan.index', ['overdue' => 1]) }}"
           class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm transition hover:shadow-md">
            <div class="text-xs font-semibold uppercase tracking-wide text-rose-700">Overdue</div>
            <div class="mt-2 text-3xl font-black text-rose-600" x-text="kpi.overdue">{{ $kpi['overdue'] ?? 0 }}</div>
        </a>
    </div>

    {{-- Ringkasan operasional admin --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pengaduan Hari Ini</p>
            <p class="mt-1 text-2xl font-extrabold text-[#022448]">{{ $adminStats['pengaduan_hari'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pengaduan Bulan Ini</p>
            <p class="mt-1 text-2xl font-extrabold text-violet-600">{{ $adminStats['pengaduan_bulan'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Pengguna</p>
            <p class="mt-1 text-2xl font-extrabold text-gray-700">{{ $adminStats['total_user'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Grafik statistik --}}
    <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="mb-1 text-base font-bold text-gray-800">Pengaduan per Kategori</h2>
            <p class="mb-4 text-xs text-gray-500">Distribusi volume berdasarkan jenis masalah</p>
            <canvas id="chartKategori" height="200"></canvas>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="mb-1 text-base font-bold text-gray-800">Pengaduan per Zona Wilayah</h2>
            <p class="mb-4 text-xs text-gray-500">Sebaran tiket menurut area layanan</p>
            <canvas id="chartZona" height="200"></canvas>
        </div>
    </div>

    <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <h2 class="mb-1 text-base font-bold text-gray-800">Tren Bulanan</h2>
        <p class="mb-4 text-xs text-gray-500">Volume pengaduan 12 bulan terakhir</p>
        <canvas id="chartTren" height="90"></canvas>
    </div>

    {{-- Pengaduan Terbaru --}}
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <h2 class="text-base font-semibold text-gray-800">Pengaduan Terbaru</h2>
            <a href="{{ route('admin.pengaduan.index') }}" class="text-xs font-semibold text-[#0F4C81] hover:underline">Lihat semua</a>
        </div>

        @if(($pengaduanTerbaru ?? collect())->isEmpty())
            <div class="p-12 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#022448]/5">
                    <span class="material-symbols-outlined text-4xl text-[#022448]/30">inbox</span>
                </div>
                <p class="text-sm text-gray-500">Belum ada pengaduan masuk dari masyarakat.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left">No Tiket</th>
                            <th class="px-6 py-3 text-left">Pelapor</th>
                            <th class="px-6 py-3 text-left">Kategori</th>
                            <th class="px-6 py-3 text-left">Zona</th>
                            <th class="px-6 py-3 text-left">Waktu Masuk</th>
                            <th class="px-6 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pengaduanTerbaru as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-mono text-xs font-semibold text-[#022448]">{{ $p->nomor_tiket }}</td>
                                <td class="px-6 py-3 text-gray-700">{{ $p->pelapor->name ?? '-' }}</td>
                                <td class="px-6 py-3 text-gray-700">{{ $p->kategori->nama_kategori ?? '-' }}</td>
                                <td class="px-6 py-3 text-gray-700">{{ $p->zona->nama_zona ?? '-' }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $p->created_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</td>
                                <td class="px-6 py-3">
                                    <x-badge-status :status="$p->status" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminDashboard', ({ statsUrl, initial }) => ({
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

</x-app-admin-layout>
