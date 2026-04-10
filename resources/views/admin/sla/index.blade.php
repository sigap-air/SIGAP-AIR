<x-app-layout>
    <x-slot name="title">Konfigurasi SLA</x-slot>

    <h1 class="text-2xl font-bold text-gray-800 mb-5">⏱️ Konfigurasi SLA per Kategori</h1>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Kategori</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Deskripsi</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">SLA (Jam)</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Status</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($kategoris as $k)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $k->nama_kategori }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $k->deskripsi ?? '—' }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full font-bold">{{ $k->sla_jam }} jam</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        @if ($k->is_active)
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">Aktif</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-xs">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('admin.sla.edit', $k) }}"
                           class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-xs hover:bg-blue-100 transition">Edit SLA</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-10 text-gray-400">Belum ada kategori</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t">{{ $kategoris->links() }}</div>
    </div>
</x-app-layout>
