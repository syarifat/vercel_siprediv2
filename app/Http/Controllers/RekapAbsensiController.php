<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use PDF;
use App\Models\Siswa;
use App\Models\RombelSiswa;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\AbsensiGuru;

class RekapAbsensiController extends Controller
{
    public function export(Request $request, $type)
    {
        $kelasId = $request->input('kelas_id');
        $periode = $request->input('periode'); // format YYYY-MM

        if (!$kelasId || !$periode) {
            return back()->with('error', 'Pilih kelas dan bulan dulu sebelum export.');
        }

        // allow exporting guru rekap by passing ?subject=guru
    $subject = $request->input('subject', 'siswa');
    // prefer explicit request param, then session (set by AppServiceProvider), then active fallback
    $tahunAjaranId = $request->input('tahun_ajaran_id') ?? session('tahun_ajaran_id') ?? \App\Models\TahunAjaran::where('aktif', true)->first()?->id;

        if ($type === 'pdf') {
            if ($subject === 'guru') {
                return $this->generatePdfGuru($periode);
            }
            return $this->generatePdf($kelasId, $periode, $tahunAjaranId);
        } elseif ($type === 'excel') {
            if ($subject === 'guru') {
                return $this->generateExcelGuru($periode);
            }
            return $this->generateExcel($kelasId, $periode);
        }
    }

    private function generatePdf($kelasId, $periode, $tahunAjaranId = null)
    {
        $daysInMonth = Carbon::parse($periode . '-01')->daysInMonth;

        // Ambil siswa di kelas untuk tahun ajaran yang relevan.
        if ($tahunAjaranId) {
            // jika user memilih tahun ajaran, gunakan itu
            $rombel = RombelSiswa::with(['siswa','kelas','tahunAjaran'])
                ->where('kelas_id', $kelasId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->get();
        } else {
            // prioritaskan tahun ajaran aktif
            $rombel = RombelSiswa::with(['siswa','kelas','tahunAjaran'])
                ->where('kelas_id', $kelasId)
                ->whereHas('tahunAjaran', function($q) {
                    $q->where('aktif', true);
                })
                ->get();

            // jika tidak ada rombel untuk tahun aktif, fallback: ambil rombel untuk kelas ini tanpa filter tahun
            if ($rombel->isEmpty()) {
                $rombel = RombelSiswa::with(['siswa','kelas','tahunAjaran'])
                    ->where('kelas_id', $kelasId)
                    ->get();
            }
        }

        // Map siswa_id => rombel_siswa_id, dan ambil semua rombel ids
        $siswaToRombel = $rombel->pluck('id', 'siswa_id')->toArray();
        $rombelIds = array_values($siswaToRombel);

        // Absensi bulan ini (per rombel_siswa_id)
        $absensi = Absensi::whereIn('rombel_siswa_id', $rombelIds)
            ->where('tanggal', 'like', "$periode%")
            ->get()
            ->groupBy('rombel_siswa_id');
        // Libur nasional dari API
        $tahun = substr($periode, 0, 4);
        $liburResponse = Http::get("https://dayoffapi.vercel.app/api?year=$tahun");
        $liburDates = [];
        if ($liburResponse->ok()) {
            foreach ($liburResponse->json() as $libur) {
                $tgl = Carbon::parse($libur['tanggal'])->format('Y-m-d');
                if (str_starts_with($tgl, $periode)) {
                    $liburDates[] = $tgl;
                }
            }
        }

        // Precompute per-day flags (is_sunday / is_libur) for header rendering
        $dayFlags = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $tgl = sprintf('%s-%02d', $periode, $d);
            $dayFlags[$d] = [
                'is_sunday' => Carbon::parse($tgl)->isSunday(),
                'is_libur' => in_array($tgl, $liburDates),
            ];
        }

        // Susun rekap
        $rekap = [];
        foreach ($rombel as $r) {
            $s = $r->siswa;
            $row = [
                'nama' => $s->nama,
                'nomor_absen' => $r->nomor_absen,
                'nis' => $s->nis,
                'data' => [],
                'H' => 0, 'S' => 0, 'I' => 0, 'A' => 0,
            ];

            for ($d=1; $d <= $daysInMonth; $d++) {
                $tgl = sprintf("%s-%02d", $periode, $d);

                // Cek apakah siswa punya data absensi (melalui rombel_siswa_id)
                $rId = $siswaToRombel[$s->id] ?? null;
                $absenHariIni = ($rId && isset($absensi[$rId]))
                    ? $absensi[$rId]->firstWhere('tanggal', $tgl)
                    : null;

                $status = $absenHariIni ? $absenHariIni->status : null;
                $statusNorm = $status ? strtolower($status) : null;

                // Default simbol
                $cell = '-';
                if ($statusNorm === 'hadir' || $statusNorm === 'h') { $cell = 'H'; $row['H']++; }
                elseif ($statusNorm === 'sakit' || $statusNorm === 's') { $cell = 'S'; $row['S']++; }
                elseif ($statusNorm === 'izin' || $statusNorm === 'i')  { $cell = 'I'; $row['I']++; }
                elseif ($statusNorm === 'alpha' || $statusNorm === 'a') { $cell = 'A'; $row['A']++; }

                $row['data'][$d] = [
                    'status' => $cell,
                    'tanggal' => $tgl,
                    'is_sunday' => $dayFlags[$d]['is_sunday'],
                    'is_libur'  => $dayFlags[$d]['is_libur'],
                ];
            }

            $rekap[] = $row;
        }

        // determine kelas name: try rombel first, then direct Kelas lookup
        $kelasNama = $rombel->first()?->kelas->nama ?? null;
        if (!$kelasNama) {
            $kelasModel = Kelas::find($kelasId);
            $kelasNama = $kelasModel?->nama ?? '-';
        }

        // tahun label: jika user pilih tahun ajaran, gunakan namanya; kalau tidak, gunakan tahun kalender dari periode
        $tahunLabel = substr($periode, 0, 4);
        if ($tahunAjaranId) {
            $ta = TahunAjaran::find($tahunAjaranId);
            if ($ta) $tahunLabel = $ta->nama;
        }
        $periodeLabel = Carbon::parse($periode . '-01')->translatedFormat('F Y');

        // Buat nama file custom
        $fileName = "Rekap Kehadiran Siswa {$kelasNama} - {$periodeLabel}.pdf";

        $tahunAjaranData = TahunAjaran::find($tahunAjaranId);
        $pdf = PDF::loadView('rekap.absensi_siswa_pdf', [
            'rekap' => $rekap,
            'daysInMonth' => $daysInMonth,
            'periode' => $periodeLabel,
            'kelas' => $kelasNama,
            'tahun' => $tahunLabel,
            'semester' => $tahunAjaranData ? $tahunAjaranData->semester : (Carbon::now()->month <= 6 ? 'Genap' : 'Ganjil'),
            'dayFlags' => $dayFlags,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($fileName);

    }

    private function generateExcel($kelasId, $periode)
    {
        // nanti isi dengan maatwebsite/excel
        return "Export Excel belum diimplementasi";
    }

    // PDF untuk rekap guru
    private function generatePdfGuru($periode)
    {
        $daysInMonth = Carbon::parse($periode . '-01')->daysInMonth;

        // Ambil absensi guru bulan ini
        $absensi = AbsensiGuru::with('guru')
            ->where('tanggal', 'like', "$periode%")
            ->get()
            ->groupBy('guru_id');

        // Ambil daftar guru yang memiliki rombel atau minimal yang ada di tabel guru
        $gurus = Guru::orderBy('nama')->get();

        // Libur nasional dari API
        $tahun = substr($periode, 0, 4);
        $liburResponse = Http::get("https://dayoffapi.vercel.app/api?year=$tahun");
        $liburDates = [];
        if ($liburResponse->ok()) {
            foreach ($liburResponse->json() as $libur) {
                $tgl = Carbon::parse($libur['tanggal'])->format('Y-m-d');
                if (str_starts_with($tgl, $periode)) {
                    $liburDates[] = $tgl;
                }
            }
        }

        // Precompute per-day flags
        $dayFlags = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $tgl = sprintf('%s-%02d', $periode, $d);
            $dayFlags[$d] = [
                'is_sunday' => Carbon::parse($tgl)->isSunday(),
                'is_libur' => in_array($tgl, $liburDates),
            ];
        }

        $rekap = [];
        foreach ($gurus as $g) {
            $row = [
                'nama' => $g->nama,
                'data' => [],
                'H' => 0, 'S' => 0, 'I' => 0, 'A' => 0,
            ];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $tgl = sprintf('%s-%02d', $periode, $d);
                $attendance = ($absensi[$g->id] ?? null) ? $absensi[$g->id]->firstWhere('tanggal', $tgl) : null;
                $status = $attendance ? $attendance->status : null;
                $statusNorm = $status ? strtolower($status) : null;
                $cell = '-';
                if ($statusNorm === 'hadir' || $statusNorm === 'h') { $cell = 'H'; $row['H']++; }
                elseif ($statusNorm === 'sakit' || $statusNorm === 's') { $cell = 'S'; $row['S']++; }
                elseif ($statusNorm === 'izin' || $statusNorm === 'i')  { $cell = 'I'; $row['I']++; }
                elseif ($statusNorm === 'alpha' || $statusNorm === 'a') { $cell = 'A'; $row['A']++; }

                $row['data'][$d] = [
                    'status' => $cell,
                    'tanggal' => $tgl,
                    'is_sunday' => $dayFlags[$d]['is_sunday'],
                    'is_libur' => $dayFlags[$d]['is_libur'],
                ];
            }
            $rekap[] = $row;
        }

        $periodeLabel = Carbon::parse($periode . '-01')->translatedFormat('F Y');
        $fileName = "Rekap Kehadiran Guru - {$periodeLabel}.pdf";

        $pdf = PDF::loadView('rekap.absensi_guru_pdf', [
            'rekap' => $rekap,
            'daysInMonth' => $daysInMonth,
            'periode' => $periodeLabel,
            'tahun' => $tahun,
            'dayFlags' => $dayFlags,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    private function generateExcelGuru($periode)
    {
        return "Export Excel Guru belum diimplementasi";
    }
}
