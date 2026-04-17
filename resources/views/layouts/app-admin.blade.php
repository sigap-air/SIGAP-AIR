<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $header }}
    </h2>
</x-slot>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP-AIR - Panel Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface">
    <div x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        showNotifications: false,
        notifications: [],
        unreadCount: 0,
        showProfileDropdown: false,
        init() {
            this.fetchNotifications();
            // Poll notifikasi setiap 30 detik
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
    }" class="min-h-screen flex flex-col">

        <!-- TOPBAR -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
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
                            <svg class="w-6 h-6 text-[#7C3AED]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                            <div>
                                <h1 class="text-lg font-bold text-gray-900">SIGAP-AIR</h1>
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
                                <!-- Badge -->
                                <span x-show="unreadCount > 0" class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                    <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                                </span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="showNotifications" @click.outside="showNotifications = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <!-- Dummy notifications -->
                                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors">
                                        <p class="text-sm font-medium text-gray-900">Pengaduan menunggu verifikasi</p>
                                        <p class="text-xs text-gray-500 mt-1">5 pengaduan baru dari masyarakat</p>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-200 text-center">
                                    <a href="#" class="text-sm text-[#7C3AED] hover:text-[#6D28D9] font-medium">Lihat Semua Notifikasi</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button @click="showProfileDropdown = !showProfileDropdown" class="flex items-center gap-3 p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <img src="https://ui-avatars.com/api/?name=Admin+SIGAP&background=7C3AED&color=fff" alt="Avatar" class="w-8 h-8 rounded-full">
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Admin</p>
                                </div>
                            </button>

                            <!-- Profile Dropdown Menu -->
                            <div x-show="showProfileDropdown" @click.outside="showProfileDropdown = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-b border-gray-200">Edit Profil</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-b border-gray-200">Ganti Password</a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- MAIN CONTAINER -->
        <div class="flex flex-1 overflow-hidden">
            <!-- SIDEBAR -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="w-60 bg-gray-900 text-white transition-transform duration-300 lg:translate-x-0 fixed lg:relative h-full z-30 overflow-y-auto flex flex-col">
                
                <!-- Sidebar Header -->
                <div class="p-6 border-b border-gray-800">
                    <div class="flex items-center gap-3 mb-6">
                        <svg class="w-8 h-8 text-[#7C3AED]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                        <div>
                            <h2 class="font-bold text-white">SIGAP-AIR</h2>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Panel Admin</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" :class="isactive('/admin/dashboard') ? 'bg-[#7C3AED] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="#" :class="isactive('/admin/pelanggan') ? 'bg-[#7C3AED] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 12a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                        <span>Data Pelanggan</span>
                    </a>

                    <a href="#" :class="isactive('/admin/kategori') ? 'bg-[#7C3AED] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zM3 16a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" />
                        </svg>
                        <span>Kategori & SLA</span>
                    </a>

                    <a href="#" :class="isactive('/admin/zona') ? 'bg-[#7C3AED] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span>Zona Wilayah</span>
                    </a>

                    <a href="#" :class="isactive('/admin/users') ? 'bg-[#7C3AED] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                        </svg>
                        <span>Manajemen User</span>
                    </a>

                    <a href="#" :class="isactive('/admin/petugas') ? 'bg-[#7C3AED] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H19a2 2 0 012 2v3h-8v-5zM6.75 5.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zM1 15.25c0 1.1.9 2 2 2h5.5a2 2 0 012-2H3a1 1 0 00-1 1v1.75c0 .414.336.75.75.75h5a.75.75 0 000-1.5H1z" />
                        </svg>
                        <span>Data Petugas</span>
                    </a>

                    <a href="#" :class="isactive('/admin/sla') ? 'bg-[#7C3AED] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        <span>Konfigurasi SLA</span>
                    </a>
                </nav>

                <!-- Sidebar Footer: User Info -->
                <div class="p-4 border-t border-gray-800 mt-auto">
                    <div class="flex items-center gap-3 p-3 bg-gray-800 rounded-lg">
                        <img src="https://ui-avatars.com/api/?name=Admin+SIGAP&background=7C3AED&color=fff" alt="Avatar" class="w-10 h-10 rounded-full">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <span class="inline-block px-2 py-1 bg-[#7C3AED] text-white text-xs rounded font-semibold mt-1">Admin</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-container py-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-gray-200 py-4 text-center text-sm text-gray-600">
            <p>&copy; 2026 SIGAP-AIR v1.0 - Sistem Informasi Gerak Cepat Pengaduan Air</p>
        </footer>
    </div>

    @include('layouts.partials.flash-message')
</body>
</html>
