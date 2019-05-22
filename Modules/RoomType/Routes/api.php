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
 * POST     /roomtype
 * GET      /roomtype
 * GET      /roomtype/all
 * GET      /roomtype/{id}
 * PUT      /roomtype/{id}
 * DELETE   /roomtype/{id}
 */

RouteApi::version('v1', function() {
    RouteApi::group(['prefix' => 'roomtype', 'namespace' => 'Modules\RoomType\Http\Controllers'], function() {

        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            // resources
            RouteApi::post('/', 'RoomTypeController@store');
            RouteApi::get('/', 'RoomTypeController@index');
            RouteApi::get('/all', 'RoomTypeController@all');
            RouteApi::get('/{id}', 'RoomTypeController@show');
            RouteApi::put('/{id}', 'RoomTypeController@update');
            RouteApi::delete('/{id}', 'RoomTypeController@destroy');

        });

    });
 });