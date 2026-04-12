<?php
/**
 * ROUTES SIGAP-AIR
 *
 * Panduan untuk semua developer:
 * - Tambahkan route KAMU di blok grup yang sesuai dengan role
 * - Jangan mengubah route milik developer lain
 * - Gunakan resource route jika memungkinkan
 *
 * Legenda PBI per developer:
 *   ARTHUR   → PBI 1, 2, 3  (Admin: master data)
 *   SANITRA  → PBI 4, 5, 6  (Pengaduan + verifikasi + assignment)
 *   FALAH    → PBI 7, 8, 9  (Tracking + profil + SLA)
 *   AMANDA   → PBI 10, 11, 12 (Riwayat + rating + notifikasi)
 *   IMANUEL  → PBI 13, 14, 15 (Filter + laporan + dashboard)
 *   FARISHA  → PBI 16, 17, 18 (User management + petugas + kinerja)
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{Admin, Supervisor, Petugas, Masyarakat};

// ============================================================
// PUBLIC ROUTES (tanpa login)
// ============================================================
Route::get('/', fn() => redirect()->route('login'));

// ============================================================
// AUTHENTICATED ROUTES
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // --------------------------------------------------------
    // MASYARAKAT / PELAPOR
    // Role: masyarakat
    // --------------------------------------------------------
    Route::middleware('role:masyarakat')->prefix('masyarakat')->name('masyarakat.')->group(function () {
        Route::get('/dashboard', [Masyarakat\DashboardController::class, 'index'])->name('dashboard');

        // PBI-04 | SANITRA — Pengajuan pengaduan baru
        Route::get('pengaduan/{pengaduan}/sukses', [Masyarakat\PengaduanController::class, 'sukses'])->name('pengaduan.sukses');
        Route::resource('pengaduan', Masyarakat\PengaduanController::class)->only(['create', 'store']);

        // PBI-10 | AMANDA — Riwayat pengaduan
        Route::resource('riwayat', Masyarakat\RiwayatController::class)->only(['index', 'show']);

        // PBI-11 | AMANDA — Rating kepuasan
        Route::resource('rating', Masyarakat\RatingController::class)->only(['create', 'store']);

        Route::get('/notifikasi', [Masyarakat\NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::patch('/notifikasi/{id}/baca', [Masyarakat\NotifikasiController::class, 'markRead'])->name('notifikasi.baca');
        Route::patch('/notifikasi/baca-semua', [Masyarakat\NotifikasiController::class, 'markAllRead'])->name('notifikasi.baca-semua');
    });

    // --------------------------------------------------------
    // PETUGAS TEKNIS
    // Role: petugas
    // --------------------------------------------------------
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [Petugas\DashboardController::class, 'index'])->name('dashboard');

        // PBI-07 | FALAH — Update status penanganan + upload foto
        Route::resource('tugas', Petugas\PenangananController::class)->only(['index', 'show', 'update']);

        // PBI-08 | FALAH — Manajemen profil
        Route::get('/profil/edit', [Petugas\ProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [Petugas\ProfilController::class, 'update'])->name('profil.update');
    });

    // --------------------------------------------------------
    // SUPERVISOR & ADMIN — SHARED FEATURES
    // Role: supervisor, admin
    // Fitur laporan, filter, kinerja accessible oleh kedua role
    // --------------------------------------------------------
    Route::middleware('role:admin,supervisor')->prefix('reports')->name('reports.')->group(function () {
        // PBI-13 | IMANUEL — Filter & pencarian pengaduan (shared)
        Route::get('/filter', [Supervisor\FilterPengaduanController::class, 'index'])->name('filter.index');

        // PBI-14 | IMANUEL — Laporan rekap + export PDF (shared)
        Route::get('/laporan', [Supervisor\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [Supervisor\LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');

        // PBI-18 | FARISHA — Laporan kinerja petugas + export Excel (shared)
        Route::get('/kinerja', [Admin\LaporanKinerjaController::class, 'index'])->name('kinerja.index');
        Route::get('/kinerja/export-excel', [Admin\LaporanKinerjaController::class, 'exportExcel'])->name('kinerja.export-excel');
    });

    // --------------------------------------------------------
    // SUPERVISOR PDAM (ONLY)
    // Role: supervisor
    // --------------------------------------------------------
    Route::middleware('role:supervisor')->prefix('supervisor')->name('supervisor.')->group(function () {
        // PBI-15 | IMANUEL — Dashboard statistik
        Route::get('/dashboard', [Supervisor\DashboardController::class, 'index'])->name('dashboard');

        // PBI-05 | SANITRA — Verifikasi pengaduan
        Route::resource('verifikasi', Supervisor\VerifikasiController::class)->only(['index', 'show', 'update']);

        // PBI-06 | SANITRA — Assignment petugas
        Route::get('assignment/{pengaduan}/create', [Supervisor\AssignmentController::class, 'create'])->name('assignment.create');
        Route::post('assignment/{pengaduan}', [Supervisor\AssignmentController::class, 'store'])->name('assignment.store');
    });

    // --------------------------------------------------------
    // ADMIN SISTEM
    // Role: admin
    // --------------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // PBI-01 | ARTHUR — CRUD data pelanggan PDAM
        Route::resource('pelanggan', Admin\PelangganController::class);

        // PBI-02 | ARTHUR — CRUD kategori + SLA
        Route::resource('kategori', Admin\KategoriController::class);

        // PBI-03 | ARTHUR — CRUD zona wilayah + mapping petugas
        Route::resource('zona', Admin\ZonaController::class);

        // PBI-09 | FALAH — Konfigurasi SLA
        Route::resource('sla', Petugas\SlaController::class)->only(['index', 'edit', 'update']);

        // PBI-16 | FARISHA — Manajemen user + role
        Route::resource('user', Admin\UserController::class);
        Route::post('user/{user}/reset-password', [Admin\UserController::class, 'resetPassword'])->name('user.reset-password');

        // PBI-17 | FARISHA — Manajemen petugas teknis
        Route::resource('petugas', Admin\PetugasController::class);
    });

});

require __DIR__.'/auth.php';
