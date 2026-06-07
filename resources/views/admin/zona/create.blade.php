<x-app-admin-layout>

{{-- Leaflet CSS & Leaflet Draw CSS --}}
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            z-index: 0;
        }
    </style>
@endpush
{{-- Page Header --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.zona.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Tambah Zona Wilayah</h1>
            <p class="text-sm text-gray-500 mt-0.5">Buat zona wilayah layanan PDAM baru</p>
        </div>
    </div>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="bg-navy-gradient px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-xl">add_circle</span>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white">Form Zona Baru</h2>
                    <p class="text-xs text-blue-200">Semua field wajib diisi kecuali Deskripsi</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.zona.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            {{-- Nama Zona --}}
            <div>
                <label for="nama_zona" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Zona <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">map</span>
                    <input type="text" id="nama_zona" name="nama_zona"
                           value="{{ old('nama_zona') }}"
                           placeholder="misal: Zona Utara, Zona Selatan"
                           class="w-full h-12 pl-11 pr-4 bg-gray-50 border @error('nama_zona') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 placeholder:text-gray-400">
                </div>
                @error('nama_zona')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Kode Zona --}}
            <div>
                <label for="kode_zona" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Kode Zona <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">tag</span>
                    <input type="text" id="kode_zona" name="kode_zona"
                           value="{{ old('kode_zona') }}"
                           placeholder="misal: ZN-UTARA, ZN-SELATAN"
                           style="text-transform: uppercase;"
                           oninput="this.value = this.value.toUpperCase()"
                           class="w-full h-12 pl-11 pr-4 bg-gray-50 border @error('kode_zona') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl text-gray-900 font-mono focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 placeholder:text-gray-400 placeholder:font-sans">
                </div>
                <p class="mt-1 text-xs text-gray-500">Hanya huruf kapital, angka, dan tanda hubung (-). Contoh: <code class="bg-gray-100 px-1 rounded">ZN-UTARA</code></p>
                @error('kode_zona')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          placeholder="Penjelasan singkat mengenai wilayah cakupan zona ini..."
                          class="w-full px-4 py-3 bg-gray-50 border @error('deskripsi') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 placeholder:text-gray-400 resize-none">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Geo Boundary Map --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Batas Wilayah Zona (Polygon) <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <p class="text-xs text-gray-500 mb-3">Gunakan tool di sebelah kiri peta (ikon polygon) untuk menggambar batas wilayah zona ini.</p>
                <div id="map"></div>
                <input type="hidden" name="geo_boundary" id="geo_boundary" value="{{ old('geo_boundary') }}">
                @error('geo_boundary')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>


            {{-- Is Active --}}
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div onclick="document.getElementById('is_active').click()"
                         class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#022448] cursor-pointer"></div>
                </div>
                <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                    Aktifkan zona ini
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.zona.index') }}"
                   class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-navy-gradient text-white text-sm font-semibold rounded-xl shadow-md shadow-[#022448]/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan Zona
                </button>
            </div>
        </form>
    </div>
</div>

</x-app-admin-layout>

@push('scripts')
    {{-- Leaflet JS & Leaflet Draw JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Peta (Default Center: Bandung)
            const map = L.map('map').setView([-6.9175, 107.6191], 12);

            // Tile layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // FeatureGroup untuk menyimpan polygon yang digambar
            const drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            // Inisialisasi Leaflet Draw Control
            const drawControl = new L.Control.Draw({
                draw: {
                    polygon: {
                        allowIntersection: false,
                        drawError: { color: '#e1e100', message: '<strong>Peringatan!</strong> Polygon tidak boleh menyilang!' },
                        shapeOptions: { color: '#022448', fillColor: '#022448', fillOpacity: 0.2 }
                    },
                    // Nonaktifkan tool lain karena kita hanya butuh polygon (batas wilayah)
                    polyline: false,
                    rectangle: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                },
                edit: {
                    featureGroup: drawnItems,
                    remove: true
                }
            });
            map.addControl(drawControl);

            const inputGeoBoundary = document.getElementById('geo_boundary');

            // Restore existing polygon from old input (jika ada error validasi)
            if (inputGeoBoundary.value) {
                try {
                    const geojson = JSON.parse(inputGeoBoundary.value);
                    const layer = L.geoJSON(geojson, {
                        style: { color: '#022448', fillColor: '#022448', fillOpacity: 0.2 }
                    });
                    
                    // Ekstrak polygon dari GeoJSON layer dan masukkan ke drawnItems
                    layer.eachLayer(function (l) {
                        drawnItems.addLayer(l);
                    });
                    map.fitBounds(drawnItems.getBounds());
                } catch (e) {
                    console.error("Gagal memparsing old geo_boundary", e);
                }
            }

            // Fungsi untuk mengupdate hidden input
            function updateGeoBoundary() {
                const data = drawnItems.toGeoJSON();
                // Jika tidak ada data yang digambar
                if (data.features.length === 0) {
                    inputGeoBoundary.value = '';
                    return;
                }
                // Ambil feature pertama saja (karena kita hanya butuh 1 polygon per zona)
                const geometry = data.features[0].geometry;
                inputGeoBoundary.value = JSON.stringify(geometry);
            }

            // Event saat selesai menggambar
            map.on(L.Draw.Event.CREATED, function (event) {
                const layer = event.layer;
                // Bersihkan polygon sebelumnya agar hanya ada 1 polygon (1 zona = 1 wilayah)
                drawnItems.clearLayers();
                drawnItems.addLayer(layer);
                updateGeoBoundary();
            });

            // Event saat polygon diedit
            map.on(L.Draw.Event.EDITED, function () {
                updateGeoBoundary();
            });

            // Event saat polygon dihapus
            map.on(L.Draw.Event.DELETED, function () {
                updateGeoBoundary();
            });
        });
    </script>
@endpush
