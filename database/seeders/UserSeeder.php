<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'admin utama',
                'email' => 'utama@gmail.com',
                'password' => Hash::make('password'),
                'role' =>  'Utama',
            ],
            [
                'name' => 'admin kabupaten',
                'email' => 'kabupaten@gmail.com',
                'password' => Hash::make('password'),
                'role' =>  'Kabupaten',
            ],
            [
                'name' => 'admin desa',
                'email' => 'desa@gmail.com',
                'password' => Hash::make('password'),
                'role' =>  'Desa',
            ]
        ];

        foreach ($users as $key) {
            $user = User::create([
                'name' => $key['name'],
                'email' => $key['email'],
                'password' => $key['password'],
                'desa_id' => 1
            ]);
            $user->assignRole($key['role']);
        }
    }
}
