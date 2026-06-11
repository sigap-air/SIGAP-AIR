<div id="status-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeStatusModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-900">Ubah Status Ketersediaan</h3>
            <p class="text-sm text-gray-500 mb-4" id="status-modal-name"></p>
            <form id="status-modal-form" method="POST">
                @csrf
                @method('PATCH')
                <select name="status_tersedia" id="status-modal-select" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mb-4">
                    <option value="tersedia">Tersedia</option>
                    <option value="sibuk">Sibuk</option>
                    <option value="tidak_aktif">Tidak Aktif</option>
                </select>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-sm bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-[#022448] text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const statusRoutes = @json(
        $petugasList->mapWithKeys(fn ($p) => [$p->id => route($routePrefix . '.update-status', $p)])
    );

    function openStatusModal(id, name, status) {
        document.getElementById('status-modal').classList.remove('hidden');
        document.getElementById('status-modal-name').textContent = name;
        document.getElementById('status-modal-select').value = status;
        document.getElementById('status-modal-form').action = statusRoutes[id];
    }
    function closeStatusModal() {
        document.getElementById('status-modal').classList.add('hidden');
    }
</script>
