<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('name')->nullable();
            $table->uuid('price')->nullable();
            $table->string('transaction_type')->comment('w - wash, d - dry, p - products');

            $table->string('email');

            $table->timestamp('synched')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('email')->references('email')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rfid_transactions');
    }
}
