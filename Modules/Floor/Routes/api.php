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
 * POST     /floor
 * GET      /floor
 * GET      /floor/all
 * GET      /floor/{id}
 * PUT      /floor/{id}
 * DELETE   /floor/{id}
 */

RouteApi::version('v1', function() {
    RouteApi::group(['prefix' => 'floor', 'namespace' => 'Modules\Floor\Http\Controllers'], function() {

        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            // resources
            RouteApi::post('/', 'FloorController@store');
            RouteApi::get('/', 'FloorController@index');
            RouteApi::get('/all', 'FloorController@all');
            RouteApi::get('/{id}', 'FloorController@show');
            RouteApi::put('/{id}', 'FloorController@update');
            RouteApi::delete('/{id}', 'FloorController@destroy');

        });

    });
 });