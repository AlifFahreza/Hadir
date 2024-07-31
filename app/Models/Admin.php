<?php

namespace App\Models; // Tambahkan namespace yang sesuai


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $fillable = [
        'name', 'email', 'password', 'institusi', 'departemen', 'address', 'phone', 'more','password','foto'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
