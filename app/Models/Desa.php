<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function admindesa()
    {
        return $this->belongsToMany(User::class);
    }

    public function permohonan()
    {
        return $this->hasMany(PermohonanSurat::class);
    }

    public function warga()
    {
        return $this->hasMany(Warga::class);
    }

    public function loket()
    {
        return $this->hasMany(Loket::class);
    }

    public function antrian()
    {
        return $this->hasMany(Antrian::class);
    }
}
