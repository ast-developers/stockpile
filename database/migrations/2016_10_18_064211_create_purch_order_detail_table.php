<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purch_order_details', function (Blueprint $table) {
            $table->increments('po_detail_item');
            $table->integer('order_no');
            $table->string('item_code', 20);
            $table->text('description');
            $table->double('qty_invoiced')->default(0);
            $table->double('unit_price')->default(0);
            $table->double('act_price')->default(0);
            $table->integer('tax_type_id');
            $table->double('std_cost_unit')->default(0);
            $table->double('quantity_ordered')->default(0);
            $table->double('quantity_received')->default(0);
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
        Schema::drop('purch_order_details');
    }
}
