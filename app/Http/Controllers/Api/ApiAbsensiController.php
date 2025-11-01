<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\RombelSiswa;
use App\Models\TahunAjaran;
use App\Models\Absensi;
use App\Services\FonnteService;
use App\Models\Guru;
use App\Models\AbsensiGuru;
use Carbon\Carbon;

class ApiAbsensiController extends Controller
{
    /**
     * Endpoint untuk device: menerima rfid dan mode_id
     * mode_id = 1 -> jam masuk
     * mode_id = 2 -> jam pulang
     * Prioritas: cari siswa (dan rombel) terlebih dahulu; jika tidak ada, cek guru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rfid' => 'required|string',
            'mode_id' => 'nullable|in:1,2',
            'tanggal' => 'nullable|date',
        ]);

        $rfid = $request->input('rfid');
        $mode = (int) ($request->input('mode_id', 1));
        $tanggal = $request->input('tanggal') ? Carbon::parse($request->input('tanggal'))->toDateString() : Carbon::now()->toDateString();
        $timeNow = Carbon::now()->toTimeString();

        // ---------- Siswa flow ----------
        $siswa = Siswa::where('rfid', $rfid)->first();
        if ($siswa) {
            // Untuk alat absensi, selalu gunakan tahun ajaran aktif
            $rombel = RombelSiswa::where('siswa_id', $siswa->id)
                ->whereHas('tahunAjaran', function ($q) { 
                    $q->where('aktif', true); 
                })
                ->first();

            if (! $rombel) {
                // Jika tidak ada di tahun aktif, cek apakah ada di tahun ajaran manapun
                $rombel = RombelSiswa::where('siswa_id', $siswa->id)
                    ->orderByDesc('tahun_ajaran_id')
                    ->first();

                // Log warning jika siswa tidak terdaftar di tahun aktif tapi ada di tahun lain
                if ($rombel) {
                    \Log::warning("Siswa dengan ID {$siswa->id} melakukan absensi tapi tidak terdaftar di tahun ajaran aktif. Menggunakan rombel dari tahun " . 
                        ($rombel->tahunAjaran->nama ?? 'tidak diketahui'));
                }
            }

            if (! $rombel) {
                return response()->json(['status' => 'error', 'message' => 'Rombel siswa tidak ditemukan'], 422);
            }

            $abs = Absensi::whereDate('tanggal', $tanggal)
                ->where('rombel_siswa_id', $rombel->id)
                ->first();

            // mode 1 -> jam masuk
            if ($mode === 1) {
                if ($abs && $abs->jam_masuk) {
                    return response()->json(['status' => 'error', 'message' => 'Sudah absen masuk', 'type' => 'siswa', 'action' => 'unchanged', 'data' => $abs], 200);
                }

                if (! $abs) {
                    $abs = Absensi::create([
                        'rombel_siswa_id' => $rombel->id,
                        'tanggal' => $tanggal,
                        'jam_masuk' => $timeNow,
                        'status' => 'hadir',
                    ]);

                    // Kirim WA ke orang tua saat pertama kali absen masuk
                    try {
                        if ($rombel->siswa && $rombel->siswa->no_hp_ortu) {
                            $wa = $rombel->siswa->no_hp_ortu;
                            if (substr($wa, 0, 1) === '0') {
                                $wa = '62' . substr($wa, 1);
                            }
                            $dayText = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
                            $kelasNama = $rombel->kelas->nama ?? '-';
                            $namaSiswa = $rombel->siswa->nama ?? '-';
                            $statusText = strtoupper('hadir');
                            $message = "Assalamu'alaikum Bapak/Ibu,\n" .
                                "Kami informasikan bahwa putra/putri Bapak/Ibu:\n" .
                                "Nama   : {$namaSiswa}\n" .
                                "Kelas  : {$kelasNama}\n" .
                                "\n" .
                                "Hari ini, {$dayText} pukul *{$timeNow}*, tercatat *{$statusText}* di sekolah.\n\n" .
                                "Terima kasih atas perhatian dan kerja samanya";
                            $fonnte = new FonnteService();
                            $res = $fonnte->sendMessage($wa, $message);
                            \Log::info('Fonnte send response (api absensi):', (array) $res);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed sending whatsapp (api absensi): ' . $e->getMessage());
                    }

                    return response()->json(['status' => 'ok', 'type' => 'siswa', 'action' => 'created', 'data' => $abs], 201);
                }

                // abs exists but jam_masuk null
                $abs->jam_masuk = $timeNow;
                $abs->save();

                // Kirim WA juga saat memperbarui jam_masuk yang sebelumnya kosong
                try {
                    if ($rombel->siswa && $rombel->siswa->no_hp_ortu) {
                        $wa = $rombel->siswa->no_hp_ortu;
                        if (substr($wa, 0, 1) === '0') {
                            $wa = '62' . substr($wa, 1);
                        }
                        $dayText = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
                        $kelasNama = $rombel->kelas->nama ?? '-';
                        $namaSiswa = $rombel->siswa->nama ?? '-';
                        $statusText = strtoupper('hadir');
                        $message = "Assalamu'alaikum Bapak/Ibu,\n" .
                            "Kami informasikan bahwa putra/putri Bapak/Ibu:\n" .
                            "Nama   : {$namaSiswa}\n" .
                            "Kelas  : {$kelasNama}\n" .
                            "Hari ini, {$dayText} pukul {$timeNow}, tercatat {$statusText} di sekolah.\n\n" .
                            "Terima kasih atas perhatian dan kerja samanya";
                        $fonnte = new FonnteService();
                        $res = $fonnte->sendMessage($wa, $message);
                        \Log::info('Fonnte send response (api absensi update jam_masuk):', (array) $res);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed sending whatsapp (api absensi update): ' . $e->getMessage());
                }

                return response()->json(['status' => 'ok', 'type' => 'siswa', 'action' => 'updated', 'data' => $abs], 200);
            }

            // mode 2 -> jam pulang
            if ($mode === 2) {
                if (! $abs || ! $abs->jam_masuk) {
                    return response()->json(['status' => 'error', 'message' => 'Belum absen masuk', 'type' => 'siswa'], 400);
                }
                if ($abs->jam_pulang) {
                    return response()->json(['status' => 'error', 'message' => 'Sudah absen pulang', 'type' => 'siswa', 'action' => 'unchanged', 'data' => $abs], 200);
                }
                $abs->jam_pulang = $timeNow;
                $abs->save();
                return response()->json(['status' => 'ok', 'type' => 'siswa', 'action' => 'updated', 'data' => $abs], 200);
            }
        }

        // ---------- Guru flow (jika bukan siswa) ----------
        $guru = Guru::where('rfid', $rfid)->first();
        if ($guru) {
            $absG = AbsensiGuru::whereDate('tanggal', $tanggal)
                ->where('guru_id', $guru->id)
                ->first();

            if ($mode === 1) {
                if ($absG && $absG->jam_masuk) {
                    return response()->json(['status' => 'error', 'message' => 'Sudah absen masuk', 'type' => 'guru', 'action' => 'unchanged', 'data' => $absG], 200);
                }
                if (! $absG) {
                    // Ambil tahun ajaran aktif
                    $tahunAjaran = TahunAjaran::where('aktif', true)->first();
                    if (!$tahunAjaran) {
                        $tahunAjaran = TahunAjaran::latest('id')->first();
                    }

                    $absG = AbsensiGuru::create([
                        'guru_id' => $guru->id,
                        'tanggal' => $tanggal,
                        'jam_masuk' => $timeNow,
                        'status' => 'hadir',
                        'tahun_ajaran_id' => $tahunAjaran ? $tahunAjaran->id : null,
                    ]);
                    return response()->json(['status' => 'ok', 'type' => 'guru', 'action' => 'created', 'data' => $absG], 201);
                }
                $absG->jam_masuk = $timeNow;
                $absG->save();
                return response()->json(['status' => 'ok', 'type' => 'guru', 'action' => 'updated', 'data' => $absG], 200);
            }

            if ($mode === 2) {
                if (! $absG || ! $absG->jam_masuk) {
                    return response()->json(['status' => 'error', 'message' => 'Belum absen masuk', 'type' => 'guru'], 400);
                }
                if ($absG->jam_pulang) {
                    return response()->json(['status' => 'error', 'message' => 'Sudah absen pulang', 'type' => 'guru', 'action' => 'unchanged', 'data' => $absG], 200);
                }
                $absG->jam_pulang = $timeNow;
                $absG->save();
                return response()->json(['status' => 'ok', 'type' => 'guru', 'action' => 'updated', 'data' => $absG], 200);
            }
        }

        return response()->json(['status' => 'error', 'message' => 'RFID tidak terdaftar di siswa maupun guru'], 404);
    }
}
