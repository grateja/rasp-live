<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\RolesSeeder::class);
        $this->call(\UsersSeeder::class);
        $this->call(\RfidCardsSeeder::class);
        $this->call(\MachinesSeeder::class);
        $this->call(\ServicesSeeder::class);
        DB::table('role_users')->insert([
            [
                'user_id' => User::first()->id,
                'role_id' => 1
            ],
        ]);
    }
}
