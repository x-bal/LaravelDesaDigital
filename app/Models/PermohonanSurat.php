<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanSurat extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function jenis()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
