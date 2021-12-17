<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $provinsi = \App\Models\Provinsi::factory(5)->create();

        $provinsi->each(function ($prov) {
            $kabupaten = \App\Models\Kabupaten::factory(3)->create([
                'provinsi_id' => $prov->id,
            ]);

            $kabupaten->each(function ($kab) {
                $kecamatan = Kecamatan::factory(3)->create([
                    'kabupaten_id' => $kab->id
                ]);

                $kecamatan->each(function ($kec) {
                    Desa::factory(3)->create([
                        'kecamatan_id' => $kec->id
                    ]);
                });
            });
        });
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(JenisSuratSeeder::class);
        $this->call(LoketAntrianWargaSeeder::class);
    }
}
