<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP-AIR - Panel Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        headline: ['Manrope'],
                        body: ['Inter'],
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .bg-navy-gradient {
            background: linear-gradient(135deg, #022448 0%, #1e3a5f 100%);
        }
    </style>
</head>
<body class="bg-gray-50 font-body text-gray-900 antialiased">
    <div x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        showNotifications: false,
        notifications: [],
        unreadCount: 0,
        showProfileDropdown: false,
        init() {
            this.fetchNotifications();
            setInterval(() => this.fetchNotifications(), 30000);
        },
        async fetchNotifications() {
            try {
                const response = await fetch('/api/notifikasi/count');
                const data = await response.json();
                this.unreadCount = data.unread_count || 0;
            } catch (error) {
                console.error('Failed to fetch notifications:', error);
            }
        },
        isactive(route) {
            return window.location.pathname.includes(route);
        }
    }" class="h-screen flex flex-col overflow-hidden">

        <!-- TOPBAR -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left: Hamburger & Logo -->
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="hidden sm:flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#022448] text-2xl" style="font-variation-settings: 'FILL' 1;">water_drop</span>
                            <div>
                                <h1 class="text-lg font-bold text-[#022448] font-headline">SIGAP-AIR</h1>
                                <p class="text-xs text-gray-500">Panel Admin</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Notifikasi & Profile -->
                    <div class="flex items-center gap-4">
                        <!-- Notification Bell -->
                        <div class="relative">
                            <button @click="showNotifications = !showNotifications" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span x-show="unreadCount > 0" class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                    <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                                </span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="showNotifications" @click.outside="showNotifications = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors">
                                        <p class="text-sm font-medium text-gray-900">Pengaduan menunggu verifikasi</p>
                                        <p class="text-xs text-gray-500 mt-1">5 pengaduan baru dari masyarakat</p>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-200 text-center">
                                    <a href="#" class="text-sm text-[#022448] hover:text-[#1e3a5f] font-medium">Lihat Semua Notifikasi</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button @click="showProfileDropdown = !showProfileDropdown" class="flex items-center gap-3 p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=022448&color=fff" alt="Avatar" class="w-8 h-8 rounded-full">
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Admin</p>
                                </div>
                            </button>

                            <div x-show="showProfileDropdown" @click.outside="showProfileDropdown = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-100">Edit Profil</a>
                                <form method="POST" action="{{ route('logout') }}" class="block" data-confirm="Yakin ingin logout dari akun ini?">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- MAIN CONTAINER -->
        <div class="flex flex-1 overflow-hidden min-h-0">
            <!-- SIDEBAR -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="w-64 bg-navy-gradient text-white transition-transform duration-300 lg:translate-x-0 fixed lg:relative inset-y-0 left-0 z-30 overflow-y-auto flex flex-col shadow-xl flex-shrink-0">

                <!-- Sidebar Header -->
                <div class="p-6 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <span class="material-symbols-outlined text-white text-xl" style="font-variation-settings: 'FILL' 1;">water_drop</span>
                        </div>
                        <div>
                            <h2 class="font-bold text-white font-headline">SIGAP-AIR</h2>
                            <p class="text-xs text-blue-200 uppercase tracking-wide">Panel Admin</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-1">
                    <p class="text-xs text-blue-300/60 uppercase tracking-wider font-semibold px-4 mb-3">Menu Utama</p>

                    <a href="{{ route('admin.dashboard') }}" :class="isactive('/admin/dashboard') ? 'bg-white/15 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">dashboard</span>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.pengaduan.index') }}" :class="isactive('/admin/pengaduan') ? 'bg-white/15 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">search</span>
                        <span>Filter Pengaduan</span>
                    </a>

                    <a href="{{ route('admin.pelanggan.index') }}" :class="isactive('/admin/pelanggan') ? 'bg-white/15 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">group</span>
                        <span>Data Pelanggan</span>
                    </a>

                    <a href="{{ route('admin.kategori.index') }}" :class="isactive('/admin/kategori') ? 'bg-white/15 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">category</span>
                        <span>Kategori & SLA</span>
                    </a>

                    <a href="#" :class="isactive('/admin/zona') ? 'bg-white/15 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">location_on</span>
                        <span>Zona Wilayah</span>
                    </a>

                    <p class="text-xs text-blue-300/60 uppercase tracking-wider font-semibold px-4 mt-6 mb-3">Pengaturan</p>

                    <a href="#" :class="isactive('/admin/users') ? 'bg-white/15 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">manage_accounts</span>
                        <span>Manajemen User</span>
                    </a>

                    <a href="#" :class="isactive('/admin/petugas') ? 'bg-white/15 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">engineering</span>
                        <span>Data Petugas</span>
                    </a>

                    <a href="#" :class="isactive('/admin/sla') ? 'bg-white/15 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">settings</span>
                        <span>Konfigurasi SLA</span>
                    </a>
                </nav>

                <!-- Sidebar Footer: User Info -->
                <div class="p-4 border-t border-white/10 mt-auto">
                    <div class="flex items-center gap-3 p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1e3a5f&color=fff" alt="Avatar" class="w-10 h-10 rounded-full ring-2 ring-white/20">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <span class="inline-block px-2 py-0.5 bg-white/20 text-blue-100 text-xs rounded-md font-semibold mt-1">Admin</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="flex-1 overflow-y-auto flex flex-col">
                <div class="flex-1 p-6 lg:p-8">
                    {{ $slot }}
                </div>
                <!-- FOOTER -->
                <footer class="bg-white border-t border-gray-200 py-4 text-center text-sm text-gray-500 flex-shrink-0">
                    <p>&copy; 2026 SIGAP-AIR v1.0 — Sistem Informasi Gerak Cepat Pengaduan Air</p>
                </footer>
            </main>
        </div>
    </div>

    @include('layouts.partials.flash-message')
</body>
</html>
