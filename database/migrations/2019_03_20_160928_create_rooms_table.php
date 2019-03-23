<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('zones', function (Blueprint $table) {
            $table->increments('id_zone');
            $table->tinyInteger('order')->default(0);
            $table->string('name');
            $table->string('code');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('floors', function (Blueprint $table) {
            $table->increments('id_floor');
            $table->unsignedInteger('id_zone')->nullable();
            $table->tinyInteger('order')->default(0);
            $table->string('number');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('id_zone')->references('id_zone')->on('zones');
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id_room');
            $table->unsignedInteger('id_room_type');
            $table->unsignedInteger('id_bed');
            $table->unsignedInteger('id_floor')->nullable();
            $table->string('number');
            $table->string('phone')->nullable();
            $table->string('description')->nullable();
            $table->enum('dont_disturb', ['yes', 'no'])->default('no');
            $table->enum('hk_status', ['clean', 'dirty', 'out_of_order', 'inspect'])->default('clean');
            $table->enum('fo_status', ['vacant', 'occupied', 'house_use'])->default('vacant');
            $table->timestamps();

            $table->foreign('id_room_type')->references('id_room_type')->on('room_types');
            $table->foreign('id_bed')->references('id_bed')->on('beds');
            $table->foreign('id_floor')->references('id_floor')->on('floors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('floors');
        Schema::dropIfExists('zones');
    }
}
