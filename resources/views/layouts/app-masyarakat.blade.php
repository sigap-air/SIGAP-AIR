<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP-AIR - Portal Pengaduan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface">
    <div x-data="{
        showMobileMenu: false,
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
                    <!-- Left: Logo -->
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#2563EB]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900 hidden sm:block">SIGAP-AIR</h1>
                            <p class="text-xs text-gray-500 hidden sm:block">Portal Pengaduan Air</p>
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
                                        <p class="text-sm font-medium text-gray-900">Pengaduan Anda telah diproses</p>
                                        <p class="text-xs text-gray-500 mt-1">Pengaduan #PRL202601 sedang ditindaklanjuti</p>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-200 text-center">
                                    <a href="#" class="text-sm text-[#2563EB] hover:text-[#1D4ED8] font-medium">Lihat Semua</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button @click="showProfileDropdown = !showProfileDropdown" class="flex items-center gap-3 p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <img src="https://ui-avatars.com/api/?name=Masyarakat+SIGAP&background=2563EB&color=fff" alt="Avatar" class="w-8 h-8 rounded-full">
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Masyarakat</p>
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

        <!-- NAVIGATION BAR - Mobile Bottom / Desktop Horizontal -->
        <div class="hidden md:flex bg-white border-b border-gray-200 sticky top-16 z-30">
            <div class="max-container flex justify-start gap-0">
                <a href="{{ route('masyarakat.dashboard') }}" :class="isactive('/pengaduan') ? 'border-b-2 border-[#2563EB] text-[#2563EB]' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-3 font-medium text-sm transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span>Beranda</span>
                </a>

                <a href="#" :class="isactive('/pengaduan/baru') ? 'border-b-2 border-[#2563EB] text-[#2563EB]' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-3 font-medium text-sm transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 3.586a1 1 0 00-1.414 0L7 7.172V5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2v2.172l-3.586-3.586zM15 13H5v2h10v-2z" clip-rule="evenodd" />
                    </svg>
                    <span>Buat Pengaduan</span>
                </a>

                <a href="#" :class="isactive('/pengaduan/riwayat') ? 'border-b-2 border-[#2563EB] text-[#2563EB]' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-3 font-medium text-sm transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H2a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2 1 1 0 000 2h2a1 1 0 110 2H4zm2 4a1 1 0 100 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    <span>Riwayat</span>
                </a>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto">
            <div class="max-container py-8">
                {{ $slot }}
            </div>
        </main>

        <!-- MOBILE BOTTOM NAVIGATION -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40">
            <div class="flex justify-around">
                <a href="{{ route('masyarakat.dashboard') }}" :class="isactive('/pengaduan') ? 'text-[#2563EB]' : 'text-gray-600'" class="flex-1 flex flex-col items-center gap-1 py-3 text-xs font-medium hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span>Beranda</span>
                </a>

                <a href="#" :class="isactive('/pengaduan/baru') ? 'text-[#2563EB]' : 'text-gray-600'" class="flex-1 flex flex-col items-center gap-1 py-3 text-xs font-medium hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Buat</span>
                </a>

                <a href="#" :class="isactive('/pengaduan/riwayat') ? 'text-[#2563EB]' : 'text-gray-600'" class="flex-1 flex flex-col items-center gap-1 py-3 text-xs font-medium hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H2a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2 1 1 0 000 2h2a1 1 0 110 2H4zm2 4a1 1 0 100 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    <span>Riwayat</span>
                </a>

                <a href="{{ route('profile.edit') }}" :class="isactive('/profil') ? 'text-[#2563EB]' : 'text-gray-600'" class="flex-1 flex flex-col items-center gap-1 py-3 text-xs font-medium hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    <span>Profil</span>
                </a>
            </div>
        </nav>

        <!-- Spacer for mobile bottom nav -->
        <div class="md:hidden h-24"></div>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-gray-200 py-4 text-center text-sm text-gray-600 hidden md:block">
            <p>&copy; 2026 SIGAP-AIR v1.0 - Sistem Informasi Gerak Cepat Pengaduan Air</p>
        </footer>
    </div>

    @include('layouts.partials.flash-message')
</body>
</html>
