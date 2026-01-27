<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAbsensiController;
use App\Http\Controllers\Api\ApiSiswaController;

// API Routes for Absensi (IoT / RFID)
Route::post('/absensi-api', [ApiAbsensiController::class, 'store']);
Route::get('/siswa-api', [ApiSiswaController::class, 'index']);
Route::get('/ping', function () {
    return response()->json([
        'pong' => true,
        'time' => now(),
    ]);
});
