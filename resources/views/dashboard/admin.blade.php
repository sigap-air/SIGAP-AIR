<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Selamat datang, {{ Auth::user()->name }}!
        </h1>
        <p class="text-gray-600 mt-2">
            Anda login sebagai <span class="font-semibold">Administrator</span>
        </p>
    </div>

    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            Halo, {{ Auth::user()->name }}! 👋
        </h2>
        <p class="text-gray-600 leading-relaxed">
            Anda sekarang telah masuk ke sistem SIGAP-AIR dengan role <span class="font-semibold text-indigo-600">Administrator</span>. 
            Anda memiliki akses penuh untuk mengelola sistem, user, dan laporan.
        </p>
    </div>
</x-app-layout>

