<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RombelSiswa;

class ApiSiswaController extends Controller
{
    // Mengambil data siswa (via rombel) untuk plotting & filter
    public function index(Request $request)
    {
        $tahunId = session('tahun_ajaran_id');
        $query = RombelSiswa::with(['siswa', 'kelas'])->where('tahun_ajaran_id', $tahunId);
        
        if($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        // Return data yang dibutuhkan frontend
        $data = $query->orderBy('nomor_absen')->get()->map(function($r){
            return [
                'id' => $r->siswa->id, // ID Siswa
                'rombel_id' => $r->id, 
                'nama' => $r->siswa->nama,
                'nis' => $r->siswa->nis,
                'kelas_nama' => $r->kelas->nama ?? '-',
                'nomor_absen' => $r->nomor_absen
            ];
        });
        return response()->json($data);
    }
}