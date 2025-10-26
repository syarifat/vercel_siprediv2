<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'nama',
        'nip',
        'rfid',
        'no_hp',
        'status',
    ];

    public function absensi()
    {
        return $this->hasMany(AbsensiGuru::class, 'guru_id');
    }
}
