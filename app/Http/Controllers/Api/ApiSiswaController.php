<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class ApiSiswaController extends Controller
{
    public function index(Request $request)
    {
        $tahunId = session('tahun_ajaran_id');

        // OPTIMASI 1: Eager Loading ('with')
        // Ambil Siswa BESERTA Rombel (yang tahun ajarannya sesuai) DAN Kelasnya sekaligus.
        $query = Siswa::with(['rombel' => function($q) use ($tahunId) {
            if ($tahunId) {
                $q->where('tahun_ajaran_id', $tahunId);
            }
            $q->with('kelas'); // Ambil data kelas di dalam rombel
        }]);

        // 1. Filter Pencarian (Nama / NIS)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // 2. Filter Kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('rombel', function($q) use ($request, $tahunId) {
                $q->where('kelas_id', $request->kelas_id);
                if ($tahunId) {
                    $q->where('tahun_ajaran_id', $tahunId);
                }
            });
        }

        // Limit data agar ringan (kecuali sedang search spesifik)
        if (!$request->filled('search')) {
            $query->limit(50);
        }

        // Ambil data
        $result = $query->orderBy('nama')->get();

        // Mapping Data (Sekarang tidak query database lagi, cuma ambil dari memori)
        $data = $result->map(function($siswa) {
            // Ambil rombel pertama dari hasil eager loading di atas
            $rombel = $siswa->rombel->first();

            return [
                'id' => $siswa->id,
                'nama' => $siswa->nama,
                'nis' => $siswa->nis,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'no_hp_ortu' => $siswa->no_hp_ortu,
                'status' => $siswa->status,
                // Data kelas diambil dari relasi yang sudah di-load (Cepat)
                'kelas_nama' => $rombel && $rombel->kelas ? $rombel->kelas->nama : '-',
                'nomor_absen' => $rombel ? $rombel->nomor_absen : '-'
            ];
        });

        return response()->json($data);
    }
}