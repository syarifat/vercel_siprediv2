<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai Query
        $query = Siswa::query();

        // 2. Cek apakah ada input pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }

        // 3. Ambil data dengan Pagination (20 per halaman)
        // orderBy nama agar rapi
        $siswa = $query->orderBy('nama', 'asc')->paginate(20);

        // 4. Return ke View
        return view('siswa.index', compact('siswa'));
    }

    public function create()
    {
        // Dropdown data (opsional, jika view create butuh)
        return view('siswa.create');
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

        Siswa::create($request->all());
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
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

        $siswa->update($request->all());
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diupdate');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
    }
}