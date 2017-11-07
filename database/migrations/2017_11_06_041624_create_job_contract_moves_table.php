<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateJobContractMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_contract_moves', function (Blueprint $table) {
            $table->increments('trans_id');
            $table->string('stock_id', 20);
            $table->integer('contract_no');
            $table->smallInteger('trans_type')->default(0);
            $table->string('loc_code', 20);
            $table->date('tran_date');
            $table->integer('person_id')->nullable();
            $table->string('contract_reference', 30);
            $table->string('reference', 30);
            $table->integer('transaction_reference_id');
            $table->integer('transfer_id')->nullable();
            $table->string('note', 30);
            $table->double('qty')->default(0);
            $table->double('price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('job_contract_moves');
    }
}
