<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasePriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_prices', function (Blueprint $table) {
            $table->increments('id');
           // $table->integer('supplier_id');
            $table->char('stock_id', 30);
            $table->double('price')->default(0);
            $table->char('suppliers_uom', 30);
            $table->double('conversion_factor')->nullable()->default(1);
            $table->char('supplier_description', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('purchase_prices');
    }
}
