<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            
            $table->increments('supplier_id');
            $table->string('supp_name', 30);
            $table->string('email', 30);
            $table->text('address');
            $table->string('contact', 30);
            $table->string('city', 50);
            $table->string('state', 50);
            $table->string('zipcode', 20);
            $table->string('country', 50);
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
        Schema::drop('suppliers');
    }
}
