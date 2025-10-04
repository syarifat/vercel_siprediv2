<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAbsensiController;
use App\Http\Controllers\Api\ApiSiswaController;
use App\Http\Controllers\Api\RombelSiswaApiController;

Route::post('/absensi-api', [ApiAbsensiController::class, 'store']);
Route::get('/siswa-api', [ApiSiswaController::class, 'index']);
Route::get('/rombel-siswa', [RombelSiswaApiController::class, 'index']);
Route::get('/absensi-terbaru', function(Request $request) {
	$query = \App\Models\Absensi::with('siswa')->orderBy('id', 'desc');
	if ($request->filled('search')) {
		$search = $request->search;
		$query->whereHas('siswa', function($q) use ($search) {
			$q->where('nama', 'like', "%$search%")
			  ->orWhere('nis', 'like', "%$search%");
		});
	}
	if ($request->filled('tanggal')) {
		$query->where('tanggal', $request->tanggal);
	}
	if ($request->filled('kelas_id')) {
		$query->whereHas('siswa', function($q) use ($request) {
			$q->whereIn('id', \App\Models\RombelSiswa::where('kelas_id', $request->kelas_id)
				->pluck('siswa_id')->toArray());
		});
	}
	$absensi = $query->limit(30)->get()->map(function($row) {
		$rombel = $row->siswa ? $row->siswa->rombel : null;
		return [
			'id' => $row->id,
			'siswa_nama' => $row->siswa->nama ?? '-',
			'nomor_absen' => $rombel ? $rombel->nomor_absen : '-',
			'siswa_nis' => $row->siswa->nis ?? '-',
			'kelas_nama' => ($rombel && $rombel->kelas) ? $rombel->kelas->nama : '-',
			'tanggal' => $row->tanggal,
			'jam' => $row->jam,
			'jam_pulang' => $row->jam_pulang ?? '-', // <-- tambahkan ini
			'status' => ucfirst($row->status),
			'keterangan' => $row->keterangan,
		];
	});
	return response()->json($absensi);
});
Route::get('/siswa', [\App\Http\Controllers\Api\SiswaApiController::class, 'index']);
