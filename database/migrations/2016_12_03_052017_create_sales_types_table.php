<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sales_type');
            $table->integer('tax_included');
            $table->double('factor');
            $table->tinyInteger('defaults');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sales_types');
    }
}
