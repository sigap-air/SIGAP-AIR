<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Selamat datang, {{ Auth::user()->name }}!
        </h1>
        <p class="text-gray-600 mt-2">
            Anda login sebagai <span class="font-semibold">Petugas</span>
        </p>
    </div>

    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            Halo, {{ Auth::user()->name }}! 👋
        </h2>
        <p class="text-gray-600 leading-relaxed">
            Anda sekarang telah masuk ke sistem SIGAP-AIR dengan role <span class="font-semibold text-indigo-600">Petugas</span>. 
            Anda dapat mengelola tugas dan melaporkan pekerjaan Anda.
        </p>
    </div>
</x-app-layout>

            <!-- Completed Tasks Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Tugas Selesai</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">156</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Performance Score Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Score Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">92%</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Petugas Menu -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Petugas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H6a6 6 0 016 6v3a6 6 0 01-6 6H6a1 1 0 000 2h4a2 2 0 002-2v-1c.268 0 .535-.007.8-.021A6 6 0 0020 10V5a2 2 0 00-2-2 1 1 0 000 2v3a4 4 0 01-4 4H8a4 4 0 01-4-4V5z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Daftar Tugas</p>
                        <p class="text-sm text-gray-600">View assigned tasks</p>
                    </div>
                </a>
                <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Laporan</p>
                        <p class="text-sm text-gray-600">Submit task reports</p>
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
                    <p class="text-sm text-gray-600">Tugas Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">8</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tugas Selesai</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">156</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Score Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">92%</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Petugas Options -->
    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Petugas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H6a6 6 0 016 6v3a6 6 0 01-6 6H6a1 1 0 000 2h4a2 2 0 002-2v-1c.268 0 .535-.007.8-.021A6 6 0 0020 10V5a2 2 0 00-2-2 1 1 0 000 2v3a4 4 0 01-4 4H8a4 4 0 01-4-4V5z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Daftar Tugas</p>
                    <p class="text-sm text-gray-600">View assigned tasks</p>
                </div>
            </a>
            <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Laporan</p>
                    <p class="text-sm text-gray-600">Submit task reports</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
