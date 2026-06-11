<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\SlaController as AdminSlaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ZonaController;
use App\Http\Controllers\Admin\DaftarPengaduanController;
use App\Http\Controllers\Admin\LaporanKinerjaController;

use App\Http\Controllers\Masyarakat\DashboardController as MasyarakatDashboardController;
use App\Http\Controllers\Masyarakat\NotifikasiController;
use App\Http\Controllers\Masyarakat\PengaduanController;
use App\Http\Controllers\Masyarakat\RiwayatController;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Supervisor\AssignmentController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;
use App\Http\Controllers\Supervisor\FilterPengaduanController;
use App\Http\Controllers\Supervisor\KinerjaPetugasController;
use App\Http\Controllers\Supervisor\LaporanController;
use App\Http\Controllers\Supervisor\VerifikasiController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

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

        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::get('/pengaduan/{pengaduan}/sukses', [PengaduanController::class, 'sukses'])->name('pengaduan.sukses');

        Route::get('/pengaduan/riwayat', [RiwayatController::class, 'index'])->name('pengaduan.riwayat');
        Route::get('/pengaduan/riwayat/{pengaduan}', [RiwayatController::class, 'show'])->name('pengaduan.riwayat.show');

        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::patch('/notifikasi/{id}/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
        Route::patch('/notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.read-all');
    });

    // ================= PETUGAS =================
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/tugas', [\App\Http\Controllers\Petugas\PenangananController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/{tugas}', [\App\Http\Controllers\Petugas\PenangananController::class, 'show'])->name('tugas.show');
        Route::patch('/tugas/{tugas}', [\App\Http\Controllers\Petugas\PenangananController::class, 'update'])->name('tugas.update');

        Route::get('/riwayat', [\App\Http\Controllers\Petugas\PenangananController::class, 'riwayat'])->name('riwayat');
    });

    // ================= SUPERVISOR =================
    Route::middleware(['role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {

        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');

        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{pengaduan}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::patch('/verifikasi/{pengaduan}', [VerifikasiController::class, 'update'])->name('verifikasi.update');

        Route::get('/filter', [FilterPengaduanController::class, 'index'])->name('filter.index');

        Route::get('/assignment/{pengaduan}/create', [AssignmentController::class, 'create'])->name('assignment.create');
        Route::post('/assignment/{pengaduan}', [AssignmentController::class, 'store'])->name('assignment.store');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/kinerja', [KinerjaPetugasController::class, 'index'])->name('kinerja.index');
    });

    // ================= ADMIN =================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/pengaduan', [DaftarPengaduanController::class, 'index'])->name('pengaduan.index');

        Route::get('/kinerja', [LaporanKinerjaController::class, 'index'])->name('kinerja.index');

        Route::get('/sla', [AdminSlaController::class, 'index'])->name('sla.index');

        Route::resource('users', UserController::class);

        Route::resource('petugas', PetugasController::class);
        Route::patch('petugas/{petugas}/status', [PetugasController::class, 'updateStatus'])->name('petugas.update-status');

        Route::resource('zona', ZonaController::class);
    });

    // Shared
    Route::middleware(['role:admin,supervisor'])->group(function () {
        //
    });
});

require __DIR__.'/auth.php';