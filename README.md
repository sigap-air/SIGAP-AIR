# 💧 SIGAP-AIR

**Sistem Informasi Gerak Cepat Pengaduan Air**
Aplikasi web berbasis Laravel untuk pengelolaan pengaduan kualitas air bersih PDAM.

---

## 🧾 Deskripsi

SIGAP-AIR adalah sistem berbasis web yang dirancang untuk mempermudah masyarakat dalam melaporkan permasalahan kualitas air, serta membantu pihak PDAM dalam mengelola, memproses, dan memonitor pengaduan secara efisien.

---

## 🧰 Teknologi yang Digunakan

* PHP 8.1+
* Laravel 10
* Laravel Breeze (Blade) – Authentication
* MySQL / MariaDB
* Tailwind CSS
* Laravel Dusk – Automated Testing
* Jira – Project Management
* Git & GitHub – Version Control

---

## 📁 Struktur Folder

```bash
sigap-air/
├── .gitignore
├── .env.example
├── README.md (sudah dibuat sebelumnya)
├── app/
│   ├── Domains/
│   │   ├── Complaint/
│   │   │   ├── Actions/
│   │   │   │   └── GenerateTicketNumberAction.php
│   │   │   ├── DataTransferObjects/
│   │   │   │   └── ComplaintData.php
│   │   │   ├── Models/
│   │   │   │   └── Complaint.php
│   │   │   ├── Repositories/
│   │   │   │   └── ComplaintRepositoryInterface.php
│   │   │   └── Services/
│   │   │       └── ComplaintService.php
│   │   ├── User/
│   │   │   ├── Actions/
│   │   │   │   └── UpdateUserProfileAction.php
│   │   │   ├── DataTransferObjects/
│   │   │   │   └── UserData.php
│   │   │   ├── Models/
│   │   │   │   └── User.php
│   │   │   ├── Repositories/
│   │   │   │   └── UserRepositoryInterface.php
│   │   │   └── Services/
│   │   │       └── UserService.php
│   │   └── MasterData/
│   │       ├── Actions/
│   │       │   └── CreateSlaAction.php
│   │       ├── DataTransferObjects/
│   │       │   └── SlaData.php
│   │       ├── Models/
│   │       │   ├── Category.php
│   │       │   └── Sla.php
│   │       ├── Repositories/
│   │       │   └── MasterDataRepositoryInterface.php
│   │       └── Services/
│   │           └── MasterDataService.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Web/
│   │   │       ├── ComplaintController.php
│   │   │       ├── UserController.php
│   │   │       └── MasterDataController.php
│   │   └── Requests/
│   │       ├── StoreComplaintRequest.php
│   │       ├── UpdateProfileRequest.php
│   │       └── StoreSlaRequest.php
│   ├── Infrastructure/
│   │   ├── Repositories/
│   │   │   ├── EloquentComplaintRepository.php
│   │   │   ├── EloquentUserRepository.php
│   │   │   └── EloquentMasterDataRepository.php
│   │   └── Services/
│   │       └── (kosong untuk integrasi pihak ketiga nanti)
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_complaints_table.php
│   │   ├── 2024_01_01_000002_create_users_table.php (sudah ada dari Laravel, override tidak perlu)
│   │   ├── 2024_01_01_000003_create_categories_table.php
│   │   └── 2024_01_01_000004_create_slas_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── routes/
│   └── web.php
└── resources/
    └── views/
        ├── complaints/
        │   └── create.blade.php (sudah diberikan)
        ├── profile/
        │   └── edit.blade.php
        └── master/
            └── sla.blade.php
```

> 📌 Setiap anggota hanya perlu fokus pada folder:

```bash
app/Domains/[domain masing-masing]
```

---

## ⚙️ Setup Proyek

Ikuti langkah berikut untuk menjalankan project secara lokal:

```bash
# 1. Clone repository
git clone https://github.com/aturrr62/SIGAP-AIR.git

# 2. Masuk ke folder project
cd SIGAP-AIR

# 3. Install dependency PHP
composer install

# 4. Install dependency frontend
npm install

# 5. Copy file environment
cp .env.example .env

# 6. Generate application key
php artisan key:generate

# 7. Konfigurasi database di file .env
# DB_DATABASE=nama_database
# DB_USERNAME=root
# DB_PASSWORD=

# 8. Jalankan migrasi database
php artisan migrate

# 9. Build assets frontend
npm run build

# 10. Jalankan server
php artisan serve
```

---

## 🧪 Testing

Untuk menjalankan automated testing menggunakan Laravel Dusk:

```bash
php artisan dusk
```

---

## 👥 Kontribusi

1. Buat branch baru dari `main`
2. Kerjakan fitur sesuai domain masing-masing
3. Commit perubahan dengan pesan yang jelas
4. Push ke branch
5. Buat Pull Request ke `main`

---

## 🔐 Git Workflow (Disarankan)

* ❌ Tidak diperbolehkan push langsung ke `main`
* ✅ Gunakan Pull Request
* ✅ Minimal 1 approval sebelum merge

---

## 📌 Catatan

* Pastikan `.env` tidak di-commit
* Gunakan struktur domain yang sudah ditentukan
* Ikuti standar penamaan file & class Laravel

---

## 📄 Lisensi

Project ini dibuat untuk keperluan akademik dan pengembangan internal.
