<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use PDF;
use App\Models\Guru;
use App\Models\AbsensiGuru;

class RekapAbsensiGuruController extends Controller
{
    public function export(Request $request, $type)
    {
        $periode = $request->input('periode'); // format YYYY-MM
        // Ambil tahun ajaran dari request, atau dari session, atau default ke yang aktif
        $tahunAjaranId = $request->input('tahun_ajaran_id') ?? 
                        session('tahun_ajaran_id') ?? 
                        \App\Models\TahunAjaran::where('aktif', true)->first()?->id;

        if (!$periode) {
            return back()->with('error', 'Pilih bulan dulu sebelum export.');
        }

        if (!$tahunAjaranId) {
            return back()->with('error', 'Pilih tahun ajaran terlebih dahulu.');
        }

        if ($type === 'pdf') {
            return $this->generatePdf($periode, $tahunAjaranId);
        } elseif ($type === 'excel') {
            return $this->generateExcel($periode, $tahunAjaranId);
        }
    }

    private function generatePdf($periode, $tahunAjaranId)
    {
        // Ambil data tahun ajaran yang dipilih
        $tahunAjaran = \App\Models\TahunAjaran::find($tahunAjaranId);
        if (!$tahunAjaran) {
            return back()->with('error', 'Tahun ajaran tidak ditemukan');
        }

        $daysInMonth = Carbon::parse($periode . '-01')->daysInMonth;

        // Ambil absensi guru bulan ini untuk tahun ajaran yang dipilih
        $absensiRecords = AbsensiGuru::with('guru')
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
            ->get();
            
        // Debug: Log all found records
        foreach ($absensiRecords as $record) {
            \Illuminate\Support\Facades\Log::info("Found attendance record:", [
                'guru_id' => $record->guru_id,
                'tanggal' => $record->tanggal,
                'status' => $record->status,
                'tahun_ajaran_id' => $record->tahun_ajaran_id,
                'guru_name' => $record->guru->nama ?? 'No Guru Found'
            ]);
        }
        
        $absensi = $absensiRecords->groupBy('guru_id');

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
                // Find attendance for this day
                $attendance = null;
                if (isset($absensi[$g->id])) {
                    foreach ($absensi[$g->id] as $abs) {
                        // Bandingkan tanggal tanpa waktu
                        $absDate = date('Y-m-d', strtotime($abs->tanggal));
                        if ($absDate === $tgl) {
                            $attendance = $abs;
                            break;
                        }
                    }
                }
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

        $tahunLabel = $tahunAjaran->nama;
        $semesterLabel = $tahunAjaran->semester;
        
        $pdf = PDF::loadView('rekap.absensi_guru_pdf', [
            'rekap' => $rekap,
            'daysInMonth' => $daysInMonth,
            'periode' => $periodeLabel,
            'dayFlags' => $dayFlags,
            'tahun' => $tahunLabel,
            'semester' => $semesterLabel,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    private function generateExcel($periode)
    {
        return "Export Excel Guru belum diimplementasi";
    }
}
