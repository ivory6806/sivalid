<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UnitSelectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\ValidasiSertifikatController;
use App\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

// Verifikasi QR publik — PENTING: route sertifikat HARUS di atas /verify/{token}
// agar "sertifikat" tidak tertangkap sebagai nilai token
Route::get('/verify/sertifikat/{token}', [VerifyController::class, 'sertifikat'])
    ->name('verify.sertifikat');
Route::get('/verify/{token}', [VerifyController::class, 'show'])
    ->name('verify.show');

// Auth routes dari Breeze
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // --------------------------------------------------
    // Pilih Unit (setelah login, sebelum masuk dashboard)
    // --------------------------------------------------
    Route::get('/pilih-unit', [UnitSelectController::class, 'index'])->name('units.select');
    Route::post('/pilih-unit/{member}', [UnitSelectController::class, 'select'])->name('units.select.choose');
    Route::get('/ganti-unit', [UnitSelectController::class, 'switch'])->name('units.switch');

    // --------------------------------------------------
    // Dashboard
    // --------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('unit.selected')
        ->name('dashboard');

    // --------------------------------------------------
    // SURAT — route spesifik HARUS di atas route parameter
    // --------------------------------------------------
    Route::middleware('unit.selected')->group(function () {
        Route::get('/surat/create', [SuratController::class, 'create'])
            ->name('surat.create');
        Route::post('/surat', [SuratController::class, 'store'])
            ->name('surat.store');
        Route::get('/surat/{surat}/download', [SuratController::class, 'download'])
            ->name('surat.download')
            ->where('surat', '[0-9]+');
        Route::get('/surat/{surat}/edit', [SuratController::class, 'edit'])
            ->name('surat.edit')
            ->where('surat', '[0-9]+');
        Route::put('/surat/{surat}', [SuratController::class, 'update'])
            ->name('surat.update')
            ->where('surat', '[0-9]+');
        Route::get('/surat/preview/{surat}', [SuratController::class, 'preview'])
            ->name('surat.preview')
            ->where('surat', '[0-9]+');
        Route::get('/surat/{surat}', [SuratController::class, 'show'])
            ->name('surat.show')
            ->where('surat', '[0-9]+');
        Route::get('/surat', [SuratController::class, 'index'])
            ->name('surat.index');
        Route::delete('/surat/{surat}', [SuratController::class, 'destroy'])
            ->name('surat.destroy')
            ->where('surat', '[0-9]+');
    });

    // --------------------------------------------------
    // Validasi Surat (Kepala Staff & Superadmin)
    // --------------------------------------------------
    Route::middleware(['role:kepala,superadmin', 'unit.selected'])->group(function () {
        Route::get('/validasi', [ValidasiController::class, 'index'])
            ->name('validasi.index');
            
        // 👇 Dari File 1: Atur QR & Approve posisi
        Route::get('/validasi/{surat}/atur-qr', [ValidasiController::class, 'aturQr'])
            ->name('validasi.atur-qr')
            ->where('surat', '[0-9]+');
        Route::post('/validasi/{surat}/approve-position', [ValidasiController::class, 'approveWithPosition'])
            ->name('validasi.approve-position')
            ->where('surat', '[0-9]+');
        // 👆 Akhir dari File 1

        Route::post('/validasi/{surat}/approve', [ValidasiController::class, 'approve'])
            ->name('validasi.approve')
            ->where('surat', '[0-9]+');
        Route::post('/validasi/{surat}/revise', [ValidasiController::class, 'revise'])
            ->name('validasi.revise')
            ->where('surat', '[0-9]+');
        Route::post('/validasi/cancel/{surat}', [ValidasiController::class, 'cancel'])
            ->name('validasi.cancel')
            ->where('surat', '[0-9]+');
        Route::post('/validasi/batch-approve', [ValidasiController::class, 'batchApprove'])
            ->name('validasi.batch-approve');
        Route::post('/validasi/batch-reject', [ValidasiController::class, 'batchReject'])
            ->name('validasi.batch-reject');
    });

    // --------------------------------------------------
    // SERTIFIKAT — route spesifik HARUS di atas route parameter
    // --------------------------------------------------
    Route::middleware('unit.selected')->group(function () {
        Route::get('/sertifikat/create', [SertifikatController::class, 'create'])
            ->name('sertifikat.create');
        Route::post('/sertifikat', [SertifikatController::class, 'store'])
            ->name('sertifikat.store');
        Route::get('/sertifikat/{sertifikat}/download', [SertifikatController::class, 'download'])
            ->name('sertifikat.download')
            ->where('sertifikat', '[0-9]+');
        Route::get('/sertifikat/{sertifikat}/preview', [SertifikatController::class, 'preview'])
            ->name('sertifikat.preview')
            ->where('sertifikat', '[0-9]+');
        Route::get('/sertifikat/{sertifikat}/edit', [SertifikatController::class, 'edit'])
            ->name('sertifikat.edit')
            ->where('sertifikat', '[0-9]+');
        Route::put('/sertifikat/{sertifikat}', [SertifikatController::class, 'update'])
            ->name('sertifikat.update')
            ->where('sertifikat', '[0-9]+');
        Route::get('/sertifikat/{sertifikat}', [SertifikatController::class, 'show'])
            ->name('sertifikat.show')
            ->where('sertifikat', '[0-9]+');
        Route::get('/sertifikat', [SertifikatController::class, 'index'])
            ->name('sertifikat.index');
        Route::delete('/sertifikat/{sertifikat}', [SertifikatController::class, 'destroy'])
            ->name('sertifikat.destroy')
            ->where('sertifikat', '[0-9]+');
    });

    // --------------------------------------------------
    // Validasi Sertifikat (Kepala Staff & Superadmin)
    // --------------------------------------------------
    Route::middleware(['role:kepala,superadmin', 'unit.selected'])->group(function () {
        Route::get('/validasi-sertifikat', [ValidasiSertifikatController::class, 'index'])
            ->name('validasi-sertifikat.index');
        Route::get('/validasi-sertifikat/{sertifikat}/adjust', [ValidasiSertifikatController::class, 'adjust'])
            ->name('validasi-sertifikat.adjust')
            ->where('sertifikat', '[0-9]+');
        Route::post('/validasi-sertifikat/{sertifikat}/generate', [ValidasiSertifikatController::class, 'generate'])
            ->name('validasi-sertifikat.generate')
            ->where('sertifikat', '[0-9]+');
        Route::post('/validasi-sertifikat/{sertifikat}/revise', [ValidasiSertifikatController::class, 'revise'])
            ->name('validasi-sertifikat.revise')
            ->where('sertifikat', '[0-9]+');
        Route::post('/validasi-sertifikat/{sertifikat}/cancel', [ValidasiSertifikatController::class, 'cancel'])
            ->name('validasi-sertifikat.cancel')
            ->where('sertifikat', '[0-9]+');
    });

    // --------------------------------------------------
    // Users, Settings & Kelola Unit (Superadmin)
    // --------------------------------------------------
    Route::middleware('role:superadmin')->group(function () {
        // Users
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')
            ->where('user', '[0-9]+');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')
            ->where('user', '[0-9]+');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')
            ->where('user', '[0-9]+');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/add', [SettingController::class, 'add'])->name('settings.add');
        Route::post('/settings/remove', [SettingController::class, 'remove'])->name('remove');

        // Kelola Unit
        Route::get('/units', [UnitController::class, 'index'])->name('units.index');
        Route::get('/units/create', [UnitController::class, 'create'])->name('units.create');
        Route::post('/units', [UnitController::class, 'store'])->name('units.store');
        Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
        Route::put('/units/{unit}', [UnitController::class, 'update'])->name('units.update');
        Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');

        // Kelola Anggota Unit
        Route::post('/units/{unit}/members', [UnitController::class, 'addMember'])->name('units.members.add');
        Route::post('/unit-members/{member}/toggle', [UnitController::class, 'toggleMember'])->name('units.members.toggle');
        // 👇 Dari File 1: Update jabatan anggota
        Route::put('/unit-members/{member}/jabatan', [UnitController::class, 'updateMemberJabatan'])->name('units.members.jabatan');
        Route::delete('/unit-members/{member}', [UnitController::class, 'removeMember'])->name('units.members.remove');
    });

    // --------------------------------------------------
    // Profile (Semua role yang login)
    // --------------------------------------------------
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});