<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Routes Auth  (  /api/*  )*/


Route::post('login', 'Auth\LoginController@login');
/*Route::post('refresh', 'Auth\LoginController@refresh');*/
Route::group(['middleware' => 'auth:api'], function (){
    Route::get('users', 'Auth\LoginController@user');
    Route::post('logout', 'Auth\LoginController@logout');
    /*Schedules*/
    Route::get('schedules/{user}', 'ScheduleController@getSchdulesByUserId');
    /*Product*/
    Route::apiResource('products', ProductController::class)->only('index');
    /*Table*/
    Route::apiResource('tables', TableController::class)->only('index');
    /*Receipt*/
    Route::apiResource('receipts', ReceiptController::class)->only('index');
    Route::get('receipts/{receipt}', 'ReceiptController@show');
    Route::delete('receipts/{receipt}', 'ReceiptController@destroy');
    Route::delete('receipts/{receipt}/{product}', 'ReceiptController@destroyProductReceipt');
    Route::post('receipts','ReceiptController@create');
    Route::post('receipts/{receipt}/{product}','ReceiptController@createProductReceipt');
    Route::get('receipts/bill/{receipt}','ReceiptController@billReceipt');
    Route::get('receipts/paid/{receipt}','ReceiptController@paidReceipt');
});


