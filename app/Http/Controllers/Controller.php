<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Return selected tahun_ajaran id with fallback to session or active year.
     * Usage: $this->tahunAjaranId($request)
     */
    protected function tahunAjaranId(Request $request = null)
    {
        $req = $request ?: request();
        $id = $req->input('tahun_ajaran_id');
        if ($id) return $id;
        if (session()->has('tahun_ajaran_id')) return session('tahun_ajaran_id');
        // fallback to active
        $ta = \App\Models\TahunAjaran::where('aktif', true)->first();
        return $ta?->id ?? null;
    }
}
