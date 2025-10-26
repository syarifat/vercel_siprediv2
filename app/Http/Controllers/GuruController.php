<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::orderBy('nama')->get();
        return view('guru.index', compact('guru'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'nullable|string|max:50',
            'rfid' => 'nullable|string|max:32|unique:guru,rfid',
            'no_hp' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Guru::create($request->only(['nama','nip','rfid','no_hp','status']));

        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'nullable|string|max:50',
            'rfid' => 'nullable|string|max:32|unique:guru,rfid,' . $guru->id,
            'no_hp' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $guru->update($request->only(['nama','nip','rfid','no_hp','status']));

        return redirect()->route('guru.index')->with('success', 'Guru berhasil diupdate.');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
