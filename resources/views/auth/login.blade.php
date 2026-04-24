<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Masuk - SIGAP-AIR</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- AlpineJS & App scripts -->
    @vite(['resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-dim": "#d8dadc",
                        "surface-container": "#eceef0",
                        "primary-fixed-dim": "#adc8f5",
                        "tertiary-container": "#4a00a4",
                        "surface-tint": "#455f87",
                        "on-primary-container": "#8aa4cf",
                        "on-secondary": "#ffffff",
                        "secondary-container": "#316bf3",
                        "inverse-on-surface": "#eff1f3",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                        "primary": "#022448",
                        "on-surface-variant": "#43474e",
                        "outline-variant": "#c4c6cf",
                        "on-tertiary-fixed-variant": "#5a00c6",
                        "inverse-surface": "#2d3133",
                        "surface-variant": "#e0e3e5",
                        "on-background": "#191c1e",
                        "on-tertiary-fixed": "#25005a",
                        "on-surface": "#191c1e",
                        "surface-bright": "#f7f9fb",
                        "on-secondary-fixed-variant": "#003ea8",
                        "on-primary-fixed": "#001c3b",
                        "secondary": "#0051d5",
                        "surface-container-low": "#f2f4f6",
                        "secondary-fixed-dim": "#b4c5ff",
                        "surface-container-high": "#e6e8ea",
                        "on-primary-fixed-variant": "#2d486d",
                        "on-secondary-fixed": "#00174b",
                        "tertiary-fixed-dim": "#d2bbff",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-container": "#b48fff",
                        "surface-container-highest": "#e0e3e5",
                        "surface": "#f7f9fb",
                        "on-tertiary": "#ffffff",
                        "outline": "#74777f",
                        "background": "#f7f9fb",
                        "on-secondary-container": "#fefcff",
                        "secondary-fixed": "#dbe1ff",
                        "on-primary": "#ffffff",
                        "on-error": "#ffffff",
                        "on-error-container": "#93000a",
                        "inverse-primary": "#adc8f5",
                        "primary-fixed": "#d5e3ff",
                        "tertiary-fixed": "#eaddff",
                        "primary-container": "#1e3a5f",
                        "tertiary": "#2f006f"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .bg-login-gradient {
            background: linear-gradient(135deg, #022448 0%, #1e3a5f 100%);
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased overflow-hidden">
<main class="flex min-h-screen w-full">

<!-- Left Side: Branding & Visuals -->
<section class="hidden lg:flex lg:w-1/2 bg-login-gradient relative overflow-hidden flex-col justify-center items-center p-12 text-center">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-secondary-container opacity-10 rounded-full blur-3xl -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-fixed opacity-5 rounded-full blur-3xl -ml-48 -mb-48"></div>
    
    <div class="relative z-10 flex flex-col items-center max-w-md">
        <!-- Water Droplet Illustration -->
        <div class="w-32 h-32 mb-8 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm shadow-xl">
            <span class="material-symbols-outlined text-white text-6xl" style="font-variation-settings: 'FILL' 1;">water_drop</span>
        </div>
        <h1 class="font-headline font-extrabold text-white text-5xl tracking-tight mb-4">SIGAP-AIR</h1>
        <p class="text-primary-fixed text-xl font-medium leading-relaxed opacity-90">
            Laporkan masalah air Anda dengan mudah dan cepat
        </p>
        
        <!-- Visual Accent -->
        <div class="mt-12 w-full grid grid-cols-3 gap-4 opacity-40">
            <div class="h-1 bg-white/20 rounded-full"></div>
            <div class="h-1 bg-white/60 rounded-full"></div>
            <div class="h-1 bg-white/20 rounded-full"></div>
        </div>
    </div>
    
    <!-- Absolute Image Background Texture -->
    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none mix-blend-overlay" data-alt="abstract close-up of water ripples with soft lighting and deep navy tones reflecting a professional atmosphere" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBK3iBogVrMyPE5_mqDrgHvEZlUNr7X1zky0p3CG7lur9MK5O8mLh7AmiYGEg3rBml_AXKBd8R1iVYRcwFtthka5QHzP8Ra43nj0i9oVtKR8Z-0DMYQDCUJinmbemEOZ_pWorc_cJELSMlb9SLP7PH0XRqtNKJkUjDZxMdvJUNYj3yI9wZ1QrY60gjMjsyXo6PtfOyYDseRlQxaZhyCLxsKLVyWY5EWSAxG29RZLeDh2zysefCKtljHQ8w262zDrwvXJNe3gFduLhE'); background-size: cover;"></div>
</section>

<!-- Right Side: Login Form -->
<section class="w-full lg:w-1/2 flex flex-col bg-surface-container-lowest justify-center px-6 md:px-20 lg:px-32 relative">
    <div class="max-w-md w-full mx-auto">
        <!-- Mobile Branding -->
        <div class="lg:hidden flex flex-col items-center mb-10">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-secondary text-3xl" style="font-variation-settings: 'FILL' 1;">water_drop</span>
                <span class="font-headline font-bold text-primary text-2xl">SIGAP-AIR</span>
            </div>
        </div>
        
        <!-- Header Content -->
        <div class="mb-10 text-left">
            <h2 class="font-headline font-semibold text-on-surface text-3xl mb-2">Masuk ke SIGAP-AIR</h2>
            <p class="text-outline text-body-md font-medium">Masukkan email dan password Anda</p>
        </div>

        <!-- FLASH MESSAGE -->
        @if(session('status'))
            <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <!-- ERROR HANDLING -->
        @if ($errors->any())
            <div class="bg-error-container border border-error/20 rounded-xl px-4 py-3 mb-6">
                <ul class="list-disc pl-5 text-sm text-error">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ loading: false }" @submit="loading = true" data-confirm="Yakin ingin login ke akun ini?">
            @csrf
            
            <input class="hidden" type="checkbox" name="remember" checked>
            
            <div>
                <label class="block text-label-sm font-semibold text-on-surface-variant mb-2 ml-1" for="email">Email</label>
                <div class="relative group">
                    <input class="w-full h-14 px-4 bg-surface-container-highest border border-transparent rounded-xl text-on-surface focus:ring-2 focus:ring-secondary-container focus:bg-surface-container-lowest transition-all duration-200 placeholder:text-outline/50" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" type="email"/>
                </div>
                @error('email')
                    <p class="mt-1 ml-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            
            <div x-data="{ show: false }">
                <div class="flex justify-between items-center mb-2 px-1">
                    <label class="block text-label-sm font-semibold text-on-surface-variant" for="password">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-label-sm font-semibold text-secondary hover:underline transition-all" href="{{ route('password.request') }}">Lupa password?</a>
                    @endif
                </div>
                <div class="relative group">
                    <input class="w-full h-14 px-4 bg-surface-container-highest border border-transparent rounded-xl text-on-surface pr-12 focus:ring-2 focus:ring-secondary-container focus:bg-surface-container-lowest transition-all duration-200" id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••" type="password"/>
                    
                    <button @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors focus:outline-none" type="button">
                        <span class="material-symbols-outlined" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 ml-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            
            <button :disabled="loading" class="w-full flex items-center justify-center gap-2 h-14 bg-login-gradient text-white font-semibold rounded-xl shadow-lg shadow-primary/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 active:scale-98 disabled:opacity-70 disabled:cursor-not-allowed" type="submit">
                <span x-show="loading" class="material-symbols-outlined animate-spin" style="font-size: 1.25rem;">sync</span>
                <span x-text="loading ? 'Memuat...' : 'Masuk'">Masuk</span>
            </button>
        </form>
        
        <!-- Divider -->
        <div class="flex items-center my-8">
            <div class="flex-grow h-[1px] bg-outline-variant/30"></div>
            <span class="px-4 text-label-sm text-outline font-medium">atau</span>
            <div class="flex-grow h-[1px] bg-outline-variant/30"></div>
        </div>
        
        <!-- Registration Link -->
        <div class="text-center">
            <p class="text-on-surface-variant text-body-md">
                Belum punya akun? 
                @if (Route::has('register'))
                    <a class="text-secondary font-bold hover:underline ml-1" href="{{ route('register') }}">Daftar sekarang</a>
                @else
                    <a class="text-secondary font-bold hover:underline ml-1" href="#">Daftar sekarang</a>
                @endif
            </p>
        </div>
    </div>
    
    <!-- Footer Integration -->
    <footer class="absolute bottom-8 left-0 w-full px-6">
        <div class="flex flex-col items-center justify-center gap-1 opacity-60">
            <p class="font-['Inter'] text-[0.7rem] text-center text-outline">
                SIGAP-AIR © 2025 · PDAM
            </p>
        </div>
    </footer>
</section>

</main>
</body>
</html>
