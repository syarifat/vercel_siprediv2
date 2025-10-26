<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::orderBy('nama')->paginate(20);
        return view('siswa.index', compact('siswa'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama')->get();
        $tahun = TahunAjaran::orderByDesc('id')->get();
        return view('siswa.create', compact('kelas','tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nis' => 'required|string|max:30|unique:siswa,nis',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp_ortu' => 'nullable|string|max:20',
            'rfid' => 'nullable|string|max:32|unique:siswa,rfid',
            'status' => 'required|in:aktif,lulus,keluar',
        ]);

        Siswa::create($request->only(['nama','nis','jenis_kelamin','no_hp_ortu','rfid','status']));

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama')->get();
        $tahun = TahunAjaran::orderByDesc('id')->get();
        return view('siswa.edit', compact('siswa','kelas','tahun'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nis' => 'required|string|max:30|unique:siswa,nis,' . $siswa->id,
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp_ortu' => 'nullable|string|max:20',
            'rfid' => 'nullable|string|max:32|unique:siswa,rfid,' . $siswa->id,
            'status' => 'required|in:aktif,lulus,keluar',
        ]);

        $siswa->update($request->only(['nama','nis','jenis_kelamin','no_hp_ortu','rfid','status']));

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diupdate');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
    }
}
