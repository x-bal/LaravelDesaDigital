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
        Warga::create([
            'desa_id' => 1,
            'kecamatan_id' => 1,
            'kabupaten_id' => 1,
            'nik' => '11806634',
            'nama_warga' => 'Ichsan Arrizqi',
            'jenis_kelamin' => 'Laki-Laki',
            'tempat_lahir' => 'Bogor',
            'tanggal_lahir' => '1998-08-25',
            'agama' => 'islam',
            'pendidikan' => 'Sekolah Menengah Kejuruan',
            'pekerjaan' => 'programmer',
            'alamat' => 'kp.pos bojonggede',
            'warga_negara' => 'indonesia',
        ]);
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
