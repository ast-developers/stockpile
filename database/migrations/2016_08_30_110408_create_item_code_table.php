<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stock_id');
            $table->string('description', 30);
            $table->smallInteger('category_id');
            $table->string('item_image', 100);
            $table->tinyInteger('inactive')->default(0);
            $table->tinyInteger('deleted_status')->default(0);
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
        Schema::drop('item_code');
    }
}
