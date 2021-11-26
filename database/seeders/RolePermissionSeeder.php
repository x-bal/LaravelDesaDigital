<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' =>  'Utama',
                'guard_name' =>  'web'
            ],
            [
                'name' =>  'Kabupaten',
                'guard_name' =>  'web'
            ],
            [
                'name' =>  'Desa',
                'guard_name' =>  'web'
            ],
            [
                'name' => 'Warga',
                'guard_name' => 'web'
            ]
        ];
        foreach ($roles as $key) {
            Role::create([
                'name' => $key['name'],
                'guard_name' =>  $key['guard_name']
            ]);
        }
    }
}
