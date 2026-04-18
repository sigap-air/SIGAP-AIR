{{-- PBI-10 Riwayat Pengaduan Masyarakat --}}
<x-app-layout>
    <x-slot name="title">Riwayat Pengaduan</x-slot>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📜 Riwayat Pengaduan Saya</h1>
        <a href="{{ route('masyarakat.pengaduan.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
            + Buat Pengaduan Baru
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="bg-white rounded-xl shadow p-4 mb-5 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-36">
            <label class="block text-xs text-gray-600 mb-1">Status</label>
            <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                @foreach (['menunggu_verifikasi'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak','ditugaskan'=>'Ditugaskan','diproses'=>'Diproses','selesai'=>'Selesai'] as $val => $lab)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lab }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-xs text-gray-600 mb-1">Kategori</label>
            <select name="kategori_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Kategori</option>
                @foreach ($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ request('dari') }}" class="border rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="border rounded-lg px-3 py-2 text-sm">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">Filter</button>
            <a href="{{ route('masyarakat.riwayat.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        @if ($pengaduans->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <div class="text-5xl mb-3">📭</div>
            <p>Belum ada pengaduan.</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">No. Tiket</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Kategori</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Tanggal</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Status</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Rating</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($pengaduans as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-mono font-semibold text-blue-700">{{ $p->nomor_tiket }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $p->kategori->nama_kategori }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $p->tanggal_pengajuan->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</td>
                    <td class="px-5 py-3 text-center"><x-badge-status :status="$p->status" /></td>
                    <td class="px-5 py-3 text-center">
                        @if ($p->rating)
                            <span class="text-yellow-500 font-semibold">{{ str_repeat('⭐', $p->rating->bintang) }}</span>
                        @elseif ($p->status === 'selesai')
                            <a href="{{ route('masyarakat.rating.create', $p) }}" class="text-xs text-blue-600 hover:underline">Beri Rating</a>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('masyarakat.riwayat.show', $p) }}"
                           class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-lg hover:bg-blue-100 transition">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-4 border-t">{{ $pengaduans->links() }}</div>
        @endif
    </div>
</x-app-layout>
