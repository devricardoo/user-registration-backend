<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profiles = [
            [
                'name' => 'ADM',
            ],
            [
                'name' => 'USER',
            ],
        ];

        Profile::insert($profiles);
    }
}