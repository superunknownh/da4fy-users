<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => env('ROOT_NAME'),
            'email' => env('ROOT_EMAIL'),
            'password' => Hash::make(env('ROOT_PASSWORD')),
            'is_super_user' => true
        ]);
    }
}
