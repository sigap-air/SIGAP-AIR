<x-app-layout>
    <x-slot name="title">Dashboard Admin</x-slot>

    <h1 class="text-2xl font-bold text-gray-800 mb-6">🛠️ Dashboard Admin</h1>

    {{-- Stats User & Pengaduan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
            <div class="text-3xl font-black text-gray-800">{{ $adminStats['total_user'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Pengguna</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
            <div class="text-3xl font-black text-gray-800">{{ $kpi['total_masuk'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Pengaduan</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-yellow-500">
            <div class="text-3xl font-black text-gray-800">{{ $adminStats['pengaduan_hari'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Pengaduan Hari Ini</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-500">
            <div class="text-3xl font-black text-gray-800">{{ $kpi['overdue'] }}</div>
            <div class="text-sm text-gray-500 mt-1">SLA Overdue</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Distribusi User per Role --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold text-gray-700 mb-4">👥 Distribusi Pengguna per Role</h2>
            <div class="grid grid-cols-2 gap-3">
                @php
                    $roleColors = ['admin'=>'blue','supervisor'=>'indigo','petugas'=>'green','masyarakat'=>'yellow'];
                    $roleLabels = ['admin'=>'Admin','supervisor'=>'Supervisor','petugas'=>'Petugas','masyarakat'=>'Masyarakat'];
                @endphp
                @foreach ($roleColors as $role => $color)
                <div class="bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-lg p-3 text-center">
                    <div class="text-xl font-black text-{{ $color }}-700">{{ $adminStats['per_role'][$role] ?? 0 }}</div>
                    <div class="text-xs text-{{ $color }}-600">{{ $roleLabels[$role] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Chart Per Kategori --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold text-gray-700 mb-4">📊 Pengaduan per Kategori</h2>
            <canvas id="chartKategori" height="180"></canvas>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="font-bold text-gray-700 mb-4">⚡ Akses Cepat</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('admin.user.create') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition text-center">
                <span class="text-2xl mb-1">➕</span>
                <span class="text-xs font-semibold text-blue-700">Tambah User</span>
            </a>
            <a href="{{ route('admin.petugas.create') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition text-center">
                <span class="text-2xl mb-1">👷</span>
                <span class="text-xs font-semibold text-green-700">Tambah Petugas</span>
            </a>
            <a href="{{ route('admin.kategori.create') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition text-center">
                <span class="text-2xl mb-1">🏷️</span>
                <span class="text-xs font-semibold text-yellow-700">Tambah Kategori</span>
            </a>
            <a href="{{ route('admin.zona.create') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition text-center">
                <span class="text-2xl mb-1">🗺️</span>
                <span class="text-xs font-semibold text-purple-700">Tambah Zona</span>
            </a>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const perKategori = @json($perKategori);
        new Chart(document.getElementById('chartKategori'), {
            type: 'bar',
            data: {
                labels: perKategori.map(d => d.label),
                datasets: [{ data: perKategori.map(d => d.count), backgroundColor: '#3b82f6' }],
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    </script>
    @endpush
</x-app-layout>
