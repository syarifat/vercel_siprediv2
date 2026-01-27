<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Absensi;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\RombelSiswa; // Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString(); // Default Timezone Server
        
        // Ambil ID tahun ajaran aktif jika session kosong
        $tahunAjaranId = session('tahun_ajaran_id') ?? TahunAjaran::where('aktif', true)->value('id');
        
        // Jika masih null (belum ada tahun ajaran aktif), ambil yang terakhir
        if (!$tahunAjaranId) {
            $tahunAjaranId = TahunAjaran::latest('id')->value('id');
        }

        // Simpan ke session agar persisten
        if (!session('tahun_ajaran_id') && $tahunAjaranId) {
            session(['tahun_ajaran_id' => $tahunAjaranId]);
        }

        $tahunAjaran = TahunAjaran::find($tahunAjaranId);

        // --- DATA SISWA ---
        // Siswa Aktif = Siswa yang punya rombel di tahun ajaran ini
        $dataSiswaAktif = RombelSiswa::with(['siswa', 'kelas'])
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();
        $jumlahSiswa = $dataSiswaAktif->count();

        // Query Absensi Hari Ini (Hanya untuk siswa di tahun ajaran ini)
        $absensiHariIni = Absensi::with(['rombel.siswa', 'rombel.kelas'])
            ->whereDate('tanggal', $today)
            ->whereHas('rombel', function($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->get();

        // Filter Collection (Lebih cepat daripada query ulang berkali-kali)
        $dataHadir = $absensiHariIni->where('status', 'hadir');
        $dataSakit = $absensiHariIni->where('status', 'sakit');
        $dataIzin = $absensiHariIni->where('status', 'izin');
        $dataTanpaKeterangan = $absensiHariIni->whereIn('status', ['alpha', 'alfa', 'tanpa keterangan']);

        $jumlahHadir = $dataHadir->count();
        $jumlahSakit = $dataSakit->count();
        $jumlahIzin = $dataIzin->count();
        $jumlahTanpaKeterangan = $dataTanpaKeterangan->count();

        // Belum Hadir = Total Siswa - (Hadir + Sakit + Izin + Alpha)
        // Atau lebih akurat: Siswa di rombel yg belum punya record absen hari ini
        $sudahAbsenIds = $absensiHariIni->pluck('rombel_siswa_id')->toArray();
        $dataBelumHadir = $dataSiswaAktif->whereNotIn('id', $sudahAbsenIds);
        $jumlahBelumHadir = $dataBelumHadir->count();


        // --- DATA GURU ---
        $dataAllGuru = Guru::where('status', 'aktif')->get();
        $totalGuru = $dataAllGuru->count();
        
        $absensiGuruHariIni = AbsensiGuru::with('guru')
            ->whereDate('tanggal', $today)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();

        $dataGuruHadir = $absensiGuruHariIni->where('status', 'hadir');
        $dataGuruSakit = $absensiGuruHariIni->where('status', 'sakit');
        $dataGuruIzin = $absensiGuruHariIni->where('status', 'izin');
        $dataGuruTanpaKet = $absensiGuruHariIni->whereIn('status', ['alpha', 'alfa']);

        $guruHadir = $dataGuruHadir->count();
        $guruSakit = $dataGuruSakit->count();
        $guruIzin = $dataGuruIzin->count();
        $guruTanpaKet = $dataGuruTanpaKet->count();
        
        // Guru Belum Hadir
        $sudahAbsenGuruIds = $absensiGuruHariIni->pluck('guru_id')->toArray();
        $guruBelumHadir = $totalGuru - count($sudahAbsenGuruIds);

        return view('dashboard', compact(
            'tahunAjaran',
            // Siswa
            'jumlahSiswa', 'jumlahHadir', 'jumlahSakit', 'jumlahIzin', 'jumlahTanpaKeterangan', 'jumlahBelumHadir',
            'dataSiswaAktif', 'dataHadir', 'dataSakit', 'dataIzin', 'dataTanpaKeterangan', 'dataBelumHadir',
            // Guru
            'totalGuru', 'guruHadir', 'guruSakit', 'guruIzin', 'guruTanpaKet', 'guruBelumHadir',
            'dataAllGuru', 'dataGuruHadir', 'dataGuruSakit', 'dataGuruIzin', 'dataGuruTanpaKet'
        ));
    }
}