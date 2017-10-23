<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->increments('order_no');
            $table->integer('trans_type');
            $table->integer('debtor_no');
            $table->integer('branch_id');
            $table->integer('person_id');
            $table->tinyInteger('version');
            $table->string('reference', 100);
            $table->string('customer_ref', 20)->nullable()->default(NULL);
            $table->integer('order_reference_id');
            $table->string('order_reference', 200)->nullable()->default(NULL);
            $table->string('comments', 200)->nullable()->default(NULL);
            $table->date('ord_date');
            $table->integer('order_type');
            $table->string('delivery_address', 100)->nullable()->default(NULL);
            $table->string('contact_phone', 30)->nullable()->default(NULL);
            $table->string('contact_email', 100)->nullable()->default(NULL);
            $table->string('deliver_to', 100)->nullable()->default(NULL);
            $table->string('from_stk_loc', 20)->nullable()->default(NULL);
            $table->date('delivery_date')->nullable()->default(NULL);
            $table->tinyInteger('payment_id');
            $table->double('total')->default(0);
            $table->double('paid_amount')->default(0);
            $table->enum('choices', ['no', 'partial_created', 'full_created']);
            $table->tinyInteger('payment_term');
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
        Schema::drop('sales_orders');
    }
}
