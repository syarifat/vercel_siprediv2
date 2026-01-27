<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\TahunAjaran; // Jangan lupa import ini

class ApiSiswaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tentukan Tahun Ajaran (Prioritas: Session -> Aktif -> Terakhir)
        $tahunId = session('tahun_ajaran_id');
        
        if (!$tahunId) {
            // Jika session kosong, ambil tahun ajaran yg statusnya 'aktif'
            $tahunId = TahunAjaran::where('aktif', true)->value('id');
        }
        
        if (!$tahunId) {
            // Jika tidak ada yg aktif, ambil ID terakhir
            $tahunId = TahunAjaran::latest('id')->value('id');
        }

        // 2. Query Siswa dengan Eager Loading Rombel & Kelas
        // Kita ambil siswa + rombel-nya KHUSUS di tahun ajaran yg dipilih tadi
        $query = Siswa::with(['rombel' => function($q) use ($tahunId) {
            $q->where('tahun_ajaran_id', $tahunId)->with('kelas');
        }]);

        // Filter Kelas (Dari Dropdown)
        if ($request->filled('kelas_id')) {
            $query->whereHas('rombel', function($q) use ($request, $tahunId) {
                $q->where('kelas_id', $request->kelas_id)
                  ->where('tahun_ajaran_id', $tahunId);
            });
        }

        // Filter Search (Nama / NIS)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Limit data jika tidak sedang filter spesifik
        if (!$request->filled('kelas_id') && !$request->filled('search')) {
            $query->limit(100);
        }

        // 3. Mapping Data
        $data = $query->orderBy('nama')->get()->map(function($siswa) {
            // Karena pakai 'with' di atas, data rombel sudah ada di memori ($siswa->rombel)
            // Ambil item pertama (karena relasinya hasMany, tapi difilter per tahun jadi cuma ada 1)
            $rombel = $siswa->rombel->first();

            return [
                'id' => $siswa->id,
                'nama' => $siswa->nama,
                'nis' => $siswa->nis,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'status' => $siswa->status,
                'no_hp_ortu' => $siswa->no_hp_ortu,
                
                // Ambil Nama Kelas
                'kelas_nama' => ($rombel && $rombel->kelas) ? $rombel->kelas->nama : '-',
            ];
        });

        return response()->json($data);
    }
}