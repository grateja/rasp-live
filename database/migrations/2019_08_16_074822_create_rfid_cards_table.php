<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRfidCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfid_cards', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name')->nullable()->comment('machine [, or products]');
            $table->string('rfid')->nullable();
            $table->string('card_type')->comment('w - washers only, d - dry only, m - any machines, p - products');
            $table->double('price')->nullable()->comment('only for products');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rfid_cards');
    }
}
