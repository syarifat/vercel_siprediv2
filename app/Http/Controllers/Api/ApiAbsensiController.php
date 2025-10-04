<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Support\Carbon;

class ApiAbsensiController extends Controller
{
    public function store(Request $request)
    {
        $rfid = $request->input('rfid');
        $mode = $request->input('mode_id', 1); // default masuk

        if (!$rfid) {
            return response()->json([
                'status' => 'error',
                'message' => 'RFID wajib diisi',
            ], 400);
        }

        // Cari siswa berdasarkan RFID
        $siswa = Siswa::where('rfid', $rfid)->first();
        if (!$siswa) {
            return response()->json([
                'status' => 'notfound',
                'message' => 'RFID tidak terdaftar',
            ], 404);
        }

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d');
    $jam = date('H:i:s');

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $tanggal)
            ->first();

        if ($mode == 1) {
            // Absen masuk
            if ($absensi) {
                return response()->json([
                    'status' => 'sudah',
                    'nama' => $siswa->nama,
                    'jam_tap' => $absensi->jam,
                ]);
            }
            $absensi = Absensi::create([
                'siswa_id' => $siswa->id,
                'tanggal' => $tanggal,
                'jam' => $jam,
                'status' => 'hadir',
                'keterangan' => null,
                'user_id' => null,
            ]);

            // Kirim WhatsApp ke orang tua dengan format khusus
            if ($siswa->no_hp_ortu) {
                $wa = $siswa->no_hp_ortu;
                if (substr($wa, 0, 1) === '0') {
                    $wa = '62' . substr($wa, 1);
                }
                // Ambil nama kelas
                $kelas = '-';
                if ($siswa->rombel && $siswa->rombel->kelas) {
                    $kelas = $siswa->rombel->kelas->nama;
                }
                // Nama sekolah (bisa diganti sesuai kebutuhan)
                $nama_sekolah = env('NAMA_SEKOLAH');
                // Nama hari
                $hari = [
                    'Sunday' => 'Minggu',
                    'Monday' => 'Senin',
                    'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Kamis',
                    'Friday' => 'Jumat',
                    'Saturday' => 'Sabtu',
                ];
                $carbon = \Carbon\Carbon::parse($tanggal);
                $nama_hari = $hari[$carbon->format('l')] ?? $carbon->format('l');
                $tanggal_indo = $carbon->translatedFormat('d F Y');
                    $message = "Assalamu’alaikum Bapak/Ibu,\n" .
                        "Kami informasikan bahwa putra/putri Bapak/Ibu:\n" .
                        "Nama   : {$siswa->nama}\n" .
                        "Kelas  : {$kelas}\n\n" .
                        "Hari ini, {$nama_hari}, {$tanggal_indo} pukul {$jam}, tercatat sudah *HADIR* di sekolah.\n" .
                        "Terima kasih atas perhatian dan kerja samanya. 🙏\n" .
                        "- {$nama_sekolah}";
                $fonnte = new \App\Services\FonnteService();
                $fonnte->sendMessage($wa, $message);
            }

            return response()->json([
                'status' => 'ok',
                'nama' => $siswa->nama,
                'jam_tap' => $jam,
            ]);
        } elseif ($mode == 2) {
            // Absen pulang
            if (intval(date('H')) < 12) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Absen pulang hanya bisa setelah jam 12',
                ], 403);
            }
            if (!$absensi) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Belum absen masuk',
                ], 400);
            }
            if ($absensi->jam_pulang) {
                return response()->json([
                    'status' => 'sudah',
                    'nama' => $siswa->nama,
                    'jam_tap' => $absensi->jam_pulang,
                ]);
            }
            // Hanya update jam_pulang, tidak mengubah status
            $absensi->jam_pulang = $jam;
            $absensi->save();

            // Kirim WhatsApp ke orang tua saat pulang
            if ($siswa->no_hp_ortu) {
                $wa = $siswa->no_hp_ortu;
                if (substr($wa, 0, 1) === '0') {
                    $wa = '62' . substr($wa, 1);
                }
                $kelas = '-';
                if ($siswa->rombel && $siswa->rombel->kelas) {
                    $kelas = $siswa->rombel->kelas->nama;
                }
                $nama_sekolah = env('NAMA_SEKOLAH');
                $hari = [
                    'Sunday' => 'Minggu',
                    'Monday' => 'Senin',
                    'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Kamis',
                    'Friday' => 'Jumat',
                    'Saturday' => 'Sabtu',
                ];
                $carbon = \Carbon\Carbon::parse($tanggal);
                $nama_hari = $hari[$carbon->format('l')] ?? $carbon->format('l');
                $tanggal_indo = $carbon->translatedFormat('d F Y');
                $message = "Assalamu’alaikum Bapak/Ibu,\n" .
                    "Kami informasikan bahwa putra/putri Bapak/Ibu:\n" .
                    "Nama   : {$siswa->nama}\n" .
                    "Kelas  : {$kelas}\n\n" .
                    "Hari ini, {$nama_hari}, {$tanggal_indo} pukul {$jam}, tercatat sudah *PULANG* dari sekolah.\n" .
                    "Terima kasih atas perhatian dan kerja samanya. 🙏\n" .
                    "- {$nama_sekolah}";
                $fonnte = new \App\Services\FonnteService();
                $fonnte->sendMessage($wa, $message);
            }

            return response()->json([
                'status' => 'ok',
                'nama' => $siswa->nama,
                'jam_tap' => $jam,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Mode tidak dikenali',
        ], 400);
    }

    public function index(Request $request)
    {
        $absensi = Absensi::with(['siswa.rombel.kelas'])
            ->when($request->search, function($q) use ($request) {
                $q->whereHas('siswa', function($qq) use ($request) {
                    $qq->where('nama', 'like', '%' . $request->search . '%')
                       ->orWhere('nis', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->tanggal, function($q) use ($request) {
                $q->whereDate('tanggal', $request->tanggal);
            })
            ->when($request->kelas_id, function($q) use ($request) {
                $q->whereHas('siswa.rombel', function($qq) use ($request) {
                    $qq->where('kelas_id', $request->kelas_id);
                });
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json($absensi->map(function($row) {
            return [
                'id' => $row->id,
                'siswa_nama' => $row->siswa ? $row->siswa->nama : '-',
                'siswa_nis' => $row->siswa ? $row->siswa->nis : '-',
                'kelas_nama' => ($row->siswa && $row->siswa->rombel && $row->siswa->rombel->kelas) ? $row->siswa->rombel->kelas->nama : '-',
                'tanggal' => $row->tanggal,
                'jam' => $row->jam,
                'jam_pulang' => $row->jam_pulang, // <--- tambahkan ini
                'status' => $row->status,
                'keterangan' => $row->keterangan,
            ];
        }));
    }
}
