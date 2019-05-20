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
    RouteApi::group(['prefix' => 'report', 'namespace' => 'Modules\Report\Http\Controllers'], function() {

        RouteApi::group(['middleware' => 'jwt.auth'], function() {

            RouteApi::get('/reservation_list', 'ReportController@reservation_list');
            RouteApi::get('/arrival_list', 'ReportController@arrival_list');
            RouteApi::get('/departure_list', 'ReportController@departure_list');
            RouteApi::get('/guest_in_house', 'ReportController@guest_in_house');
            RouteApi::get('/room_status', 'ReportController@room_status');
            RouteApi::get('/folio_history', 'ReportController@folio_history');

        });
    });
});