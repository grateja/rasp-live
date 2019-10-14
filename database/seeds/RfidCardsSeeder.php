<?php

use App\RfidCard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RfidCardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rfidCards = [
            [
                'id' => Str::uuid(),
                'rfid' => '0001',
                'name' => 'Machine',
                'card_type' => 'm',
                'price' => null,
            ],
            [
                'id' => Str::uuid(),
                'rfid' => '0002',
                'name' => 'Ariel',
                'card_type' => 'p',
                'price' => 15,
            ],
            [
                'id' => Str::uuid(),
                'rfid' => '0003',
                'name' => 'Breeze',
                'card_type' => 'p',
                'price' => 12,
            ],
        ];

        RfidCard::insert($rfidCards);
    }
}
