<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RombelSiswa;

class RombelSiswaApiController extends Controller
{
    public function index(Request $request)
    {
        // Default ambil tahun ajaran dari session
        $tahunId = session('tahun_ajaran_id');
        
        $query = RombelSiswa::with(['siswa', 'kelas', 'tahunAjaran']);

        // Filter Tahun Ajaran
        if ($tahunId) {
            $query->where('tahun_ajaran_id', $tahunId);
        }

        // Filter Kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter Search (Nama Siswa / NIS)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('nomor_absen')->get()->map(function($row) {
            return [
                'id' => $row->id, // ID Rombel
                'siswa_id' => $row->siswa_id,
                'siswa_nama' => $row->siswa->nama ?? '-',
                'siswa_nis' => $row->siswa->nis ?? '-',
                'kelas_nama' => $row->kelas->nama ?? '-',
                'nomor_absen' => $row->nomor_absen,
                'tahun_ajaran' => $row->tahunAjaran->nama ?? '-'
            ];
        });

        return response()->json($data);
    }
}