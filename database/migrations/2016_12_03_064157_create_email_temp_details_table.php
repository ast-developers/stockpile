<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTempDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_temp_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('temp_id');
            $table->string('subject');
            $table->text('body');
            $table->string('lang');
            $table->tinyInteger('lang_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_temp_details');
    }
}
