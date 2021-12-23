<?php

namespace Database\Factories;

use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WargaFactory extends Factory
{
    protected $model = Warga::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'desa_id' => random_int(1, 3),
            'kecamatan_id' => random_int(1, 3),
            'kabupaten_id' => random_int(1, 3),
            'kk' => rand(),
            'nik' => rand(),
            'nama_warga' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-Laki', 'Perempuan']),
            'tempat_lahir' => $this->faker->address(),
            'tanggal_lahir' => $this->faker->date(),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Dll']),
            'pekerjaan' => $this->faker->jobTitle(),
            'pendidikan' => $this->faker->name(),
            'alamat' => $this->faker->address(),
            'warga_negara' => $this->faker->country(),
            'status_pernikahan' => $this->faker->randomElement(['Belum Menikah', 'Menikah', '-']),
            'golongan_darah' =>  $this->faker->randomElement(['A', 'B', 'O', 'AB', '-']),
        ];
    }
}
