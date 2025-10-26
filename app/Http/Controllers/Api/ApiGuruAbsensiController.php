<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\RombelSiswa;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\AbsensiGuru;
use Carbon\Carbon;

class ApiGuruAbsensiController extends Controller
{
	/**
	 * Device akan POST rfid ke endpoint ini.
	 * Logika: cek siswa.rfid dulu -> buat/ubah absensi_siswa.
	 * Jika tidak ketemu, cek guru.rfid -> buat/ubah absensi_guru.
	 */
	public function store(Request $request)
	{
		$request->validate([
			'rfid' => 'required|string',
			'tanggal' => 'nullable|date',
		]);

		$rfid = $request->rfid;
		$tanggal = $request->tanggal ?? Carbon::now()->toDateString();

		// Cek siswa dulu
		$siswa = Siswa::where('rfid', $rfid)->first();
		if ($siswa) {
			// ambil rombel terbaru untuk siswa (prioritaskan tahun ajaran aktif)
			$rombel = RombelSiswa::where('siswa_id', $siswa->id)
				->orderByDesc('tahun_ajaran_id')
				->first();

			if (! $rombel) {
				return response()->json(['status' => 'error', 'message' => 'Rombel siswa tidak ditemukan'], 422);
			}

			$abs = AbsensiSiswa::where('rombel_siswa_id', $rombel->id)
				->where('tanggal', $tanggal)
				->first();

			if (! $abs) {
				$abs = AbsensiSiswa::create([
					'rombel_siswa_id' => $rombel->id,
					'tanggal' => $tanggal,
					'jam_masuk' => Carbon::now()->toTimeString(),
					'status' => 'hadir',
				]);
				return response()->json(['status' => 'ok', 'type' => 'siswa', 'action' => 'created', 'data' => $abs]);
			}

			// jika sudah ada tetapi belum ada jam_pulang, set jam_pulang
			if (! $abs->jam_pulang) {
				$abs->jam_pulang = Carbon::now()->toTimeString();
				$abs->save();
				return response()->json(['status' => 'ok', 'type' => 'siswa', 'action' => 'updated', 'data' => $abs]);
			}

			return response()->json(['status' => 'ok', 'type' => 'siswa', 'action' => 'unchanged', 'data' => $abs]);
		}

		// Cek guru
		$guru = Guru::where('rfid', $rfid)->first();
		if ($guru) {
			$absG = AbsensiGuru::where('guru_id', $guru->id)
				->where('tanggal', $tanggal)
				->first();

			if (! $absG) {
				$absG = AbsensiGuru::create([
					'guru_id' => $guru->id,
					'tanggal' => $tanggal,
					'jam_masuk' => Carbon::now()->toTimeString(),
					'status' => 'hadir',
				]);
				return response()->json(['status' => 'ok', 'type' => 'guru', 'action' => 'created', 'data' => $absG]);
			}

			if (! $absG->jam_pulang) {
				$absG->jam_pulang = Carbon::now()->toTimeString();
				$absG->save();
				return response()->json(['status' => 'ok', 'type' => 'guru', 'action' => 'updated', 'data' => $absG]);
			}

			return response()->json(['status' => 'ok', 'type' => 'guru', 'action' => 'unchanged', 'data' => $absG]);
		}

		return response()->json(['status' => 'error', 'message' => 'RFID tidak terdaftar di siswa maupun guru'], 404);
	}
}
