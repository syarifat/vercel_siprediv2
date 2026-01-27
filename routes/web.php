<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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

// Halaman Depan
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

// --- AREA PUBLIK (Tanpa Login) ---
Route::get('/set-password', [SetPasswordController::class, 'create'])->name('set-password.create');
Route::post('/set-password', [SetPasswordController::class, 'store'])->name('set-password.store');
Route::post('/webhook/fonnte', [WhatsappController::class, 'webhook'])->name('whatsapp.webhook');

// --- AREA AUTH (Harus Login) ---
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resource Controllers (CRUD Standar)
    Route::resource('guru', GuruController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('tahun_ajaran', TahunAjaranController::class);
    Route::resource('user', UserController::class);
    
    // Rombel Siswa (Plus Custom Actions)
    Route::resource('rombel_siswa', RombelSiswaController::class);
    Route::post('/rombel_siswa/mass_store', [RombelSiswaController::class, 'mass_store'])->name('rombel_siswa.mass_store');
    Route::post('/rombel_siswa/ganti-kelas-massal', [RombelSiswaController::class, 'gantiKelasMassal'])->name('rombel_siswa.ganti_kelas_massal');
    Route::get('/rombel_siswa/export/pdf', [RombelSiswaController::class, 'exportPdf'])->name('rombel_siswa.export.pdf');

    // Absensi Siswa
    Route::resource('absensi', AbsensiController::class);
    Route::get('/absensi/export/{type}', [RekapAbsensiController::class, 'export'])->name('absensi.export');

    // Absensi Guru
    Route::resource('absensi_guru', AbsensiGuruController::class);
    Route::get('/rekap/absensi-guru/export/{type}', [RekapAbsensiGuruController::class, 'export'])->name('rekap.absensi-guru.export');

    // WhatsApp
    Route::get('/whatsapp', [WhatsappController::class, 'index'])->name('whatsapp.index');
    Route::post('/whatsapp/send', [WhatsappController::class, 'send'])->name('whatsapp.send');
    Route::get('/whatsapp/qr', [WhatsappController::class, 'qr'])->name('whatsapp.qr');
    Route::get('/whatsapp/report', [WhatsappController::class, 'report'])->name('whatsapp.report');
});

require __DIR__.'/auth.php';