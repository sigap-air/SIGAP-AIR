<x-app-supervisor-layout>
    <x-slot name="title">Monitor Petugas</x-slot>

    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Monitor Status Petugas</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Pantau status Available, On-Duty, dan Off sebelum menugaskan pengaduan.
                </p>
            </div>
            <form method="GET" action="{{ route('supervisor.monitor-petugas.index') }}" class="flex items-center gap-2">
                <select name="zona_id" onchange="this.form.submit()"
                    class="h-10 rounded-xl border border-gray-200 bg-white px-3 text-sm focus:ring-2 focus:ring-[#0F4C81]">
                    <option value="">Semua zona</option>
                    @foreach ($zonas as $zona)
                        <option value="{{ $zona->id }}" @selected($zonaId === $zona->id)>{{ $zona->nama_zona }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <x-supervisor-petugas-monitor
            :petugas-list="$petugasList"
            :summary="$summary"
            :zona-id="$zonaId"
            :compact="false"
        />

        <div class="mt-6 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4">
                <h2 class="font-bold text-gray-800">Daftar Petugas</h2>
                <p class="text-xs text-gray-500">Status diperbarui otomatis setiap 10 detik.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">NIP</th>
                            <th class="px-5 py-3">Zona</th>
                            <th class="px-5 py-3">Tugas Aktif</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Assignment</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="petugas-table-body">
                        @forelse ($petugasList as $row)
                            <tr>
                                <td class="px-5 py-3 font-medium text-gray-800">{{ $row['nama'] }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $row['nip'] }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $row['zona_nama'] }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $row['tugas_aktif'] }}</td>
                                <td class="px-5 py-3">
                                    <x-petugas-status-badge :status-key="$row['status_key']" />
                                </td>
                                <td class="px-5 py-3">
                                    @if ($row['dapat_dipilih'])
                                        <span class="text-xs font-semibold text-emerald-700">Dapat dipilih</span>
                                    @else
                                        <span class="text-xs font-semibold text-gray-400">Tidak dapat dipilih</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-gray-400">Tidak ada petugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-supervisor-layout>
