<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Absensi;
use App\Models\AbsensiGuru;
use App\Models\Guru;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now('Asia/Jakarta')->toDateString();
        $tahunAjaranId = session('tahun_ajaran_id');
        $tahunAjaran = TahunAjaran::find($tahunAjaranId);

        $dataSiswaAktif = \App\Models\RombelSiswa::with(['siswa', 'kelas', 'tahunAjaran'])
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();
        $jumlahSiswa = $dataSiswaAktif->count();

        $dataHadir = \App\Models\Absensi::with(['rombel.siswa', 'rombel.kelas'])
            ->where('status', 'Hadir')
            ->whereDate('tanggal', $today)
            ->whereHas('rombel', function($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->get();

        $dataSakit = \App\Models\Absensi::with(['rombel.siswa', 'rombel.kelas'])
            ->where('status', 'Sakit')
            ->whereDate('tanggal', $today)
            ->whereHas('rombel', function($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->get();

        $dataIzin = \App\Models\Absensi::with(['rombel.siswa', 'rombel.kelas'])
            ->where('status', 'Izin')
            ->whereDate('tanggal', $today)
            ->whereHas('rombel', function($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->get();

        $dataTanpaKeterangan = \App\Models\Absensi::with(['rombel.siswa', 'rombel.kelas'])
            ->whereIn('status', ['Tanpa Keterangan', 'Alpha'])
            ->whereDate('tanggal', $today)
            ->whereHas('rombel', function($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->get();

        // Belum Hadir: ambil dari rombel_siswa yang tidak punya absensi hari ini
        $dataBelumHadir = \App\Models\RombelSiswa::with(['siswa', 'kelas'])
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereDoesntHave('absensi', function($q) use ($today) {
                $q->whereDate('tanggal', $today);
            })->get();

        $jumlahHadir = $dataHadir->count();
        $jumlahSakit = $dataSakit->count();
        $jumlahIzin = $dataIzin->count();
        $jumlahTanpaKeterangan = $dataTanpaKeterangan->count();
        $jumlahBelumHadir = $dataBelumHadir->count();

        // Data Guru
        $dataAllGuru = Guru::all();
        $totalGuru = $dataAllGuru->count();
        
        // Data Guru Hadir
        $dataGuruHadir = AbsensiGuru::with('guru')
            ->where('status', 'Hadir')
            ->whereDate('tanggal', $today)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();
        $guruHadir = $dataGuruHadir->count();
        
        // Data Guru Sakit
        $dataGuruSakit = AbsensiGuru::with('guru')
            ->where('status', 'Sakit')
            ->whereDate('tanggal', $today)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();
        $guruSakit = $dataGuruSakit->count();

        // Data Guru Izin
        $dataGuruIzin = AbsensiGuru::with('guru')
            ->where('status', 'Izin')
            ->whereDate('tanggal', $today)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();
        $guruIzin = $dataGuruIzin->count();
        
        // Data Guru Tanpa Keterangan
        $dataGuruTanpaKet = AbsensiGuru::with('guru')
            ->where('status', 'Tanpa Keterangan')
            ->whereDate('tanggal', $today)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();
        $guruTanpaKet = $dataGuruTanpaKet->count();
        $guruBelumHadir = $totalGuru - ($guruHadir + $guruSakit + $guruIzin + $guruTanpaKet);

        return view('dashboard', compact(
            'tahunAjaran',
            'dataAllGuru',
            'dataSiswaAktif',
            'jumlahSiswa',
            'dataHadir',
            'jumlahHadir',
            'dataSakit',
            'jumlahSakit',
            'dataIzin',
            'jumlahIzin',
            'dataTanpaKeterangan',
            'jumlahTanpaKeterangan',
            'dataBelumHadir',
            'jumlahBelumHadir',
            'totalGuru',
            'guruHadir',
            'dataGuruHadir',
            'guruSakit',
            'dataGuruSakit',
            'guruIzin',
            'dataGuruIzin',
            'guruTanpaKet',
            'dataGuruTanpaKet',
            'guruBelumHadir'
        ));
    }
}