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
 * POST     /room
 * GET      /room
 * GET      /room/available
 * GET      /room/all
 * GET      /room/{id}
 * PUT      /room/{id}
 * DELETE   /room/{id}
 */

RouteApi::version('v1', function() {
    RouteApi::group(['prefix' => 'room', 'namespace' => 'Modules\Room\Http\Controllers'], function() {

        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            // resources
            RouteApi::post('/', 'RoomController@store');
            RouteApi::get('/', 'RoomController@index');
            RouteApi::get('/available', 'RoomController@available');
            RouteApi::get('/all', 'RoomController@all');
            RouteApi::get('/{id}', 'RoomController@show');
            RouteApi::put('/{id}', 'RoomController@update');
            RouteApi::delete('/{id}', 'RoomController@destroy');

        });

    });
 });