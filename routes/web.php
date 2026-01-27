<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// --- IMPORT CONTROLLERS UTAMA ---
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
    WhatsappController,
    SetPasswordController,
    RekapAbsensiController,
    RekapAbsensiGuruController
};

// --- IMPORT CONTROLLERS API (Untuk Ajax & IoT) ---
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

// Set Tahun Ajaran (Session Global)
Route::post('/set-tahun-ajaran', function (Request $request) {
    $id = $request->input('id');
    if ($id) {
        session(['tahun_ajaran_id' => $id]);
    } else {
        session()->forget('tahun_ajaran_id');
    }
    return response()->json(['ok' => true]);
})->name('tahun_ajaran.set');

// Setup Password Guru & Webhook WA
Route::get('/set-password', [SetPasswordController::class, 'create'])->name('set-password.create');
Route::post('/set-password', [SetPasswordController::class, 'store'])->name('set-password.store');
Route::post('/webhook/fonnte', [WhatsappController::class, 'webhook'])->name('whatsapp.webhook');


/*
|--------------------------------------------------------------------------
| GROUP 2: API HARDWARE / IOT (Tanpa Login Session)
|--------------------------------------------------------------------------
| Note: Route ini biasanya diakses oleh alat RFID (NodeMCU/ESP32).
| Jika alat error 419 (CSRF), masukkan route ini ke $except di VerifyCsrfToken.
*/
Route::prefix('api')->group(function () {
    Route::post('/absensi-api', [ApiAbsensiController::class, 'store']); 
    Route::get('/siswa-api', [ApiSiswaController::class, 'index']);
});


/*
|--------------------------------------------------------------------------
| GROUP 3: WEB AUTH (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // --- DASHBOARD & PROFILE ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- MASTER DATA (CRUD) ---
    Route::resource('guru', GuruController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('tahun_ajaran', TahunAjaranController::class);
    Route::resource('user', UserController::class);
    
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

    // --- WHATSAPP GATEWAY ---
    Route::get('/whatsapp', [WhatsappController::class, 'index'])->name('whatsapp.index');
    Route::post('/whatsapp/send', [WhatsappController::class, 'send'])->name('whatsapp.send');
    Route::get('/whatsapp/qr', [WhatsappController::class, 'qr'])->name('whatsapp.qr');
    Route::get('/whatsapp/report', [WhatsappController::class, 'report'])->name('whatsapp.report');


    /*
    |--------------------------------------------------------------------------
    | GROUP 4: INTERNAL API (AJAX FRONTEND)
    |--------------------------------------------------------------------------
    | Route ini dipanggil oleh Javascript (Fetch) di halaman Dashboard/Index.
    | Ditaruh di sini agar bisa membaca Session Login user.
    | Prefix 'api' ditambahkan agar URL-nya tetap: /api/siswa, /api/absensi-terbaru
    */
    Route::prefix('api')->group(function () {
        // Data Siswa (Via Rombel/Master) - Untuk fitur ploting kelas & filter
        Route::get('/siswa', [ApiSiswaController::class, 'index']);
        
        // Data Absensi Siswa - Untuk Dashboard & Rekap
        Route::get('/absensi-terbaru', [ApiAbsensiController::class, 'index']);
        
        // Data Absensi Guru - Untuk Dashboard & Rekap
        Route::get('/absensi-guru-terbaru', [ApiAbsensiGuruController::class, 'index']);
        
        // CRUD Rombel (Helper)
        Route::get('/rombel-siswa', [RombelSiswaApiController::class, 'index']);
    });

});

require __DIR__.'/auth.php';