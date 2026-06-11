# 🎨 SIGAP-AIR — Frontend Design Guide

> **Versi:** 1.0 | **Tanggal:** Mei 2026  
> Dokumen ini adalah panduan desain frontend wajib bagi seluruh developer SIGAP-AIR.  
> Baca dan pahami seluruh bagian sebelum mulai menulis kode Blade/CSS apapun.

---

## Daftar Isi

1. [Design Philosophy](#1-design-philosophy)
2. [Design Token — Warna, Tipografi, Spacing](#2-design-token)
3. [Komponen Global](#3-komponen-global)
4. [Layout & Navigasi per Role](#4-layout--navigasi-per-role)
5. [PBI-01 — Manajemen Data Pelanggan (Arthur)](#5-pbi-01--manajemen-data-pelanggan)
6. [PBI-02 — Kategori & SLA (Arthur)](#6-pbi-02--kategori--sla)
7. [PBI-03 — Zona Wilayah & Mapping Petugas (Arthur)](#7-pbi-03--zona-wilayah--mapping-petugas)
8. [PBI-04 — Pengajuan Pengaduan (Sanitra)](#8-pbi-04--pengajuan-pengaduan)
9. [PBI-05 — Verifikasi Pengaduan (Sanitra)](#9-pbi-05--verifikasi-pengaduan)
10. [PBI-06 — Assignment Petugas (Sanitra)](#10-pbi-06--assignment-petugas)
11. [PBI-07 — Tracking & Dokumentasi Penanganan (Falah)](#11-pbi-07--tracking--dokumentasi-penanganan)
12. [PBI-08 — Manajemen Profil Pengguna (Falah)](#12-pbi-08--manajemen-profil-pengguna)
13. [PBI-09 — SLA & Eskalasi Otomatis (Falah)](#13-pbi-09--sla--eskalasi-otomatis)
14. [PBI-10 — Riwayat Pengaduan (Amanda)](#14-pbi-10--riwayat-pengaduan)
15. [PBI-11 — Rating & Feedback (Amanda)](#15-pbi-11--rating--feedback)
16. [PBI-12 — Notifikasi In-App (Amanda)](#16-pbi-12--notifikasi-in-app)
17. [PBI-13 — Filter & Pencarian Lanjutan (Imanuel)](#17-pbi-13--filter--pencarian-lanjutan)
18. [PBI-14 — Laporan Rekap & Export PDF (Imanuel)](#18-pbi-14--laporan-rekap--export-pdf)
19. [PBI-15 — Dashboard Statistik (Imanuel)](#19-pbi-15--dashboard-statistik)
20. [PBI-16 — Manajemen User & Role (Farisha)](#20-pbi-16--manajemen-user--role)
21. [PBI-17 — Manajemen Petugas Teknis (Farisha)](#21-pbi-17--manajemen-petugas-teknis)
22. [PBI-18 — Laporan Kinerja Petugas & Export Excel (Farisha)](#22-pbi-18--laporan-kinerja-petugas--export-excel)
23. [PBI-19 — Authentication (Semua Developer)](#23-pbi-19--authentication)
24. [Panduan UX Writing](#24-panduan-ux-writing)
25. [Checklist Review Frontend](#25-checklist-review-frontend)

---

## 1. Design Philosophy

SIGAP-AIR adalah sistem informasi internal PDAM yang digunakan oleh empat kelompok pengguna dengan kebutuhan berbeda: masyarakat awam di HP, petugas lapangan di kondisi terburu-buru, supervisor di kantor dengan banyak data, dan admin IT yang butuh kontrol penuh. Desain harus melayani semua kebutuhan tersebut sekaligus.

### Prinsip Utama

| Prinsip | Artinya dalam Kode |
|---|---|
| **Clarity First** | Informasi paling penting tampil paling besar dan paling awal |
| **Action-Oriented** | Tombol aksi utama selalu mudah ditemukan, tidak tersembunyi |
| **Status Visibility** | Status pengaduan selalu terlihat dengan warna yang konsisten di seluruh sistem |
| **Mobile-First** | Semua halaman harus fungsional di layar 375px (HP Android entry-level) |
| **Feedback Loops** | Setiap aksi user harus mendapat respons visual segera (loading, success, error) |

### Tone Visual

SIGAP-AIR menggunakan tone **Industrial Utilitarian** — bersih, padat, fungsional, tidak dekoratif berlebihan. Seperti dashboard operasional profesional, bukan aplikasi consumer. Palet warna didominasi biru tua dengan aksen status yang tajam (merah, hijau, kuning).

---

## 2. Design Token

Semua nilai di bawah ini wajib digunakan melalui kelas Tailwind yang sudah dikonfigurasi. **Jangan hardcode warna HEX langsung di template.**

### 2.1 Warna

```
BRAND
  Primary       : blue-700   (#1d4ed8) — tombol utama, link aktif, header
  Primary Hover : blue-800   (#1e40af)
  Primary Light : blue-50    (#eff6ff) — background section highlight

SEMANTIC — STATUS PENGADUAN (gunakan konsisten di SELURUH sistem)
  menunggu_verifikasi  : yellow-100 text (yellow-800) border (yellow-300)
  disetujui            : blue-100   text (blue-800)   border (blue-300)
  ditolak              : red-100    text (red-800)     border (red-300)
  ditugaskan           : purple-100 text (purple-800)  border (purple-300)
  sedang_diproses      : orange-100 text (orange-800)  border (orange-300)
  selesai              : green-100  text (green-800)   border (green-300)

SEMANTIC — SLA
  berjalan   : blue-500
  terpenuhi  : green-500
  overdue    : red-600  (BOLD — ini kondisi kritis)

SEMANTIC — PETUGAS
  tersedia      : green-500
  sibuk         : yellow-500
  tidak_aktif   : gray-400

NEUTRAL
  Background   : gray-50   (#f9fafb)
  Surface      : white     (#ffffff)
  Border       : gray-200  (#e5e7eb)
  Text Primary : gray-900  (#111827)
  Text Muted   : gray-500  (#6b7280)
  Divider      : gray-100  (#f3f4f6)
```

### 2.2 Tipografi

```
Font Stack (sudah include di layout utama):
  Heading  : font-semibold atau font-bold — gunakan kelas Tailwind saja
  Body     : font-normal text-sm (14px) untuk tabel dan form
  Caption  : text-xs text-gray-500 untuk label, hint, timestamp

Ukuran Heading yang boleh digunakan:
  Page Title    : text-2xl font-bold text-gray-900
  Section Title : text-lg font-semibold text-gray-800
  Card Title    : text-base font-semibold text-gray-700
  Label         : text-sm font-medium text-gray-700
```

### 2.3 Spacing & Layout

```
Container utama  : max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
Card padding     : p-6
Section gap      : space-y-6
Form field gap   : space-y-4
Tabel padding    : px-6 py-4 (th), px-6 py-4 (td)
```

### 2.4 Border Radius

```
Button    : rounded-lg
Card      : rounded-xl
Badge     : rounded-full
Input     : rounded-lg
Modal     : rounded-2xl
```

---

## 3. Komponen Global

Komponen berikut digunakan di seluruh PBI. Buat sebagai Blade component di `resources/views/components/`.

### 3.1 Badge Status Pengaduan

**File:** `resources/views/components/status-badge.blade.php`

```blade
@props(['status'])
@php
$config = [
    'menunggu_verifikasi' => ['bg-yellow-100 text-yellow-800 ring-yellow-200',  'Menunggu Verifikasi'],
    'disetujui'           => ['bg-blue-100 text-blue-800 ring-blue-200',        'Disetujui'],
    'ditolak'             => ['bg-red-100 text-red-800 ring-red-200',            'Ditolak'],
    'ditugaskan'          => ['bg-purple-100 text-purple-800 ring-purple-200',  'Ditugaskan'],
    'sedang_diproses'     => ['bg-orange-100 text-orange-800 ring-orange-200',  'Sedang Diproses'],
    'selesai'             => ['bg-green-100 text-green-800 ring-green-200',     'Selesai'],
];
[$classes, $label] = $config[$status] ?? ['bg-gray-100 text-gray-600 ring-gray-200', ucfirst($status)];
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $classes }}">
    {{ $label }}
</span>
```

**Penggunaan:** `<x-status-badge :status="$pengaduan->status" />`

---

### 3.2 Badge Status SLA

**File:** `resources/views/components/sla-badge.blade.php`

```blade
@props(['status', 'sisaJam' => null])
@php
$config = [
    'berjalan'  => ['bg-blue-100 text-blue-700',   'Berjalan'],
    'terpenuhi' => ['bg-green-100 text-green-700',  'Terpenuhi'],
    'overdue'   => ['bg-red-100 text-red-700 font-semibold', 'OVERDUE'],
];
[$classes, $label] = $config[$status] ?? ['bg-gray-100 text-gray-600', $status];
@endphp
<span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs {{ $classes }}">
    @if($status === 'overdue') ⚠️ @endif
    {{ $label }}
    @if($sisaJam !== null && $status === 'berjalan')
        <span class="text-blue-500">({{ round($sisaJam) }}j lagi)</span>
    @endif
</span>
```

---

### 3.3 Flash Message

**File:** `resources/views/components/flash-message.blade.php`

```blade
@foreach(['success' => 'green', 'error' => 'red', 'warning' => 'yellow', 'info' => 'blue'] as $type => $color)
    @if(session($type))
    <div x-data="{ show: true }" x-show="show" x-transition
         class="mb-4 flex items-start gap-3 rounded-lg border border-{{ $color }}-200 bg-{{ $color }}-50 p-4">
        <div class="flex-1 text-sm text-{{ $color }}-800">{{ session($type) }}</div>
        <button @click="show = false" class="text-{{ $color }}-400 hover:text-{{ $color }}-600">✕</button>
    </div>
    @endif
@endforeach
```

**Penggunaan:** `<x-flash-message />` — taruh di awal content, setelah `<x-slot:header>`.

---

### 3.4 Empty State

**File:** `resources/views/components/empty-state.blade.php`

```blade
@props(['icon' => '📋', 'title' => 'Belum ada data', 'description' => '', 'action' => null, 'actionLabel' => ''])
<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="text-5xl mb-4">{{ $icon }}</div>
    <h3 class="text-base font-semibold text-gray-900 mb-1">{{ $title }}</h3>
    @if($description)
        <p class="text-sm text-gray-500 max-w-sm mb-6">{{ $description }}</p>
    @endif
    @if($action)
        <a href="{{ $action }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">
            {{ $actionLabel }}
        </a>
    @endif
</div>
```

---

### 3.5 Card Container

**File:** `resources/views/components/card.blade.php`

```blade
@props(['title' => null, 'description' => null])
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    @if($title)
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-800">{{ $title }}</h3>
        @if($description)
            <p class="text-sm text-gray-500 mt-0.5">{{ $description }}</p>
        @endif
    </div>
    @endif
    <div class="p-6">{{ $slot }}</div>
</div>
```

---

### 3.6 Tombol Standar

Gunakan kelas Tailwind berikut secara konsisten. **Jangan buat variasi warna sendiri.**

```
Tombol Primary (aksi utama):
  class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-4 py-2 
         text-sm font-medium text-white hover:bg-blue-800 
         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
         disabled:opacity-50 disabled:cursor-not-allowed transition-colors"

Tombol Secondary (aksi sampingan):
  class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white 
         px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50
         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"

Tombol Danger (hapus, tolak):
  class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 
         text-sm font-medium text-white hover:bg-red-700 transition-colors"

Tombol Ghost (aksi minor, link-like):
  class="text-sm text-blue-700 hover:text-blue-900 hover:underline font-medium"

Tombol Icon Only:
  class="rounded-lg p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
```

---

### 3.7 Input Form Standar

```blade
{{-- Text Input --}}
<div>
    <label for="field_name" class="block text-sm font-medium text-gray-700 mb-1">
        Label Field <span class="text-red-500">*</span>
    </label>
    <input type="text" id="field_name" name="field_name"
        value="{{ old('field_name') }}"
        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
               text-gray-900 placeholder-gray-400
               focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500
               @error('field_name') border-red-400 ring-1 ring-red-400 @enderror">
    @error('field_name')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
    <p class="mt-1 text-xs text-gray-500">Teks hint jika diperlukan.</p>
</div>

{{-- Textarea --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
    <textarea name="deskripsi" rows="4"
        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
               focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500
               @error('deskripsi') border-red-400 ring-1 ring-red-400 @enderror">
        {{ old('deskripsi') }}
    </textarea>
    @error('deskripsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

{{-- Select / Dropdown --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Opsi</label>
    <select name="kategori_id"
        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
               focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500
               @error('kategori_id') border-red-400 @enderror">
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategoris as $k)
            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                {{ $k->nama_kategori }}
            </option>
        @endforeach
    </select>
    @error('kategori_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>
```

---

### 3.8 Tabel Data Standar

```blade
<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Kolom Judul
                </th>
                {{-- tambah kolom lain --}}
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->field }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="N" class="px-6 py-12 text-center text-sm text-gray-400">
                    Belum ada data.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{-- Pagination --}}
    @if($items->hasPages())
    <div class="border-t border-gray-100 px-6 py-3">
        {{ $items->links() }}
    </div>
    @endif
</div>
```

---

## 4. Layout & Navigasi per Role

Setiap role memiliki layout Blade sendiri. Extend layout yang sesuai, bukan layout generik.

| Role | Layout yang di-extend | Sidebar Warna |
|---|---|---|
| Admin | `layouts.admin` | Biru tua (`blue-900`) |
| Supervisor | `layouts.supervisor` | Biru slate (`slate-800`) |
| Petugas | `layouts.petugas` | Abu gelap (`gray-800`) |
| Masyarakat | `layouts.masyarakat` | Putih + header biru |

### Sidebar Item Aktif

```blade
{{-- Item sidebar aktif vs tidak aktif --}}
<a href="{{ route('admin.zona.index') }}"
   class="{{ request()->routeIs('admin.zona.*') 
       ? 'bg-blue-800 text-white' 
       : 'text-blue-100 hover:bg-blue-800 hover:text-white' }} 
   flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors">
    <span class="text-lg">🗺️</span>
    Zona Wilayah
</a>
```

### Breadcrumb Standar

```blade
<nav class="mb-4 flex items-center gap-2 text-sm text-gray-500">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
    <span>/</span>
    <a href="{{ route('admin.zona.index') }}" class="hover:text-gray-700">Zona Wilayah</a>
    <span>/</span>
    <span class="text-gray-900 font-medium">Detail Zona</span>
</nav>
```

---

## 5. PBI-01 — Manajemen Data Pelanggan

**Developer:** Arthur | **Role:** Admin | **Blade path:** `resources/views/admin/pelanggan/`

### Halaman Index (`index.blade.php`)

- **Header:** "Data Pelanggan PDAM" + tombol Primary "Tambah Pelanggan"
- **Filter bar (1 baris):** Search (nama / nomor sambungan) + Dropdown zona + Dropdown status aktif + tombol "Filter"
- **Tabel kolom:** No | Nama Pelanggan | Nomor Sambungan | Zona | No. Telepon | Status | Aksi
- **Kolom Status:** badge `bg-green-100 text-green-800` (Aktif) / `bg-gray-100 text-gray-500` (Nonaktif)
- **Kolom Aksi:** ikon Detail 🔍 | Edit ✏️ | Hapus 🗑️ (semua tombol ghost kecil)
- **Hapus:** `onclick="return confirm('Hapus data pelanggan ini? Aksi tidak dapat dibatalkan.')"` dengan form DELETE

### Halaman Create & Edit (`create.blade.php`, `edit.blade.php`)

- **Layout:** Card tunggal lebar penuh, max-w-2xl mx-auto
- **Field (urutan tampil):**
  1. Nama Pelanggan (required)
  2. Nomor Sambungan (required, hint: "Nomor sambungan PDAM, contoh: 1234567890")
  3. Zona Wilayah (dropdown — hanya tampilkan yang `is_active = true`)
  4. Alamat (textarea)
  5. No. Telepon (optional)
  6. Status Aktif (toggle switch)
- **Tombol footer:** "Simpan" (primary) + "Batal" (secondary, link ke index)
- **Edit:** pre-fill semua field dengan `old()` atau `$pelanggan->field`, gunakan `@method('PUT')`

### Halaman Show (`show.blade.php`)

- Grid 2 kolom: info pelanggan (kiri) + info pengaduan ringkasan (kanan)
- Kanan: tampilkan 3 stat kecil (Total Pengaduan, Aktif, Selesai)
- Di bawah: tabel riwayat pengaduan (5 terbaru) dari pelanggan ini

---

## 6. PBI-02 — Kategori & SLA

**Developer:** Arthur | **Role:** Admin | **Blade path:** `resources/views/admin/kategori/`

### Halaman Index (`index.blade.php`)

- **Tabel kolom:** No | Kode | Nama Kategori | SLA (jam) | Jml. Pengaduan Aktif | Status | Aksi
- **Kolom SLA:** tampilkan sebagai `badge` biru: "24 jam" — bukan angka biasa
- **Catatan UI:** di atas tabel, tampilkan info box `bg-blue-50 border-l-4 border-blue-500`:
  > "Perubahan SLA hanya berlaku untuk pengaduan BARU. Pengaduan yang sudah berjalan tidak terpengaruh."

### Halaman Create & Edit

- **Field:**
  1. Nama Kategori (required)
  2. Kode Kategori (required, uppercase otomatis via JS, pattern: `[A-Z0-9-]+`)
  3. Batas Waktu SLA (input number, satuan jam, min: 1, max: 720, hint: "Contoh: 24 = 1 hari, 48 = 2 hari")
  4. Deskripsi (textarea, optional)
  5. Status Aktif (toggle)

---

## 7. PBI-03 — Zona Wilayah & Mapping Petugas

**Developer:** Arthur | **Role:** Admin | **Blade path:** `resources/views/admin/zona/`

### Halaman Index (`index.blade.php`)

- **KPI row** di atas tabel (3 card kecil): Total Zona | Zona Aktif | Zona Tanpa Petugas
- **Tabel kolom:** No | Kode Zona | Nama Zona | Jml. Petugas | Jml. Pengaduan Aktif | Beban Kerja | Status | Aksi
- **Kolom Beban Kerja:** progress bar horizontal tipis (`h-2`):
  - Warna hijau: < 50% kapasitas
  - Warna kuning: 50–80%
  - Warna merah: > 80% atau overdue > 0
  - Tooltip `title="X pengaduan aktif dari Y total"`

### Halaman Show (`show.blade.php`)

**Terbagi 4 section:**

**Section A — Info Zona (Card)**
Grid 2 kolom: info dasar (kiri) + 5 widget statistik (kanan)
Statistik: Total Pengaduan | Menunggu | Sedang Diproses | Selesai | Overdue (merah bold)

**Section B — Pengaduan Terbaru**
Tabel compact 5 baris: No Tiket | Kategori | Status (badge) | Tanggal
Link "Lihat semua →"

**Section C — Daftar Petugas di Zona**
Tabel: Nama | NIP | Status Ketersediaan (badge) | Aksi (Lepas dari Zona)
Tombol "Lepas" warna merah kecil, dengan konfirmasi dialog.

**Section D — Tambah Petugas ke Zona**
Dropdown petugas tanpa zona + tombol "Petakan"
Jika semua petugas sudah terpetakan: info box abu-abu.

---

## 8. PBI-04 — Pengajuan Pengaduan

**Developer:** Sanitra | **Role:** Masyarakat | **Blade path:** `resources/views/masyarakat/pengaduan/`

### Halaman Create (`create.blade.php`)

- **Layout mobile-first:** max-w-xl mx-auto, padding bottom tambahan untuk HP
- **Header card biru muda:** "📋 Buat Laporan Pengaduan" + teks kecil "Isi data berikut untuk melaporkan masalah air bersih Anda"
- **Field (urutan):**
  1. Kategori Pengaduan (dropdown, required)
     - Di bawah dropdown: info box dinamis SLA kategori yang dipilih (via JS):
       `"Estimasi penyelesaian: 24 jam setelah diverifikasi"`
  2. Zona Wilayah (dropdown, required)
     - Info validasi zona aktif (via JS `checkZonaStatus()`)
  3. Lokasi / Alamat Lengkap (input text, required, placeholder: "Contoh: Jl. Merdeka No. 12, RT 03/RW 05")
  4. Deskripsi Masalah (textarea, min 20 karakter, required)
     - Character counter: `"X / 2000 karakter"` (JS)
  5. Foto Bukti (file upload, optional)
     - Preview gambar setelah dipilih
     - Hint: "Format JPG, PNG, WebP. Maksimal 5MB."
     - Custom upload area dengan drag-and-drop style:
       `border-2 border-dashed border-gray-300 rounded-xl p-8 text-center`
- **Footer form:** tombol "Kirim Pengaduan" (Primary, full-width di mobile) + tombol "Batal"

### Halaman Show (`show.blade.php`)

- **Header:** Nomor Tiket + badge Status besar
- **Info grid:** Kategori | Zona | Tanggal Pengajuan | SLA (jika ada)
- **Timeline status** (vertikal, kompak):
  Setiap `StatusLog` ditampilkan sebagai titik di garis vertikal biru
- **Foto bukti:** tampilkan jika ada, bisa diklik untuk zoom
- **Info petugas:** tampilkan nama petugas jika sudah di-assign
- **Tombol Beri Rating:** muncul hanya jika `status === 'selesai'` dan belum rating

---

## 9. PBI-05 — Verifikasi Pengaduan

**Developer:** Sanitra | **Role:** Supervisor | **Blade path:** `resources/views/supervisor/verifikasi/`

### Halaman Index (`index.blade.php`)

- **Badge counter** di page header: "Pengaduan Masuk (X)"
- **Info bar kuning:** "Pengaduan diproses berdasarkan urutan masuk (FIFO)"
- **Tabel kolom:** No Tiket | Pelapor | Kategori | Zona | Tanggal Masuk | Aksi
- Urutkan dari yang paling lama (asc)
- **Kolom Aksi:** tombol "Tinjau" (Primary kecil)

### Halaman Show / Review (`show.blade.php`)

- Layout 2 kolom:
  - **Kiri (2/3):** Detail pengaduan lengkap + foto bukti (galeri jika multiple)
  - **Kanan (1/3):** Panel Aksi — dua tombol besar:
    - ✅ "Setujui Pengaduan" (green)
    - ❌ "Tolak Pengaduan" (red) — buka modal/accordion textarea alasan
- **Modal Tolak:** tampil inline di bawah tombol (bukan popup), field textarea wajib min 10 karakter
- Konfirmasi setujui: `confirm("Setujui pengaduan ${nomor_tiket}? Anda akan diarahkan ke halaman penugasan.")`

---

## 10. PBI-06 — Assignment Petugas

**Developer:** Sanitra | **Role:** Supervisor | **Blade path:** `resources/views/supervisor/assignment/`

### Halaman Create (`create.blade.php`)

- **Header:** "Tugaskan Petugas — Tiket [NOMOR]"
- **Info box pengaduan** (read-only card): Kategori | Zona | Lokasi | SLA Kategori
- **Section "Pilih Petugas":**
  - Jika ada petugas di zona yang sama: tampilkan daftar card petugas (bukan dropdown biasa):
    ```
    [Radio button] Budi Santoso — Zona Utara — Status: Tersedia
    ```
  - Jika tidak ada petugas di zona: warning kuning + tampilkan petugas zona lain (fallback)
- **Field tambahan:**
  - Jadwal Penanganan (datetime-local, optional)
  - Instruksi Khusus (textarea, optional)
- **Tombol:** "Tugaskan Petugas" (Primary)

---

## 11. PBI-07 — Tracking & Dokumentasi Penanganan

**Developer:** Falah | **Role:** Petugas | **Blade path:** `resources/views/petugas/tugas/`

### Halaman Index (`index.blade.php`)

- **Design mobile-first** — ini halaman utama petugas di HP
- **Tidak ada tabel** — gunakan card list untuk kemudahan touch
- Setiap card tugas:
  ```
  ┌──────────────────────────────────┐
  │ 🔴 OVERDUE   SA-20260501-0003   │
  │ Air Keruh — Zona Selatan         │
  │ Jl. Mawar No. 5                  │
  │ SLA: 2 jam lagi ⏱️              │
  │ [Mulai Kerjakan]  [Detail]       │
  └──────────────────────────────────┘
  ```
- Urutkan: overdue di atas, lalu berdasarkan waktu masuk (FIFO)
- **Tombol "Mulai Kerjakan":** hanya tampil jika `status_assignment === 'ditugaskan'`

### Halaman Show (`show.blade.php`)

- **Countdown timer SLA** (JavaScript, hitung mundur dari `sisaDetik`):
  ```
  ⏱️ Sisa Waktu: 02:34:15
  ```
  Warna: biru (normal) → kuning (< 2 jam) → merah berkedip (< 30 menit)
- **Instruksi Supervisor** — card abu-abu highlight
- **Foto bukti pelapor** — bisa di-zoom
- **Tombol aksi (kondisional):**
  - Status `ditugaskan`: tombol "Mulai Proses" (primary)
  - Status `sedang_diproses`: form "Selesaikan Tugas" (upload foto + catatan + tombol "Tandai Selesai")
- **Form Selesaikan:** upload foto wajib, tampilkan preview sebelum submit

---

## 12. PBI-08 — Manajemen Profil Pengguna

**Developer:** Falah | **Role:** Semua Role | **Blade path:** `resources/views/profil/`

### Halaman Edit (`edit.blade.php`)

- **Layout:** max-w-2xl mx-auto, dibagi 2 card terpisah:

**Card 1 — Info Profil:**
- Avatar foto profil (lingkaran, 80px) dengan tombol "Ganti Foto" overlay
- Preview foto baru sebelum disimpan (JS)
- Field: Nama (editable) | Email (disabled, readonly) | Username (disabled, readonly)
- No. Telepon (editable)
- Catatan di bawah email & username: "Email dan username hanya dapat diubah oleh Admin"
- Tombol "Simpan Perubahan" (Primary)

**Card 2 — Ganti Password:**
- Field: Password Lama | Password Baru | Konfirmasi Password Baru
- Strength indicator password baru (JS): Lemah / Sedang / Kuat
- Show/hide password toggle (ikon mata)
- Tombol "Perbarui Password" (Secondary)

---

## 13. PBI-09 — SLA & Eskalasi Otomatis

**Developer:** Falah | **Role:** Admin + Sistem | **Blade path:** `resources/views/supervisor/` (terintegrasi)

### Integrasi di Dashboard Supervisor

- **Widget SLA Overdue** (card merah) di pojok kanan atas dashboard:
  ```
  ⚠️ 3 Pengaduan OVERDUE
  [Lihat Semua →]
  ```
- Widget ini refresh otomatis setiap 5 menit via `setTimeout + fetch` ke endpoint `/supervisor/dashboard/stats`

### Indikator Overdue di Tabel

Di setiap tabel yang menampilkan pengaduan, jika `sla.status_sla === 'overdue'`:
- Tambahkan latar baris: `class="bg-red-50"`
- Tambahkan badge `<x-sla-badge status="overdue" />` di kolom SLA

---

## 14. PBI-10 — Riwayat Pengaduan

**Developer:** Amanda | **Role:** Masyarakat | **Blade path:** `resources/views/masyarakat/riwayat/`

### Halaman Index (`index.blade.php`)

- **Filter (collapsible di mobile):** Status | Kategori | Tanggal Dari | Tanggal Sampai
- **Tampilan:** card list (bukan tabel) agar mudah di HP:
  ```
  ┌────────────────────────────────────┐
  │ SA-20260501-0001                   │
  │ Air Keruh — Zona Utara             │
  │ 1 Mei 2026                         │
  │ [Menunggu Verifikasi]     [Detail] │
  └────────────────────────────────────┘
  ```
- Badge status menggunakan `<x-status-badge />` yang sudah ada

### Halaman Show (`show.blade.php`)

- **Timeline vertikal** untuk `StatusLog`:
  ```
  ● Menunggu Verifikasi — 1 Mei 2026, 09:00 — oleh: Sistem
  ● Disetujui — 1 Mei 2026, 10:30 — oleh: Supervisor
  ● Ditugaskan ke: Budi Petugas — 1 Mei 2026, 11:00
  ● Sedang Diproses — 1 Mei 2026, 13:00
  ● Selesai — 1 Mei 2026, 15:30
  ```
  Garis vertikal biru, dot berwarna sesuai status

---

## 15. PBI-11 — Rating & Feedback

**Developer:** Amanda | **Role:** Masyarakat | **Blade path:** `resources/views/masyarakat/rating/`

### Halaman Form Rating (`form.blade.php`)

- **Header card hijau:** "✅ Pengaduan Selesai! Berikan Penilaian Anda"
- **Star rating interaktif (JavaScript):**
  - 5 bintang SVG, klik untuk pilih nilai
  - Hover effect: bintang berubah warna kuning saat di-hover
  - Label teks di bawah bintang: "1 = Sangat Buruk" ... "5 = Sangat Puas"
  - Wajib pilih sebelum submit (validasi JS + server)
- **Textarea komentar:** optional, placeholder "Ceritakan pengalaman Anda..."
- **Tombol:** "Kirim Penilaian" (Primary)

```javascript
// Star rating implementation
document.querySelectorAll('.star-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const val = this.dataset.value;
        document.getElementById('rating-input').value = val;
        document.querySelectorAll('.star-btn').forEach((s, i) => {
            s.classList.toggle('text-yellow-400', i < val);
            s.classList.toggle('text-gray-300', i >= val);
        });
    });
});
```

---

## 16. PBI-12 — Notifikasi In-App

**Developer:** Amanda | **Role:** Semua Role | **Blade path:** `resources/views/notifikasi/`

### Bell Icon di Navbar (Layout utama)

```blade
{{-- Letakkan di semua layout: admin, supervisor, petugas, masyarakat --}}
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative rounded-lg p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100">
        🔔
        <span id="notif-badge"
              class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center
                     rounded-full bg-red-500 text-xs font-bold text-white"
              style="display: none;">
            0
        </span>
    </button>
    {{-- Dropdown preview 3 notifikasi terbaru --}}
    <div x-show="open" @click.outside="open = false"
         class="absolute right-0 mt-2 w-80 rounded-xl border border-gray-200 bg-white shadow-lg z-50">
        {{-- isi dari AJAX atau Blade partial --}}
        <a href="{{ route('notifikasi.index') }}" 
           class="block px-4 py-2 text-center text-sm text-blue-700 hover:bg-blue-50 border-t">
            Lihat semua notifikasi
        </a>
    </div>
</div>

<script>
// Polling badge setiap 60 detik
async function refreshNotifBadge() {
    const res = await fetch('{{ route("notifikasi.count") }}');
    const data = await res.json();
    const badge = document.getElementById('notif-badge');
    badge.textContent = data.count;
    badge.style.display = data.count > 0 ? 'flex' : 'none';
}
refreshNotifBadge();
setInterval(refreshNotifBadge, 60000);
</script>
```

### Halaman Index Notifikasi (`index.blade.php`)

- **Filter tab:** Semua | Belum Dibaca | Sudah Dibaca
- **Item notifikasi (card list):**
  - Latar biru sangat muda (`bg-blue-50`) untuk yang belum dibaca
  - Ikon sesuai `tipe`: 📋 status_berubah | ✅ verifikasi | 👷 assignment | ⚠️ overdue | ⭐ rating
  - Judul + pesan + waktu relatif ("5 menit lalu", "2 jam lalu")
  - Klik item → `POST /notifikasi/{id}/baca` → redirect ke pengaduan terkait
- **Tombol "Tandai Semua Dibaca"** di header halaman

---

## 17. PBI-13 — Filter & Pencarian Lanjutan

**Developer:** Imanuel | **Role:** Supervisor + Admin | **Blade path:** `resources/views/supervisor/pengaduan/`

### Halaman Index (`index.blade.php`)

- **Filter panel** (expandable, default terbuka di desktop):
  ```
  [Search: nomor tiket / lokasi / deskripsi]
  [Kategori ▼] [Status ▼] [Zona ▼] [Petugas ▼]
  [Dari Tanggal: ____] [Sampai: ____]
  [Sort: Terbaru ▼]  [Terapkan Filter] [Reset]
  ```
- **Chips filter aktif** di bawah filter panel:
  Setiap filter yang aktif tampil sebagai chip yang bisa dihapus:
  ```
  Status: Sedang Diproses ✕   Zona: Utara ✕
  ```
- **Tabel:** No Tiket | Pelapor | Kategori | Zona | Status | SLA | Petugas | Tanggal | Aksi
- **Export CSV:** tombol "Export CSV" di kanan atas tabel (secondary, ikon unduh)
- **Info hasil:** "Menampilkan 15 dari 87 pengaduan sesuai filter"

---

## 18. PBI-14 — Laporan Rekap & Export PDF

**Developer:** Imanuel | **Role:** Supervisor + Admin | **Blade path:** `resources/views/supervisor/laporan/`

### Halaman Index (`index.blade.php`)

- **Form filter periode:**
  ```
  [Dari: ____] [Sampai: ____] [Zona: Semua ▼] [Tampilkan Laporan]
  ```
- **Hasil laporan** (muncul setelah form diisi):
  - 4 KPI card: Total | Selesai | Overdue | Rata-rata Waktu
  - Tabel distribusi per status
  - Tabel distribusi per kategori
- **Tombol "Export PDF"** (merah, ikon PDF) — POST form dengan parameter filter yang sama

### Template PDF (`resources/views/laporan/rekap-pdf.blade.php`)

> ⚠️ DomPDF tidak support CSS eksternal. Gunakan **inline style saja**.

Struktur HTML:
```html
<style>
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #111; }
  .header { background: #1d4ed8; color: white; padding: 20px; }
  table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  th { background: #f3f4f6; padding: 8px; text-align: left; font-size: 11px; }
  td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
  .badge-selesai { color: #15803d; font-weight: bold; }
  .badge-overdue { color: #dc2626; font-weight: bold; }
</style>
<div class="header">
  <h1>SIGAP-AIR — Laporan Rekap Pengaduan</h1>
  <p>Periode: {{ $periode['dari'] }} s/d {{ $periode['sampai'] }} | Zona: {{ $zona_nama }}</p>
</div>
{{-- KPI row, tabel per status, tabel per kategori --}}
<div style="margin-top:20px; font-size:10px; color:#999; text-align:center;">
  Dicetak pada {{ now()->format('d M Y H:i') }} | SIGAP-AIR v1.0
</div>
```

---

## 19. PBI-15 — Dashboard Statistik

**Developer:** Imanuel | **Role:** Supervisor + Admin | **Blade path:** `resources/views/supervisor/`

### Layout Dashboard (`dashboard.blade.php`)

**Baris 1 — KPI Cards (5 card):**

```
┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐
│ Total    │ │ Menunggu │ │ Diproses │ │ Selesai  │ │ OVERDUE  │
│ Hari Ini │ │Verifikasi│ │          │ │ Bulan Ini│ │  🔴 3    │
│    12    │ │    5     │ │    4     │ │   87     │ │          │
└──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘
```

Card Overdue: latar `bg-red-50 border-red-200`, angka `text-3xl font-bold text-red-600`

**Baris 2 — Grafik (2 kolom):**
- Kiri: Bar chart "Pengaduan per Kategori" — gunakan library `Chart.js` (CDN)
- Kanan: Pie/Doughnut chart "Distribusi per Zona"

**Baris 3 — Grafik + Tabel:**
- Kiri: Line chart "Tren Bulanan 6 Bulan Terakhir"
- Kanan: Tabel "Pengaduan Membutuhkan Perhatian" (overdue + menunggu lama)

**Implementasi Chart.js:**
```blade
<canvas id="chartKategori" class="max-h-64"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
// Data diambil via AJAX dari /supervisor/dashboard/stats
fetch('{{ route("supervisor.dashboard.stats") }}')
  .then(r => r.json())
  .then(data => {
    new Chart(document.getElementById('chartKategori'), {
      type: 'bar',
      data: {
        labels: data.per_kategori.map(d => d.label),
        datasets: [{ label: 'Pengaduan', data: data.per_kategori.map(d => d.value),
                     backgroundColor: '#3b82f6' }]
      },
      options: { responsive: true, plugins: { legend: { display: false } } }
    });
  });
</script>
```

---

## 20. PBI-16 — Manajemen User & Role

**Developer:** Farisha | **Role:** Admin | **Blade path:** `resources/views/admin/users/`

### Halaman Index (`index.blade.php`)

- **Filter:** Search (nama/email/username) + Dropdown Role + Dropdown Status Aktif
- **Tabel kolom:** No | Foto | Nama | Email | Username | Role | Status | Aksi
- **Kolom Role:** badge per role:
  - Admin: `bg-purple-100 text-purple-800`
  - Supervisor: `bg-blue-100 text-blue-800`
  - Petugas: `bg-orange-100 text-orange-800`
  - Masyarakat: `bg-gray-100 text-gray-700`
- **Kolom Aksi:** Edit | Reset Password | Toggle Aktif | Hapus
- **Reset Password:** setelah klik, tampilkan modal atau flash `session('password_baru')` dengan:
  ```
  ⚠️ Password baru: xK3jP92m — Catat dan berikan ke pengguna. Tidak akan ditampilkan lagi.
  ```

### Halaman Create (`create.blade.php`)

- Field khusus: dropdown Role — jika pilih "Petugas", tampilkan field tambahan "Zona" (via JS toggle)
  ```javascript
  document.getElementById('role').addEventListener('change', function() {
      const zonaField = document.getElementById('zona-field');
      zonaField.style.display = this.value === 'petugas' ? 'block' : 'none';
  });
  ```

---

## 21. PBI-17 — Manajemen Petugas Teknis

**Developer:** Farisha | **Role:** Admin + Supervisor | **Blade path:** `resources/views/admin/petugas/`

### Halaman Index (`index.blade.php`)

- **Tabel kolom:** No | Nama | Zona | NIP | Status Ketersediaan | Total Selesai | Total Aktif | Aksi
- **Kolom Status Ketersediaan:** badge warna (tersedia=hijau, sibuk=kuning, tidak_aktif=merah)
- **Kolom Aksi:** Detail | Ubah Status

### Halaman Show (`show.blade.php`)

Grid 2 kolom:
- **Kiri:** Info petugas + statistik kinerja (total ditangani, rata-rata waktu, rata-rata rating)
- **Kanan:** Histori assignment (tabel ringkas, paginated 10)

Rating rata-rata: tampilkan sebagai bintang visual (1-5) + angka

---

## 22. PBI-18 — Laporan Kinerja Petugas & Export Excel

**Developer:** Farisha | **Role:** Supervisor + Admin | **Blade path:** `resources/views/supervisor/kinerja/`

### Halaman Index (`index.blade.php`)

- **Tabel kinerja:**
  Nama | Zona | Total Selesai | Total Aktif | Rata-rata Waktu | Rata-rata Rating | Aksi
- **Kolom Rata-rata Rating:** tampilkan bintang SVG + angka, contoh: ⭐ 4.2/5
- **Kolom Rata-rata Waktu:** tampilkan dalam format "X.X jam"
- **Tombol "Export Excel"** di atas tabel: `bg-green-600 text-white hover:bg-green-700`
- **Baris tanpa data:** tampilkan "-" bukan error

---

## 23. PBI-19 — Authentication

**Developer:** Semua | **Blade path:** `resources/views/auth/`

### Halaman Login (`login.blade.php`)

- **Layout:** layar penuh, 2 kolom di desktop (kiri: branding, kanan: form)
- **Kiri (hidden di mobile):** background biru tua + logo + tagline SIGAP-AIR
- **Kanan:** card form putih:
  - Logo kecil SIGAP-AIR di atas form
  - Field: Email + Password (show/hide toggle)
  - Checkbox "Ingat Saya"
  - Tombol Login (primary, full-width)
  - Link registrasi di bawah

### Halaman Register (`register.blade.php`)

- Layout serupa login
- Field: Nama Lengkap | Email | Username | No. Telepon | Password | Konfirmasi Password
- Catatan: "Akun yang dibuat melalui halaman ini hanya berlaku untuk pelapor/masyarakat"

---

## 24. Panduan UX Writing

Gunakan bahasa Indonesia yang ramah, jelas, dan konsisten di seluruh sistem.

### Label Tombol

| ❌ Hindari | ✅ Gunakan |
|---|---|
| Submit | Kirim / Simpan |
| Delete | Hapus |
| Cancel | Batal |
| OK | Oke / Konfirmasi |
| Update | Perbarui |
| Create New | Tambah [Entitas] |

### Pesan Flash

```
✅ Success  : "Data berhasil disimpan."  /  "Pengaduan berhasil dikirim."
❌ Error    : "Terjadi kesalahan. [Alasan spesifik jika ada]."
⚠️ Warning  : "Perhatian: [kondisi yang perlu diketahui user]."
ℹ️ Info     : "[Informasi konteks yang membantu user]."
```

### Konfirmasi Hapus

Selalu spesifik, jangan generik:
```
❌ "Apakah Anda yakin?"
✅ "Hapus data pelanggan Budi Santoso? Tindakan ini tidak dapat dibatalkan."
```

---

## 25. Checklist Review Frontend

Jalankan checklist ini sebelum mengajukan pull request untuk setiap PBI.

```
LAYOUT & RESPONSIF
[ ] Halaman berfungsi di layar 375px (mobile)
[ ] Halaman berfungsi di layar 1280px (desktop)
[ ] Tidak ada teks atau elemen yang terpotong di mobile
[ ] Sidebar / navbar aktif sesuai halaman yang sedang dibuka

KOMPONEN
[ ] Menggunakan <x-status-badge /> bukan badge custom sendiri
[ ] Menggunakan <x-flash-message /> untuk flash session
[ ] Menggunakan kelas tombol standar (Primary/Secondary/Danger/Ghost)
[ ] Semua form punya @csrf
[ ] Form PUT/PATCH/DELETE punya @method()

FORM & VALIDASI
[ ] Error validasi tampil di bawah setiap field yang error
[ ] Field yang error diberi border merah
[ ] old() digunakan untuk mempertahankan input saat validasi gagal
[ ] Label field sudah sesuai (label for = input id)
[ ] Field required sudah ditandai dengan * merah

DATA & TAMPILAN
[ ] Empty state ada untuk tabel/list yang bisa kosong
[ ] Pagination tampil jika data lebih dari 15 baris
[ ] withQueryString() dipakai agar filter tidak hilang saat pindah halaman
[ ] Kolom status pengaduan menggunakan warna yang konsisten (lihat Design Token)

AKSI BERBAHAYA
[ ] Tombol Hapus ada konfirmasi JavaScript
[ ] Tombol Hapus menggunakan form dengan method DELETE (bukan GET)
[ ] Tombol Toggle Aktif/Nonaktif ada konfirmasi jika ada dampak besar

AKSESIBILITAS DASAR
[ ] Semua gambar ada atribut alt
[ ] Tombol ikon saja ada atribut title atau aria-label
[ ] Kontras warna teks cukup (minimal AA)
```

---

> **Pertanyaan?** Hubungi Arthur (lead frontend) atau buat issue di Jira board dengan label `[FE-DESIGN]`.  
> Dokumen ini diperbarui setiap awal sprint. Cek versi terbaru sebelum mulai coding.

---

*SIGAP-AIR Frontend Design Guide v1.0 — Tim Pengembang SI, 2026*
