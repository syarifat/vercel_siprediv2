<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderByDesc('id')->get();
        return view('tahun_ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'semester' => 'required|in:Ganjil,Genap',
            'aktif' => 'nullable|boolean',
        ]);

        $aktif = $request->boolean('aktif');

        // Jika diset aktif, nonaktifkan yang lain
        if ($aktif) {
            TahunAjaran::where('aktif', 1)->update(['aktif' => 0]);
        }

        TahunAjaran::create([
            'nama' => $request->nama,
            'semester' => $request->semester,
            'aktif' => $aktif,
        ]);

        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun ajaran berhasil disimpan.');
    }

    public function edit($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        return view('tahun_ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $request->validate([
            'nama' => 'required|string|max:100',
            'semester' => 'required|in:Ganjil,Genap',
            'aktif' => 'nullable|boolean',
        ]);

        $aktif = $request->boolean('aktif');

        if ($aktif) {
            TahunAjaran::where('aktif', 1)->where('id', '!=', $id)->update(['aktif' => 0]);
        }

        $tahunAjaran->update([
            'nama' => $request->nama,
            'semester' => $request->semester,
            'aktif' => $aktif,
        ]);

        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun ajaran berhasil diupdate.');
    }

    public function destroy($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        if ($tahunAjaran->aktif) {
            return redirect()->route('tahun_ajaran.index')->with('error', 'Tidak bisa menghapus tahun ajaran aktif.');
        }
        $tahunAjaran->delete();
        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}