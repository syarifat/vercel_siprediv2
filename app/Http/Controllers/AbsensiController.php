<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\RombelSiswa;
use App\Services\FonnteService; // Pastikan Service ini ada, atau pakai Guzzle langsung di sini
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        // Data diload via API (AJAX) di view absensi.index untuk performa
        // Controller ini hanya mereturn view kosong
        return view('absensi.index'); 
    }

    // Untuk manual input via tombol "Edit" di tabel (sebenarnya Edit, tapi viewnya bisa dipakai create juga kalau butuh)
    public function edit(Absensi $absensi)
    {
        return view('absensi.edit', compact('absensi'));
    }

    // Update Status Absensi Manual
    public function update(Request $request, Absensi $absensi)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'user_id' => auth()->id(), // Log siapa yang ngubah
        ]);

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function show(Absensi $absensi)
    {
        return view('absensi.show', compact('absensi'));
    }
    
    // Note: Create & Store manual jarang dipakai karena biasanya dari mesin atau edit status dari Alpha ke Izin/Sakit.
    // Tapi jika butuh, bisa copy dari kode lama Mas.
}