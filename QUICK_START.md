# SIGAP-AIR Quick Commands Reference

## ⚠️ PENTING: Database Port Configuration
MySQL server untuk SIGAP-AIR berjalan di port **3309** (bukan default 3306).

### Untuk Windows CMD:
```batch
set DB_PORT=3309
```

### Untuk PowerShell:
```powershell
$env:DB_PORT = "3309"
```

---

## 🚀 Common Commands

### 1. **Start Development Server**
**Batch (CMD):**
```batch
run-server.bat
```

**PowerShell:**
```powershell
.\run-server.ps1
```

**Manual:**
```bash
set DB_PORT=3309
php artisan serve --port=8000
```

---

### 2. **Run Migrations Only**
```bash
set DB_PORT=3309
php artisan migrate
```

---

### 3. **Run Migrations + Fresh Seed**
```bash
set DB_PORT=3309
php artisan migrate:refresh --seed
```

---

### 4. **Seed Database dengan Test Data**
```bash
set DB_PORT=3309
php artisan db:seed
```

---

### 5. **Check Migration Status**
```bash
set DB_PORT=3309
php artisan migrate:status
```

---

### 6. **Clear All Cache**
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 📋 Test Accounts (sudah ter-seed)

| Akun | Email | Username | Password | Role |
|------|-------|----------|----------|------|
| Admin | admin@sigapair.test | admin | password | admin |
| Supervisor | supervisor@sigapair.test | supervisor | password | supervisor |
| Petugas | petugas@sigapair.test | petugas | password | petugas |
| Masyarakat | masyarakat@sigapair.test | masyarakat | password | masyarakat |

---

## 🌐 URLs

| URL | Deskripsi |
|-----|-----------|
| http://localhost:8000 | Home / Welcome |
| http://localhost:8000/login | Login Page |
| http://localhost:8000/register | Register Page |
| http://localhost:8000/dashboard | Main Dashboard (after login) |
| http://localhost:8000/admin/dashboard | Admin Dashboard |
| http://localhost:8000/supervisor/dashboard | Supervisor Dashboard |
| http://localhost:8000/petugas/dashboard | Petugas Dashboard |
| http://localhost:8000/pengaduan/dashboard | Masyarakat Dashboard |

---

## 🔧 Troubleshooting

### Database Connection Refused?
1. Pastikan MySQL service berjalan pada port 3309
2. Cek .env file: `DB_PORT=3309`
3. Clear config: `php artisan config:clear`
4. Gunakan helper script: `run-server.bat` atau `run-server.ps1`

### Still Having Issues?
- Cek database: `mysql -u root -h 127.0.0.1 -P 3309`
- Verify port: `netstat -ano | findstr 3309`
- Check process: `Get-Process mysqld`

---

## 📝 Catatan Penting

- **DB_PORT=3309** harus di-set setiap kali menjalankan artisan command
- Gunakan helper script (`run-server.bat` / `run-server.ps1`) agar otomatis
- Jangan lupa clear config cache jika ada perubahan di .env
- Password untuk semua test accounts adalah: `password`

