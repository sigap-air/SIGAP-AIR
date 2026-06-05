<x-app-supervisor-layout>
    @include('petugas-manajemen._show-content')

    {{-- Status Modal --}}
    <div id="status-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" onclick="closeStatusModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Ubah Status Ketersediaan</h3>
                <p class="text-sm text-gray-500 mb-4" id="status-modal-name"></p>
                <form id="status-modal-form" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="status_tersedia" id="status-modal-select"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448]/20 focus:border-[#022448] mb-4">
                        <option value="tersedia">✅ Tersedia</option>
                        <option value="sibuk">🕐 Sibuk</option>
                        <option value="tidak_aktif">❌ Tidak Aktif</option>
                    </select>
                    <div class="flex gap-2 justify-end">
                        <button type="button" onclick="closeStatusModal()"
                                class="px-4 py-2 text-sm bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">Batal</button>
                        <button type="submit"
                                class="px-4 py-2 text-sm bg-[#022448] text-white font-semibold rounded-xl hover:bg-[#033466] transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const statusRouteShow = "{{ route('supervisor.petugas.update-status', $petugas) }}";
        function openStatusModal(id, name, status) {
            document.getElementById('status-modal').classList.remove('hidden');
            document.getElementById('status-modal-name').textContent = name;
            document.getElementById('status-modal-select').value = status;
            document.getElementById('status-modal-form').action = statusRouteShow;
        }
        function closeStatusModal() {
            document.getElementById('status-modal').classList.add('hidden');
        }
    </script>
</x-app-supervisor-layout>
