<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class ApiSiswaController extends Controller
{
    public function index(Request $request)
    {
        // Query langsung ke tabel 'siswa' tanpa join/relation apapun
        $query = Siswa::query();

        // Filter Pencarian (Nama / NIS)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Limit data (Wajib ada biar load awal tidak berat)
        if (!$request->filled('search')) {
            $query->limit(50);
        }

        // Ambil data (Hanya field yang ada di tabel siswa)
        $data = $query->orderBy('nama')->get()->map(function($siswa) {
            return [
                'id' => $siswa->id,
                'nama' => $siswa->nama,
                'nis' => $siswa->nis,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'no_hp_ortu' => $siswa->no_hp_ortu,
                'status' => $siswa->status,
                
                // Kita set null/strip karena di tabel siswa murni tidak ada data ini
                // Frontend akan otomatis menghandle nilai null ini
                'kelas_nama' => null, 
                'nomor_absen' => null
            ];
        });

        return response()->json($data);
    }
}