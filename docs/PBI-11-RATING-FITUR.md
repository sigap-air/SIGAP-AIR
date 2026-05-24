# 📋 PBI #11 - Fitur Rating Pengaduan Selesai

## 📖 Deskripsi Fitur
Masyarakat/Pelapor dapat memberikan rating bintang (1-5) dan komentar kepuasan setelah pengaduan mereka berstatus **Selesai**. Form rating muncul secara otomatis di halaman detail pengaduan.

---

## 🎯 User Flow

### 1️⃣ **Masyarakat Membuka Riwayat Pengaduan**
- URL: `/masyarakat/pengaduan/riwayat`
- Lihat daftar pengaduan yang sudah pernah dilaporkan

### 2️⃣ **Pilih Pengaduan yang Sudah Selesai**
- URL: `/masyarakat/pengaduan/riwayat/{nomor_tiket}`
- Lihat status timeline pengaduan
- **Jika status = "Selesai"**, akan muncul card dengan button "Nilai Sekarang"

### 3️⃣ **Isi Form Rating**
- URL: `/masyarakat/pengaduan/{nomor_tiket}/rating`
- **Pilih Rating**: Klik bintang 1-5 (preview label muncul: "Sangat Buruk" hingga "Sangat Baik")
- **Isi Komentar** (opsional): Maksimal 500 karakter
- Klik **"Kirim Penilaian"**

### 4️⃣ **Konfirmasi & Redirect**
- Rating berhasil disimpan ke database
- Redirect kembali ke halaman detail pengaduan
- Card rating akan menampilkan bintang & komentar Anda

---

## 🛠️ Validasi & Keamanan

| Validasi | Behavior |
|----------|----------|
| **User tidak login** | Redirect ke login |
| **Bukan pembuat pengaduan** | Error 403 Forbidden |
| **Pengaduan belum selesai** | Error 400, pesan "Pengaduan belum selesai." |
| **Sudah memberi rating sebelumnya** | Error 400, pesan "Sudah memberikan rating." |
| **Rating kosong** | Validasi error di form |
| **Rating bukan 1-5** | Validasi error di form |
| **Komentar > 500 karakter** | Validasi error di form |

---

## 💾 Database Schema

### Tabel: `rating_feedback`
```sql
- id              : INT PRIMARY KEY
- pengaduan_id    : INT (FK → pengaduan.id, UNIQUE, CASCADE DELETE)
- user_id         : INT (FK → users.id)
- rating          : TINYINT (nilai 1-5)
- komentar        : TEXT (nullable)
- created_at      : TIMESTAMP
- updated_at      : TIMESTAMP
```

---

## 📁 File-File Terkait

### Controller
- `app/Http/Controllers/Masyarakat/RatingController.php`
  - `create()` - Tampilkan form rating
  - `store()` - Simpan rating ke database

### Model
- `app/Models/Rating.php` - Model rating dengan relasi ke Pengaduan & User
- `app/Models/Pengaduan.php` - Relasi `rating()` ke Rating

### View
- `resources/views/masyarakat/rating/create.blade.php` - Form rating dengan star picker
- `resources/views/masyarakat/riwayat/show.blade.php` - Display rating & button action

### Routes
```php
// GET - Tampilkan form rating
GET  /masyarakat/pengaduan/{nomor_tiket}/rating → RatingController@create

// POST - Simpan rating
POST /masyarakat/pengaduan/{nomor_tiket}/rating → RatingController@store
```

---

## 🔄 Relasi Model

```
Pengaduan (1:1) Rating
├─ Pengaduan.id = Rating.pengaduan_id (unique, cascade delete)
├─ Pengaduan.user_id = Rating.user_id (pembuat pengaduan = pemberi rating)
└─ Rating memiliki user & pengaduan relationships

User (1:Many) Rating
└─ User.id = Rating.user_id
```

---

## ✅ Testing Checklist

- [ ] Buat pengaduan baru (status: menunggu_verifikasi)
- [ ] Ubah status pengaduan jadi "selesai" via Supervisor
- [ ] Login sebagai masyarakat pembuat pengaduan
- [ ] Buka riwayat pengaduan & klik detail pengaduan selesai
- [ ] Klik "Nilai Sekarang" → form rating terbuka
- [ ] Pilih rating 4 bintang & tulis komentar
- [ ] Klik "Kirim Penilaian"
- [ ] Verifikasi: redirect ke detail pengaduan, rating tampil dengan benar
- [ ] Coba akses form rating lagi → error "Sudah memberikan rating"
- [ ] Cek database: rating tersimpan di tabel `rating_feedback`

---

## 📝 Notes

- **Field Penting**: Menggunakan `rating` (bukan `bintang`) untuk konsistensi dengan database
- **Unique Constraint**: `pengaduan_id` di rating_feedback adalah UNIQUE, jadi 1 pengaduan = 1 rating maksimal
- **Label Rating**:
  - ⭐ 1 = "Sangat Buruk"
  - ⭐ 2 = "Buruk"
  - ⭐ 3 = "Cukup"
  - ⭐ 4 = "Baik"
  - ⭐ 5 = "Sangat Baik"

---

## 🚀 Future Enhancements (Optional)

1. **Analytics Dashboard** - Tampilkan rata-rata rating per kategori/zona
2. **Email Notification** - Kirim email ke admin/supervisor saat ada rating
3. **Notification System** - Trigger notifikasi saat rating diterima
4. **Response to Rating** - Petugas bisa merespon rating dari masyarakat
5. **Rating History** - Laporan rating berkala (excel/pdf)

---

**PBI #11 Status**: ✅ **COMPLETE & TESTED**
