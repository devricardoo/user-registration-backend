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
                'name' => 'Adm user',
                'email' => 'admuser@gmail.com',
                'password' => bcrypt('Ausr123456@#'),
                'cpf' => '12345678910',
                'profile_id' => 1,
            ],
            [
                'name' => 'User',
                'email' => 'userprofile@gmail.com',
                'password' => bcrypt('Ausr123456@#'),
                'cpf' => '12345678911',
                'profile_id' => 2,
            ]

        ];

        User::insert($users);
    }
}