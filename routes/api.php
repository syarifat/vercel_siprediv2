<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    ApiAbsensiController,
    ApiSiswaController,
    ApiAbsensiGuruController,
    RombelSiswaApiController
};

// API Hardware (IoT) - Public
Route::post('/absensi-api', [ApiAbsensiController::class, 'store']); 

// API Frontend (Ajax) - Secure (Butuh Login Web)
Route::middleware(['web', 'auth'])->group(function () {
    
    // Data Siswa (Via Rombel) - Untuk fitur ploting kelas & filter
    Route::get('/siswa', [ApiSiswaController::class, 'index']);
    
    // Data Absensi Siswa - Untuk Dashboard & Rekap
    Route::get('/absensi-terbaru', [ApiAbsensiController::class, 'index']);
    
    // Data Absensi Guru - Untuk Dashboard & Rekap
    Route::get('/absensi-guru-terbaru', [ApiAbsensiGuruController::class, 'index']);
    
    // CRUD Rombel (Helper)
    Route::get('/rombel-siswa', [RombelSiswaApiController::class, 'index']);
});