<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PermohonanSuratKuasa extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function permohonan_surat()
    {
        return $this->belongsTo(PermohonanSurat::class);
    }
    public static function getJeniskelamin()
    {
        $level = DB::select(DB::raw('SHOW COLUMNS FROM permohonan_surat_kuasas WHERE Field = "jenis_kelamin_pem"'))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $level, $matches);
        $values = [];
        foreach (explode(',', $matches[1]) as $value) {
            $values[] = trim($value, "'");
        }

        return $values;
    }
}
