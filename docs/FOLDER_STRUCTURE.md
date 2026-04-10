# 📁 Struktur Folder SIGAP-AIR — Panduan Lengkap Developer

> Dokumen ini menjelaskan setiap folder dan file dalam proyek SIGAP-AIR.
> Setiap developer **wajib membaca bagian PBI mereka** sebelum mulai coding.

---

## 🗺️ Peta Tanggung Jawab per Developer

```
SIGAP-AIR/
│
├── 📂 app/
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/
│   │   │   ├── 📂 Admin/          ← ARTHUR (PBI 1,2,3) + FARISHA (PBI 16,17,18)
│   │   │   ├── 📂 Auth/           ← SEMUA DEVELOPER (shared)
│   │   │   ├── 📂 Masyarakat/     ← SANITRA (PBI 4) + AMANDA (PBI 10,11,12)
│   │   │   ├── 📂 Petugas/        ← FALAH (PBI 7,8,9)
│   │   │   └── 📂 Supervisor/     ← SANITRA (PBI 5,6) + IMANUEL (PBI 13,14,15)
│   │   │
│   │   ├── 📂 Middleware/         ← FARISHA (setup role middleware)
│   │   └── 📂 Requests/           ← Developer masing-masing (validasi form)
│   │       ├── 📂 Pengaduan/      ← SANITRA
│   │       ├── 📂 User/           ← FALAH + FARISHA
│   │       └── 📂 Master/         ← ARTHUR
│   │
│   ├── 📂 Models/                 ← SEMUA DEVELOPER (sesuai entitas ERD)
│   ├── 📂 Notifications/          ← FARISHA (PBI 12,16,17,18) + AMANDA (PBI 12)
│   ├── 📂 Policies/               ← FARISHA (role-based access)
│   ├── 📂 Services/               ← Developer masing-masing (business logic)
│   └── 📂 Observers/              ← FALAH (SLA observer) + FARISHA
│
├── 📂 database/
│   ├── 📂 migrations/             ← ARTHUR setup awal + masing-masing sesuai PBI
│   ├── 📂 seeders/                ← ARTHUR (DataSeeder) + semua developer
│   └── 📂 factories/              ← Semua developer (untuk testing)
│
├── 📂 resources/views/
│   ├── 📂 layouts/                ← SEMUA (layout utama shared)
│   ├── 📂 components/             ← SEMUA (komponen reusable)
│   ├── 📂 auth/                   ← FARISHA (login, register)
│   ├── 📂 admin/                  ← ARTHUR + FARISHA
│   ├── 📂 supervisor/             ← SANITRA + IMANUEL
│   ├── 📂 petugas/                ← FALAH
│   ├── 📂 masyarakat/             ← SANITRA + AMANDA
│   └── 📂 emails/                 ← FARISHA + AMANDA
│
├── 📂 routes/                     ← Masing-masing developer menambah route-nya
│
└── 📂 tests/
    ├── 📂 Browser/Pages/          ← Masing-masing developer (sesuai PBI)
    └── 📂 Feature/                ← Masing-masing developer
```

---

## 📂 Detail Folder & File

---

### `app/Http/Controllers/`

Berisi semua controller yang mengatur logika request-response HTTP.

#### `Admin/` — ARTHUR (PBI 1,2,3) & FARISHA (PBI 16,17,18)
```
Admin/
├── PelangganController.php     ← ARTHUR | PBI-01 | CRUD data pelanggan PDAM
├── KategoriController.php      ← ARTHUR | PBI-02 | CRUD kategori pengaduan + SLA default
├── ZonaController.php          ← ARTHUR | PBI-03 | CRUD zona wilayah + mapping petugas
├── UserController.php          ← FARISHA | PBI-16 | CRUD user + assign role
├── PetugasController.php       ← FARISHA | PBI-17 | Kelola data petugas teknis
└── LaporanKinerjaController.php ← FARISHA | PBI-18 | Laporan kinerja + export Excel
```

#### `Masyarakat/` — SANITRA (PBI 4) & AMANDA (PBI 10,11,12)
```
Masyarakat/
├── PengaduanController.php     ← SANITRA | PBI-04 | Form pengaduan + upload foto + nomor tiket
├── RiwayatController.php       ← AMANDA  | PBI-10 | Riwayat pengaduan + filter + timeline
├── RatingController.php        ← AMANDA  | PBI-11 | Submit rating bintang + komentar
└── NotifikasiController.php    ← AMANDA  | PBI-12 | Lihat & mark-read notifikasi
```

#### `Supervisor/` — SANITRA (PBI 5,6) & IMANUEL (PBI 13,14,15)
```
Supervisor/
├── VerifikasiController.php    ← SANITRA  | PBI-05 | Approve/tolak pengaduan + notif pelapor
├── AssignmentController.php    ← SANITRA  | PBI-06 | Tugaskan petugas berdasarkan zona
├── FilterPengaduanController.php ← IMANUEL | PBI-13 | Search + filter multi-kriteria
├── LaporanController.php       ← IMANUEL  | PBI-14 | Laporan rekap periodik + export PDF
└── DashboardController.php     ← IMANUEL  | PBI-15 | Dashboard statistik real-time
```

#### `Petugas/` — FALAH (PBI 7,8,9)
```
Petugas/
├── PenangananController.php    ← FALAH | PBI-07 | Update status + upload foto penanganan
├── ProfilController.php        ← FALAH | PBI-08 | Edit profil + foto profil + ganti password
└── SlaController.php           ← FALAH | PBI-09 | Konfigurasi SLA + auto-flag overdue
```

---

### `app/Http/Requests/`

Form Request untuk validasi input server-side.

```
Requests/
├── Pengaduan/
│   ├── StorePengaduanRequest.php   ← SANITRA | Validasi form pengaduan baru
│   └── UpdateStatusRequest.php     ← FALAH   | Validasi update status penanganan
├── User/
│   ├── UpdateProfilRequest.php     ← FALAH   | Validasi edit profil pengguna
│   └── StoreUserRequest.php        ← FARISHA | Validasi tambah user baru
└── Master/
    ├── StorePelangganRequest.php   ← ARTHUR  | Validasi data pelanggan
    ├── StoreKategoriRequest.php    ← ARTHUR  | Validasi kategori + SLA
    └── StoreZonaRequest.php        ← ARTHUR  | Validasi zona wilayah
```

---

### `app/Models/`

Model Eloquent sesuai ERD SIGAP-AIR. Semua developer membuat model sesuai entitas yang mereka tangani.

```
Models/
├── User.php            ← FARISHA | Role, relasi ke pengaduan & petugas
├── Pengaduan.php       ← SANITRA | Core model, relasi ke semua entitas
├── Pelanggan.php       ← ARTHUR  | Data pelanggan PDAM
├── Kategori.php        ← ARTHUR  | Jenis pengaduan
├── Zona.php            ← ARTHUR  | Wilayah layanan PDAM
├── Assignment.php      ← SANITRA | Penghubung pengaduan ↔ petugas
├── Petugas.php         ← FARISHA | Data petugas teknis
├── Rating.php          ← AMANDA  | Rating kepuasan pelanggan
├── Sla.php             ← FALAH   | Konfigurasi & status SLA
└── Notifikasi.php      ← AMANDA  | Log notifikasi in-app
```

---

### `app/Services/`

Business logic yang dipisah dari controller agar controller tetap slim.

```
Services/
├── PengaduanService.php        ← SANITRA | Logic buat tiket, validasi, simpan
├── AssignmentService.php       ← SANITRA | Logic distribusi petugas per zona
├── SlaService.php              ← FALAH   | Hitung deadline, cek overdue, auto-flag
├── NotifikasiService.php       ← AMANDA  | Kirim notifikasi in-app
├── LaporanService.php          ← IMANUEL | Generate laporan + export PDF
├── KinerjaService.php          ← FARISHA | Hitung kinerja petugas + export Excel
└── DashboardService.php        ← IMANUEL | Agregasi data untuk dashboard statistik
```

---

### `app/Notifications/`

Notifikasi Laravel untuk sistem in-app (PBI-12).

```
Notifications/
├── PengaduanDiterimaNotification.php   ← AMANDA | Notif ke pelapor: pengaduan masuk
├── PengaduanDisetujuiNotification.php  ← AMANDA | Notif ke pelapor: pengaduan disetujui
├── PengaduanDitolakNotification.php    ← AMANDA | Notif ke pelapor: pengaduan ditolak
├── PengaduanDitugaskanNotification.php ← AMANDA | Notif ke petugas: ada penugasan baru
├── StatusDiupdateNotification.php      ← AMANDA | Notif ke pelapor: status berubah
└── SlaOverdueNotification.php          ← FALAH  | Alert ke supervisor: SLA terlampaui
```

---

### `app/Http/Middleware/`

```
Middleware/
├── CheckRole.php       ← FARISHA | Middleware cek role (admin/supervisor/petugas/masyarakat)
└── EnsureActive.php    ← FARISHA | Middleware pastikan akun aktif sebelum akses
```

---

### `database/migrations/`

Urutan migrasi harus diperhatikan karena ada foreign key dependency (lihat ERD).

```
migrations/
├── 2024_01_01_000001_create_users_table.php         ← FARISHA (Breeze sudah generate)
├── 2024_01_01_000002_create_zonas_table.php         ← ARTHUR | PBI-03
├── 2024_01_01_000003_create_kategoris_table.php     ← ARTHUR | PBI-02
├── 2024_01_01_000004_create_pelanggans_table.php    ← ARTHUR | PBI-01
├── 2024_01_01_000005_create_petugas_table.php       ← FARISHA | PBI-17
├── 2024_01_01_000006_create_zona_petugas_table.php  ← ARTHUR | PBI-03 (pivot)
├── 2024_01_01_000007_create_pengaduans_table.php    ← SANITRA | PBI-04
├── 2024_01_01_000008_create_slas_table.php          ← FALAH   | PBI-09
├── 2024_01_01_000009_create_assignments_table.php   ← SANITRA | PBI-06
├── 2024_01_01_000010_create_ratings_table.php       ← AMANDA  | PBI-11
└── 2024_01_01_000011_create_notifikasis_table.php   ← AMANDA  | PBI-12
```

⚠️ **PENTING:** Selalu koordinasi dengan Arthur sebelum menambah migrasi baru agar urutan tidak konflik!

---

### `database/seeders/`

```
seeders/
├── DatabaseSeeder.php          ← ARTHUR | Master seeder, panggil semua seeder
├── UserSeeder.php              ← FARISHA | Buat 4 akun default (admin/supervisor/petugas/masyarakat)
├── ZonaSeeder.php              ← ARTHUR | Seeder zona wilayah contoh
├── KategoriSeeder.php          ← ARTHUR | Seeder kategori + SLA default
├── PelangganSeeder.php         ← ARTHUR | Seeder data pelanggan dummy
├── PetugasSeeder.php           ← FARISHA | Seeder data petugas dummy
└── PengaduanSeeder.php         ← SANITRA | Seeder data pengaduan dummy (untuk testing)
```

---

### `resources/views/`

Blade template untuk antarmuka pengguna. Gunakan layout dan komponen yang sudah ada agar konsisten.

#### `layouts/`
```
layouts/
├── app.blade.php           ← SEMUA DEVELOPER | Layout utama dengan sidebar & navbar
├── guest.blade.php         ← FARISHA | Layout untuk halaman login/register
└── print.blade.php         ← IMANUEL | Layout khusus cetak laporan PDF
```

#### `components/`
```
components/
├── sidebar.blade.php       ← SEMUA | Sidebar navigasi (berbeda per role)
├── navbar.blade.php        ← SEMUA | Navbar atas dengan bell notifikasi
├── alert.blade.php         ← SEMUA | Komponen pesan sukses/error
├── modal-konfirmasi.blade.php ← SEMUA | Modal konfirmasi hapus/aksi kritis
├── badge-status.blade.php  ← SEMUA | Badge warna untuk status pengaduan
├── card-kpi.blade.php      ← IMANUEL | Widget KPI untuk dashboard
├── tabel-pengaduan.blade.php ← IMANUEL | Tabel daftar pengaduan reusable
└── form-rating.blade.php   ← AMANDA | Form rating bintang
```

#### `masyarakat/`
```
masyarakat/
├── dashboard.blade.php             ← SANITRA  | Beranda masyarakat
├── pengaduan/
│   ├── create.blade.php            ← SANITRA  | PBI-04 Form pengaduan baru
│   └── tiket-sukses.blade.php      ← SANITRA  | PBI-04 Konfirmasi tiket berhasil
├── riwayat/
│   ├── index.blade.php             ← AMANDA   | PBI-10 Daftar riwayat pengaduan
│   └── show.blade.php              ← AMANDA   | PBI-10 Detail + timeline pengaduan
└── rating/
    └── create.blade.php            ← AMANDA   | PBI-11 Form rating kepuasan
```

#### `supervisor/`
```
supervisor/
├── dashboard.blade.php             ← IMANUEL  | PBI-15 Dashboard statistik real-time
├── verifikasi/
│   ├── index.blade.php             ← SANITRA  | PBI-05 Antrean verifikasi pengaduan
│   └── show.blade.php              ← SANITRA  | PBI-05 Detail pengaduan + tombol approve/tolak
├── assignment/
│   └── create.blade.php            ← SANITRA  | PBI-06 Form penugasan petugas
├── filter/
│   └── index.blade.php             ← IMANUEL  | PBI-13 Filter & pencarian pengaduan
└── laporan/
    ├── rekap.blade.php             ← IMANUEL  | PBI-14 Laporan rekap periodik
    └── kinerja.blade.php           ← FARISHA  | PBI-18 Laporan kinerja petugas
```

#### `petugas/`
```
petugas/
├── dashboard.blade.php             ← FALAH    | Beranda petugas — daftar tugas aktif
├── tugas/
│   ├── index.blade.php             ← FALAH    | PBI-07 Daftar tugas harian
│   └── update.blade.php            ← FALAH    | PBI-07 Form update status + upload foto
└── profil/
    └── edit.blade.php              ← FALAH    | PBI-08 Edit profil + foto + password
```

#### `admin/`
```
admin/
├── dashboard.blade.php             ← ARTHUR   | Beranda admin
├── pelanggan/
│   ├── index.blade.php             ← ARTHUR   | PBI-01 Daftar pelanggan
│   ├── create.blade.php            ← ARTHUR   | PBI-01 Form tambah pelanggan
│   └── edit.blade.php              ← ARTHUR   | PBI-01 Form edit pelanggan
├── kategori/
│   ├── index.blade.php             ← ARTHUR   | PBI-02 Daftar kategori + SLA
│   └── form.blade.php              ← ARTHUR   | PBI-02 Form tambah/edit kategori
├── zona/
│   ├── index.blade.php             ← ARTHUR   | PBI-03 Daftar zona wilayah
│   └── mapping.blade.php           ← ARTHUR   | PBI-03 Mapping petugas ke zona
├── user/
│   ├── index.blade.php             ← FARISHA  | PBI-16 Daftar semua user
│   └── form.blade.php              ← FARISHA  | PBI-16 Form tambah/edit user + role
└── petugas/
    ├── index.blade.php             ← FARISHA  | PBI-17 Data petugas teknis
    └── detail.blade.php            ← FARISHA  | PBI-17 Histori + statistik petugas
```

---

### `routes/`

```
routes/
├── web.php         ← SEMUA | File utama — daftarkan route sesuai PBI masing-masing
├── auth.php        ← FARISHA | Route login, register, logout (Breeze)
└── api.php         ← Opsional (tidak digunakan di sprint ini)
```

> ⚠️ **Konvensi route:** Gunakan route group dengan middleware `auth` dan `role`. Contoh:
> ```php
> // routes/web.php
> Route::middleware(['auth', 'role:masyarakat'])->group(function () {
>     Route::resource('pengaduan', PengaduanController::class); // SANITRA
> });
> ```

---

### `tests/Browser/Pages/`

Test case Laravel Dusk per PBI. Setiap developer **wajib membuat test** untuk PBI mereka.

```
Browser/Pages/
├── PBI01_PelangganCrudTest.php         ← ARTHUR
├── PBI02_KategoriSlaTest.php           ← ARTHUR
├── PBI03_ZonaMappingTest.php           ← ARTHUR
├── PBI04_PengajuanPengaduanTest.php    ← SANITRA
├── PBI05_VerifikasiPengaduanTest.php   ← SANITRA
├── PBI06_AssignmentPetugasTest.php     ← SANITRA
├── PBI07_UpdateStatusPenangananTest.php ← FALAH
├── PBI08_EditProfilTest.php            ← FALAH
├── PBI09_SlaEskalasiTest.php           ← FALAH
├── PBI10_RiwayatPengaduanTest.php      ← AMANDA
├── PBI11_RatingFeedbackTest.php        ← AMANDA
├── PBI12_NotifikasiTest.php            ← AMANDA
├── PBI13_FilterPencarianTest.php       ← IMANUEL
├── PBI14_ExportPdfTest.php             ← IMANUEL
├── PBI15_DashboardStatistikTest.php    ← IMANUEL
├── PBI16_ManajemenUserTest.php         ← FARISHA
├── PBI17_ManajemenPetugasTest.php      ← FARISHA
└── PBI18_LaporanKinerjaTest.php        ← FARISHA
```

---

### `docs/`

Dokumentasi teknis proyek.

```
docs/
├── FOLDER_STRUCTURE.md     ← File ini
├── diagrams/               ← Simpan file ERD, Class Diagram, Use Case, Sequence Diagram
│   ├── ERD.png
│   ├── ClassDiagram.png
│   ├── UseCaseDiagram.png
│   └── SequenceDiagram_*.png
├── mockups/                ← Simpan file mockup UI (figma export / screenshot)
│   ├── Dashboard_Supervisor.png
│   ├── Form_Pengaduan.png
│   └── Daftar_Tugas_Petugas.png
└── api/                    ← Dokumentasi endpoint jika dibutuhkan
    └── endpoints.md
```

---

## 📌 Aturan Penting yang Wajib Diikuti

1. **Jangan push langsung ke `main`** — selalu buat branch dan Pull Request.
2. **Koordinasi migrasi database** lewat grup sebelum membuat file migrasi baru.
3. **Jangan edit file milik developer lain** tanpa diskusi terlebih dahulu.
4. **Buat Form Request** untuk setiap validasi form — jangan validasi di controller.
5. **Wajib buat test Dusk** minimal 1 happy path + 1 error case per PBI.
6. **Gunakan komponen Blade** yang sudah ada di `resources/views/components/` — jangan buat ulang.
7. **Gunakan Service class** untuk logic yang kompleks agar controller tetap bersih.
