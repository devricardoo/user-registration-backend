<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
                'name' => 'Admin user',
                'email' => 'adminuser@gmail.com',
                'password' => bcrypt('Ab123456@#'),
                'cpf' => '12345678910',
                'profile_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'User',
                'email' => 'userprofile@gmail.com',
                'password' => bcrypt('Ab123456@#'),
                'cpf' => '12345678911',
                'profile_id' => 2,
                'created_at' => now(),
            ]

        ];

        User::insert($users);
    }
}
