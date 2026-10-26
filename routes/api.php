<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SertifikatController;
use App\Http\Controllers\Api\SuratController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ValidasiSertifikatController;
use App\Http\Controllers\Api\ValidasiSuratController;
use App\Http\Controllers\Api\VerifyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — SIAKTIF Mobile
|--------------------------------------------------------------------------
|
| Public routes: /api/verify/*
| Protected routes: requires Authorization: Bearer {token} header
| Unit-scoped routes: also require X-Unit-Id & X-Unit-Role headers
|
*/

// ============================================
// PUBLIC — Verifikasi QR (tanpa login)
// ============================================
Route::prefix('verify')->group(function () {
    Route::get('/surat/{token}', [VerifyController::class, 'surat']);
    Route::get('/sertifikat/{token}', [VerifyController::class, 'sertifikat']);
});

// ============================================
// PUBLIC - Auth (login, register)
// Tanpa middleware guest: request login dari mobile bisa membawa token lama
// yang masih valid; guest:sanctum akan me-redirect dan merusak respons JSON.
// ============================================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ============================================
// AUTHENTICATED (but no unit selection required)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/profile/password', [AuthController::class, 'changePassword']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Settings (public read untuk form dropdown)
    Route::get('/settings', [SettingController::class, 'index']);

    // Unit selection (mobile) — harus sebelum route superadmin /units/{unit}
    Route::get('/units/members', [UnitController::class, 'myMembers']);
    Route::post('/units/select/{member}', [UnitController::class, 'selectMember']);

    // Surat (unit-scoped via X-Unit-Id header)
    Route::get('/surat', [SuratController::class, 'index']);
    Route::post('/surat', [SuratController::class, 'store']);
    Route::get('/surat/settings', [SuratController::class, 'settings']);
    Route::get('/surat/{surat}', [SuratController::class, 'show']);
    Route::put('/surat/{surat}', [SuratController::class, 'update']);
    Route::delete('/surat/{surat}', [SuratController::class, 'destroy']);
    Route::get('/surat/{surat}/download', [SuratController::class, 'download']);

    // Validasi Surat (Kepala/Superadmin — unit-scoped via X-Unit-Id header)
    Route::get('/validasi', [ValidasiSuratController::class, 'index']);
    Route::get('/validasi/{surat}', [ValidasiSuratController::class, 'show']);
    Route::post('/validasi/{surat}/approve', [ValidasiSuratController::class, 'approve']);
    Route::post('/validasi/{surat}/revise', [ValidasiSuratController::class, 'revise']);
    Route::post('/validasi/{surat}/cancel', [ValidasiSuratController::class, 'cancel']);
    Route::post('/validasi/batch-approve', [ValidasiSuratController::class, 'batchApprove']);
    Route::post('/validasi/batch-reject', [ValidasiSuratController::class, 'batchReject']);

    // Sertifikat
    Route::get('/sertifikat', [SertifikatController::class, 'index']);
    Route::post('/sertifikat', [SertifikatController::class, 'store']);
    Route::get('/sertifikat/{sertifikat}', [SertifikatController::class, 'show']);
    Route::put('/sertifikat/{sertifikat}', [SertifikatController::class, 'update']);
    Route::delete('/sertifikat/{sertifikat}', [SertifikatController::class, 'destroy']);
    Route::get('/sertifikat/{sertifikat}/preview', [SertifikatController::class, 'preview']);
    Route::get('/sertifikat/{sertifikat}/download', [SertifikatController::class, 'download']);

    // Validasi Sertifikat (Kepala/Superadmin)
    Route::get('/validasi-sertifikat', [ValidasiSertifikatController::class, 'index']);
    Route::get('/validasi-sertifikat/{sertifikat}', [ValidasiSertifikatController::class, 'show']);
    Route::get('/validasi-sertifikat/{sertifikat}/adjust', [ValidasiSertifikatController::class, 'adjust']);
    Route::post('/validasi-sertifikat/{sertifikat}/generate', [ValidasiSertifikatController::class, 'generate']);
    Route::post('/validasi-sertifikat/{sertifikat}/revise', [ValidasiSertifikatController::class, 'revise']);
    Route::post('/validasi-sertifikat/{sertifikat}/cancel', [ValidasiSertifikatController::class, 'cancel']);

    // ============================================
    // SUPERADMIN ONLY
    // ============================================
    Route::middleware('role:superadmin')->group(function () {
        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        // Units
        Route::get('/units', [UnitController::class, 'index']);
        Route::post('/units', [UnitController::class, 'store']);
        Route::get('/units/{unit}', [UnitController::class, 'show']);
        Route::put('/units/{unit}', [UnitController::class, 'update']);
        Route::delete('/units/{unit}', [UnitController::class, 'destroy']);

        // Unit Members
        Route::post('/units/{unit}/members', [UnitController::class, 'addMember']);
        Route::post('/unit-members/{member}/toggle', [UnitController::class, 'toggleMember']);
        Route::put('/unit-members/{member}/jabatan', [UnitController::class, 'updateMemberJabatan']);
        Route::delete('/unit-members/{member}', [UnitController::class, 'removeMember']);

        // Settings Management
        Route::post('/settings/add', [SettingController::class, 'add']);
        Route::post('/settings/remove', [SettingController::class, 'remove']);
    });
});
