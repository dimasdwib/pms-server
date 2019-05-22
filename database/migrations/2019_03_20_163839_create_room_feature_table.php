<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomFeatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('room_features', function (Blueprint $table) {
            $table->increments('id_room_feature');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });


        // create many to many relation
        Schema::create('room_has_features', function (Blueprint $table) {
            $table->increments('id_room_has_feature');
            $table->unsignedInteger('id_room');
            $table->unsignedInteger('id_room_feature');
            $table->timestamps();

            $table->foreign('id_room')->references('id_room')->on('rooms')->onDelete('cascade');
            $table->foreign('id_room_feature')->references('id_room_feature')->on('room_features')->onDelete('cascade');
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
        Schema::dropIfExists('room_features');
        Schema::dropIfExists('room_has_features');
    }
}
