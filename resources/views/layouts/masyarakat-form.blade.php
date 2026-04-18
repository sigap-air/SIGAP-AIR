<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} — SIGAP-AIR</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#022448', dark: '#1e3a5f' },
                    },
                },
            },
        }
    </script>
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ $backUrl }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100">
                            <span aria-hidden="true">←</span>
                        </a>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Panel Masyarakat</p>
                            <h1 class="text-sm font-semibold text-brand">{{ $pageTitle }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 py-6">
            <div class="mx-auto w-full max-w-4xl px-4 sm:px-6">
                @include('components.alert')
                {{ $slot }}
            </div>
        </main>

        <footer class="border-t border-gray-200 bg-white py-3 text-center text-xs text-gray-500">
            &copy; 2026 SIGAP-AIR v1.0 — Sistem Informasi Gerak Cepat Pengaduan Air
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
