<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiGuru;

class ApiAbsensiGuruController extends Controller
{
    public function index(Request $request)
    {
        $query = AbsensiGuru::with('guru')->orderBy('created_at', 'desc');
        
        if(session('tahun_ajaran_id')) $query->where('tahun_ajaran_id', session('tahun_ajaran_id'));
        if($request->filled('tanggal')) $query->whereDate('tanggal', $request->tanggal);
        
        if($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('guru', fn($q)=>$q->where('nama','like',"%$s%"));
        }

        if(!$request->filled('tanggal')) $query->limit(50);

        $data = $query->get()->map(function($d){
            return [
                'id' => $d->id,
                'guru_nama' => $d->guru->nama ?? '-',
                'tanggal' => $d->tanggal,
                'jam_masuk' => $d->jam_masuk ? substr($d->jam_masuk,0,5) : '-',
                'jam_pulang' => $d->jam_pulang ? substr($d->jam_pulang,0,5) : '-',
                'status' => strtolower($d->status),
                'keterangan' => $d->keterangan
            ];
        });
        return response()->json($data);
    }
}