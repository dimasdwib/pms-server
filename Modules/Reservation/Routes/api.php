<?php

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
 * POST     /reservation
 * GET      /reservation
 * GET      /reservation/{id}
 * PUT      /reservation/{id}
 * DELETE   /reservation/{id}
 */

RouteApi::version('v1', function() {
    RouteApi::group(['prefix' => 'reservation', 'namespace' => 'Modules\Reservation\Http\Controllers'], function() {

        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            // resources
            RouteApi::post('/', 'ReservationController@store');
            RouteApi::get('/', 'ReservationController@index');
            RouteApi::get('/{id}', 'ReservationController@show');
            RouteApi::put('/{id}', 'ReservationController@update');
            RouteApi::delete('/{id}', 'ReservationController@destroy');

        });

    });
 });