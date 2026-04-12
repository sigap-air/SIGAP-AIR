<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SIGAP-AIR' }} — Sistem Informasi Pengaduan Air</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { brand: { DEFAULT: '#3B82F6', dark: '#2563EB' } } } }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans">

    {{-- NAVBAR (fixed top) --}}
    @include('components.navbar')

    <div class="flex min-h-screen">
        {{-- SIDEBAR (fixed left) --}}
        @include('components.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1 ml-56 mt-16 p-6 min-h-screen">
            {{-- Alert / Flash Message --}}
            @include('components.alert')

            {{-- Page Content --}}
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
