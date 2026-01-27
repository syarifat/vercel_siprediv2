<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAbsensiController;
use App\Http\Controllers\Api\ApiSiswaController;
use App\Http\Controllers\Api\RombelSiswaApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API untuk Hardware (IoT) - Tidak perlu Session/Web Middleware
Route::post('/absensi-api', [ApiAbsensiController::class, 'store']);
Route::get('/siswa-api', [ApiSiswaController::class, 'index']);

// API untuk Frontend Web (Butuh Session Tahun Ajaran)
Route::middleware(['web', 'auth'])->group(function () {

    // 1. API DAFTAR SISWA (ROMBEL)
    // Digunakan untuk filter "Belum Hadir" dan Plotting Kelas
    Route::get('/siswa', function(Request $request) {
        $kelas_id = $request->query('kelas_id');
        $search = $request->query('search');
        $tahunAjaranId = session('tahun_ajaran_id'); // Ambil dari session

        // Query ke tabel rombel_siswa (karena siswa terikat tahun ajaran lewat rombel)
        $query = \App\Models\RombelSiswa::with(['siswa', 'kelas'])
            ->where('tahun_ajaran_id', $tahunAjaranId);

        if ($kelas_id) {
            $query->where('kelas_id', $kelas_id);
        }

        if ($search) {
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Ambil semua data (tanpa limit) agar perhitungan "Belum Hadir" akurat
        // Urutkan berdasarkan nomor absen atau nama
        $data = $query->orderBy('nomor_absen', 'asc')->get()->map(function($rombel) {
            return [
                'id' => $rombel->siswa->id, // ID Siswa
                'rombel_id' => $rombel->id, // ID Rombel
                'nama' => $rombel->siswa->nama,
                'nis' => $rombel->siswa->nis,
                'jenis_kelamin' => $rombel->siswa->jenis_kelamin,
                'no_hp_ortu' => $rombel->siswa->no_hp_ortu,
                'kelas_nama' => $rombel->kelas->nama ?? '-',
                'nomor_absen' => $rombel->nomor_absen
            ];
        });

        return response()->json($data);
    });

    // 2. API ABSENSI SISWA TERBARU (Untuk Tabel & Rekap)
    Route::get('/absensi-terbaru', function(Request $request) {
        $query = \App\Models\Absensi::with(['rombel.siswa', 'rombel.kelas'])
            ->orderBy('created_at', 'desc'); // Urutkan dari yang terbaru input

        $tahunAjaranId = session('tahun_ajaran_id');
        
        // Filter by Tahun Ajaran (Wajib, agar data tahun lalu tidak muncul)
        if ($tahunAjaranId) {
            $query->whereHas('rombel', function($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            });
        }

        // Filter Search (Nama/NIS)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('rombel.siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nis', 'like', "%$search%");
            });
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter Kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('rombel', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        // PENTING: Jika ada filter tanggal/kelas, JANGAN di-limit 30.
        // Kita butuh semua data hari itu untuk hitungan chart/card rekap.
        // Jika tidak ada filter (tampilan awal), baru di-limit biar ringan.
        if ($request->filled('tanggal') || $request->filled('kelas_id')) {
            $data = $query->get(); 
        } else {
            $data = $query->limit(50)->get();
        }

        $absensi = $data->map(function($row) {
            $rombel = $row->rombel;
            $siswa = $rombel ? $rombel->siswa : null;
            
            return [
                'id' => $row->id,
                'siswa_nama' => $siswa->nama ?? '-',
                'siswa_nis' => $siswa->nis ?? '-',
                'nomor_absen' => $rombel->nomor_absen ?? '-',
                'kelas_nama' => ($rombel && $rombel->kelas) ? $rombel->kelas->nama : '-',
                'tanggal' => $row->tanggal, // Biarkan format Y-m-d, JS yang format ulang
                'jam_masuk' => $row->jam_masuk ? substr($row->jam_masuk, 0, 5) : '-', // Ambil HH:mm
                'jam_pulang' => $row->jam_pulang ? substr($row->jam_pulang, 0, 5) : '-',
                'status' => strtolower($row->status), // Kirim lowercase biar mudah di JS (hadir, sakit, izin)
                'keterangan' => $row->keterangan,
            ];
        });

        return response()->json($absensi);
    });

    // 3. API ABSENSI GURU TERBARU
    Route::get('/absensi-guru-terbaru', function(Request $request) {
        $query = \App\Models\AbsensiGuru::with('guru')->orderBy('created_at', 'desc');

        // Filter Tahun Ajaran
        $tahunAjaranId = session('tahun_ajaran_id');
        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('guru', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nip', 'like', "%$search%");
            });
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Limit logic: Kalau filter tanggal aktif, ambil semua (untuk rekap)
        if ($request->filled('tanggal')) {
            $data = $query->get();
        } else {
            $data = $query->limit(50)->get();
        }

        $absensi = $data->map(function($row) {
            return [
                'id' => $row->id,
                'guru_nama' => $row->guru->nama ?? '-',
                'guru_nip' => $row->guru->nip ?? '-',
                'tanggal' => $row->tanggal,
                'jam_masuk' => $row->jam_masuk ? substr($row->jam_masuk, 0, 5) : '-',
                'jam_pulang' => $row->jam_pulang ? substr($row->jam_pulang, 0, 5) : '-',
                'status' => strtolower($row->status),
                'keterangan' => $row->keterangan,
            ];
        });

        return response()->json($absensi);
    });

    // 4. API UNTUK CRUD ROMBEL (OPSIONAL JIKA PAKE AJAX)
    Route::get('/rombel-siswa', [RombelSiswaApiController::class, 'index']);
});