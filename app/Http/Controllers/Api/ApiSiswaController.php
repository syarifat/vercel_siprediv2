<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class ApiSiswaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai Query Siswa
        $query = Siswa::query();

        // 2. Cek apakah ada input 'search' dari frontend
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            // Filter berdasarkan nama atau nis
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
        } else {
            // Jika tidak ada search, limit 50 biar tidak berat load semua
            $query->limit(50);
        }

        // 3. Ambil datanya (Get) dan urutkan
        $siswa = $query->orderBy('nama', 'asc')->get();

        // 4. Return langsung datanya (JSON)
        // Kita return Array langsung agar cocok dengan script JS di view siswa.index
        return response()->json($siswa);
    }
}