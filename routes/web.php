<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ZonaController;
use App\Http\Controllers\Admin\DaftarPengaduanController;
use App\Http\Controllers\Masyarakat\DashboardController as MasyarakatDashboardController;
use App\Http\Controllers\Masyarakat\NotifikasiController;
use App\Http\Controllers\Masyarakat\PengaduanController;
use App\Http\Controllers\Masyarakat\RiwayatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Supervisor\AssignmentController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;
use App\Http\Controllers\Admin\LaporanKinerjaController;
use App\Http\Controllers\Supervisor\FilterPengaduanController;
use App\Http\Controllers\Supervisor\KinerjaPetugasController;
use App\Http\Controllers\Supervisor\LaporanController;
use App\Http\Controllers\Supervisor\VerifikasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Role: Masyarakat
    Route::middleware(['role:masyarakat'])->prefix('masyarakat')->name('masyarakat.')->group(function () {
        Route::get('/dashboard', [MasyarakatDashboardController::class, 'index'])->name('dashboard');

        // PBI-04 Pengajuan Pengaduan Digital
        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::get('/pengaduan/{pengaduan}/sukses', [PengaduanController::class, 'sukses'])->name('pengaduan.sukses');

        // PBI-10 Riwayat Pengaduan
        Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
        Route::get('/riwayat/{pengaduan}', [RiwayatController::class, 'show'])->name('riwayat.show');

        // PBI-12 Notifikasi
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::patch('/notifikasi/{id}/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
        Route::patch('/notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.read-all');
    });

    // Role: Petugas
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');

        // PBI-07 — Penanganan Tugas
        Route::get('/tugas', [\App\Http\Controllers\Petugas\PenangananController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/{tugas}', [\App\Http\Controllers\Petugas\PenangananController::class, 'show'])->name('tugas.show');
        Route::patch('/tugas/{tugas}', [\App\Http\Controllers\Petugas\PenangananController::class, 'update'])->name('tugas.update');
        Route::get('/riwayat', [\App\Http\Controllers\Petugas\PenangananController::class, 'riwayat'])->name('riwayat');
    });

    // Role: Supervisor
    Route::middleware(['role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');

        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{pengaduan}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::patch('/verifikasi/{pengaduan}', [VerifikasiController::class, 'update'])->name('verifikasi.update');

        Route::get('/filter', [FilterPengaduanController::class, 'index'])->name('filter.index');
        Route::get('/filter/export-csv', [FilterPengaduanController::class, 'exportCsv'])->name('filter.export-csv');

        Route::get('/assignment/{pengaduan}/create', [AssignmentController::class, 'create'])->name('assignment.create');
        Route::post('/assignment/{pengaduan}', [AssignmentController::class, 'store'])->name('assignment.store');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
        Route::get('/kinerja', [KinerjaPetugasController::class, 'index'])->name('kinerja.index');
        Route::get('/kinerja/export-excel', [KinerjaPetugasController::class, 'exportExcel'])->name('kinerja.export-excel');
    });

    // Role: Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengaduan', [DaftarPengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/export-csv', [DaftarPengaduanController::class, 'exportCsv'])->name('pengaduan.export-csv');
        Route::get('/kinerja', [LaporanKinerjaController::class, 'index'])->name('kinerja.index');
        Route::get('/kinerja/export-excel', [LaporanKinerjaController::class, 'exportExcel'])->name('kinerja.export-excel');
        // PBI-01,02,03,09,16,17 routes here
        Route::resource('pelanggan', \App\Http\Controllers\Admin\PelangganController::class);
        Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class)
            ->except(['show']);

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
    });

    // Shared: Admin & Supervisor
    Route::middleware(['role:admin,supervisor'])->group(function () {
        // Shared routes here
    });
});

require __DIR__.'/auth.php';
