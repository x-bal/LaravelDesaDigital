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
            $kabupaten = \App\Models\Kabupaten::factory(10)->create([
                'provinsi_id' => $prov->id,
            ]);

            $kabupaten->each(function ($kab) {
                $kecamatan = Kecamatan::factory(10)->create([
                    'kabupaten_id' => $kab->id
                ]);

                $kecamatan->each(function ($kec) {
                    Desa::factory(10)->create([
                        'kecamatan_id' => $kec->id
                    ]);
                });
            });
        });
        \App\Models\Warga::factory(300)->create();
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(JenisSuratSeeder::class);
        $this->call(LoketAntrianWargaSeeder::class);
    }
}
