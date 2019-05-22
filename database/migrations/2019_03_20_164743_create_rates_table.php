<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->increments('id_rate');
            $table->unsignedInteger('id_room_type')->nullable();
            $table->string('code');
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('amount_nett', 19, 4)->default(0);
            $table->timestamps();

            $table->foreign('id_room_type')->references('id_room_type')->on('room_types');
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
        Schema::dropIfExists('rates');
    }
}
