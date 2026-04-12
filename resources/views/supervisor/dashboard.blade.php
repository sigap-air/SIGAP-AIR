<x-app-layout>
    <x-slot name="title">Dashboard Supervisor</x-slot>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        @php
            $kpiDef = [
                ['key'=>'total_masuk','label'=>'Total Masuk','color'=>'blue','icon'=>'📥'],
                ['key'=>'menunggu_verifikasi','label'=>'Menunggu','color'=>'yellow','icon'=>'⏳'],
                ['key'=>'diproses','label'=>'Diproses','color'=>'indigo','icon'=>'🔧'],
                ['key'=>'selesai','label'=>'Selesai','color'=>'green','icon'=>'✅'],
                ['key'=>'overdue','label'=>'Overdue','color'=>'red','icon'=>'🚨'],
                ['key'=>'total_petugas','label'=>'Petugas Aktif','color'=>'purple','icon'=>'👷'],
            ];
        @endphp
        @foreach ($kpiDef as $k)
        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-{{ $k['color'] }}-500">
            <div class="text-2xl mb-1">{{ $k['icon'] }}</div>
            <div class="text-2xl font-black text-gray-800">{{ $kpi[$k['key']] ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $k['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Chart Per Kategori --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold text-gray-700 mb-4">📊 Per Kategori</h2>
            <canvas id="chartKategori" height="220"></canvas>
        </div>

        {{-- Chart Per Zona --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold text-gray-700 mb-4">🗺️ Per Zona</h2>
            <canvas id="chartZona" height="220"></canvas>
        </div>

        {{-- Antrean Verifikasi --}}
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-700">⏳ Antrean Verifikasi</h2>
                <a href="{{ route('supervisor.verifikasi.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-2">
                @forelse ($antrean as $p)
                <a href="{{ route('supervisor.verifikasi.show', $p) }}"
                   class="flex items-center justify-between p-2 rounded-lg hover:bg-blue-50 transition">
                    <div>
                        <p class="text-xs font-semibold text-gray-800">{{ $p->nomor_tiket }}</p>
                        <p class="text-xs text-gray-500">{{ $p->kategori->nama_kategori }} · {{ $p->zona->nama_zona }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $p->tanggal_pengajuan->diffForHumans() }}</span>
                </a>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Tidak ada antrean 🎉</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tren Bulanan --}}
    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="font-bold text-gray-700 mb-4">📈 Tren Pengaduan 12 Bulan Terakhir</h2>
        <canvas id="chartTren" height="80"></canvas>
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
</x-app-layout>
