<?php
namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiAbsensiController;

// API Routes for Absensi
Route::post('/absensi-api', [ApiAbsensiController::class, 'store']); 
Route::get('/siswa-api', [ApiSiswaController::class, 'index']);