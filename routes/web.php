<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// --- IMPORT SEMUA CONTROLLER ---
use App\Http\Controllers\{
    DashboardController,
    ProfileController,
    SiswaController,
    GuruController,
    KelasController,
    TahunAjaranController,
    RombelSiswaController,
    AbsensiController,
    AbsensiGuruController,
    UserController,
    WhatsappController,     // Pastikan ini ada
    SetPasswordController,
    RekapAbsensiController,
    RekapAbsensiGuruController
};

// --- IMPORT CONTROLLERS API ---
use App\Http\Controllers\Api\{
    ApiAbsensiController,
    ApiSiswaController,
    ApiAbsensiGuruController,
    RombelSiswaApiController
};

/*
|--------------------------------------------------------------------------
| GROUP 1: WEB PUBLIC (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

// Webhook WA & Setup Password Guru
Route::post('/webhook/fonnte', [WhatsappController::class, 'webhook'])->name('whatsapp.webhook');
Route::get('/set-password', [SetPasswordController::class, 'create'])->name('set-password.create');
Route::post('/set-password', [SetPasswordController::class, 'store'])->name('set-password.store');

/*
|--------------------------------------------------------------------------
| GROUP 3: WEB AUTH (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'web'])->group(function () {
    
    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- PROFILE ---
    // Tambahan: profile.index (View Profil)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index'); 
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- MASTER DATA (CRUD) ---
    Route::resource('guru', GuruController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('tahun_ajaran', TahunAjaranController::class);
    Route::resource('user', UserController::class);
    
    // Set Tahun Ajaran Aktif (Session)
    Route::post('/set-tahun-ajaran', function (Request $request) {
        session(['tahun_ajaran_id' => $request->id]);
        return response()->json(['ok' => true]);
    })->name('tahun_ajaran.set');

    // --- ROMBEL SISWA ---
    Route::resource('rombel_siswa', RombelSiswaController::class);
    Route::post('/rombel_siswa/mass_store', [RombelSiswaController::class, 'mass_store'])->name('rombel_siswa.mass_store');
    Route::post('/rombel_siswa/ganti-kelas-massal', [RombelSiswaController::class, 'gantiKelasMassal'])->name('rombel_siswa.ganti_kelas_massal');
    Route::get('/rombel_siswa/export/pdf', [RombelSiswaController::class, 'exportPdf'])->name('rombel_siswa.export.pdf');

    // --- ABSENSI SISWA ---
    Route::resource('absensi', AbsensiController::class);
    Route::get('/absensi/export/{type}', [RekapAbsensiController::class, 'export'])->name('absensi.export');

    // --- ABSENSI GURU ---
    Route::resource('absensi_guru', AbsensiGuruController::class);
    Route::get('/rekap/absensi-guru/export/{type}', [RekapAbsensiGuruController::class, 'export'])->name('rekap.absensi-guru.export');

    // --- WHATSAPP GATEWAY (Ini yang tadi error) ---
    Route::get('/whatsapp', [WhatsappController::class, 'index'])->name('whatsapp.index');
    Route::post('/whatsapp/send', [WhatsappController::class, 'send'])->name('whatsapp.send');
    Route::get('/whatsapp/report', [WhatsappController::class, 'report'])->name('whatsapp.report');

    /*
    |--------------------------------------------------------------------------
    | GROUP 4: INTERNAL API (AJAX FRONTEND)
    |--------------------------------------------------------------------------
    */
    Route::prefix('ajax')->group(function () {
        // Data Absensi Siswa
        Route::get('/absensi-data', [ApiAbsensiController::class, 'index']);
        Route::get('/siswa-data', [ApiSiswaController::class, 'index']);
        // Data Absensi Guru
        Route::get('/absensi-guru-data', [ApiAbsensiGuruController::class, 'index']);
    });

});

require __DIR__.'/auth.php';