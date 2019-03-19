<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->increments('id_guest');
            $table->string('name');
            $table->enum('title', ['mr', 'mrs', 'ms', 'miss'])->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('zipcode')->nullable();
            $table->unsignedInteger('id_country')->nullable();
            $table->unsignedInteger('id_state')->nullable();
            $table->unsignedInteger('id_city')->nullable();
            $table->string('phone')->nullable();
            $table->string('idcard')->nullable();
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
        Schema::dropIfExists('guests');
    }
}
