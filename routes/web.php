<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KontenController;
use App\Http\Controllers\Login\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\WebsiteSettingController;
use Illuminate\Support\Facades\Route;

// ── Halaman publik (tanpa login) ─────────────────────────────
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/informasi', [BerandaController::class, 'daftar'])->name('publik.konten.index');
Route::get('/informasi/{slug}', [BerandaController::class, 'detail'])->name('publik.konten.show');

// ── Login / Logout ───────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_post'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── Panel admin (harus login) ────────────────────────────────
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Profil user yang login (edit saja)
    Route::get('profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');

    // Pengaturan website (satu baris data, edit saja)
    Route::get('pengaturan-website', [WebsiteSettingController::class, 'edit'])->name('website-setting.edit');
    Route::put('pengaturan-website', [WebsiteSettingController::class, 'update'])->name('website-setting.update');

    // Upload gambar Summernote (harus sebelum resource)
    Route::post('konten/upload-image', [KontenController::class, 'uploadImage'])
        ->name('konten.upload-image');

    Route::resource('konten', KontenController::class)->except(['show']);
});
