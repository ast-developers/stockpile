<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email_protocol');
            $table->string('email_encryption');
            $table->string('smtp_host');
            $table->string('smtp_port');
            $table->string('smtp_email');
            $table->string('smtp_username');
            $table->string('smtp_password');
            $table->string('from_address');
            $table->string('from_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_config');
    }
}
