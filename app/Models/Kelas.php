<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama'];

    public function rombel()
    {
        return $this->hasMany(RombelSiswa::class, 'kelas_id');
    }
}