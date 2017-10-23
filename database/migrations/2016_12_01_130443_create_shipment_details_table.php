<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipment_id');
            $table->integer('order_no');
            $table->string('stock_id');
            $table->tinyInteger('tax_type_id');
            $table->double('unit_price');
            $table->double('quantity');
            $table->double('discount_percent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shipment_details');
    }
}
