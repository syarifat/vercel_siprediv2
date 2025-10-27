<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RombelSiswa;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use PDF;

class RombelSiswaController extends Controller
{
    public function index()
    {
        $rombel = RombelSiswa::with(['siswa', 'kelas', 'tahunAjaran'])->get();
        return view('rombel_siswa.index', compact('rombel'));
    }

    public function create()
    {
        // Show all siswa and allow selecting tahun ajaran in the form.
        $siswa = \App\Models\Siswa::orderBy('nama')->get();
        $kelas = \App\Models\Kelas::all();
        $tahunAjaran = \App\Models\TahunAjaran::all();

        return view('rombel_siswa.create', compact('siswa', 'kelas', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);
        RombelSiswa::create($request->all());
        return redirect()->route('rombel_siswa.index')->with('success', 'Rombel siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rombel = RombelSiswa::findOrFail($id);
        $siswa = Siswa::all();
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::all();
        return view('rombel_siswa.edit', compact('rombel', 'siswa', 'kelas', 'tahunAjaran'));
    }

    public function update(Request $request, $id)
    {
        $rombel = RombelSiswa::findOrFail($id);
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);
        $rombel->update($request->all());
        return redirect()->route('rombel_siswa.index')->with('success', 'Rombel siswa berhasil diupdate.');
    }

    public function destroy($id)
    {
        $rombel = RombelSiswa::findOrFail($id);
        $rombel->delete();

        // Jika request AJAX/fetch, kirim 204 No Content
        if (request()->expectsJson()) {
            return response()->json(['success' => true], 200);
        }

        // Jika request biasa, redirect
        return redirect()->route('rombel_siswa.index')->with('success', 'Data berhasil dihapus');
    }

    public function mass_store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|array',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        // Recalculate nomor_absen (Option B): insert/update selected siswa then reassign numbers
        \DB::beginTransaction();
        try {
            $siswaList = \App\Models\Siswa::whereIn('id', $request->siswa_id)
                ->orderBy('nama', 'asc')->get();

            // track affected old classes to recompute their numbering after moves
            $affectedOldKelas = [];
            $targetKelasId = $request->kelas_id;
            $tahunId = $request->tahun_ajaran_id;

            foreach ($siswaList as $siswa) {
                $existing = \App\Models\RombelSiswa::where('siswa_id', $siswa->id)
                    ->where('tahun_ajaran_id', $tahunId)
                    ->first();
                if ($existing && $existing->kelas_id && $existing->kelas_id != $targetKelasId) {
                    $affectedOldKelas[] = $existing->kelas_id;
                }

                $rombel = \App\Models\RombelSiswa::updateOrCreate([
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $tahunId,
                ], [
                    'kelas_id' => $targetKelasId,
                ]);
            }

            // Recompute nomor_absen for target kelas
            $this->recomputeNomorAbsen($targetKelasId, $tahunId);

            // Recompute nomor_absen for any old kelas affected by moves
            $affectedOldKelas = array_values(array_unique($affectedOldKelas));
            foreach ($affectedOldKelas as $oldKelasId) {
                // avoid recomputing target twice
                if ($oldKelasId == $targetKelasId) continue;
                $this->recomputeNomorAbsen($oldKelasId, $tahunId);
            }

            \DB::commit();
            return redirect()->route('rombel_siswa.index')->with('success', 'Siswa berhasil dimasukkan dan nomor absen direkap ulang.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // helper to recompute nomor_absen for a kelas + tahun
    protected function recomputeNomorAbsen($kelasId, $tahunId)
    {
        $rombels = \App\Models\RombelSiswa::with('siswa')
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunId)
            ->get()
            ->sortBy(function($r) { return $r->siswa->nama ?? ''; });

        $no = 1;
        foreach ($rombels as $rombel) {
            $rombel->nomor_absen = $no;
            $rombel->save();
            $no++;
        }
    }

    public function gantiKelasMassal(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'kelas_baru_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        try {
            $tahunId = $request->tahun_ajaran_id;

            // Ambil rombel lama sebelum update (only for this tahun)
            $rombelsLama = \App\Models\RombelSiswa::whereIn('id', $request->ids)
                ->where('tahun_ajaran_id', $tahunId)
                ->get();
            $kelasLamaIds = $rombelsLama->pluck('kelas_id')->unique();

            if ($rombelsLama->count() === 0) {
                return response()->json(['success' => false, 'message' => 'Tidak ada data rombel untuk tahun ajaran yang dipilih atau ID tidak sesuai.'], 400);
            }

            // Update kelas_id ke kelas baru but only for entries in this tahun
            \App\Models\RombelSiswa::whereIn('id', $request->ids)
                ->where('tahun_ajaran_id', $tahunId)
                ->update(['kelas_id' => $request->kelas_baru_id]);

            // Update nomor_absen di kelas lama (restrict by tahun)
            foreach ($kelasLamaIds as $kelasId) {
                $rombels = \App\Models\RombelSiswa::where('kelas_id', $kelasId)
                    ->where('tahun_ajaran_id', $tahunId)
                    ->get()->sortBy(function($r) { return $r->siswa->nama ?? ''; });
                $no = 1;
                foreach ($rombels as $rombel) {
                    $rombel->nomor_absen = $no;
                    $rombel->save();
                    $no++;
                }
            }

            // Update nomor_absen di kelas baru (restrict by tahun)
            $rombelsBaru = \App\Models\RombelSiswa::where('kelas_id', $request->kelas_baru_id)
                ->where('tahun_ajaran_id', $tahunId)
                ->get()->sortBy(function($r) { return $r->siswa->nama ?? ''; });
            $no = 1;
            foreach ($rombelsBaru as $rombel) {
                $rombel->nomor_absen = $no;
                $rombel->save();
                $no++;
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function exportPdf(Request $request)
    {
        $kelasId = $request->query('kelas_id');
        if (!$kelasId) {
            abort(400, 'kelas_id wajib diisi');
        }

        $kelas = \App\Models\Kelas::findOrFail($kelasId);
        $rombel = \App\Models\RombelSiswa::with('siswa', 'kelas', 'tahunAjaran')
            ->where('kelas_id', $kelasId)
            ->orderBy('nomor_absen')
            ->get();

        $fileName = "Daftar Siswa {$kelas->nama} - " . Carbon::now()->format('Y-m-d') . ".pdf";

        $pdf = PDF::loadView('rombel_siswa.pdf', [
            'kelas' => $kelas,
            'rombel' => $rombel
        ])->setPaper('a4', 'portrait');

        return $pdf->download($fileName);
    }
}
