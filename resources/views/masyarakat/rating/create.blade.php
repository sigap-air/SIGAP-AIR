{{-- PBI-11 Form Rating --}}
<x-app-layout>
    <x-slot name="title">Beri Penilaian</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="mb-4">
            <a href="{{ route('masyarakat.riwayat.show', $pengaduan) }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Detail</a>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <div class="text-center mb-6">
                <div class="text-4xl mb-2">⭐</div>
                <h1 class="text-xl font-bold text-gray-800">Beri Penilaian Layanan</h1>
                <p class="text-sm text-gray-500 mt-1">Pengaduan {{ $pengaduan->nomor_tiket }} telah selesai</p>
            </div>

            <form method="POST" action="{{ route('masyarakat.rating.store', $pengaduan) }}">
                @csrf

                {{-- Star Rating --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 text-center">Seberapa puas Anda?</label>
                    <div class="flex justify-center gap-2" id="starContainer">
                        @for ($i = 1; $i <= 5; $i++)
                        <button type="button" data-value="{{ $i }}"
                            class="star-btn text-4xl text-gray-200 hover:text-yellow-400 transition cursor-pointer"
                            onclick="setRating({{ $i }})">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="bintang" id="bintangInput" value="{{ old('bintang') }}" required>
                    <p class="text-center text-xs text-gray-400 mt-2" id="ratingLabel">Klik bintang untuk memberi nilai</p>
                    @error('bintang') <p class="text-center text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Komentar --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Komentar <span class="text-gray-400">(opsional)</span>
                    </label>
                    <textarea name="komentar" rows="4"
                        placeholder="Bagikan pengalaman Anda tentang pelayanan PDAM..."
                        class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('komentar') }}</textarea>
                    @error('komentar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-xl font-bold transition">
                    Kirim Penilaian
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const labels = ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];
        function setRating(val) {
            document.getElementById('bintangInput').value = val;
            document.getElementById('ratingLabel').textContent = labels[val];
            document.querySelectorAll('.star-btn').forEach((btn, i) => {
                btn.classList.toggle('text-yellow-400', i < val);
                btn.classList.toggle('text-gray-200', i >= val);
            });
        }
    </script>
    @endpush
</x-app-layout>
