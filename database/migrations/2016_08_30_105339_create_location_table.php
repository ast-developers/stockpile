<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('location');
        
        Schema::create('location', function (Blueprint $table) {
            $table->increments('id');
            $table->string('loc_code', 10);
            $table->string('location_name', 60);
            $table->string('delivery_address', 60);
            $table->string('email', 60);
            $table->string('phone', 60);
            $table->string('fax', 60);
            $table->string('contact', 60);
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
        Schema::drop('location');
    }
}
