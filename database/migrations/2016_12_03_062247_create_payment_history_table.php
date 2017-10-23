<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('payment_type_id');
            $table->string('order_reference');
            $table->string('invoice_reference');
            $table->date('payment_date');
            $table->double('amount')->nullable()->default(0);
            $table->integer('person_id');
            $table->integer('customer_id');
            $table->string('reference');
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
        Schema::drop('payment_history');
    }
}
