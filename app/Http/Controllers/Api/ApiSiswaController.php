<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class ApiSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        // 1. Filter Pencarian (Nama / NIS)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // 2. Filter Kelas (Opsional, via relasi rombel)
        // Hanya jika ingin mencari siswa di kelas tertentu
        if ($request->filled('kelas_id')) {
            $tahunId = session('tahun_ajaran_id');
            $query->whereHas('rombel', function($q) use ($request, $tahunId) {
                $q->where('kelas_id', $request->kelas_id);
                if ($tahunId) {
                    $q->where('tahun_ajaran_id', $tahunId);
                }
            });
        }

        // Ambil data (Limit jika tidak ada search spesifik agar ringan)
        if (!$request->filled('search')) {
            $query->limit(50);
        }

        $data = $query->orderBy('nama')->get()->map(function($siswa) {
            // Ambil info kelas aktif siswa ini (jika ada)
            // Mengambil rombel terakhir yang aktif (tahun ajaran session)
            $tahunId = session('tahun_ajaran_id');
            $rombel = $siswa->rombel()
                ->when($tahunId, fn($q) => $q->where('tahun_ajaran_id', $tahunId))
                ->with('kelas')
                ->first();

            return [
                'id' => $siswa->id,
                'nama' => $siswa->nama,
                'nis' => $siswa->nis,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'no_hp_ortu' => $siswa->no_hp_ortu,
                'status' => $siswa->status,
                'kelas_nama' => $rombel ? $rombel->kelas->nama : '-', // Tampilkan kelas jika ada
                'nomor_absen' => $rombel ? $rombel->nomor_absen : '-'
            ];
        });

        return response()->json($data);
    }
}