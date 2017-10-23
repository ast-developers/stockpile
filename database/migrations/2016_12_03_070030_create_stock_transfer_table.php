<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id');
            $table->string('source',55);
            $table->string('destination',55);
            $table->text('note');
            $table->integer('qty');
            $table->date('transfer_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stock_transfer');
    }
}
