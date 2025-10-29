<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now('Asia/Jakarta')->toDateString();

        $dataSiswaAktif = \App\Models\RombelSiswa::with(['siswa', 'kelas'])
            ->whereHas('tahunAjaran', function($q) { $q->where('aktif', true); })
            ->get();
        $jumlahSiswa = $dataSiswaAktif->count();

        $dataHadir = \App\Models\Absensi::with(['rombel.siswa', 'rombel.kelas'])
            ->where('status', 'Hadir')
            ->whereDate('tanggal', $today)
            ->whereHas('rombel.tahunAjaran', function($q) { $q->where('aktif', true); })
            ->get();

        $dataSakitIzin = \App\Models\Absensi::with(['rombel.siswa', 'rombel.kelas'])
            ->whereIn('status', ['Sakit', 'Izin'])
            ->whereDate('tanggal', $today)
            ->whereHas('rombel.tahunAjaran', function($q) { $q->where('aktif', true); })
            ->get();

        $dataTanpaKeterangan = \App\Models\Absensi::with(['rombel.siswa', 'rombel.kelas'])
            ->whereIn('status', ['Tanpa Keterangan', 'Alpha'])
            ->whereDate('tanggal', $today)
            ->whereHas('rombel.tahunAjaran', function($q) { $q->where('aktif', true); })
            ->get();

        // Belum Hadir: ambil dari rombel_siswa yang tidak punya absensi hari ini
        $dataBelumHadir = \App\Models\RombelSiswa::with(['siswa', 'kelas'])
            ->whereHas('tahunAjaran', function($q) { $q->where('aktif', true); })
            ->whereDoesntHave('absensi', function($q) use ($today) {
                $q->whereDate('tanggal', $today);
            })->get();

        $jumlahHadir = $dataHadir->count();
        $jumlahSakitIzin = $dataSakitIzin->count();
        $jumlahTanpaKeterangan = $dataTanpaKeterangan->count();
        $jumlahBelumHadir = $dataBelumHadir->count();

        return view('dashboard', compact(
            'dataSiswaAktif',
            'jumlahSiswa',
            'dataHadir',
            'jumlahHadir',
            'dataSakitIzin',
            'jumlahSakitIzin',
            'dataTanpaKeterangan',
            'jumlahTanpaKeterangan',
            'dataBelumHadir',
            'jumlahBelumHadir'
        ));
    }
}