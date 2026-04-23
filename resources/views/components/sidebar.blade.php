@php
    $role = auth()->user()?->role;
    $menus = match($role) {
        'admin' => [
            ['label' => 'Dashboard',  'route' => 'admin.dashboard',     'icon' => '🏠'],
            ['label' => 'Pengaduan',  'route' => 'admin.pengaduan.index', 'icon' => '🔍'],
            ['label' => 'User',       'route' => 'admin.user.index',     'icon' => '👥'],
            ['label' => 'Petugas',    'route' => 'admin.petugas.index',  'icon' => '👷'],
            ['label' => 'Pelanggan',  'route' => 'admin.pelanggan.index','icon' => '🏘️'],
            ['label' => 'Kategori',   'route' => 'admin.kategori.index', 'icon' => '🏷️'],
            ['label' => 'Zona',       'route' => 'admin.zona.index',     'icon' => '🗺️'],
            ['label' => 'Konfigurasi SLA', 'route' => 'admin.sla.index', 'icon' => '⏱️'],
            ['label' => 'Kinerja',    'route' => 'admin.kinerja.index',  'icon' => '📊'],
        ],
        'supervisor' => [
            ['label' => 'Dashboard',  'route' => 'supervisor.dashboard',       'icon' => '🏠'],
            ['label' => 'Verifikasi', 'route' => 'supervisor.verifikasi.index','icon' => '✅'],
            ['label' => 'Filter Pengaduan','route' => 'supervisor.filter.index','icon'=> '🔍'],
            ['label' => 'Laporan',    'route' => 'supervisor.laporan.index',   'icon' => '📄'],
        ],
        'petugas' => [
            ['label' => 'Dashboard',  'route' => 'petugas.dashboard',   'icon' => '🏠'],
            ['label' => 'Tugas Aktif','route' => 'petugas.tugas.index', 'icon' => '🔧'],
            ['label' => 'Profil',     'route' => 'petugas.profil.edit', 'icon' => '👤'],
        ],
        'masyarakat' => [
            ['label' => 'Dashboard',    'route' => 'masyarakat.dashboard',      'icon' => '🏠'],
            ['label' => 'Buat Pengaduan','route' => 'masyarakat.pengaduan.create','icon'=> '📋'],
            ['label' => 'Riwayat',      'route' => 'masyarakat.riwayat.index',  'icon' => '📜'],
            ['label' => 'Notifikasi',   'route' => 'masyarakat.notifikasi.index','icon'=> '🔔'],
        ],
        default => [],
    };
@endphp

<aside class="w-56 bg-gray-900 min-h-screen pt-16 flex-shrink-0 fixed left-0 top-0 bottom-0 z-40">
    <div class="py-4">
        @foreach ($menus as $menu)
            @php
                $isActive = request()->routeIs(rtrim($menu['route'], '.index') . '*');
            @endphp
            <a href="{{ route($menu['route']) }}"
               class="flex items-center gap-3 px-5 py-3 text-sm transition
                       {{ $isActive
                           ? 'bg-blue-700 text-white font-semibold border-r-4 border-blue-400'
                           : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <span class="text-base">{{ $menu['icon'] }}</span>
                <span>{{ $menu['label'] }}</span>
            </a>
        @endforeach
    </div>
</aside>
