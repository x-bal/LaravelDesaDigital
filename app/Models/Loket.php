<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loket extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function antrian()
    {
        return $this->hasMany(Antrian::class);
    }

    public function admin()
    {
        return $this->hasMany(User::class);
    }
}
