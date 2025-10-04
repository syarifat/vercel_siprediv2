<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $fillable = [
        'siswa_id',
        'tanggal',
        'jam',
        'jam_pulang',
        'status',
        'keterangan',
        'user_id',
    ];
    public function siswa()
    {
        return $this->belongsTo(\App\Models\Siswa::class, 'siswa_id');
    }
    public function rombel()
    {
        return $this->hasOne(\App\Models\RombelSiswa::class, 'siswa_id', 'siswa_id');
    }
}
