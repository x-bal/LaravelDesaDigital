<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function jenis()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function loket()
    {
        return $this->belongsTo(Loket::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
