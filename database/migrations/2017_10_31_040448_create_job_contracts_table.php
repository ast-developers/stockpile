<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateJobContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_contracts', function (Blueprint $table) {
            $table->increments('job_contract_no');
            $table->integer('trans_type');
            $table->integer('debtor_no');
            $table->integer('branch_id');
            $table->integer('person_id');
            $table->tinyInteger('version');
            $table->string('reference', 100);
            $table->string('customer_ref', 20)->nullable()->default(NULL);
            $table->integer('contract_reference_id');
            $table->string('contract_reference', 200)->nullable()->default(NULL);
            $table->string('comments', 200)->nullable()->default(NULL);
            $table->date('contract_date');
            $table->integer('contract_type');
            $table->string('delivery_address', 100)->nullable()->default(NULL);
            $table->string('contact_phone', 30)->nullable()->default(NULL);
            $table->string('contact_email', 100)->nullable()->default(NULL);
            $table->string('deliver_to', 100)->nullable()->default(NULL);
            $table->string('from_stk_loc', 20)->nullable()->default(NULL);
            $table->date('delivery_date')->nullable()->default(NULL);
            $table->double('delivery_price')->default(0);
            $table->tinyInteger('discount_type')->default(1)->comment('1=Per Item, 2=Per Order');
            $table->double('discount_percent')->default(0);
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
        Schema::drop('job_contracts');
    }
}
