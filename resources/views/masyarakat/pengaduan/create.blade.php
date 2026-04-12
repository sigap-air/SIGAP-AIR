{{--
    PBI-04 — Form Pengajuan Pengaduan
    TANGGUNG JAWAB: Sanitra Savitri
--}}
<x-masyarakat-form-layout title="Pengaduan Baru">
    <h1 class="mb-6 text-xl font-bold text-brand">Pengaduan Baru</h1>

    <form action="{{ route('masyarakat.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col">
        @csrf

        <x-sigap-form-field label="Kategori Pengaduan" name="kategori_id" :required="true">
            <select name="kategori_id"
                class="w-full rounded-xl border-0 bg-gray-200 px-4 py-3 text-gray-800 shadow-sm focus:ring-2 focus:ring-brand"
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
            <select name="zona_id"
                class="w-full rounded-xl border-0 bg-gray-200 px-4 py-3 text-gray-800 shadow-sm focus:ring-2 focus:ring-brand"
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
            <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                placeholder="Alamat atau patokan lokasi"
                class="w-full rounded-xl border-0 bg-gray-200 px-4 py-3 text-gray-800 placeholder-gray-500 shadow-sm focus:ring-2 focus:ring-brand"
                required />
        </x-sigap-form-field>

        <x-sigap-form-field label="Deskripsi Masalah" name="deskripsi" :required="true">
            <textarea name="deskripsi" rows="4" placeholder="Jelaskan kendala secara detail"
                class="w-full resize-y rounded-xl border-0 bg-gray-200 px-4 py-3 text-gray-800 placeholder-gray-500 shadow-sm focus:ring-2 focus:ring-brand"
                required>{{ old('deskripsi') }}</textarea>
            <p class="mt-1 text-xs text-gray-500">Minimal 20 karakter.</p>
        </x-sigap-form-field>

        <x-sigap-image-upload label="Bukti Foto" name="foto_bukti" :optional="true" />

        <div class="mt-2 flex flex-col gap-3">
            <x-sigap-action-button variant="primary" type="submit">Kirim Pengaduan</x-sigap-action-button>
            <x-sigap-action-button variant="secondary" href="{{ route('masyarakat.dashboard') }}">Batal</x-sigap-action-button>
        </div>
    </form>
</x-masyarakat-form-layout>
