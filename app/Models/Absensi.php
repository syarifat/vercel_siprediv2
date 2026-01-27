<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    // Sesuai SQL: absensi_siswa
    protected $table = 'absensi_siswa';

    protected $fillable = [
        'rombel_siswa_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'keterangan',
    ];

    public function rombel()
    {
        return $this->belongsTo(RombelSiswa::class, 'rombel_siswa_id');
    }
    
    // Akses cepat ke siswa via rombel
    public function getSiswaAttribute()
    {
        return $this->rombel ? $this->rombel->siswa : null;
    }
}