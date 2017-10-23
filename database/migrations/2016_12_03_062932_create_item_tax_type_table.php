<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTaxTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_tax_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->float('tax_rate');
            $table->tinyInteger('exempt');
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
        Schema::drop('item_tax_types');
    }
}
