{{-- 
    PBI-04 — Form Pengajuan Pengaduan
    TANGGUNG JAWAB: Sanitra Savitri
--}}

<x-masyarakat-form-layout title="Pengaduan Baru" :back-url="route('masyarakat.dashboard')">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pengaduan Baru</h1>
        <p class="mt-1 text-sm text-gray-500">Lengkapi form berikut untuk mengirim laporan gangguan layanan air.</p>
    </div>

    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
        <form action="{{ route('masyarakat.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col" data-confirm="Yakin ingin mengirim pengaduan ini?">
            @csrf

            <x-sigap-form-field label="Kategori Pengaduan" name="kategori_id" :required="true">
                <select name="kategori_id"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm focus:ring-2 focus:ring-brand"
                    required>
                    <option value="" disabled {{ old('kategori_id') ? '' : 'selected' }}>Pilih kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" {{ (string) old('kategori_id') === (string) $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }} (SLA {{ $k->sla_jam }} jam)
                        </option>
                    @endforeach
                </select>
            </x-sigap-form-field>

            <x-sigap-form-field label="Zona Wilayah" name="zona_id" :required="true">
                <select name="zona_id" id="zona_id"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm focus:ring-2 focus:ring-brand"
                    required>
                    <option value="" disabled {{ old('zona_id') ? '' : 'selected' }}>Pilih zona</option>
                    @foreach ($zonas as $z)
                        <option value="{{ $z->id }}" {{ (string) old('zona_id') === (string) $z->id ? 'selected' : '' }}>
                            {{ $z->nama_zona }}
                        </option>
                    @endforeach
                </select>
            </x-sigap-form-field>

            <x-sigap-form-field label="Lokasi" name="lokasi" :required="true">
                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                    placeholder="Alamat atau patokan lokasi"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 placeholder-gray-500 shadow-sm focus:ring-2 focus:ring-brand"
                    required />
                <div id="zona-validation-alert" class="hidden mt-2 p-3 rounded-xl border text-sm font-medium flex items-start gap-2 transition-all">
                    <span class="material-symbols-outlined text-lg alert-icon">info</span>
                    <span class="alert-message">Memeriksa kesesuaian wilayah...</span>
                </div>
            </x-sigap-form-field>

            <x-sigap-form-field label="Nomor Telepon" name="no_telepon" :required="true">
                <input type="tel" name="no_telepon" value="{{ old('no_telepon') }}"
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
                    required>{{ old('deskripsi') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Minimal 20 karakter.</p>
            </x-sigap-form-field>

            <x-sigap-image-upload label="Bukti Foto" name="foto_bukti" :required="true" :optional="false" />

            <div class="mt-4">
                <x-sigap-action-button variant="primary" type="submit">Kirim Pengaduan</x-sigap-action-button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lokasiInput = document.getElementById('lokasi');
            const zonaSelect = document.getElementById('zona_id');
            const alertBox = document.getElementById('zona-validation-alert');
            const alertIcon = alertBox.querySelector('.alert-icon');
            const alertMessage = alertBox.querySelector('.alert-message');
            
            let debounceTimer;

            function validateZona() {
                const lokasi = lokasiInput.value.trim();
                const zonaId = zonaSelect.value;

                if (lokasi.length < 3 || !zonaId) {
                    alertBox.classList.add('hidden');
                    return;
                }

                // Tampilkan state loading
                alertBox.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-700', 'bg-amber-50', 'border-amber-200', 'text-amber-700', 'bg-red-50', 'border-red-200', 'text-red-700');
                alertBox.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-700');
                alertIcon.textContent = 'hourglass_empty';
                alertMessage.textContent = 'Memeriksa kesesuaian wilayah...';

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
                        alertIcon.textContent = 'check_circle';
                        alertMessage.textContent = data.message;
                    } else {
                        alertBox.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-700');
                        alertIcon.textContent = 'warning';
                        alertMessage.textContent = data.message;
                    }
                })
                .catch(error => {
                    alertBox.classList.remove('bg-blue-50', 'border-blue-200', 'text-blue-700');
                    alertBox.classList.add('hidden');
                });
            }

            lokasiInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(validateZona, 500);
            });

            zonaSelect.addEventListener('change', validateZona);
        });
    </script>
    @endpush
</x-masyarakat-form-layout>