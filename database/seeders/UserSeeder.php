<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'VT',
            'email' => 'test@gmail.com',
            'password' => bcrypt('12345'),
            'group_id' => 1,
            'status' => 1
        ]);
    }
}
