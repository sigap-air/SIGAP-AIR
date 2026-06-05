{{-- Form catatan assignment dari halaman Data Petugas --}}
<div id="tugaskan-petugas" class="mb-6 rounded-2xl border border-[#022448]/20 bg-gradient-to-br from-[#022448]/5 to-white p-6 shadow-sm">
    <div class="mb-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-2xl text-[#022448]">assignment_ind</span>
        <div>
            <h2 class="text-lg font-bold text-gray-900">Tugaskan Petugas + Catatan Assignment</h2>
            <p class="mt-1 text-sm text-gray-600">
                Pilih pengaduan di zona <strong>{{ $petugas->zona?->nama_zona }}</strong>, tulis instruksi perbaikan,
                lalu simpan — petugas <strong>{{ $petugas->user?->name }}</strong> akan menerima catatan ini.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('supervisor.petugas.assign', $petugas) }}" class="space-y-4">
        @csrf

        <div>
            <label class="mb-1 block text-sm font-semibold text-gray-700">
                Pilih Pengaduan <span class="text-red-500">*</span>
            </label>
            <select name="pengaduan_id" required
                class="h-11 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm focus:ring-2 focus:ring-[#022448]">
                <option value="">— Pilih pengaduan yang sudah disetujui —</option>
                @foreach ($pengaduanMenungguTugas as $p)
                    <option value="{{ $p->id }}" @selected(old('pengaduan_id') == $p->id)>
                        {{ $p->nomor_tiket }} · {{ $p->kategori->nama_kategori }} · {{ Str::limit($p->lokasi, 40) }}
                    </option>
                @endforeach
            </select>
            @error('pengaduan_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-4">
            <label class="mb-1 flex items-center gap-2 text-sm font-semibold text-gray-800">
                <span class="material-symbols-outlined text-lg text-amber-600">edit_note</span>
                Catatan Assignment
                <span class="font-normal text-gray-500">(instruksi perbaikan untuk petugas)</span>
            </label>
            <textarea name="instruksi" rows="4"
                placeholder="Contoh: Periksa tekanan pipa, bawa alat ukur, koordinasi dengan RT setempat..."
                class="mt-2 w-full rounded-xl border border-amber-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-amber-400">{{ old('instruksi') }}</textarea>
            @error('instruksi')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-gray-700">
                Jadwal Penanganan <span class="text-red-500">*</span>
            </label>
            <input type="datetime-local" name="jadwal_penanganan"
                value="{{ old('jadwal_penanganan') }}"
                min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}"
                class="h-11 w-full rounded-xl border border-gray-200 bg-white px-3 focus:ring-2 focus:ring-[#022448]"
                required>
            @error('jadwal_penanganan')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#022448] py-3 text-sm font-semibold text-white hover:bg-[#1e3a5f] sm:w-auto sm:px-8">
            <span class="material-symbols-outlined text-lg">send</span>
            Simpan & Tugaskan Petugas
        </button>
    </form>
</div>
