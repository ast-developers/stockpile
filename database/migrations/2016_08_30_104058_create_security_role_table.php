<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('security_role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role', 30);
            $table->string('description', 50)->nullable()->default(NULL);
            $table->text('sections')->nullable()->default(NULL);
            $table->text('areas')->nullable()->default(NULL);
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
        Schema::drop('security_role');
    }
}
