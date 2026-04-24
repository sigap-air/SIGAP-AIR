{{-- PBI-07 Detail Tugas + Update Status — styling identik admin --}}
<x-app-petugas-layout>

{{-- Breadcrumb --}}
<div class="mb-6">
    <a href="{{ route('petugas.tugas.index') }}" class="inline-flex items-center gap-1 text-sm text-[#022448] hover:text-[#1e3a5f] font-medium transition-colors">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Kembali ke Daftar Tugas
    </a>
</div>

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center gap-3 flex-wrap">
        <h1 class="text-2xl font-bold text-gray-900 font-headline">{{ $tugas->pengaduan->nomor_tiket }}</h1>
        @php $sla = $tugas->pengaduan->sla; $isOverdue = $sla && $sla->is_overdue; @endphp
        @if ($tugas->status_assignment === 'selesai')
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                <span class="material-symbols-outlined text-sm">check_circle</span> Selesai
            </span>
        @elseif ($isOverdue)
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                <span class="material-symbols-outlined text-sm">warning</span> Overdue
            </span>
        @elseif ($tugas->status_assignment === 'diproses')
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-700 border border-sky-200">
                <span class="material-symbols-outlined text-sm">autorenew</span> Sedang Diproses
            </span>
        @else
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                <span class="material-symbols-outlined text-sm">schedule</span> Ditugaskan
            </span>
        @endif
    </div>
    <p class="text-sm text-gray-500 mt-1">Detail pengaduan dan formulir pembaruan status penanganan.</p>
</div>

{{-- Progress Stepper --}}
@php
    $steps = [
        ['label' => 'Ditugaskan', 'icon' => 'assignment_ind', 'done' => true],
        ['label' => 'Diproses',   'icon' => 'construction',    'done' => in_array($tugas->status_assignment, ['diproses', 'selesai'])],
        ['label' => 'Selesai',    'icon' => 'verified',        'done' => $tugas->status_assignment === 'selesai'],
    ];
@endphp
<div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-5 mb-6">
    <div class="flex items-center justify-between">
        @foreach ($steps as $i => $step)
            <div class="flex items-center gap-2 {{ $step['done'] ? '' : 'opacity-40' }}">
                <div class="w-9 h-9 rounded-full flex items-center justify-center {{ $step['done'] ? 'bg-[#022448] text-white' : 'bg-gray-200 text-gray-500' }} transition-all">
                    <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' {{ $step['done'] ? 1 : 0 }};">{{ $step['icon'] }}</span>
                </div>
                <span class="text-sm font-medium {{ $step['done'] ? 'text-gray-900' : 'text-gray-400' }}">{{ $step['label'] }}</span>
            </div>
            @if ($i < count($steps) - 1)
                <div class="flex-1 h-0.5 mx-3 rounded {{ $steps[$i + 1]['done'] ? 'bg-[#022448]' : 'bg-gray-200' }} transition-all"></div>
            @endif
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Col 1-2: Detail Pengaduan --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Detail Info --}}
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#022448]">info</span>
                Detail Pengaduan
            </h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Kategori</dt>
                    <dd class="font-medium text-gray-900 mt-1">{{ $tugas->pengaduan->kategori->nama_kategori }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Zona</dt>
                    <dd class="font-medium text-gray-900 mt-1">{{ $tugas->pengaduan->zona->nama_zona }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Lokasi</dt>
                    <dd class="font-medium text-gray-900 mt-1">{{ $tugas->pengaduan->lokasi }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Deskripsi Masalah</dt>
                    <dd class="text-gray-700 mt-1 leading-relaxed">{{ $tugas->pengaduan->deskripsi }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pelapor</dt>
                    <dd class="font-medium text-gray-900 mt-1">{{ $tugas->pengaduan->pelapor->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">No. Telepon</dt>
                    <dd class="font-medium text-gray-900 mt-1">{{ $tugas->pengaduan->pelapor->no_telepon ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Foto Bukti Pelapor --}}
        @if ($tugas->pengaduan->foto_bukti)
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-600">photo_camera</span>
                Foto Masalah dari Pelapor
            </h2>
            <a href="{{ asset('storage/' . $tugas->pengaduan->foto_bukti) }}" target="_blank" class="group block">
                <img src="{{ asset('storage/' . $tugas->pengaduan->foto_bukti) }}"
                     alt="Foto Bukti" class="w-full max-h-72 object-cover rounded-xl border border-gray-200 transition group-hover:opacity-80">
            </a>
        </div>
        @endif

        {{-- Foto Penanganan Sebelumnya --}}
        @if ($tugas->foto_hasil)
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#022448]">image</span>
                Foto Dokumentasi Penanganan
            </h2>
            <a href="{{ asset('storage/' . $tugas->foto_hasil) }}" target="_blank" class="group block">
                <img src="{{ asset('storage/' . $tugas->foto_hasil) }}"
                     alt="Foto Hasil" class="w-full max-h-72 object-cover rounded-xl border border-gray-200 transition group-hover:opacity-80">
            </a>
        </div>
        @endif
    </div>

    {{-- Col 3: Sidebar --}}
    <div class="lg:col-span-1 space-y-4">
        {{-- SLA Info --}}
        @if ($sla)
        <div class="rounded-2xl border p-4 {{ $isOverdue ? 'bg-red-50 border-red-200' : 'bg-amber-50 border-amber-200' }}">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-lg {{ $isOverdue ? 'text-red-600' : 'text-amber-600' }}">{{ $isOverdue ? 'warning' : 'timer' }}</span>
                <p class="font-semibold text-sm {{ $isOverdue ? 'text-red-700' : 'text-amber-700' }}">
                    {{ $isOverdue ? 'SLA TERLAMPAUI!' : 'Batas Waktu SLA' }}
                </p>
            </div>
            <p class="text-xs {{ $isOverdue ? 'text-red-600' : 'text-amber-600' }}">
                {{ $sla->deadline->translatedFormat('d M Y H:i') }} WIB
                ({{ $sla->deadline->diffForHumans() }})
            </p>
        </div>
        @endif

        {{-- Instruksi Supervisor --}}
        @if ($tugas->instruksi)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-amber-600 text-lg">description</span>
                <p class="font-semibold text-sm text-amber-700">Instruksi Supervisor</p>
            </div>
            <p class="text-xs text-amber-800 mt-1">{{ $tugas->instruksi }}</p>
        </div>
        @endif

        {{-- Form Update --}}
        @if ($tugas->status_assignment !== 'selesai')
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-5" x-data="{
            status: '{{ $tugas->status_assignment === 'ditugaskan' ? 'diproses' : 'selesai' }}',
            previewUrl: null,
            fileName: null,
            isDragging: false,
            showConfirm: false,
            handleFile(file) {
                if (!file) return;
                if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                    alert('Format file harus JPG atau PNG.');
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB.');
                    return;
                }
                this.fileName = file.name;
                this.previewUrl = URL.createObjectURL(file);
                $refs.fileInput.files = this.createFileList(file);
            },
            createFileList(file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                return dt.files;
            },
            handleDrop(e) {
                this.isDragging = false;
                const file = e.dataTransfer.files[0];
                this.handleFile(file);
            },
            submitForm(e) {
                if (this.status === 'selesai') {
                    e.preventDefault();
                    this.showConfirm = true;
                }
            },
            confirmSubmit() {
                this.showConfirm = false;
                $refs.form.submit();
            }
        }">
            <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#022448]">sync</span>
                Update Status
            </h2>

            <form method="POST" action="{{ route('petugas.tugas.update', $tugas) }}" enctype="multipart/form-data" x-ref="form" @submit="submitForm">
                @csrf
                @method('PATCH')

                {{-- Status Select --}}
                <div class="mb-4">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status_assignment" x-model="status" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448] focus:border-transparent transition" required>
                        @if ($tugas->status_assignment === 'ditugaskan')
                            <option value="diproses">🔧 Sedang Diproses</option>
                        @endif
                        <option value="selesai">✅ Selesai</option>
                    </select>
                </div>

                {{-- Foto Upload with Drag & Drop --}}
                <div class="mb-4">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">
                        Foto Dokumentasi
                        <span x-show="status === 'selesai'" class="text-red-500">*</span>
                    </label>

                    {{-- Drag Drop Zone --}}
                    <div
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        @click="$refs.fileInput.click()"
                        :class="isDragging ? 'border-[#022448] bg-blue-50' : 'border-gray-300 bg-gray-50 hover:bg-gray-100'"
                        class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all">

                        {{-- Preview --}}
                        <template x-if="previewUrl">
                            <div>
                                <img :src="previewUrl" class="max-h-40 mx-auto rounded-lg mb-2 shadow-sm">
                                <p class="text-xs text-[#022448] font-medium" x-text="fileName"></p>
                                <p class="text-xs text-gray-400 mt-0.5">Klik atau seret untuk mengganti</p>
                            </div>
                        </template>

                        {{-- Empty State --}}
                        <template x-if="!previewUrl">
                            <div>
                                <span class="material-symbols-outlined text-3xl text-gray-400 mb-1">cloud_upload</span>
                                <p class="text-sm text-gray-600 font-medium">Seret foto ke sini</p>
                                <p class="text-xs text-gray-400 mt-0.5">atau klik untuk memilih file</p>
                                <p class="text-xs text-gray-400 mt-1">JPG/PNG, maks 5MB</p>
                            </div>
                        </template>
                    </div>

                    <input type="file" name="foto_hasil" accept="image/jpeg,image/png" x-ref="fileInput"
                           @change="handleFile($event.target.files[0])"
                           class="hidden">
                    @error('foto_hasil') <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><span class="material-symbols-outlined text-xs">error</span> {{ $message }}</p> @enderror
                </div>

                {{-- Catatan --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Catatan Tindakan</label>
                    <textarea name="catatan_penanganan" rows="3"
                        placeholder="Apa yang sudah dilakukan di lapangan..."
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#022448] focus:border-transparent transition resize-none">{{ old('catatan_penanganan', $tugas->catatan_penanganan) }}</textarea>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-[#022448] text-white py-3 rounded-xl font-semibold hover:bg-[#1e3a5f] transition-all shadow-sm hover:shadow-md">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan Update
                </button>
            </form>

            {{-- Konfirmasi Dialog (untuk status Selesai) --}}
            <template x-teleport="body">
                <div x-show="showConfirm" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
                    <div x-show="showConfirm" x-transition class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100">
                            <span class="material-symbols-outlined text-amber-600 text-3xl">help</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 font-headline">Selesaikan Tugas?</h3>
                        <p class="text-sm text-gray-500 mb-6">Status akan berubah menjadi <strong>Selesai</strong> dan pelapor akan diberitahu. Tindakan ini tidak dapat dibatalkan.</p>
                        <div class="flex gap-3">
                            <button @click="showConfirm = false" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button @click="confirmSubmit()" class="flex-1 px-4 py-2.5 bg-[#022448] text-white rounded-xl text-sm font-semibold hover:bg-[#1e3a5f] transition shadow-sm">
                                Ya, Selesaikan
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        @else
        {{-- Status Selesai Card --}}
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-center">
            <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100">
                <span class="material-symbols-outlined text-emerald-600 text-3xl" style="font-variation-settings: 'FILL' 1;">verified</span>
            </div>
            <p class="font-bold text-emerald-700 text-lg font-headline">Tugas Selesai</p>
            <p class="text-xs text-emerald-600 mt-1">{{ $tugas->tanggal_selesai?->translatedFormat('d M Y H:i') }} WIB</p>
            @if ($tugas->catatan_penanganan)
            <div class="mt-3 bg-white/60 rounded-xl p-3 text-left">
                <p class="text-xs font-semibold text-emerald-700 mb-1">Catatan:</p>
                <p class="text-xs text-gray-600">{{ $tugas->catatan_penanganan }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

</x-app-petugas-layout>
