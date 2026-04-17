<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP-AIR - Panel Supervisor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface">
    <div x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        showNotifications: false,
        notifications: [],
        unreadCount: 0,
        verifikasiCount: 0,
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
                this.verifikasiCount = data.verifikasi_count || 0;
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
                            <svg class="w-6 h-6 text-[#0F4C81]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                            <div>
                                <h1 class="text-lg font-bold text-gray-900">SIGAP-AIR</h1>
                                <p class="text-xs text-gray-500">Panel Supervisor</p>
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
                                        <p class="text-sm font-medium text-gray-900">Pengaduan menunggu verifikasi</p>
                                        <p class="text-xs text-gray-500 mt-1"><span x-text="verifikasiCount"></span> tiket perlu ditindaklanjuti</p>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-200 text-center">
                                    <a href="#" class="text-sm text-[#0F4C81] hover:text-[#0D3F6E] font-medium">Lihat Semua Notifikasi</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button @click="showProfileDropdown = !showProfileDropdown" class="flex items-center gap-3 p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <img src="https://ui-avatars.com/api/?name=Supervisor+SIGAP&background=0F4C81&color=fff" alt="Avatar" class="w-8 h-8 rounded-full">
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Supervisor</p>
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
                        <svg class="w-8 h-8 text-[#0F4C81]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                        <div>
                            <h2 class="font-bold text-white">SIGAP-AIR</h2>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Supervisor</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('supervisor.dashboard') }}" :class="isactive('/supervisor/dashboard') ? 'bg-[#0F4C81] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="#" :class="isactive('/supervisor/verifikasi') ? 'bg-[#0F4C81] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors relative">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.5 1A1.5 1.5 0 001 2.5v15A1.5 1.5 0 002.5 19h15a1.5 1.5 0 001.5-1.5v-15A1.5 1.5 0 0017.5 1h-15zM7 9a2 2 0 11-4 0 2 2 0 014 0zM7 13a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                        <span>Verifikasi Tiket</span>
                        <span x-show="verifikasiCount > 0" class="absolute -right-2 -top-2 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold" x-text="verifikasiCount"></span>
                    </a>

                    <a href="#" :class="isactive('/supervisor/pengaduan') ? 'bg-[#0F4C81] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.429 5.951 1.429a1 1 0 001.169-1.409l-7-14z" />
                        </svg>
                        <span>Semua Pengaduan</span>
                    </a>

                    <a href="#" :class="isactive('/supervisor/assignment') ? 'bg-[#0F4C81] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                        </svg>
                        <span>Assignment</span>
                    </a>

                    <a href="#" :class="isactive('/supervisor/laporan') ? 'bg-[#0F4C81] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm0 4a1 1 0 100 2h6a1 1 0 100-2H7zm0 4a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                        <span>Laporan PDF</span>
                    </a>

                    <a href="#" :class="isactive('/supervisor/kinerja') ? 'bg-[#0F4C81] text-white' : 'text-gray-300 hover:bg-gray-800'" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                        <span>Kinerja Petugas</span>
                    </a>
                </nav>

                <!-- Sidebar Footer: User Info -->
                <div class="p-4 border-t border-gray-800 mt-auto">
                    <div class="flex items-center gap-3 p-3 bg-gray-800 rounded-lg">
                        <img src="https://ui-avatars.com/api/?name=Supervisor+SIGAP&background=0F4C81&color=fff" alt="Avatar" class="w-10 h-10 rounded-full">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <span class="inline-block px-2 py-1 bg-[#0F4C81] text-white text-xs rounded font-semibold mt-1">Supervisor</span>
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
