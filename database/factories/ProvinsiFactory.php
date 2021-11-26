<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinsiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_provinsi' => $this->faker->country()
        ];
    }
}
