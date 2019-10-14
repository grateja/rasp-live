<?php

use App\Machine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MachinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $machines = [
            [
                'id' => Str::uuid(),
                'ip_address' => 'localhost',
                'machine_type' => 'rw',
                'machine_name' => 'Washer 1',
                'initial_time' => 39,
                'additional_time' => 12,
                'initial_price' => 60,
                'additional_price' => 15,
            ],
            [
                'id' => Str::uuid(),
                'ip_address' => 'localhost',
                'machine_type' => 'rd',
                'machine_name' => 'Dryer 1',
                'initial_time' => 10,
                'additional_time' => 10,
                'initial_price' => 15,
                'additional_price' => 15,
            ],
        ];
        Machine::insert($machines);
    }
}
