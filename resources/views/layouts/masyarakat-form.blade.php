<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} — SIGAP-AIR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#3B82F6', dark: '#2563EB' },
                    },
                },
            },
        }
    </script>
    @stack('styles')
</head>
<body class="min-h-screen bg-white font-sans text-gray-800 antialiased pb-8">

    <x-sigap-mobile-header :back-url="$backUrl" />

    <div class="px-4 pt-4 max-w-lg mx-auto w-full">
        @include('components.alert')
        {{ $slot }}
    </div>

    @stack('scripts')
</body>
</html>
