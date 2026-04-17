<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP-AIR - Panel Petugas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface">
    <div x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        showNotifications: false,
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
                            <svg class="w-6 h-6 text-[#059669]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                            <div>
                                <h1 class="text-lg font-bold text-gray-900">SIGAP-AIR</h1>
                                <p class="text-xs text-gray-500">Panel Petugas</p>
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
                                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors">
                                        <p class="text-sm font-medium text-gray-900">Tugas baru ditugaskan</p>
                                        <p class="text-xs text-gray-500 mt-1">Anda memiliki tugas baru menunggu dikerjakan</p>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-200 text-center">
                                    <a href="#" class="text-sm text-[#059669] hover:text-[#047857] font-medium">Lihat Semua</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button @click="showProfileDropdown = !showProfileDropdown" class="flex items-center gap-3 p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <img src="https://ui-avatars.com/api/?name=Petugas+SIGAP&background=059669&color=fff" alt="Avatar" class="w-8 h-8 rounded-full">
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Petugas</p>
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
            <!-- SIDEBAR - MINIMAL -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="w-60 bg-gray-900 text-white transition-transform duration-300 lg:translate-x-0 fixed lg:relative h-full z-30 overflow-y-auto flex flex-col">
                
                <!-- Sidebar Header -->
                <div class="p-6 border-b border-gray-800">
                    <div class="flex items-center gap-3 mb-6">
                        <svg class="w-8 h-8 text-[#059669]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                        <div>
                            <h2 class="font-bold text-white">SIGAP-AIR</h2>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Petugas</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu - MINIMAL -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('petugas.dashboard') }}" :class="isactive('/petugas/dashboard') ? 'bg-[#059669] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H5a3.5 3.5 0 00-3.5 3.5v12A3.5 3.5 0 005 20.5h10a3.5 3.5 0 003.5-3.5V11h-8a1.5 1.5 0 110-3h8V5a3.5 3.5 0 00-3.5-3.5z" />
                        </svg>
                        <span>Tugas Aktif</span>
                    </a>

                    <a href="{{ route('petugas.riwayat') }}" :class="isactive('/petugas/riwayat') ? 'bg-[#059669] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 3.002A3.066 3.066 0 0117 11a3.066 3.066 0 01-2.812 3.002 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-3.002 3.066 3.066 0 01.835-2.545 3.066 3.066 0 00.835-2.455 3.066 3.066 0 002.812-3.002zm9.724 0c-.403-.963-1.411-1.646-2.589-1.646a2.025 2.025 0 00-1.01.25 2.025 2.025 0 01-2.25 0 2.025 2.025 0 00-1.01-.25c-1.178 0-2.186.683-2.589 1.646a2.025 2.025 0 01-.5 1.518 2.025 2.025 0 00-.5 1.518c0 .6.22 1.149.585 1.581a2.025 2.025 0 01.5 1.517 2.025 2.025 0 01-.5 1.518 2.025 2.025 0 00-.585 1.581c0 .6.22 1.149.585 1.58a2.025 2.025 0 01.5 1.518 2.025 2.025 0 01-.5 1.518c.41.834 1.41 1.416 2.589 1.416 1.178 0 2.179-.582 2.589-1.416a2.025 2.025 0 01.5-1.518 2.025 2.025 0 00.5-1.518 2.025 2.025 0 01.5-1.517 2.025 2.025 0 00.585-1.581 2.025 2.025 0 01-.585-1.58 2.025 2.025 0 00-.5-1.518 2.025 2.025 0 01-.5-1.512z" clip-rule="evenodd" />
                        </svg>
                        <span>Riwayat Selesai</span>
                    </a>

                    <a href="{{ route('profile.edit') }}" :class="isactive('/profil') ? 'bg-[#059669] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                </nav>

                <!-- Sidebar Footer: User Info -->
                <div class="p-4 border-t border-gray-800 mt-auto">
                    <div class="flex items-center gap-3 p-3 bg-gray-800 rounded-lg">
                        <img src="https://ui-avatars.com/api/?name=Petugas+SIGAP&background=059669&color=fff" alt="Avatar" class="w-10 h-10 rounded-full">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <span class="inline-block px-2 py-1 bg-[#059669] text-white text-xs rounded font-semibold mt-1">Petugas</span>
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
