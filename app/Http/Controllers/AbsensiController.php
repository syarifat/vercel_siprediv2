<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\RombelSiswa;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; // untuk export Excel
use PDF; // untuk export PDF (pastikan sudah install barryvdh/laravel-dompdf)

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter: kelas, tanggal, search
        $query = Absensi::query();
        // filter by kelas through rombel relation
        if ($request->filled('kelas_id')) {
            $query->whereHas('rombel.kelas', function($q) use ($request) {
                $q->where('id', $request->kelas_id);
            });
        }
        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('rombel.siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nis', 'like', "%$search%");
            });
        }
        $absensi = $query->with(['rombel.siswa','rombel.kelas'])->orderBy('tanggal', 'desc')->get();
        return view('absensi.index', compact('absensi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tampilkan form tambah absensi
        // For student absensi we expect selecting rombel_siswa (student in class)
        $rombels = RombelSiswa::with(['siswa','kelas'])->orderBy('nomor_absen')->get();
        return view('absensi.create', compact('rombels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rombel_siswa_id' => 'required|exists:rombel_siswa,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable',
        ]);

        $absensi = Absensi::create([
            'rombel_siswa_id' => $request->rombel_siswa_id,
            'tanggal' => $request->tanggal,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang ?? null,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        // Kirim WA ke orang tua via rombel->siswa
        $rombel = RombelSiswa::with('siswa')->find($request->rombel_siswa_id);
        if ($rombel && $rombel->siswa && $rombel->siswa->no_hp_ortu) {
            $wa = $rombel->siswa->no_hp_ortu;
            // Pastikan format nomor sudah 62xxx
            if (substr($wa, 0, 1) === '0') {
                $wa = '62' . substr($wa, 1);
            }
            $dayText = Carbon::parse($request->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
            $namaSiswa = $rombel->siswa->nama ?? '-';
            $kelasNama = $rombel->kelas->nama ?? '-';
            $statusText = strtoupper($request->status);
            $message = "Assalamu'alaikum Bapak/Ibu,\n" .
                "Kami informasikan bahwa putra/putri Bapak/Ibu:\n" .
                "Nama   : {$namaSiswa}\n" .
                "Kelas  : {$kelasNama}\n" .
                "\n" .
                "Hari ini, {$dayText} pukul *{$request->jam_masuk}*, tercatat *{$statusText}* di sekolah.\n\n" .
                "Terima kasih atas perhatian dan kerja samanya";
            $fonnte = new FonnteService();
            $fonnte->sendMessage($wa, $message);
        }

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil ditambahkan dan notifikasi WA dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        return view('absensi.show', compact('absensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        return view('absensi.edit', compact('absensi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable',
        ]);
        $absensi->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        $absensi->delete();
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus.');
    }

    /**
     * Export the resource to the specified type.
     */
    public function export($type)
    {
        $absensi = \App\Models\Absensi::with(['rombel.kelas'])->orderBy('tanggal', 'desc')->get();

        if ($type === 'excel') {
            // Export Excel
            return Excel::download(new \App\Exports\AbsensiExport($absensi), 'absensi.xlsx');
        } elseif ($type === 'pdf') {
            // Export PDF
            $pdf = PDF::loadView('absensi.export_pdf', compact('absensi'));
            return $pdf->download('absensi.pdf');
        }
        return back();
    }
}
