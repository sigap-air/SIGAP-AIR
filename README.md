# 🚰 SIGAP-AIR — Sistem Informasi Gerak Cepat Pengaduan Air

> Aplikasi web berbasis **PHP Laravel 10** untuk mengelola pengaduan kualitas layanan air bersih PDAM secara digital, terstruktur, dan terintegrasi **.

---

## 👥 Tim Pengembang & Pembagian PBI

| No | Nama | Role | PBI | Branch |
|---|---|---|---|---|
| 1 | Arthur Budi Maharesi | Full Stack — Administrasi | PBI 1, 2, 3 | `feature/arthur-admin-master` |
| 2 | Sanitra Savitri | Full Stack — Pengaduan | PBI 4, 5, 6 | `feature/sanitra-pengaduan` |
| 3 | Falah Adhi Chandra | Full Stack — Tracking & Data | PBI 7, 8, 9 | `feature/falah-tracking` |
| 4 | Amanda Zuhra Azis | Full Stack — Interaksi | PBI 10, 11, 12 | `feature/amanda-interaksi` |
| 5 | Imanuel Karmelio V. Liuw | Full Stack — Operasional | PBI 13, 14, 15 | `feature/imanuel-operasional` |
| 6 | Farisha Huwaida Shofha | Full Stack — Notifikasi & Sistem | PBI 16, 17, 18 | `feature/farisha-notifikasi` |

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | PHP 8.1+, Laravel 10 |
| Auth | Laravel Breeze (Blade) |
| Frontend | Blade Template + Tailwind CSS |
| Database | MySQL / MariaDB |
| Testing | Laravel Dusk (Browser Automation) |
| PDF Export | DomPDF / Browsershot |
| Web Server | Apache / Nginx |

---

## ⚙️ Setup Lokal (Wajib Dibaca Semua Developer!)

```bash
# 1. Clone repo
git clone https://github.com/aturrr62/SIGAP-AIR.git
cd SIGAP-AIR

# 2. Install dependencies
composer install
npm install

# 3. Copy environment file
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Setting database di file .env
DB_DATABASE=sigap_air
DB_USERNAME=root
DB_PASSWORD=

# 6. Jalankan migrasi + seeder
php artisan migrate --seed

# 7. Link storage
php artisan storage:link

# 8. Build assets
npm run dev

# 9. Jalankan server
php artisan serve
```

---

## 🌿 Git Workflow — Wajib Diikuti Semua Developer

```bash
# Sebelum mulai kerja, selalu pull dari main
git checkout main
git pull origin main

# Buat branch sesuai namamu (lihat tabel di atas)
git checkout -b feature/nama-fitur

# Commit dengan format yang jelas
git commit -m "feat(PBI-04): tambah form pengajuan pengaduan"

# Push ke branch kamu
git push origin feature/nama-fitur

# Buat Pull Request ke branch main setelah selesai
```

### Format Commit Message
```
feat(PBI-XX): deskripsi singkat fitur baru
fix(PBI-XX): deskripsi bug yang diperbaiki
test(PBI-XX): tambah test case Dusk
docs: update dokumentasi
```

---

## 📁 Struktur Folder
Lihat file [`docs/FOLDER_STRUCTURE.md`](docs/FOLDER_STRUCTURE.md) untuk penjelasan lengkap setiap folder dan file beserta siapa yang bertanggung jawab.

---

## 🧪 Menjalankan Test Laravel Dusk

```bash
# Jalankan semua test
php artisan dusk

# Jalankan test untuk PBI tertentu
php artisan dusk tests/Browser/Pages/PBI04_PengajuanPengaduanTest.php
```

---

## 📋 Akun Default (Seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@sigapair.test | password |
| Supervisor | supervisor@sigapair.test | password |
| Petugas | petugas@sigapair.test | password |
| Masyarakat | masyarakat@sigapair.test | password |

---

## 📞 Kontak & Koordinasi
Gunakan grup WhatsApp tim dan buat issue di GitHub jika menemukan bug atau blocker.
