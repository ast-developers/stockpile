<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 60);
            $table->string('password', 100);
            $table->string('real_name', 100);
            $table->integer('role_id')->default(1);
            $table->string('phone', 30);
            $table->string('email', 100)->nullable()->default(NULL);
            $table->string('picture', 100);
            $table->tinyInteger('inactive')->default(0);
            $table->string('remember_token', 100);
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
        Schema::drop('users');
    }
}
