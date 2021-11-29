<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'jenis_surat_id');
    }

    public function permohonan()
    {
        return $this->hasMany(PermohonanSurat::class, 'jenis_surat_id');
    }
}
