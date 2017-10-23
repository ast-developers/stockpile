<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebtorMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debtors_master', function (Blueprint $table) {
            $table->increments('debtor_no');
            $table->string('name', 30);
            $table->string('email', 30);
            $table->string('password', 100);
            $table->text('address');
            $table->string('phone', 30);
            $table->integer('sales_type');
            $table->string('remember_token');
            $table->tinyInteger('inactive')->default(0);
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
        Schema::drop('debtors_master');
    }
}
