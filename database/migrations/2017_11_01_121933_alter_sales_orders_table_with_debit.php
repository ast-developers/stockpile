<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterSalesOrdersTableWithDebit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_orders', function(Blueprint $table){
            $table->double('debit_amount')->after('paid_amount')->default(0);
            $table->double('credit_amount')->after('debit_amount')->default(0);
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
            $table->dropColumn('debit_amount');
            $table->dropColumn('credit_amount');
        });
    }
}
