<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class ApiSiswaController extends Controller
{
    public function index(Request $request)
    {
        // Query Dasar ke Tabel Siswa
        $query = Siswa::query();

        // 1. FILTER KELAS (Penting untuk halaman Absensi)
        if ($request->filled('kelas_id')) {
            $tahunId = session('tahun_ajaran_id');
            
            // Cari siswa yang punya rombel di kelas & tahun ajaran ini
            $query->whereHas('rombel', function($q) use ($request, $tahunId) {
                $q->where('kelas_id', $request->kelas_id);
                if ($tahunId) {
                    $q->where('tahun_ajaran_id', $tahunId);
                }
            });
        }

        // 2. FILTER SEARCH (Opsional)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Ambil data (tanpa limit jika untuk perhitungan statistik "Belum Hadir")
        // Tapi jika tanpa filter kelas, kita limit biar tidak berat
        if (!$request->filled('kelas_id') && !$request->filled('search')) {
            $query->limit(100);
        }

        $data = $query->orderBy('nama')->get()->map(function($siswa) {
            // Ambil info kelas untuk ditampilkan (jika perlu)
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
                'status' => $siswa->status,
                'kelas_nama' => $rombel ? $rombel->kelas->nama : '-',
            ];
        });

        return response()->json($data);
    }
}