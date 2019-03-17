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
    RouteApi::group(['prefix' => 'permission', 'namespace' => 'Modules\Permission\Http\Controllers'], function() {

        // resource
        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            // Group 
            RouteApi::get('/group', 'PermissionGroupController@index');            
            RouteApi::post('/group', 'PermissionGroupController@store');           
            RouteApi::get('/group/tree', 'PermissionGroupController@group_tree');           
            RouteApi::put('/group/{id}', 'PermissionGroupController@update');
            RouteApi::delete('/group/{id}', 'PermissionGroupController@destroy');

            RouteApi::post('/', 'PermissionController@store');
            RouteApi::get('/', 'PermissionController@index');
            RouteApi::get('/all', 'PermissionController@all');
            RouteApi::get('/{id}', 'PermissionController@show');
            RouteApi::put('/{id}', 'PermissionController@update');
            RouteApi::delete('/{id}', 'PermissionController@destroy');
        });

    });
 });