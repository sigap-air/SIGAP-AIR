<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\PetugasController as AdminPetugasController;
use App\Http\Controllers\Admin\SlaController as AdminSlaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ZonaController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\DaftarPengaduanController;
use App\Http\Controllers\Admin\LaporanKinerjaController;

use App\Http\Controllers\Masyarakat\DashboardController as MasyarakatDashboardController;
use App\Http\Controllers\Masyarakat\PengaduanController;
use App\Http\Controllers\Masyarakat\RatingController;
use App\Http\Controllers\Masyarakat\RiwayatController;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Supervisor\AssignmentController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;
use App\Http\Controllers\Supervisor\MonitorSlaController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Supervisor\FilterPengaduanController;
use App\Http\Controllers\Supervisor\KinerjaPetugasController;
use App\Http\Controllers\Supervisor\LaporanController;
use App\Http\Controllers\Supervisor\ProfilController as SupervisorProfilController;
use App\Http\Controllers\Supervisor\VerifikasiController;
use App\Http\Controllers\Supervisor\ZonaController as SupervisorZonaController;
use App\Http\Controllers\Supervisor\ManajemenPetugasController;
use App\Http\Controllers\Supervisor\BebanPenangananController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route profil global (dipakai oleh semua layout/navbar)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // PBI-12 Notifikasi (Global for all authenticated users)
    Route::get('/notifikasi', [\App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/count', [\App\Http\Controllers\NotifikasiController::class, 'count'])->name('notifikasi.count');
    Route::post('/notifikasi/baca-semua', [\App\Http\Controllers\NotifikasiController::class, 'markAllRead'])->name('notifikasi.baca-semua');
    Route::get('/notifikasi/{id}/baca', [\App\Http\Controllers\NotifikasiController::class, 'markRead'])->name('notifikasi.baca');

    // GLOBAL NOTIFIKASI
    Route::get('/notifikasi', [\App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/count', [\App\Http\Controllers\NotifikasiController::class, 'count'])->name('notifikasi.count');
    Route::post('/notifikasi/baca-semua', [\App\Http\Controllers\NotifikasiController::class, 'markAllRead'])->name('notifikasi.baca-semua');
    Route::get('/notifikasi/{id}/baca', [\App\Http\Controllers\NotifikasiController::class, 'markRead'])->name('notifikasi.baca');

    // ================= MASYARAKAT =================
    Route::middleware(['role:masyarakat'])->prefix('masyarakat')->name('masyarakat.')->group(function () {

        Route::get('/dashboard', [MasyarakatDashboardController::class, 'index'])->name('dashboard');

        Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [ProfileController::class, 'update'])->name('profil.update');

        // PBI-08: Kelola Profil Masyarakat
        Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [ProfileController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('profil.update-password');
        Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profil.destroy');

        // PBI-04 Pengajuan Pengaduan Digital
        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::post('/pengaduan/validate-zona', [PengaduanController::class, 'validateZona'])->name('pengaduan.validate-zona');
        Route::get('/pengaduan/{pengaduan}/sukses', [PengaduanController::class, 'sukses'])->name('pengaduan.sukses');

        Route::get('/pengaduan/riwayat', [RiwayatController::class, 'index'])->name('pengaduan.riwayat');
        Route::get('/pengaduan/riwayat/{pengaduan}', [RiwayatController::class, 'show'])->name('pengaduan.riwayat.show');

        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::patch('/notifikasi/{id}/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
        Route::patch('/notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.read-all');
        // PBI-10 Riwayat Pengaduan
        // Routes: GET /masyarakat/pengaduan/riwayat & /masyarakat/pengaduan/riwayat/{pengaduan}
        Route::get('/pengaduan/riwayat', [RiwayatController::class, 'index'])->name('pengaduan.riwayat');
        Route::get('/pengaduan/riwayat/{pengaduan}/revisi', [PengaduanController::class, 'editRevisi'])->name('pengaduan.revisi.edit');
        Route::put('/pengaduan/riwayat/{pengaduan}/revisi', [PengaduanController::class, 'updateRevisi'])->name('pengaduan.revisi.update');
        Route::get('/pengaduan/riwayat/{pengaduan}', [RiwayatController::class, 'show'])->name('pengaduan.riwayat.show');

        // PBI-11 Rating & Feedback (hanya setelah pengaduan selesai)
        Route::get('/pengaduan/{nomor_tiket}/rating', [RatingController::class, 'create'])->name('rating.create');
        Route::post('/pengaduan/{nomor_tiket}/rating', [RatingController::class, 'store'])->name('rating.store');

        // PBI-12 Notifikasi
        Route::get('/notifikasi', [\App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::patch('/notifikasi/{id}/read', [\App\Http\Controllers\NotifikasiController::class, 'markRead'])->name('notifikasi.read');
        Route::patch('/notifikasi/read-all', [\App\Http\Controllers\NotifikasiController::class, 'markAllRead'])->name('notifikasi.read-all');

    });

    // ================= PETUGAS =================
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/tugas', [\App\Http\Controllers\Petugas\PenangananController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/{tugas}', [\App\Http\Controllers\Petugas\PenangananController::class, 'show'])->name('tugas.show');
        Route::patch('/tugas/{tugas}', [\App\Http\Controllers\Petugas\PenangananController::class, 'update'])->name('tugas.update');

        Route::get('/riwayat', [\App\Http\Controllers\Petugas\PenangananController::class, 'riwayat'])->name('riwayat');

        // PBI-24: Profil & Status Petugas
        Route::get('/profil', [\App\Http\Controllers\Petugas\ProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [\App\Http\Controllers\Petugas\ProfilController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [\App\Http\Controllers\Petugas\ProfilController::class, 'updatePassword'])->name('profil.update-password');
    });

    // ================= SUPERVISOR =================
    Route::middleware(['role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {

        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [SupervisorDashboardController::class, 'stats'])->name('dashboard.stats');

        // PBI-27: Kelola Profil Supervisor
        Route::get('/profil', [SupervisorProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [SupervisorProfilController::class, 'update'])->name('profil.update');

        // PBI-08: Kelola Profil Supervisor
        Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [ProfileController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('profil.update-password');

        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{pengaduan}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::patch('/verifikasi/{pengaduan}', [VerifikasiController::class, 'update'])->name('verifikasi.update');

        Route::get('/filter', [FilterPengaduanController::class, 'index'])->name('filter.index');
        Route::get('/filter/export-csv', [FilterPengaduanController::class, 'exportCsv'])->name('filter.export-csv');
        Route::get('/pengaduan/{pengaduan}', [FilterPengaduanController::class, 'show'])->name('pengaduan.show');

        Route::get('/assignment', [AssignmentController::class, 'index'])->name('assignment.index');
        Route::get('/assignment/{pengaduan}/create', [AssignmentController::class, 'create'])->name('assignment.create');
        Route::post('/assignment/{pengaduan}', [AssignmentController::class, 'store'])->name('assignment.store');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/kinerja', [KinerjaPetugasController::class, 'index'])->name('kinerja.index');
        Route::get('/kinerja/export-excel', [KinerjaPetugasController::class, 'exportExcel'])->name('kinerja.export-excel');

        // PBI-36: Monitoring Beban Penanganan Pengaduan
        Route::get('/beban-penanganan', [BebanPenangananController::class, 'index'])->name('beban-penanganan.index');

        // PBI-09: Monitor SLA & Alert Overdue
        Route::get('/monitor-sla', [MonitorSlaController::class, 'index'])->name('monitor-sla.index');

        // PBI-21: Zona Wilayah (read-only untuk Supervisor)
        Route::get('/zona',       [SupervisorZonaController::class, 'index'])->name('zona.index');
        Route::get('/zona/{id}',  [SupervisorZonaController::class, 'show'])->name('zona.show');
        // PBI-17 — Manajemen Petugas Teknis (Supervisor)
        Route::get('/petugas/status', [ManajemenPetugasController::class, 'status'])->name('petugas.status');
        Route::get('/petugas', [ManajemenPetugasController::class, 'index'])->name('petugas.index');
        Route::get('/petugas/{petugas}', [ManajemenPetugasController::class, 'show'])->name('petugas.show');
        Route::post('/petugas/{petugas}/assign', [ManajemenPetugasController::class, 'assign'])->name('petugas.assign');
        Route::patch('/petugas/{petugas}/status', [PetugasController::class, 'updateStatus'])->name('petugas.update-status');
    });

    // ================= ADMIN =================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/pengaduan', [DaftarPengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/export-csv', [DaftarPengaduanController::class, 'exportCsv'])->name('pengaduan.export-csv');
        // ✅ SLA (punyamu)
        Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [ProfileController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('profil.update-password');
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats'])->name('dashboard.stats');
        Route::get('/pengaduan', [DaftarPengaduanController::class, 'index'])->name('pengaduan.index');

        Route::get('/kinerja', [LaporanKinerjaController::class, 'index'])->name('kinerja.index');

        Route::get('/sla', [AdminSlaController::class, 'index'])->name('sla.index');

        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        Route::resource('petugas', PetugasController::class);
        Route::patch('petugas/{petugas}/status', [PetugasController::class, 'updateStatus'])->name('petugas.update-status');

        Route::resource('zona', ZonaController::class);

        Route::resource('pelanggan', \App\Http\Controllers\Admin\PelangganController::class);
        Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class)->except(['show']);
        Route::get('/kinerja/export-excel', [LaporanKinerjaController::class, 'exportExcel'])->name('kinerja.export-excel');
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [AdminLaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
        // PBI-09: Konfigurasi SLA per Kategori
        Route::get('/sla', [AdminSlaController::class, 'index'])->name('sla.index');
        Route::get('/sla/{sla}/edit', [AdminSlaController::class, 'edit'])->name('sla.edit');
        Route::patch('/sla/{sla}', [AdminSlaController::class, 'update'])->name('sla.update');
        Route::resource('pelanggan', \App\Http\Controllers\Admin\PelangganController::class);
        Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class)
            ->except(['show']);
            
        // Manajemen User
        Route::resource('user', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
        Route::post('user/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('user.reset-password');

        // PBI-16 — Kelola Data Petugas Teknis & PBI-17 — Manajemen Petugas Teknis
        Route::resource('petugas', AdminPetugasController::class)->parameters(['petugas' => 'petugas']);
        // Hapus permanen petugas (hard delete)
        Route::delete('petugas/{petugas}/hapus-permanen', [AdminPetugasController::class, 'hapusPermanen'])->name('petugas.hapus-permanen');
        // PBI-16 / PBI-17 — Kelola & Manajemen Petugas Teknis
        Route::resource('petugas', PetugasController::class)->parameters(['petugas' => 'petugas']);

        // PBI-16 — Manajemen User & Role
        Route::post('user/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('user.reset-password');
        Route::resource('user', \App\Http\Controllers\Admin\UserController::class);

        // PBI-16 — Kelola Data Petugas Teknis & PBI-17 — Manajemen Petugas Teknis
        Route::resource('petugas', AdminPetugasController::class)->parameters(['petugas' => 'petugas']);
        // Hapus permanen petugas (hard delete)
        Route::delete('petugas/{petugas}/hapus-permanen', [AdminPetugasController::class, 'hapusPermanen'])->name('petugas.hapus-permanen');
        // PBI-16 / PBI-17 — Kelola & Manajemen Petugas Teknis
        Route::resource('petugas', PetugasController::class)->parameters(['petugas' => 'petugas']);
        // ✅ USER ROLE (temenmu)
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // ✅ PETUGAS (FIX duplicate)
        Route::resource('petugas', AdminPetugasController::class);
        Route::patch('petugas/{petugas}/status', [PetugasController::class, 'updateStatus'])->name('petugas.update-status');
        Route::delete('petugas/{petugas}/hapus-permanen', [PetugasController::class, 'hapusPermanen'])->name('petugas.hapus-permanen');

        // Zona
        Route::resource('zona', ZonaController::class);
        // PBI-03 — Zona Wilayah & Pemetaan Petugas
        Route::get('zona',                              [ZonaController::class, 'index'])->name('zona.index');
        Route::get('zona/create',                       [ZonaController::class, 'create'])->name('zona.create');
        Route::post('zona',                             [ZonaController::class, 'store'])->name('zona.store');
        Route::get('zona/{id}',                         [ZonaController::class, 'show'])->name('zona.show');
        Route::get('zona/{id}/edit',                    [ZonaController::class, 'edit'])->name('zona.edit');
        Route::put('zona/{id}',                         [ZonaController::class, 'update'])->name('zona.update');
        Route::delete('zona/{id}',                      [ZonaController::class, 'destroy'])->name('zona.destroy');
        Route::post('zona/{id}/assign-petugas',         [ZonaController::class, 'assignPetugas'])->name('zona.assign-petugas');
        Route::delete('zona/{id}/remove-petugas/{petugasId}', [ZonaController::class, 'removePetugas'])->name('zona.remove-petugas');

        // CRUD Pengumuman Layanan
        Route::resource('announcements', AnnouncementController::class);
    });

    // Shared
    Route::middleware(['role:admin,supervisor'])->group(function () {
        //
    });

});
require __DIR__.'/auth.php';
require __DIR__.'/auth.php';

// Route temporary untuk reset data petugas (PBI-16)
Route::get('/clear-petugas-temp', function () {
    // Disable foreign key checks untuk SQLite/MySQL
    \Illuminate\Support\Facades\DB::statement('PRAGMA foreign_keys=OFF;');
    \App\Models\Assignment::truncate(); 
    \App\Models\Petugas::truncate();
    \App\Models\User::where('role', 'petugas')->delete();
    \Illuminate\Support\Facades\DB::statement('PRAGMA foreign_keys=ON;');
    return 'Semua data petugas berhasil dihapus dan dikosongkan!';
});
