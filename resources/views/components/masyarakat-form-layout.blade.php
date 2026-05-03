<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SIGAP-AIR' }} — SIGAP-AIR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- TOPBAR -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Left: Back Button & Title -->
                    <div class="flex items-center gap-3">
                        @if (isset($backUrl))
                        <a href="{{ $backUrl }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        @endif
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Panel Masyarakat</p>
                            <h1 class="text-sm font-semibold text-[#022448]">{{ $title ?? 'SIGAP-AIR' }}</h1>
                        </div>
                    </div>

                    <!-- Right: User Menu -->
                    <div class="flex items-center gap-4">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=022448&color=fff" alt="Avatar" class="w-8 h-8 rounded-full">
                                <span class="hidden sm:inline text-sm font-medium">{{ Auth::user()->name }}</span>
                            </button>
                            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-b">Edit Profil</a>
                                <form method="POST" action="{{ route('logout') }}" data-confirm="Yakin ingin logout dari akun ini?">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="flex-1 py-6">
            <div class="mx-auto w-full max-w-5xl px-4 sm:px-6">
                @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-sm font-semibold text-red-800">Terjadi kesalahan:</p>
                    <ul class="mt-2 space-y-1">
                        @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-700">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                </div>
                @endif

                {{ $slot }}
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="border-t border-gray-200 bg-white py-3 text-center text-xs text-gray-500">
            &copy; 2026 SIGAP-AIR v1.0 — Sistem Informasi Gerak Cepat Pengaduan Air
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('[data-confirm]');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm(this.dataset.confirm)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
