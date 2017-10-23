<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_master', function (Blueprint $table) {
            $table->string('stock_id', 30);
            $table->integer('category_id');
            $table->integer('tax_type_id');
            $table->string('description', 30);
            $table->text('long_description');
            $table->string('units', 30);
            $table->tinyInteger('inactive')->default(0);
            $table->tinyInteger('deleted_status')->default(0);
            $table->primary('stock_id');
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
        Schema::drop('stock_master');
    }
}
