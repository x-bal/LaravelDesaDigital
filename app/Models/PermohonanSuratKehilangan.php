<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanSuratKehilangan extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function permohonan_surat()
    {
        return $this->belongsTo(PermohonanSurat::class);
    }
}
