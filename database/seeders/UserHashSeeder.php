<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserHashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Ricardo',
            'email' => 'ricardo@gmail.com',
            'password' => bcrypt('12345678'),
            'cpf' => '12345678901',
        ]);
    }
}
