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
            $table->unsignedInteger('id_room')->nullable();
            $table->timestamps();

            $table->foreign('id_reservation')->references('id_reservation')->on('reservations')->onDelete('cascade');
            $table->foreign('id_room')->references('id_room')->on('rooms');
        });

        // reservation room guest
        Schema::create('reservation_room_guests', function (Blueprint $table) {
            $table->increments('id_reservation_room_guest');
            $table->unsignedInteger('id_reservation_room');
            $table->unsignedInteger('id_guest');
            $table->date('date_arrival');
            $table->date('date_departure');
            $table->dateTime('date_checkin')->nullable();
            $table->dateTime('date_checkout')->nullable();
            $table->timestamps();
            
            $table->foreign('id_reservation_room')->references('id_reservation_room')->on('reservation_rooms')->onDelete('cascade');
            $table->foreign('id_guest')->references('id_guest')->on('guests');
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
            $table->enum('status', ['closed', 'open'])->default('closed');
            $table->timestamps();
            
            $table->foreign('id_bill')->references('id_bill')->on('bills')->onDelete('cascade');
            $table->foreign('id_reservation')->references('id_reservation')->on('reservations')->onDelete('cascade');;
        });

        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->increments('id_transaction_category');
            $table->string('code', 20);
            $table->string('name');
            $table->enum('pos', ['dr', 'cr']);
            $table->enum('is_default', [0, 1])->default(0);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id_transaction');
            $table->unsignedInteger('id_bill');
            $table->unsignedInteger('id_transaction_category');
            $table->dateTime('date');
            $table->decimal('amount_nett', 19, 4)->default(0);
            $table->string('description');
            $table->timestamps();
            
            $table->foreign('id_bill')->references('id_bill')->on('bills')->onDelete('cascade');
            $table->foreign('id_transaction_category')->references('id_transaction_category')->on('transaction_categories')->onDelete('restrict');
        });

        //--------------------------------------------------------------------------------------------------------------------------

        // reservation room charges
        Schema::create('room_charges', function (Blueprint $table) {
            $table->increments('id_room_charge');
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
