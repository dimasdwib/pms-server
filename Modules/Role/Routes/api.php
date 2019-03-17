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
    RouteApi::group(['prefix' => 'role', 'namespace' => 'Modules\Role\Http\Controllers'], function() {

        // resource
        RouteApi::group(['middleware' => 'jwt.auth'], function() {
            RouteApi::post('/', 'RoleController@store');
            RouteApi::get('/', 'RoleController@index');
            RouteApi::get('/all', 'RoleController@all');
            RouteApi::get('/{id}', 'RoleController@show');
            RouteApi::put('/{id}', 'RoleController@update');
            RouteApi::delete('/{id}', 'RoleController@destroy');
        });

    });
 });