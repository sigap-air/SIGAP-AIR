# 🚀 PBI #11 - Rating Pengaduan Selesai - SETUP & DEPLOYMENT

## ✅ Status: COMPLETED & READY TO DEPLOY

---

## 📋 Quick Checklist Pre-Deployment

### Database
- [x] Migration `2024_01_01_000010_create_rating_feedback_table.php` sudah ada
- [x] Table `rating_feedback` dengan fields: id, pengaduan_id, user_id, rating, komentar, timestamps
- [x] Foreign key `pengaduan_id` dengan UNIQUE constraint dan CASCADE DELETE

### Backend Code
- [x] Model: `app/Models/Rating.php` dengan relasi ke Pengaduan & User
- [x] Controller: `app/Http/Controllers/Masyarakat/RatingController.php` (create & store)
- [x] Routes: `/masyarakat/pengaduan/{nomor_tiket}/rating` (GET & POST)
- [x] Middleware: auth & role:masyarakat

### Frontend Code
- [x] View form: `resources/views/masyarakat/rating/create.blade.php`
- [x] View display: `resources/views/masyarakat/riwayat/show.blade.php`
- [x] Button trigger: "Nilai Sekarang" di halaman detail pengaduan selesai

### Testing
- [x] Feature tests: `tests/Feature/Masyarakat/RatingTest.php` (10 test cases)
- [x] Manual testing scenario documented

### Documentation
- [x] User flow documentation
- [x] Validation & security rules
- [x] Database schema documentation
- [x] Future enhancement ideas

---

## 🔧 Setup Awal (Local Development)

### 1. Pastikan Migration Sudah Dijalankan

```bash
# Jalankan semua migration (jika belum)
php artisan migrate

# atau khusus rating migration saja
php artisan migrate --path=database/migrations/2024_01_01_000010_create_rating_feedback_table.php
```

### 2. Refresh Database (Development)

```bash
# Fresh + Seed
php artisan migrate:fresh --seed

# atau hanya refresh
php artisan migrate:refresh
```

### 3. Test Routes (Development)

```bash
# Lihat semua routes
php artisan route:list | grep rating

# Output yang diharapkan:
# GET|POST  /masyarakat/pengaduan/{nomor_tiket}/rating
```

---

## 🧪 Testing

### Run Feature Tests

```bash
# Run semua test untuk rating
php artisan test tests/Feature/Masyarakat/RatingTest.php

# Dengan verbose output
php artisan test tests/Feature/Masyarakat/RatingTest.php -v

# Run test tertentu
php artisan test tests/Feature/Masyarakat/RatingTest.php --filter=user_can_submit_rating
```

### Expected Output
```
✓ guest_cannot_access_rating_form
✓ user_cannot_rate_other_users_pengaduan
✓ cannot_rate_incomplete_pengaduan
✓ user_can_view_rating_form_for_completed_pengaduan
✓ user_can_submit_rating
✓ cannot_rate_same_pengaduan_twice
✓ rating_validation_required
✓ rating_must_be_between_1_and_5
✓ komentar_optional
✓ komentar_max_500_characters

Tests: 10 passed
```

---

## 📱 Manual Testing Flow

### Test Case 1: Happy Path (Rating Berhasil)

1. **Login sebagai Masyarakat**
   ```
   URL: /login
   Email: masyarakat@test.com
   Password: password
   ```

2. **Buka Riwayat Pengaduan**
   ```
   URL: /masyarakat/pengaduan/riwayat
   Expected: Lihat daftar pengaduan yang sudah dilaporkan
   ```

3. **Klik Detail Pengaduan Selesai**
   ```
   URL: /masyarakat/pengaduan/riwayat/SIGAP-20260523-0001
   Expected: Lihat detail lengkap + card "Berikan Penilaian"
   ```

4. **Klik "Nilai Sekarang"**
   ```
   URL: /masyarakat/pengaduan/SIGAP-20260523-0001/rating
   Expected: Form rating dengan 5 bintang kosong
   ```

5. **Pilih Rating 4 Bintang**
   ```
   Action: Hover & klik bintang ke-4
   Expected: Bintang 1-4 berubah warna kuning, label "Baik"
   ```

6. **Tulis Komentar**
   ```
   Text: "Pelayanan cepat, sangat memuaskan!"
   Expected: Text ter-input di textarea
   ```

7. **Submit Form**
   ```
   Click: "Kirim Penilaian"
   Expected: Redirect ke /masyarakat/pengaduan/riwayat/SIGAP-20260523-0001
             Success message "Terima kasih atas penilaian Anda!"
             Card rating menampilkan 4 bintang + komentar
   ```

### Test Case 2: Prevent Double Rating

1. **Coba Akses Form Rating Lagi**
   ```
   URL: /masyarakat/pengaduan/SIGAP-20260523-0001/rating
   Expected: Error 400 "Sudah memberikan rating."
   ```

### Test Case 3: Unauthorized Access

1. **Login sebagai User Lain**
   ```
   Action: Logout & login dengan masyarakat lain
   ```

2. **Coba Akses Rating Pengaduan User Pertama**
   ```
   URL: /masyarakat/pengaduan/SIGAP-20260523-0001/rating
   Expected: Error 403 Forbidden
   ```

### Test Case 4: Incomplete Pengaduan

1. **Ubah Status Pengaduan ke "diproses"** (via Supervisor/Admin)
   ```
   Status: diproses
   ```

2. **Coba Akses Form Rating**
   ```
   URL: /masyarakat/pengaduan/SIGAP-20260523-0001/rating
   Expected: Error 400 "Pengaduan belum selesai."
   ```

---

## 🚨 Validation Rules

### Frontend Validation (Browser)
- Rating wajib dipilih (required)
- Form tidak bisa submit tanpa rating

### Backend Validation (Server)
```php
[
    'rating'   => 'required|integer|between:1,5',
    'komentar' => 'nullable|string|max:500',
]
```

### Business Logic Validation
```php
// Di RatingController::create()
abort_if($pengaduan->user_id !== auth()->id(), 403);           // Hanya pembuat bisa rate
abort_if($pengaduan->status !== 'selesai', 400);               // Hanya pengaduan selesai
abort_if($pengaduan->rating()->exists(), 400);                 // Hanya 1x rating per pengaduan
```

---

## 📊 Database Checks

### Query untuk Verifikasi Rating Tersimpan

```sql
-- Lihat semua rating
SELECT 
    r.id,
    r.pengaduan_id,
    p.nomor_tiket,
    u.name as pelapor,
    r.rating,
    r.komentar,
    r.created_at
FROM rating_feedback r
JOIN pengaduan p ON r.pengaduan_id = p.id
JOIN users u ON r.user_id = u.id
ORDER BY r.created_at DESC;

-- Cek rata-rata rating per kategori
SELECT 
    kp.nama_kategori,
    COUNT(r.id) as total_rating,
    AVG(r.rating) as rata_rata_rating
FROM rating_feedback r
JOIN pengaduan p ON r.pengaduan_id = p.id
JOIN kategori_pengaduan kp ON p.kategori_id = kp.id
GROUP BY kp.nama_kategori;
```

---

## 🐛 Troubleshooting

### Issue: "Route [masyarakat.rating.create] not defined"

**Cause**: Routes belum ditambahkan ke `routes/web.php`

**Solution**:
```php
// Tambahkan ke routes/web.php
Route::get('/pengaduan/{nomor_tiket}/rating', [RatingController::class, 'create'])->name('rating.create');
Route::post('/pengaduan/{nomor_tiket}/rating', [RatingController::class, 'store'])->name('rating.store');
```

### Issue: "Target class [RatingController] does not exist"

**Cause**: Import RatingController belum ditambahkan

**Solution**:
```php
// Di routes/web.php
use App\Http\Controllers\Masyarakat\RatingController;
```

### Issue: "SQLSTATE[HY000]: General error: 1364 Field 'tanggal_rating' doesn't have a default value"

**Cause**: Model menggunakan `tanggal_rating` tapi database tidak punya field ini atau tidak ada default value

**Solution**:
```php
// Di controller, gunakan 'timestamps' bukan 'tanggal_rating' khusus
// Atau pastikan migration punya field tanggal_rating
// Atau hapus tanggal_rating dan gunakan created_at Laravel default
```

### Issue: Form Rating Tidak Muncul di Halaman Detail

**Cause**: Status pengaduan bukan "selesai"

**Solution**:
1. Ubah status pengaduan ke "selesai" via Supervisor
2. Refresh halaman
3. Card "Berikan Penilaian" seharusnya muncul

---

## 📦 Deployment Checklist

- [ ] Run `php artisan migrate` di production
- [ ] Verify routes: `php artisan route:list | grep rating`
- [ ] Test di staging: submit rating & verify database
- [ ] Check file permissions: `storage/` & `bootstrap/cache/`
- [ ] Monitor error logs: `storage/logs/laravel.log`
- [ ] Verify user roles middleware berfungsi
- [ ] Test akses dari mobile browser
- [ ] Performance test: jangan ada N+1 query

### Performance Check

```php
// Di controller, verify relasi di-eager load jika diperlukan
$pengaduan = Pengaduan::with('rating', 'pelapor')->find($id);
```

---

## 📞 Support & Maintenance

### PIC: Amanda Zuhra Azis (PBI-11)

### Related PBIs
- PBI-04: Pengajuan Pengaduan Digital
- PBI-10: Riwayat Pengaduan
- PBI-12: Notifikasi (future integration)

### Enhancement Ideas (Future)
1. Analytics dashboard untuk melihat trend rating
2. Email notification ke admin saat ada rating baru
3. SMS notification ke petugas jika rating rendah
4. Fitur respons dari petugas ke rating
5. Laporan rating berkala (monthly, quarterly)
6. Rating breakdown chart per kategori/zona

---

**Last Updated**: May 23, 2026
**Status**: ✅ Ready for Production
