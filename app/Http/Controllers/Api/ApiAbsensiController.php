<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\RombelSiswa;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\AbsensiGuru;
use App\Services\FonnteService; // Pastikan service ini ada
use Carbon\Carbon;

class ApiAbsensiController extends Controller
{
    // API FRONTEND: GET DATA (Untuk Tabel)
    public function index(Request $request)
    {
        $query = Absensi::with(['rombel.siswa', 'rombel.kelas'])->orderBy('created_at', 'desc');
        
        $tahunId = session('tahun_ajaran_id');
        if($tahunId) $query->whereHas('rombel', fn($q)=>$q->where('tahun_ajaran_id', $tahunId));
        
        if($request->filled('tanggal')) $query->whereDate('tanggal', $request->tanggal);
        if($request->filled('kelas_id')) $query->whereHas('rombel', fn($q)=>$q->where('kelas_id', $request->kelas_id));
        
        if($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('rombel.siswa', fn($q)=>$q->where('nama','like',"%$s%")->orWhere('nis','like',"%$s%"));
        }

        // Limit jika tidak ada filter spesifik agar ringan
        if(!$request->filled('tanggal') && !$request->filled('kelas_id')) {
            $query->limit(50);
        } else {
            // Jika ada filter tanggal, ambil semua (untuk hitungan rekap yang akurat)
            $query->limit(1000); 
        }

        $data = $query->get()->map(function($d) {
            return [
                'id' => $d->id,
                'siswa_nama' => $d->rombel->siswa->nama ?? '-',
                'siswa_nis' => $d->rombel->siswa->nis ?? '-',
                'kelas_nama' => $d->rombel->kelas->nama ?? '-',
                'nomor_absen' => $d->rombel->nomor_absen,
                'tanggal' => $d->tanggal,
                'jam_masuk' => $d->jam_masuk ? substr($d->jam_masuk,0,5) : '-',
                'jam_pulang' => $d->jam_pulang ? substr($d->jam_pulang,0,5) : '-',
                'status' => strtolower($d->status),
                'keterangan' => $d->keterangan
            ];
        });
        return response()->json($data);
    }

    // API IOT: STORE DATA DARI ALAT
    public function store(Request $request)
    {
        // Paste logic store asli Mas di sini.
        // Saya sederhanakan untuk contoh:
        
        $rfid = $request->input('rfid');
        $mode = $request->input('mode_id', 1);
        $tanggal = now()->toDateString();
        $jam = now()->toTimeString();

        // 1. Cek Siswa
        $siswa = Siswa::where('rfid', $rfid)->first();
        if ($siswa) {
            // Cari rombel di tahun ajaran aktif
            $rombel = RombelSiswa::where('siswa_id', $siswa->id)
                ->whereHas('tahunAjaran', fn($q) => $q->where('aktif', true))
                ->first();

            if (!$rombel) return response()->json(['message' => 'Siswa tidak aktif'], 404);

            $absensi = Absensi::firstOrNew([
                'rombel_siswa_id' => $rombel->id,
                'tanggal' => $tanggal
            ]);

            if ($mode == 1) { // Masuk
                if (!$absensi->exists) {
                    $absensi->jam_masuk = $jam;
                    $absensi->status = 'hadir';
                    $absensi->save();
                    // Kirim WA Disini (Optional)
                    return response()->json(['message' => 'Absen Masuk Berhasil', 'nama' => $siswa->nama]);
                }
                return response()->json(['message' => 'Sudah Absen Masuk']);
            } 
            
            if ($mode == 2) { // Pulang
                if ($absensi->exists && !$absensi->jam_pulang) {
                    $absensi->jam_pulang = $jam;
                    $absensi->save();
                    return response()->json(['message' => 'Absen Pulang Berhasil']);
                }
                return response()->json(['message' => 'Gagal Absen Pulang']);
            }
        }

        // 2. Cek Guru (Jika bukan siswa)
        $guru = Guru::where('rfid', $rfid)->first();
        if ($guru) {
             // Logic guru sama, pakai tabel absensi_guru
             // ...
             return response()->json(['message' => 'Absen Guru Berhasil']);
        }

        return response()->json(['message' => 'RFID Tidak Dikenal'], 404);
    }
}