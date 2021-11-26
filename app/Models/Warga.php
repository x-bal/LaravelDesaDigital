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
}
