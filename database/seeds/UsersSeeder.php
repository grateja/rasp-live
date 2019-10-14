<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
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
                'id' => Str::uuid(),
                'name' => 'admin',
                'shopname' => 'Shop name',
                'email' => 'shopname@gmail.com',
                'password' => bcrypt('admin'),
            ],
        ];

        \App\User::insert($users);
    }
}
