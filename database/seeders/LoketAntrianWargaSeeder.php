<?php

namespace Database\Seeders;

use App\Models\Antrian;
use App\Models\Loket;
use App\Models\PermohonanSurat;
use App\Models\Warga;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LoketAntrianWargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Loket::create([
            'desa_id' => 1,
            'nama' => 'a',
            'kuota' => 20
        ]);
        Antrian::create([
            'warga_id' => 1,
            'desa_id' => 1,
            'loket_id' => 1,
            'jenis_surat_id' => 1,
            'no_antrian' => 1,
            'tanggal_antri' => Carbon::now()->format('Y-m-d'),
            'status' => 1,
        ]);
        PermohonanSurat::create([
            'jenis_surat_id' => 5,
            'desa_id' => 1,
            'warga_id' => 1,
        ]);
    }
}
