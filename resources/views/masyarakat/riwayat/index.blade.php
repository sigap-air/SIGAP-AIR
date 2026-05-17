{{-- PBI-10 Riwayat Pengaduan Masyarakat --}}
<x-masyarakat-form-layout title="Riwayat Pengaduan" :back-url="route('masyarakat.dashboard')">

    {{-- Header dengan CTA --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Pengaduan Saya</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola dan pantau semua pengaduan yang telah Anda ajukan.</p>
        </div>
        <a href="{{ route('masyarakat.pengaduan.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#022448] text-white font-semibold rounded-xl hover:bg-[#1e3a5f] transition-colors shadow-sm hover:shadow-md whitespace-nowrap">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5H9a1 1 0 100 2h3v3.5a1 1 0 102 0v-3.5h3.5a1 1 0 100-2h-3.5V7z" clip-rule="evenodd" />
            </svg>
            Buat Pengaduan Baru
        </a>
    </div>

    {{-- Filter Section --}}
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1H3zm0 1h14v2H3V4zm0 3h14v2H3V7zm0 3h14v2H3v-2zm0 3h14v2H3v-2z" clip-rule="evenodd" />
            </svg>
            Filter Pengaduan
        </h2>

        <form method="GET" class="flex flex-col sm:flex-row sm:flex-wrap gap-4">
            {{-- Status --}}
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-semibold text-gray-600 mb-2">Status</label>
                <select name="status"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448] focus:border-transparent transition-all">
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditugaskan" {{ request('status') === 'ditugaskan' ? 'selected' : '' }}>Ditugaskan</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Kategori --}}
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-semibold text-gray-600 mb-2">Kategori</label>
                <select name="kategori_id"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448] focus:border-transparent transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal Dari --}}
            <div class="flex-1 min-w-40">
                <label class="block text-xs font-semibold text-gray-600 mb-2">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448] focus:border-transparent transition-all">
            </div>

            {{-- Tanggal Sampai --}}
            <div class="flex-1 min-w-40">
                <label class="block text-xs font-semibold text-gray-600 mb-2">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#022448] focus:border-transparent transition-all">
            </div>

            {{-- Buttons --}}
            <div class="flex items-end gap-3 sm:col-span-full">
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#022448] text-white font-semibold rounded-xl hover:bg-[#1e3a5f] transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1H3zm0 1h14v2H3V4zm0 3h14v2H3V7z" clip-rule="evenodd" />
                    </svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('masyarakat.pengaduan.riwayat') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 1119.414 4.414c-.195-.196-.46-.293-.707-.293h-.121l.83-.83a1 1 0 00-1.414-1.414l-2.5 2.5a1 1 0 000 1.414l2.5 2.5a1 1 0 001.414-1.414l-.83-.83h.121A9.002 9.002 0 005.049 5.209V7a1 1 0 11-2 0V3a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        @if ($pengaduan->isEmpty())
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-16 px-6">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <p class="text-gray-500 text-center">
                    <strong>Belum ada pengaduan.</strong><br>
                    <span class="text-sm">Mulai dengan membuat pengaduan baru untuk melaporkan gangguan layanan air.</span>
                </p>
            </div>
        @else
            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">No. Tiket</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Kategori</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Lokasi</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($pengaduan as $p)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            {{-- No. Tiket --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-[#022448]/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-[#022448]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="font-mono font-semibold text-gray-900">{{ $p->nomor_tiket }}</span>
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-6 py-4 text-gray-700">{{ $p->kategori->nama_kategori }}</td>

                            {{-- Lokasi --}}
                            <td class="px-6 py-4 text-gray-600 text-xs">{{ Str::limit($p->lokasi, 30) }}</td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                <x-badge-status :status="$p->status" />
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-6 py-4 text-gray-600 text-xs whitespace-nowrap">
                                {{ $p->tanggal_pengajuan->timezone('Asia/Jakarta')->format('d M Y') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('masyarakat.pengaduan.riwayat.show', $p->nomor_tiket) }}"
                                       title="Lihat Detail"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if ($p->status === 'selesai' && !$p->rating)
                                    <a href="{{ route('masyarakat.rating.create', $p) }}"
                                       title="Beri Rating"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $pengaduan->links() }}
            </div>
        @endif
    </div>

</x-masyarakat-form-layout>
