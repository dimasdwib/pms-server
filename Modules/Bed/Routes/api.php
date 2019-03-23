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
 * POST     /bed
 * GET      /bed
 * GET      /bed/all
 * GET      /bed/{id}
 * PUT      /bed/{id}
 * DELETE   /bed/{id}
 */

RouteApi::version('v1', function() {
    RouteApi::group(['prefix' => 'bed', 'namespace' => 'Modules\Bed\Http\Controllers'], function() {

        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            // resources
            RouteApi::post('/', 'BedController@store');
            RouteApi::get('/', 'BedController@index');
            RouteApi::get('/all', 'BedController@all');
            RouteApi::get('/{id}', 'BedController@show');
            RouteApi::put('/{id}', 'BedController@update');
            RouteApi::delete('/{id}', 'BedController@destroy');

        });

    });
 });