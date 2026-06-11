<x-app-admin-layout>

{{-- Breadcrumb --}}
<div class="mb-6">
    <a href="{{ route('admin.sla.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-[#022448] transition-colors group">
        <span class="material-symbols-outlined text-lg group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
        Kembali ke Daftar SLA
    </a>
</div>

<div class="max-w-2xl mx-auto">
    {{-- Header Card --}}
    <div class="bg-gradient-to-br from-[#022448] to-[#1e3a5f] rounded-2xl p-6 mb-6 text-white shadow-xl shadow-[#022448]/10">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/10 backdrop-blur rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl">timer</span>
            </div>
            <div>
                <h1 class="text-xl font-bold font-headline">{{ $kategori->nama_kategori }}</h1>
                <p class="text-blue-200 text-sm mt-0.5">Edit Batas Waktu SLA</p>
            </div>
        </div>
        @if(isset($pengaduanStats))
        <div class="grid grid-cols-4 gap-3 mt-6">
            <div class="bg-white/10 backdrop-blur rounded-xl p-3 text-center">
                <p class="text-2xl font-black">{{ $pengaduanStats['total'] }}</p>
                <p class="text-[10px] text-blue-200 uppercase tracking-wide mt-1">Total</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-3 text-center">
                <p class="text-2xl font-black text-sky-300">{{ $pengaduanStats['berjalan'] }}</p>
                <p class="text-[10px] text-blue-200 uppercase tracking-wide mt-1">Berjalan</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-3 text-center">
                <p class="text-2xl font-black text-red-300">{{ $pengaduanStats['overdue'] }}</p>
                <p class="text-[10px] text-blue-200 uppercase tracking-wide mt-1">Overdue</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-3 text-center">
                <p class="text-2xl font-black text-emerald-300">{{ $pengaduanStats['terpenuhi'] }}</p>
                <p class="text-[10px] text-blue-200 uppercase tracking-wide mt-1">Terpenuhi</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <span class="material-symbols-outlined text-gray-400 text-lg">settings</span>
                Pengaturan SLA
            </h2>
        </div>
        <form method="POST" action="{{ route('admin.sla.update', $kategori) }}" class="p-6" id="form-edit-sla">
            @csrf @method('PATCH')
            <div class="mb-6">
                <label for="sla_jam" class="block text-sm font-semibold text-gray-700 mb-2">
                    Batas Waktu SLA (Jam) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 text-xl">schedule</span>
                    </div>
                    <input type="number" name="sla_jam" id="sla_jam"
                           value="{{ old('sla_jam', $kategori->sla_jam) }}" min="1" max="720"
                           class="w-full pl-12 pr-16 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] text-lg font-bold text-gray-900 transition-all" required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <span class="text-sm text-gray-400 font-medium">jam</span>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 mt-3">
                    <button type="button" onclick="document.getElementById('sla_jam').value=6" class="px-3 py-1 bg-red-50 text-red-700 rounded-lg text-xs font-medium hover:bg-red-100 transition ring-1 ring-red-200">6 jam</button>
                    <button type="button" onclick="document.getElementById('sla_jam').value=12" class="px-3 py-1 bg-orange-50 text-orange-700 rounded-lg text-xs font-medium hover:bg-orange-100 transition ring-1 ring-orange-200">12 jam</button>
                    <button type="button" onclick="document.getElementById('sla_jam').value=24" class="px-3 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-medium hover:bg-amber-100 transition ring-1 ring-amber-200">24 jam</button>
                    <button type="button" onclick="document.getElementById('sla_jam').value=48" class="px-3 py-1 bg-sky-50 text-sky-700 rounded-lg text-xs font-medium hover:bg-sky-100 transition ring-1 ring-sky-200">48 jam</button>
                    <button type="button" onclick="document.getElementById('sla_jam').value=72" class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium hover:bg-indigo-100 transition ring-1 ring-indigo-200">72 jam</button>
                </div>
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">info</span>
                    Minimal 1 jam, maksimal 720 jam (30 hari)
                </p>
                @error('sla_jam')
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">error</span> {{ $message }}
                    </p>
                @enderror
            </div>
            <div class="mb-8 p-4 bg-gray-50 rounded-xl">
                <label class="flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-500">toggle_on</span>
                        <div>
                            <span class="text-sm font-semibold text-gray-700">Kategori Aktif</span>
                            <p class="text-xs text-gray-400 mt-0.5">Kategori nonaktif tidak akan muncul di form pengaduan</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active"
                               {{ old('is_active', $kategori->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#022448]/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#022448]"></div>
                    </div>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" id="btn-simpan-sla"
                        class="flex-1 inline-flex items-center justify-center gap-2 bg-[#022448] text-white py-3 rounded-xl font-semibold hover:bg-[#1e3a5f] transition-all duration-200 shadow-lg shadow-[#022448]/20">
                    <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
                </button>
                <a href="{{ route('admin.sla.index') }}"
                   class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200">
                    <span class="material-symbols-outlined text-lg">close</span> Batal
                </a>
            </div>
        </form>
    </div>

    <div class="mt-4 p-4 bg-amber-50 border border-amber-100 rounded-xl flex items-center gap-3">
        <span class="material-symbols-outlined text-amber-600">history</span>
        <p class="text-xs text-amber-700">
            <strong>Nilai saat ini:</strong> {{ $kategori->sla_jam }} jam.
            Perubahan SLA hanya berlaku untuk pengaduan baru. Pengaduan yang sudah ada tetap menggunakan batas waktu lama.
        </p>
    </div>
</div>

</x-app-admin-layout>
