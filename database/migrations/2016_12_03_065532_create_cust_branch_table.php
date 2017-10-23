<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cust_branch', function (Blueprint $table) {
            $table->increments('branch_code');
            $table->integer('debtor_no');
            $table->string('br_name');
            $table->string('br_address');
            $table->string('br_contact');
            $table->string('billing_street');
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_zip_code');
            $table->string('billing_country_id');
            $table->string('shipping_street');
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_zip_code');
            $table->string('shipping_country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cust_branch');
    }
}
