<?php

use App\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'id' => Str::uuid(),
                'name' => 'Regular wash',
                'price' => 60,
                'service_type' => 'rw',
            ],
            [
                'id' => Str::uuid(),
                'name' => '10 mins dry',
                'price' => 60,
                'service_type' => 'rd',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Add super wash',
                'price' => 15,
                'service_type' => 'rw',
            ],
        ];

        Service::insert($services);
    }
}
