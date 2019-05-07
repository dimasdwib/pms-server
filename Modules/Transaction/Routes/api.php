<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Resources
 * POST     /transaction
 * POST     /transaction/post_room_charge/{id_room_charge}
 */

RouteApi::version('v1', function() {
    RouteApi::group(['prefix' => 'transaction', 'namespace' => 'Modules\Transaction\Http\Controllers'], function() {

        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            // resources
            RouteApi::post('/', 'TransactionController@store');
            RouteApi::post('/close_reservation_bill/{id_bill}', 'TransactionController@close_reservation_bill');
            RouteApi::post('/post_room_charge/{id_room_charge}', 'TransactionController@post_room_charge');
            RouteApi::post('/payment/{id_bill}', 'TransactionController@add_payment');
            
        });

    });
 });