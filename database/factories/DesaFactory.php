<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_desa' => $this->faker->word(),
            'alamat' => $this->faker->word()
        ];
    }
}
