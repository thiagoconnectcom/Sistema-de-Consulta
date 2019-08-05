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

Route::apiResource('/schedule', 'ScheduleController');
Route::get('/notification', 'NotificationController@index');
Route::get('/notification/{notification_id}', 'NotificationController@show');