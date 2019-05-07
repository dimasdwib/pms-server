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

RouteApi::version('v1', function() {
    RouteApi::group(['prefix' => 'housekeeping', 'namespace' => 'Modules\HouseKeeping\Http\Controllers'], function() {

        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            RouteApi::put('/clean/{id}', 'HouseKeepingController@cleanRoom');

            // resources
            RouteApi::get('/', 'HouseKeepingController@index');

        });

    });
 });