<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Selamat datang, {{ Auth::user()->name }}!
        </h1>
        <p class="text-gray-600 mt-2">
            Anda login sebagai <span class="font-semibold">Supervisor</span>
        </p>
    </div>

    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            Halo, {{ Auth::user()->name }}! 👋
        </h2>
        <p class="text-gray-600 leading-relaxed">
            Anda sekarang telah masuk ke sistem SIGAP-AIR dengan role <span class="font-semibold text-indigo-600">Supervisor</span>. 
            Anda dapat mengelola petugas dan memantau kinerja tim.
        </p>
    </div>
</x-app-layout>
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- In Progress Reports Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Laporan Proses</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">45</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supervisor Menu -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Supervisor</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Kelola Tim</p>
                        <p class="text-sm text-gray-600">Manage team members</p>
                    </div>
                </a>
                <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Analitik</p>
                        <p class="text-sm text-gray-600">View performance metrics</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

    <!-- Card Container -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tim Anda</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">15</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 0a3 3 0 11-6 0 3 3 0 016 0zM9 12a3 3 0 11-6 0 3 3 0 016 0zM18 6a3 3 0 11-6 0 3 3 0 016 0zM14.305 15.405a6 6 0 002.645-5.405 18 18 0 00-36 0 6 6 0 002.645 5.405A6 6 0 009 21h.995a5.979 5.979 0 001.059-11.596 3.993 3.993 0 003.948 3.596h4.996a3.993 3.993 0 003.948-3.596A5.98 5.98 0 0118 21h.995a6 6 0 005.305-5.595z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Laporan Selesai</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">234</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Laporan Proses</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">45</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Supervisor Options -->
    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Supervisor</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Kelola Tim</p>
                    <p class="text-sm text-gray-600">Manage team members</p>
                </div>
            </a>
            <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Analitik</p>
                    <p class="text-sm text-gray-600">View performance metrics</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
