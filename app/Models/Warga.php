<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Warga extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function antrian()
    {
        return $this->hasMany(Antrian::class);
    }

    public function permohonan()
    {
        return $this->hasMany(PermohonanSurat::class);
    }

    public static function getPossibleJenisKelamin()
    {
        $level = DB::select(DB::raw('SHOW COLUMNS FROM wargas WHERE Field = "jenis_kelamin"'))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $level, $matches);
        $values = [];
        foreach (explode(',', $matches[1]) as $value) {
            $values[] = trim($value, "'");
        }

        return $values;
    }

    public static function getAgama()
    {
        $agama = DB::select(DB::raw('SHOW COLUMNS FROM wargas WHERE Field = "agama"'))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $agama, $matches);
        $values = [];
        foreach (explode(',', $matches[1]) as $value) {
            $values[] = trim($value, "'");
        }

        return $values;
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function aduan()
    {
        return $this->hasMany(Aduan::class);
    }
}
