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
 * POST     /guest
 * GET      /guest
 * GET      /guest/all
 * GET      /guest/{id}
 * PUT      /guest/{id}
 * DELETE   /guest/{id}
 */

 RouteApi::version('v1', function() {
    RouteApi::group(['prefix' => 'guest', 'namespace' => 'Modules\Guest\Http\Controllers'], function() {

        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            // resources
            RouteApi::post('/', 'GuestController@store');
            RouteApi::get('/', 'GuestController@index');
            RouteApi::get('/all', 'GuestController@all');
            RouteApi::get('/{id}', 'GuestController@show');
            RouteApi::put('/{id}', 'GuestController@update');
            RouteApi::delete('/{id}', 'GuestController@destroy');

        });

    });
 });