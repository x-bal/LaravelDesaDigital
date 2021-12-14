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

    public function PermohonanSuratDomisiliUsaha()
    {
        return $this->hasMany(PermohonanSuratDomisiliUsaha::class);
    }
    public function PermohonanSuratIzinKeramaian()
    {
        return $this->hasMany(PermohonanSuratIzinKeramaian::class);
    }
    public function PermohonanSuratJaminanKesehatan()
    {
        return $this->hasMany(PermohonanSuratJaminanKesehatan::class);
    }
    public function PermohonanSuratKehilangan()
    {
        return $this->hasMany(PermohonanSuratKehilangan::class);
    }
    public function PermohonanSuratKuasa()
    {
        return $this->hasMany(PermohonanSuratKuasa::class);
    }
    public function PermohonanSuratKurangMampu()
    {
        return $this->hasMany(PermohonanSuratKurangMampu::class);
    }
    public function PermohonanSuratPengantar()
    {
        return $this->hasMany(PermohonanSuratPengantar::class);
    }
    public function PermohonanSuratPergiKawin()
    {
        return $this->hasMany(PermohonanSuratPergiKawin::class);
    }
    public function PermohonanSuratSkck()
    {
        return $this->hasMany(PermohonanSuratSkck::class);
    }
    public function PermohonanSuratUsaha()
    {
        return $this->hasMany(PermohonanSuratUsaha::class);
    }
}
