{{-- 
    PBI-04 — Form Pengajuan Pengaduan
    TANGGUNG JAWAB: Sanitra Savitri
    
    Inovasi: Integrasi Peta Interaktif Leaflet + OpenStreetMap + GPS Geolocation
--}}
@php
    $isRevisi = isset($pengaduanRevisi);
    $p = $pengaduanRevisi ?? null;
@endphp
<x-masyarakat-form-layout
    :title="$isRevisi ? 'Revisi Pengaduan' : 'Pengaduan Baru'"
    :back-url="$isRevisi ? route('masyarakat.pengaduan.riwayat.show', $p->nomor_tiket) : route('masyarakat.dashboard')">
    {{-- Leaflet CSS (CDNJS) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <style>
        #map {
            height: 380px;
            width: 100%;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            z-index: 0;
        }
        /* Pastikan tooltip Leaflet tampil di atas elemen lain */
        .leaflet-container {
            font-family: 'Inter', sans-serif;
            border-radius: 12px;
        }
        .zona-label {
            background: transparent;
            border: none;
            box-shadow: none;
            font-size: 11px;
            font-weight: 600;
            color: #374151;
        }
        .gps-btn-pulse {
            animation: pulse-blue 2s infinite;
        }
        @keyframes pulse-blue {
            0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(37, 99, 235, 0); }
        }
    </style>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $isRevisi ? 'Revisi Pengaduan' : 'Pengaduan Baru' }}</h1>
        <p class="mt-1 text-sm text-gray-500">
            @if ($isRevisi)
                Perbaiki data pengaduan <span class="font-semibold">{{ $p->nomor_tiket }}</span> sesuai catatan penolakan, lalu ajukan ulang untuk verifikasi supervisor.
            @else
                Lengkapi form berikut untuk mengirim laporan gangguan layanan air.
            @endif
        </p>
    </div>

    @if ($isRevisi && $p->alasan_penolakan)
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
        <p class="text-sm font-semibold text-red-800">Alasan penolakan supervisor</p>
        <p class="mt-1 text-sm text-red-700">{{ $p->alasan_penolakan }}</p>
    </div>
    @endif

    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
        <form action="{{ $isRevisi ? route('masyarakat.pengaduan.revisi.update', $p->nomor_tiket) : route('masyarakat.pengaduan.store') }}"
              method="POST" enctype="multipart/form-data" class="flex flex-col"
              data-confirm="{{ $isRevisi ? 'Yakin ingin mengajukan ulang pengaduan yang sudah direvisi?' : 'Yakin ingin mengirim pengaduan ini?' }}">
            @csrf
            @if ($isRevisi)
                @method('PUT')
            @endif

            <x-sigap-form-field label="Kategori Pengaduan" name="kategori_id" :required="true">
                <select name="kategori_id"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm focus:ring-2 focus:ring-brand"
                    required>
                    <option value="" disabled {{ old('kategori_id', $p?->kategori_id) ? '' : 'selected' }}>Pilih kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" {{ (string) old('kategori_id', $p?->kategori_id) === (string) $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }} (SLA {{ $k->sla_jam }} jam)
                        </option>
                    @endforeach
                </select>
            </x-sigap-form-field>

            {{-- =====================================================
                 SEKSI PETA INTERAKTIF — Leaflet + OpenStreetMap + GPS
                 ===================================================== --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Lokasi Pengaduan
                    <span class="text-red-500 ml-0.5">*</span>
                </label>
                <p class="text-xs text-gray-500 mb-3">
                    Klik di dalam area berwarna di peta (Utara/Selatan/Barat/Timur) untuk menandai lokasi dan mengisi zona otomatis.
                    Anda juga bisa klik di mana saja pada peta, atau gunakan tombol <strong>Gunakan Lokasi Saya Saat Ini</strong> di bawah peta.
                </p>

                {{-- Peta Leaflet --}}
                <div id="map" class="mb-3"></div>

                {{-- Tombol GPS --}}
                <button type="button" id="btn-gps"
                    class="gps-btn-pulse mb-4 flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span id="gps-btn-text">Gunakan Lokasi Saya Saat Ini</span>
                </button>

                {{-- Hidden fields untuk koordinat --}}
                <input type="hidden" name="latitude"  id="input-latitude"  value="{{ old('latitude', $p?->latitude) }}">
                <input type="hidden" name="longitude" id="input-longitude" value="{{ old('longitude', $p?->longitude) }}">

                {{-- Koordinat info (ditampilkan setelah pin diletakkan) --}}
                <div id="coords-info" class="hidden mb-3 flex items-center gap-2 rounded-lg bg-emerald-50 border border-emerald-200 px-3 py-2 text-xs text-emerald-700">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="coords-text">Titik lokasi sudah ditandai</span>
                </div>
            </div>

            {{-- Zona Wilayah (auto-detect dari peta, bisa juga pilih manual) --}}
            <x-sigap-form-field label="Zona Wilayah" name="zona_id" :required="true">
                <select name="zona_id" id="zona_id"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm focus:ring-2 focus:ring-brand"
                    required>
                    <option value="" disabled {{ old('zona_id', $p?->zona_id) ? '' : 'selected' }}>Pilih zona (atau klik peta untuk auto-detect)</option>
                    @foreach ($zonas as $z)
                        <option value="{{ $z->id }}" {{ (string) old('zona_id', $p?->zona_id) === (string) $z->id ? 'selected' : '' }}>
                            {{ $z->nama_zona }}
                        </option>
                    @endforeach
                </select>
                {{-- Badge zona terdeteksi --}}
                <div id="zona-detected-badge" class="hidden mt-2 flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span id="zona-detected-text">Zona terdeteksi otomatis dari titik peta</span>
                </div>
                <div id="zona-outside-badge" class="hidden mt-2 flex items-center gap-1.5 text-xs font-medium text-amber-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>Titik berada di luar batas zona. Pastikan zona yang dipilih sudah benar.</span>
                </div>
            </x-sigap-form-field>

            {{-- Alamat / Patokan (teks, tetap wajib) --}}
            <x-sigap-form-field label="Alamat / Patokan Lokasi" name="lokasi" :required="true">
                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $p?->lokasi) }}"
                    placeholder="Contoh: Jl. Merdeka No. 10, dekat SPBU, RT 03 RW 07"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 placeholder-gray-500 shadow-sm focus:ring-2 focus:ring-brand"
                    required />
                <p class="mt-1 text-xs text-gray-500">Tambahkan keterangan alamat untuk memudahkan petugas menemukan lokasi.</p>
                <div id="zona-validation-alert" class="hidden mt-2 p-3 rounded-xl border text-sm font-medium flex items-start gap-2 transition-all">
                    <span class="material-symbols-outlined text-lg alert-icon">info</span>
                    <span class="alert-message">Memeriksa kesesuaian wilayah...</span>
                </div>
            </x-sigap-form-field>

            <x-sigap-form-field label="Nomor Telepon" name="no_telepon" :required="true">
                <input type="tel" name="no_telepon" value="{{ old('no_telepon', auth()->user()->no_telepon) }}"
                    placeholder="Contoh: 08123456789"
                    inputmode="numeric" pattern="[0-9]*"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    autocomplete="off"
                    maxlength="20"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 placeholder-gray-500 shadow-sm focus:ring-2 focus:ring-brand"
                    required />
                <p class="mt-1 text-xs text-gray-500">Hanya angka, tanpa spasi atau huruf.</p>
            </x-sigap-form-field>

            <x-sigap-form-field label="Deskripsi Masalah" name="deskripsi" :required="true">
                <textarea name="deskripsi" rows="5" placeholder="Jelaskan kendala secara detail"
                    class="w-full resize-y rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 placeholder-gray-500 shadow-sm focus:ring-2 focus:ring-brand"
                    required>{{ old('deskripsi', $p?->deskripsi) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Minimal 20 karakter.</p>
            </x-sigap-form-field>

            @if ($isRevisi && $p->foto_bukti)
            <div class="mb-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs font-semibold text-gray-600 mb-2">Foto bukti saat ini</p>
                <img src="{{ asset('storage/' . $p->foto_bukti) }}" alt="Foto bukti" class="max-h-48 rounded-lg object-contain">
                <p class="mt-2 text-xs text-gray-500">Unggah foto baru di bawah jika ingin mengganti bukti laporan.</p>
            </div>
            @endif

            <x-sigap-image-upload label="Bukti Foto" name="foto_bukti" :required="!$isRevisi" :optional="$isRevisi" />

            <div class="mt-4">
                <x-sigap-action-button variant="primary" type="submit">
                    {{ $isRevisi ? 'Ajukan Ulang Pengaduan' : 'Kirim Pengaduan' }}
                </x-sigap-action-button>
            </div>
        </form>
    </div>

    {{-- Leaflet JS (CDNJS) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
    (function() {
        if (typeof L === 'undefined') {
            console.error('Leaflet JS gagal dimuat!');
            alert('Gagal memuat sistem peta (Leaflet). Pastikan koneksi internet Anda stabil atau matikan AdBlocker, lalu refresh halaman.');
            return;
        }
        // =====================================================================
        // DATA ZONA BOUNDARY (dari PHP/database)
        // =====================================================================
        const ZONA_BOUNDARIES = @json($zonaBoundaries);

        // Warna per zona untuk polygon di peta
        const ZONA_COLORS = [
            '#2563EB', // Biru — Zona 1
            '#16A34A', // Hijau — Zona 2
            '#D97706', // Oranye — Zona 3
            '#DC2626', // Merah — Zona 4
            '#7C3AED', // Ungu — Zona 5+
        ];

        // =====================================================================
        // INISIALISASI PETA LEAFLET
        // Default center: Bandung, Indonesia
        // =====================================================================
        const DEFAULT_LAT = -6.9175;
        const DEFAULT_LNG = 107.6191;
        const DEFAULT_ZOOM = 12;

        const map = L.map('map').setView([DEFAULT_LAT, DEFAULT_LNG], DEFAULT_ZOOM);

        // Tile layer OpenStreetMap (gratis, tanpa API key)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);

        // =====================================================================
        // RENDER POLYGON ZONA DI PETA
        // =====================================================================
        const zonaLayers = {};
        const allPolygonBounds = [];

        ZONA_BOUNDARIES.forEach(function(zona, index) {
            if (!zona.geo_boundary || !zona.geo_boundary.coordinates) return;

            const color = ZONA_COLORS[index % ZONA_COLORS.length];

            // Konversi GeoJSON coordinates [lng, lat] ke format Leaflet [lat, lng]
            const coords = zona.geo_boundary.coordinates[0].map(c => [c[1], c[0]]);

            const polygon = L.polygon(coords, {
                color: color,
                weight: 2,
                fillColor: color,
                fillOpacity: 0.08,
                dashArray: '5, 5',
            }).addTo(map);

            allPolygonBounds.push(polygon.getBounds());

            // Tooltip nama zona di tengah polygon
            polygon.bindTooltip(zona.nama_zona, {
                permanent: true,
                direction: 'center',
                className: 'zona-label',
            });

            // Klik polygon → pilih zona otomatis
            polygon.on('click', function(e) {
                L.DomEvent.stopPropagation(e);
                placeMarker(e.latlng.lat, e.latlng.lng);
                showDetectedZona(zona.id, zona.nama_zona);
            });

            zonaLayers[zona.id] = { polygon, color, nama: zona.nama_zona };
        });

        if (allPolygonBounds.length > 0) {
            const groupBounds = allPolygonBounds.reduce((acc, b) => acc.extend(b), L.latLngBounds(allPolygonBounds[0]));
            map.fitBounds(groupBounds, { padding: [24, 24] });
        }

        // =====================================================================
        // MARKER (PIN) YANG BISA DIGESER
        // =====================================================================
        let marker = null;

        // Custom icon marker biru
        const markerIcon = L.divIcon({
            className: '',
            html: `<div style="
                width: 32px; height: 32px;
                background: #2563EB;
                border: 3px solid white;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                position: relative;
            ">
                <div style="
                    width: 10px; height: 10px;
                    background: white;
                    border-radius: 50%;
                    position: absolute;
                    top: 50%; left: 50%;
                    transform: translate(-50%, -50%);
                "></div>
            </div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32],
        });

        function placeMarker(lat, lng) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], {
                    draggable: true,
                    icon: markerIcon,
                }).addTo(map);

                marker.on('dragend', function(e) {
                    const pos = e.target.getLatLng();
                    updateCoordinates(pos.lat, pos.lng);
                    autoDetectZona(pos.lat, pos.lng);
                });
            }

            updateCoordinates(lat, lng);
        }

        function updateCoordinates(lat, lng) {
            document.getElementById('input-latitude').value  = lat.toFixed(7);
            document.getElementById('input-longitude').value = lng.toFixed(7);

            const coordsInfo = document.getElementById('coords-info');
            const coordsText = document.getElementById('coords-text');
            coordsInfo.classList.remove('hidden');
            coordsText.textContent = `Koordinat: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        }

        // Klik di mana saja di peta → letakkan marker
        map.on('click', function(e) {
            placeMarker(e.latlng.lat, e.latlng.lng);
            autoDetectZona(e.latlng.lat, e.latlng.lng);
        });

        // =====================================================================
        // AUTO-DETECT ZONA (Point-in-Polygon di sisi klien)
        // =====================================================================
        function isPointInPolygon(lat, lng, polygonCoords) {
            // polygonCoords dalam format GeoJSON [lng, lat]
            const n = polygonCoords.length;
            let inside = false;
            let j = n - 1;
            for (let i = 0; i < n; i++) {
                const xi = polygonCoords[i][0]; // lng
                const yi = polygonCoords[i][1]; // lat
                const xj = polygonCoords[j][0];
                const yj = polygonCoords[j][1];
                const intersect = ((yi > lat) !== (yj > lat)) &&
                    (lng < (xj - xi) * (lat - yi) / (yj - yi) + xi);
                if (intersect) inside = !inside;
                j = i;
            }
            return inside;
        }

        function autoDetectZona(lat, lng) {
            let detectedZonaId = null;
            let detectedZonaName = null;

            for (const zona of ZONA_BOUNDARIES) {
                if (!zona.geo_boundary || !zona.geo_boundary.coordinates) continue;
                if (isPointInPolygon(lat, lng, zona.geo_boundary.coordinates[0])) {
                    detectedZonaId   = zona.id;
                    detectedZonaName = zona.nama_zona;
                    break;
                }
            }

            if (detectedZonaId) {
                showDetectedZona(detectedZonaId, detectedZonaName);
            } else {
                // Titik di luar semua zona — tetap biarkan user pilih manual
                document.getElementById('zona-detected-badge').classList.add('hidden');
                // Hanya tampilkan warning jika ada zona di database
                if (ZONA_BOUNDARIES.some(z => z.geo_boundary)) {
                    document.getElementById('zona-outside-badge').classList.remove('hidden');
                }
            }
        }

        function showDetectedZona(zonaId, zonaName) {
            selectZona(zonaId, zonaName);
            document.getElementById('zona-detected-badge').classList.remove('hidden');
            document.getElementById('zona-detected-text').textContent = `Zona terdeteksi: ${zonaName}`;
            document.getElementById('zona-outside-badge').classList.add('hidden');
        }

        function selectZona(zonaId, zonaName) {
            const select = document.getElementById('zona_id');
            select.value = zonaId;
            select.dispatchEvent(new Event('change'));

            // Highlight polygon yang dipilih
            Object.entries(zonaLayers).forEach(([id, layer]) => {
                const isSelected = parseInt(id) === parseInt(zonaId);
                layer.polygon.setStyle({
                    fillOpacity: isSelected ? 0.25 : 0.08,
                    weight: isSelected ? 3 : 2,
                    dashArray: isSelected ? '' : '5, 5',
                });
            });
        }

        // =====================================================================
        // TOMBOL GPS — Gunakan Lokasi Saya
        // =====================================================================
        document.getElementById('btn-gps').addEventListener('click', function() {
            const btnText = document.getElementById('gps-btn-text');

            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung fitur GPS. Silakan tandai lokasi secara manual di peta.');
                return;
            }

            btnText.textContent = 'Mendeteksi lokasi...';
            this.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Zoom peta ke lokasi GPS
                    map.setView([lat, lng], 16);

                    // Letakkan marker
                    placeMarker(lat, lng);

                    // Auto-detect zona
                    autoDetectZona(lat, lng);

                    btnText.textContent = 'Lokasi GPS berhasil digunakan';
                    document.getElementById('btn-gps').disabled = false;
                    document.getElementById('btn-gps').classList.remove('gps-btn-pulse');
                },
                function(error) {
                    let msg = 'Gagal mendapatkan lokasi GPS. ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            msg += 'Izin lokasi ditolak. Silakan izinkan akses lokasi di browser Anda.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            msg += 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            msg += 'Waktu deteksi lokasi habis.';
                            break;
                    }
                    alert(msg + '\nSilakan tandai lokasi secara manual di peta.');
                    btnText.textContent = 'Gunakan Lokasi Saya Saat Ini';
                    document.getElementById('btn-gps').disabled = false;
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        });

        // =====================================================================
        // RESTORE POSISI MARKER JIKA ADA OLD INPUT (misal validasi error)
        // =====================================================================
        const oldLat = document.getElementById('input-latitude').value;
        const oldLng = document.getElementById('input-longitude').value;
        if (oldLat && oldLng) {
            const lat = parseFloat(oldLat);
            const lng = parseFloat(oldLng);
            map.setView([lat, lng], 15);
            placeMarker(lat, lng);
            autoDetectZona(lat, lng);
        }

        // =====================================================================
        // VALIDASI ZONA BERBASIS TEKS (fallback, sama seperti sebelumnya)
        // =====================================================================
        const lokasiInput = document.getElementById('lokasi');
        const zonaSelect  = document.getElementById('zona_id');
        const alertBox    = document.getElementById('zona-validation-alert');
        const alertIcon   = alertBox ? alertBox.querySelector('.alert-icon') : null;
        const alertMessage = alertBox ? alertBox.querySelector('.alert-message') : null;

        let debounceTimer;

        function validateZonaByText() {
            const lokasi = lokasiInput.value.trim();
            const zonaId = zonaSelect.value;

            if (lokasi.length < 3 || !zonaId) {
                alertBox.classList.add('hidden');
                return;
            }

            // Jika sudah ada koordinat GPS, skip validasi teks (sudah valid dari peta)
            if (document.getElementById('input-latitude').value) {
                alertBox.classList.add('hidden');
                return;
            }

            alertBox.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-700',
                'bg-amber-50', 'border-amber-200', 'text-amber-700', 'bg-red-50', 'border-red-200', 'text-red-700');
            alertBox.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-700');
            if (alertIcon) alertIcon.textContent = 'hourglass_empty';
            if (alertMessage) alertMessage.textContent = 'Memeriksa kesesuaian wilayah...';

            fetch('{{ route('masyarakat.pengaduan.validate-zona') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ lokasi, zona_id: zonaId })
            })
            .then(response => response.json())
            .then(data => {
                alertBox.classList.remove('bg-blue-50', 'border-blue-200', 'text-blue-700');
                if (data.is_valid) {
                    alertBox.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
                    if (alertIcon) alertIcon.textContent = 'check_circle';
                    if (alertMessage) alertMessage.textContent = data.message;
                } else {
                    alertBox.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-700');
                    if (alertIcon) alertIcon.textContent = 'warning';
                    if (alertMessage) alertMessage.textContent = data.message;
                }
            })
            .catch(() => {
                alertBox.classList.add('hidden');
            });
        }

        if (lokasiInput) {
            lokasiInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(validateZonaByText, 600);
            });
        }

        if (zonaSelect) {
            zonaSelect.addEventListener('change', validateZonaByText);
        }

    })();
    </script>
</x-masyarakat-form-layout>