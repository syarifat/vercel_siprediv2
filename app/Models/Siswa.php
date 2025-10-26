<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nama',
        'jenis_kelamin',
        'no_hp_ortu',
        'rfid',
        'status',
    ];

    public function rombel()
    {
        return $this->hasMany(RombelSiswa::class, 'siswa_id');
    }
}
