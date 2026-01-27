<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AbsensiGuruController extends Controller
{
    public function index()
    {
        return view('absensi.guru'); // Data load by AJAX
    }

    public function create()
    {
        $gurus = Guru::where('status', 'aktif')->orderBy('nama')->get();
        return view('absensi.guru_create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
        ]);

        $tahunId = session('tahun_ajaran_id') ?? TahunAjaran::where('aktif', true)->value('id');

        AbsensiGuru::create([
            'guru_id' => $request->guru_id,
            'tanggal' => $request->tanggal,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'tahun_ajaran_id' => $tahunId,
        ]);

        // PERBAIKAN DI SINI: route('absensi_guru.index')
        return redirect()->route('absensi_guru.index')->with('success', 'Absensi guru berhasil disimpan.');
    }

    public function edit(AbsensiGuru $absensiGuru)
    {
        $gurus = Guru::all();
        return view('absensi.guru_edit', ['absensi' => $absensiGuru, 'gurus' => $gurus]);
    }

    public function update(Request $request, AbsensiGuru $absensiGuru)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
        ]);

        $absensiGuru->update($request->only(['jam_masuk', 'jam_pulang', 'status', 'keterangan']));
        
        // PERBAIKAN DI SINI: route('absensi_guru.index')
        return redirect()->route('absensi_guru.index')->with('success', 'Data berhasil diperbarui.');
    }
    
    public function show(AbsensiGuru $absensiGuru)
    {
        return view('absensi.guru_show', ['absensi' => $absensiGuru]);
    }

    public function destroy(AbsensiGuru $absensiGuru)
    {
        $absensiGuru->delete();
        
        // PERBAIKAN DI SINI: route('absensi_guru.index')
        return redirect()->route('absensi_guru.index')->with('success', 'Data berhasil dihapus.');
    }
}