<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\RombelSiswa;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('nama')->get();
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100']);
        Kelas::create($request->only(['nama']));
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama' => 'required|string|max:100']);
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->only(['nama']));
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        // Validasi: jangan hapus jika ada siswa
        if (RombelSiswa::where('kelas_id', $kelas->id)->exists()) {
            return redirect()->route('kelas.index')->with('error', 'Gagal: Masih ada siswa di kelas ini.');
        }
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}