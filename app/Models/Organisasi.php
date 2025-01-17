<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    protected $table = 'organisasi';

    protected $fillable = [
        'nama', 'deskripsi',
    ];

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id_organisasi', 'id');
    }
}
