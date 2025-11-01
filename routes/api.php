<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAbsensiController;
use App\Http\Controllers\Api\ApiSiswaController;
use App\Http\Controllers\Api\RombelSiswaApiController;

Route::post('/absensi-api', [ApiAbsensiController::class, 'store']);
Route::get('/siswa-api', [ApiSiswaController::class, 'index']);
// Route rombel-siswa perlu akses session untuk tahun ajaran
Route::middleware('web')->get('/rombel-siswa', [RombelSiswaApiController::class, 'index']);
Route::middleware('web')->get('/absensi-terbaru', function(Request $request) {
	// Use new schema: Absensi has relation rombel -> siswa and kelas
	$query = \App\Models\Absensi::with(['rombel.siswa','rombel.kelas'])->orderBy('id', 'desc');

	// Tambahkan filter tahun ajaran dari session
	$tahunAjaranId = session('tahun_ajaran_id');
	if ($tahunAjaranId) {
		$query->whereHas('rombel', function($q) use ($tahunAjaranId) {
			$q->where('tahun_ajaran_id', $tahunAjaranId);
		});
	}

	if ($request->filled('search')) {
		$search = $request->search;
		$query->whereHas('rombel', function($q) use ($search) {
			$q->whereHas('siswa', function($q2) use ($search) {
				$q2->where('nama', 'like', "%$search%")
				   ->orWhere('nis', 'like', "%$search%");
			});
		});
	}
	if ($request->filled('tanggal')) {
		$query->where('tanggal', $request->tanggal);
	}
	if ($request->filled('kelas_id')) {
		$query->whereHas('rombel', function($q) use ($request) {
			$q->where('kelas_id', $request->kelas_id);
		});
	}
	$absensi = $query->limit(30)->get()->map(function($row) {
		$rombel = $row->rombel ?? null;
		$siswa = $rombel ? ($rombel->siswa ?? null) : null;
		return [
			'id' => $row->id,
			'siswa_nama' => $siswa->nama ?? '-',
			'nomor_absen' => $rombel ? $rombel->nomor_absen : '-',
			'siswa_nis' => $siswa->nis ?? '-',
			'kelas_nama' => ($rombel && $rombel->kelas) ? $rombel->kelas->nama : '-',
			'tanggal' => $row->tanggal ? (is_string($row->tanggal) ? substr($row->tanggal,0,10) : $row->tanggal->toDateString()) : null,
			'jam_masuk' => $row->jam_masuk ?? '-',
			'jam_pulang' => $row->jam_pulang ?? '-',
			'status' => ucfirst($row->status),
			'keterangan' => $row->keterangan,
		];
	});
	return response()->json($absensi);
});
Route::middleware('web')->get('/siswa', function(\Illuminate\Http\Request $request) {
    $kelas_id = $request->query('kelas_id');
    $search = $request->query('search');
    
    // Gunakan tahun ajaran dari session
    $tahunAjaranId = session('tahun_ajaran_id');
    
    // Query dari tabel rombel
    $query = \App\Models\RombelSiswa::with(['siswa', 'kelas'])
        ->when($tahunAjaranId, function($q) use ($tahunAjaranId) {
            return $q->where('tahun_ajaran_id', $tahunAjaranId);
        });

    if ($kelas_id) {
        $query->where('kelas_id', $kelas_id);
    }

    if ($search) {
        $query->whereHas('siswa', function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('nis', 'like', "%{$search}%");
        });
    }

    // Map data rombel ke format yang diharapkan frontend
    $data = $query->get()->map(function($rombel) {
        $siswa = $rombel->siswa;
        return [
            'id' => $siswa->id,
            'nama' => $siswa->nama,
            'nis' => $siswa->nis,
            'jenis_kelamin' => $siswa->jenis_kelamin,
            'status' => $siswa->status,
            'no_hp_ortu' => $siswa->no_hp_ortu,
            'kelas_nama' => $rombel->kelas->nama ?? '-',
            'nomor_absen' => $rombel->nomor_absen
        ];
    });

    return response()->json($data);
});

// Latest absensi guru (for live search/filtering in frontend)
Route::middleware('web')->get('/absensi-guru-terbaru', function(Request $request) {
	$query = \App\Models\AbsensiGuru::with('guru')->orderBy('id', 'desc');

	// Filter berdasarkan tahun ajaran dari session
	$tahunAjaranId = session('tahun_ajaran_id');
	if ($tahunAjaranId) {
		$query->where('tahun_ajaran_id', $tahunAjaranId);
	}

	if ($request->filled('search')) {
		$search = $request->search;
		$query->whereHas('guru', function($q) use ($search) {
			$q->where('nama', 'like', "%$search%")
			  ->orWhere('nip', 'like', "%$search%");
		});
	}
	if ($request->filled('tanggal')) {
		$query->where('tanggal', $request->tanggal);
	}

	$absensi = $query->limit(100)->get()->map(function($row) {
		return [
			'id' => $row->id,
			'guru_nama' => $row->guru->nama ?? '-',
			'guru_nip' => $row->guru->nip ?? '-',
			'tanggal' => $row->tanggal ? (is_string($row->tanggal) ? substr($row->tanggal,0,10) : $row->tanggal->toDateString()) : null,
			'jam_masuk' => $row->jam_masuk ?? '-',
			'jam_pulang' => $row->jam_pulang ?? '-',
			'status' => ucfirst($row->status),
			'keterangan' => $row->keterangan,
		];
	});

	return response()->json($absensi);
});