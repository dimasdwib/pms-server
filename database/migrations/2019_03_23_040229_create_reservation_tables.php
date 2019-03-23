<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // reservation
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id_reservation');
            $table->unsignedInteger('id_booker');
            $table->enum('status', ['tentative', 'definite', 'cancel']);
            $table->string('note')->nullable();
            $table->tinyInteger('adult')->nullable();
            $table->tinyInteger('child')->nullable();
            $table->timestamps();
        });

        // reservation room
        Schema::create('reservation_rooms', function (Blueprint $table) {
            $table->increments('id_reservation_room');
            $table->unsignedInteger('id_reservation');
            $table->unsignedInteger('id_room');
            $table->timestamps();

            $table->foreign('id_reservation')->references('id_reservation')->on('reservations')->onDelete('cascade');
        });

        // reservation room guest
        Schema::create('reservation_room_guests', function (Blueprint $table) {
            $table->increments('id_reservation_room_guest');
            $table->unsignedInteger('id_reservation_room');
            $table->unsignedInteger('id_guest');
            $table->date('date_arrival');
            $table->date('date_departure');
            $table->dateTime('date_checkin');
            $table->dateTime('date_checkout');
            $table->timestamps();
            
            $table->foreign('id_reservation_room')->references('id_reservation_room')->on('reservation_rooms')->onDelete('cascade');
        });

        // ------------------------------------------------------------------------------------------------------------------------
        // master transactions for reservation
        //
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id_bill');
            $table->unsignedInteger('id_guest');
            $table->timestamps();
            
            $table->foreign('id_guest')->references('id_guest')->on('guests');
        });

        Schema::create('reservation_bills', function (Blueprint $table) {
            $table->increments('id_reservation_bill');
            $table->unsignedInteger('id_reservation');
            $table->unsignedInteger('id_bill');
            $table->timestamps();
            
            $table->foreign('id_bill')->references('id_bill')->on('bills')->onDelete('cascade');
            $table->foreign('id_reservation')->references('id_reservation')->on('reservations')->onDelete('cascade');;
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id_transaction');
            $table->unsignedInteger('id_bill');
            $table->dateTime('date');
            $table->decimal('amount_nett', 19, 4)->default(0);
            $table->string('description');
            $table->timestamps();
            
            $table->foreign('id_bill')->references('id_bill')->on('bills')->onDelete('cascade');
        });
        //--------------------------------------------------------------------------------------------------------------------------

        // reservation room charges
        Schema::create('room_charges', function (Blueprint $table) {
            $table->increments('id_room_charges');
            $table->unsignedInteger('id_reservation_room_guest');
            $table->unsignedInteger('id_rate');
            $table->unsignedInteger('id_transaction')->nullable();
            $table->decimal('amount_nett', 19, 4);
            $table->enum('status', ['pending', 'charged', 'cancel'])->default('pending');
            $table->date('date');
            $table->timestamps();
            
            $table->foreign('id_reservation_room_guest')->references('id_reservation_room_guest')->on('reservation_room_guests')->onDelete('cascade');
            $table->foreign('id_rate')->references('id_rate')->on('rates');
            $table->foreign('id_transaction')->references('id_transaction')->on('transactions');
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
        Schema::dropIfExists('room_charges');
        Schema::dropIfExists('reservation_room_guests');
        Schema::dropIfExists('reservation_rooms');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('reservation_bills');
    }
}
