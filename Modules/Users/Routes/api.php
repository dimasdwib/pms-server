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
 * POST     /users
 * GET      /users
 * GET      /users/all
 * GET      /users/{id}
 * PUT      /users/{id}
 * DELETE   /users/{id}
 */

 RouteApi::version('v1', function() {
    RouteApi::group(['prefix' => 'users', 'namespace' => 'Modules\Users\Http\Controllers'], function() {

        // login stuff
        RouteApi::post('/login', 'AuthController@login');
        RouteApi::post('/logout', 'AuthController@logout');

        RouteApi::group(['middleware' => 'jwt.auth'], function() {
            
            // auth attribute
            RouteApi::get('/auth_attribute', 'AuthController@auth_attributes');

            // resources
            RouteApi::post('/', 'UsersController@store');
            RouteApi::get('/', 'UsersController@index');
            RouteApi::get('/all', 'UsersController@all');
            RouteApi::get('/{id}', 'UsersController@show');
            RouteApi::put('/{id}', 'UsersController@update');
            RouteApi::delete('/{id}', 'UsersController@destroy');

        });

    });
 });