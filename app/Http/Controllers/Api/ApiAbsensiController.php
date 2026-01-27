<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// Models
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\RombelSiswa;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\AbsensiGuru;

class ApiAbsensiController extends Controller
{
    /**
     * API FRONTEND: GET DATA (Untuk Tabel Rekap Siswa di Web)
     */
    public function index(Request $request)
    {
        // 1. Setup Query Dasar
        $query = Absensi::with(['rombel.siswa', 'rombel.kelas'])
            ->orderBy('created_at', 'desc');
        
        // 2. Filter Wajib: Tahun Ajaran (Dari Session Web)
        $tahunId = session('tahun_ajaran_id');
        if($tahunId) {
            $query->whereHas('rombel', fn($q) => $q->where('tahun_ajaran_id', $tahunId));
        }
        
        // 3. Filter Tanggal
        // Jika ada input tanggal, gunakan. Jika tidak, default hari ini.
        if($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            $query->whereDate('tanggal', Carbon::now()->toDateString());
        }

        // 4. Filter Kelas
        if($request->filled('kelas_id')) {
            $query->whereHas('rombel', fn($q) => $q->where('kelas_id', $request->kelas_id));
        }
        
        // 5. Filter Search (Nama Siswa / NIS)
        if($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('rombel.siswa', fn($q) => 
                $q->where('nama', 'like', "%{$s}%")->orWhere('nis', 'like', "%{$s}%")
            );
        }

        // 6. Limit Logic (Optimasi Performa)
        // Jika user sedang melakukan filter spesifik, tampilkan lebih banyak data (untuk rekap)
        if($request->filled('tanggal') || $request->filled('kelas_id') || $request->filled('search')) {
            $query->limit(500); 
        } else {
            // Tampilan awal (biar ringan) limit 50 saja
            $query->limit(50);
        }

        // 7. Format Data untuk JSON
        $data = $query->get()->map(function($d) {
            return [
                'id' => $d->id,
                'siswa_nama' => $d->rombel->siswa->nama ?? '-',
                'siswa_nis'  => $d->rombel->siswa->nis ?? '-',
                'kelas_nama' => $d->rombel->kelas->nama ?? '-',
                'nomor_absen'=> $d->rombel->nomor_absen,
                'tanggal'    => $d->tanggal,
                'jam_masuk'  => $d->jam_masuk ? substr($d->jam_masuk, 0, 5) : '-',
                'jam_pulang' => $d->jam_pulang ? substr($d->jam_pulang, 0, 5) : '-',
                'status'     => strtolower($d->status),
                'keterangan' => $d->keterangan
            ];
        });

        return response()->json($data);
    }

    /**
     * API IOT: STORE DATA DARI ALAT (Hardware)
     * Endpoint: /api/absensi-api (POST)
     * Body: rfid, mode_id (1=Masuk, 2=Pulang)
     */
    public function store(Request $request)
    {
        // Validasi Input Dasar
        if (!$request->has('rfid')) {
            return response()->json(['status' => 'error', 'message' => 'RFID Kosong'], 400);
        }

        $rfid = $request->rfid;
        $mode = $request->input('mode_id', 1); // Default 1 (Masuk)
        $today = Carbon::now()->toDateString();
        $timeNow = Carbon::now()->format('H:i:s');

        // ==========================================
        // CEK 1: APAKAH KARTU MILIK SISWA?
        // ==========================================
        $siswa = Siswa::where('rfid', $rfid)->where('status', 'aktif')->first();

        if ($siswa) {
            return $this->processAbsensiSiswa($siswa, $mode, $today, $timeNow);
        }

        // ==========================================
        // CEK 2: APAKAH KARTU MILIK GURU?
        // ==========================================
        $guru = Guru::where('rfid', $rfid)->where('status', 'aktif')->first();

        if ($guru) {
            return $this->processAbsensiGuru($guru, $mode, $today, $timeNow);
        }

        // ==========================================
        // KARTU TIDAK DIKENAL
        // ==========================================
        return response()->json([
            'status' => 'error',
            'message' => 'Kartu Tidak Dikenal'
        ], 404);
    }

    /**
     * LOGIC PRIVATE: PROSES ABSENSI SISWA
     */
    private function processAbsensiSiswa($siswa, $mode, $today, $timeNow)
    {
        // Cari Tahun Ajaran Aktif
        $tahunId = TahunAjaran::where('aktif', true)->value('id') ?? TahunAjaran::latest('id')->value('id');

        // Cari Rombel Siswa (Kelasnya apa tahun ini?)
        $rombel = RombelSiswa::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunId)
            ->first();

        if (!$rombel) {
            return response()->json(['status' => 'error', 'message' => 'Siswa Belum Masuk Kelas'], 400);
        }

        // Cek data absensi hari ini
        $absensi = Absensi::where('rombel_siswa_id', $rombel->id)
            ->where('tanggal', $today)
            ->first();

        // --- MODE 1: ABSEN MASUK ---
        if ($mode == 1) {
            if ($absensi) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Sudah Absen Masuk',
                    'nama' => $siswa->nama
                ]);
            }

            // Logic Terlambat (Contoh: Lewat 07:15)
            $status = 'hadir';
            $keterangan = 'Tepat Waktu';
            if ($timeNow > '07:15:00') {
                $keterangan = 'Terlambat';
            }

            Absensi::create([
                'rombel_siswa_id' => $rombel->id,
                'tanggal' => $today,
                'jam_masuk' => $timeNow,
                'status' => $status,
                'keterangan' => $keterangan
            ]);

            // TODO: Kirim WhatsApp Notifikasi ke Ortu di sini (Optional)

            return response()->json([
                'status' => 'success',
                'message' => 'Absen Masuk Berhasil',
                'nama' => $siswa->nama,
                'role' => 'Siswa'
            ]);
        }

        // --- MODE 2: ABSEN PULANG ---
        if ($mode == 2) {
            if (!$absensi) {
                return response()->json(['status' => 'error', 'message' => 'Belum Absen Masuk']);
            }

            if ($absensi->jam_pulang) {
                return response()->json(['status' => 'warning', 'message' => 'Sudah Absen Pulang']);
            }

            $absensi->update(['jam_pulang' => $timeNow]);

            return response()->json([
                'status' => 'success',
                'message' => 'Absen Pulang Berhasil',
                'nama' => $siswa->nama
            ]);
        }
    }

    /**
     * LOGIC PRIVATE: PROSES ABSENSI GURU
     */
    private function processAbsensiGuru($guru, $mode, $today, $timeNow)
    {
        // Cari Tahun Ajaran Aktif
        $tahunId = TahunAjaran::where('aktif', true)->value('id') ?? TahunAjaran::latest('id')->value('id');

        // Cek Absensi Guru Hari Ini
        $absensi = AbsensiGuru::where('guru_id', $guru->id)
            ->where('tanggal', $today)
            ->first();

        // --- MODE 1: MASUK ---
        if ($mode == 1) {
            if ($absensi) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Sudah Absen Masuk',
                    'nama' => $guru->nama
                ]);
            }

            AbsensiGuru::create([
                'guru_id' => $guru->id,
                'tahun_ajaran_id' => $tahunId,
                'tanggal' => $today,
                'jam_masuk' => $timeNow,
                'status' => 'hadir',
                'keterangan' => 'Hadir (Tap Kartu)'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Absen Guru Berhasil',
                'nama' => $guru->nama,
                'role' => 'Guru'
            ]);
        }

        // --- MODE 2: PULANG ---
        if ($mode == 2) {
            if (!$absensi) {
                return response()->json(['status' => 'error', 'message' => 'Belum Absen Masuk']);
            }
            if ($absensi->jam_pulang) {
                return response()->json(['status' => 'warning', 'message' => 'Sudah Absen Pulang']);
            }

            $absensi->update(['jam_pulang' => $timeNow]);

            return response()->json([
                'status' => 'success',
                'message' => 'Absen Pulang Berhasil',
                'nama' => $guru->nama
            ]);
        }
    }
}