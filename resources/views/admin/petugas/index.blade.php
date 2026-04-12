<x-app-layout>
    <x-slot name="title">Manajemen Petugas</x-slot>

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-2xl font-bold text-gray-800">👷 Manajemen Petugas Teknis</h1>
        <a href="{{ route('admin.petugas.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
            + Tambah Petugas
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Nama</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">No. Pegawai</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Email</th>
                    <th class="text-left px-5 py-3 text-gray-600 font-semibold">Zona</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Ketersediaan</th>
                    <th class="text-center px-5 py-3 text-gray-600 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($petugas as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $p->user->name }}</td>
                    <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $p->nomor_pegawai }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $p->user->email }}</td>
                    <td class="px-5 py-3 text-xs text-gray-600">
                        {{ $p->zonas->pluck('nama_zona')->join(', ') ?: '—' }}
                    </td>
                    <td class="px-5 py-3 text-center">
                        @php
                            $colors = ['tersedia'=>'green','sibuk'=>'yellow','tidak_aktif'=>'gray'];
                            $c = $colors[$p->status_ketersediaan] ?? 'gray';
                        @endphp
                        <span class="bg-{{ $c }}-100 text-{{ $c }}-700 px-2 py-0.5 rounded-full text-xs font-medium">
                            {{ ucwords(str_replace('_',' ',$p->status_ketersediaan)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center flex justify-center gap-2">
                        <a href="{{ route('admin.petugas.edit', $p) }}"
                           class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-xs hover:bg-blue-100 transition">Edit</a>
                        <form method="POST" action="{{ route('admin.petugas.destroy', $p) }}" onsubmit="return confirm('Nonaktifkan petugas ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-50 text-red-700 px-3 py-1 rounded-lg text-xs hover:bg-red-100 transition">
                                Nonaktifkan
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-400">Belum ada data petugas</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t">{{ $petugas->links() }}</div>
    </div>
</x-app-layout>
