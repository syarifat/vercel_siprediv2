<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = ['nama', 'semester', 'aktif'];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function rombel()
    {
        return $this->hasMany(RombelSiswa::class, 'tahun_ajaran_id');
    }
}