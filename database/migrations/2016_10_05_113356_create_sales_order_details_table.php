<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_no');
            $table->integer('trans_type');
            $table->string('stock_id', 20);
            $table->tinyInteger('tax_type_id');
            $table->text('description');
            $table->double('unit_price')->default(0);
            $table->double('qty_sent')->default(0);
            $table->double('quantity')->default(0);
            $table->double('shipment_qty')->default(0);
            $table->double('discount_percent')->default(0);
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
        Schema::drop('sales_order_details');
    }
}
