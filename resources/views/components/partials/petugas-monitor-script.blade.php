@once
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('petugasMonitor', ({ pollUrl, initial }) => ({
                pollUrl,
                summary: initial.summary,
                petugas: initial.petugas,
                selectedId: initial.selectedId ?? null,
                lastUpdated: 'baru saja',
                timer: null,
                init() {
                    this.refresh();
                    this.timer = setInterval(() => this.refresh(), 10000);
                },
                destroy() {
                    if (this.timer) clearInterval(this.timer);
                },
                async refresh() {
                    try {
                        const response = await fetch(this.pollUrl, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        });
                        if (!response.ok) return;
                        const data = await response.json();
                        this.summary = data.summary;
                        this.petugas = data.petugas;
                        const at = new Date(data.updated_at);
                        this.lastUpdated = at.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        this.$dispatch('petugas-monitor-updated', { summary: this.summary, petugas: this.petugas });
                    } catch (e) {
                        console.error('Monitor petugas:', e);
                    }
                },
            }));
        });
    </script>
    @endpush
@endonce
