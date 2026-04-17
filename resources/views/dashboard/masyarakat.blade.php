<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Selamat datang, {{ Auth::user()->name }}!
        </h1>
        <p class="text-gray-600 mt-2">
            Anda login sebagai <span class="font-semibold">Masyarakat</span>
        </p>
    </div>

    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            Halo, {{ Auth::user()->name }}! 👋
        </h2>
        <p class="text-gray-600 leading-relaxed">
            Anda sekarang telah masuk ke sistem SIGAP-AIR dengan role <span class="font-semibold text-indigo-600">Masyarakat</span>. 
            Anda dapat membuat pengaduan dan melacak statusnya.
        </p>
    </div>
</x-app-layout>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Menunggu Proses</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">3</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Reports Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Terselesaikan</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">9</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Masyarakat Menu -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Masyarakat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Buat Laporan</p>
                        <p class="text-sm text-gray-600">Submit a new report</p>
                    </div>
                </a>
                <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Riwayat Laporan</p>
                        <p class="text-sm text-gray-600">View your reports</p>
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
                    <p class="text-sm text-gray-600">Laporan Anda</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">12</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Menunggu Proses</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">3</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Terselesaikan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">9</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Masyarakat Options -->
    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Masyarakat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Buat Laporan</p>
                    <p class="text-sm text-gray-600">Submit a new report</p>
                </div>
            </a>
            <a href="#" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Riwayat Laporan</p>
                    <p class="text-sm text-gray-600">View your reports</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
