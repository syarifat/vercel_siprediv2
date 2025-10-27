<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RombelSiswa;

class RombelSiswaApiController extends Controller
{
    public function index(Request $request)
    {
        $query = RombelSiswa::with(['siswa', 'kelas', 'tahunAjaran']);

        if ($request->search) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Support filtering by tahun ajaran if provided
        if ($request->tahun_ajaran_id) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        // Prefer ordering by nomor_absen when available, otherwise by id desc
        if (in_array('nomor_absen', $query->getModel()->getFillable())) {
            $dataQuery = $query->orderBy('nomor_absen');
        } else {
            $dataQuery = $query->orderBy('id', 'desc');
        }

        $data = $dataQuery->get()->map(function($row) {
            return [
                'id' => $row->id,
                'siswa_id' => $row->siswa_id,
                'kelas_id' => $row->kelas_id,
                'tahun_ajaran_id' => $row->tahun_ajaran_id,
                'nomor_absen' => $row->nomor_absen ?? '-',
                'siswa_nama' => $row->siswa->nama ?? '-',
                'siswa_nis' => $row->siswa->nis ?? '-',
                'kelas_nama' => $row->kelas->nama ?? '-',
                'tahun_ajaran_nama' => $row->tahunAjaran->nama ?? '-',
            ];
        });

        return response()->json($data);
    }
}