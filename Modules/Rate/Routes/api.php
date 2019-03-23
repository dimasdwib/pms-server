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
 * POST     /rate
 * GET      /rate
 * GET      /rate/all
 * GET      /rate/{id}
 * PUT      /rate/{id}
 * DELETE   /rate/{id}
 */

RouteApi::version('v1', function() {
    RouteApi::group(['prefix' => 'rate', 'namespace' => 'Modules\Rate\Http\Controllers'], function() {

        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            // resources
            RouteApi::post('/', 'RateController@store');
            RouteApi::get('/', 'RateController@index');
            RouteApi::get('/all', 'RateController@all');
            RouteApi::get('/{id}', 'RateController@show');
            RouteApi::put('/{id}', 'RateController@update');
            RouteApi::delete('/{id}', 'RateController@destroy');

        });

    });
 });