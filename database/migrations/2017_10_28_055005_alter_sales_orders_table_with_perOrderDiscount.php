<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterSalesOrdersTableWithPerOrderDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_orders', function(Blueprint $table){
            $table->tinyInteger('discount_type')->after('delivery_price')->default(1)->comment('1=Per Item, 2=Per Order');
            $table->double('discount_percent')->after('discount_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_orders', function(Blueprint $table){
            $table->dropColumn('discount_type');
            $table->dropColumn('discount_percent');
        });
    }
}
