<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Import Controllers
use App\Http\Controllers\{
    DashboardController, ProfileController, SiswaController, GuruController,
    KelasController, TahunAjaranController, RombelSiswaController,
    AbsensiController, AbsensiGuruController, UserController,
    WhatsappController, SetPasswordController, RekapAbsensiController,
    RekapAbsensiGuruController
};

use App\Http\Controllers\Api\{
    ApiAbsensiController, ApiSiswaController, ApiAbsensiGuruController,
    RombelSiswaApiController
};

// --- 1. ROUTE PUBLIC (Tanpa Login) ---
Route::get('/', function () { return view('welcome'); });
Route::post('/webhook/fonnte', [WhatsappController::class, 'webhook'])->name('whatsapp.webhook');
Route::get('/set-password', [SetPasswordController::class, 'create'])->name('set-password.create');
Route::post('/set-password', [SetPasswordController::class, 'store'])->name('set-password.store');

// --- 2. API HARDWARE / IOT (Tanpa Login Session, Bypass CSRF) ---
// Route ini ditaruh DI LUAR middleware auth agar bisa diakses alat
Route::prefix('api')->group(function () {
    Route::post('/absensi-api', [ApiAbsensiController::class, 'store']); 
    Route::get('/siswa-api', [ApiSiswaController::class, 'index']);
});

// --- 3. WEB AUTHENTICATED ROUTES ---
Route::middleware(['auth', 'web'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource & Fitur Web Lainnya (Copy paste yang lama, ini aman)
    Route::resource('guru', GuruController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('tahun_ajaran', TahunAjaranController::class);
    Route::resource('user', UserController::class);
    Route::resource('rombel_siswa', RombelSiswaController::class);
    Route::resource('absensi', AbsensiController::class);
    Route::resource('absensi_guru', AbsensiGuruController::class);
    
    // Custom Routes Web
    Route::post('/set-tahun-ajaran', function (Request $request) {
        session(['tahun_ajaran_id' => $request->id]);
        return response()->json(['ok' => true]);
    })->name('tahun_ajaran.set');
    
    // ... (Tambahkan route export/whatsapp/profile di sini seperti file sebelumnya) ...

    // =================================================================
    // --- 4. INTERNAL API (AJAX) - PENYEBAB MASALAH ---
    // =================================================================
    // Kita taruh route ini SECARA EKSPLISIT agar tidak tertukar.
    
    Route::get('/api/siswa', [ApiSiswaController::class, 'index']);
    Route::get('/api/absensi-terbaru', [ApiAbsensiController::class, 'index']);
    Route::get('/api/absensi-guru-terbaru', [ApiAbsensiGuruController::class, 'index']);
    Route::get('/api/rombel-siswa', [RombelSiswaApiController::class, 'index']);
    
});

require __DIR__.'/auth.php';