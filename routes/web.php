<?php

use Barryvdh\DomPDF\Facade as PDF2;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*Authen Routes*/

/**/
Route::group(['middleware' => 'checklogin'], function (){
    Route::get('login', 'Auth\LoginController@index')->name('login');
    Route::post('login', 'Auth\LoginController@create');

});

/*Routes Dashboard*/
Route::group(['middleware' => 'checkloggedin'], function (){
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('', 'HomeController@index')->name('dashboard');

    /*Manage Users*/
    /*--------------users----------------*/
    Route::get('users', 'ManageUsers\UserController@index')->middleware('can:viewAny, App\Models\User');
    /*------------schedule------------*/
    Route::get('schedules', 'ManageUsers\ScheduleController@schedule')->name('schedules')->middleware('can:schedule, App\Models\Schedule');

    /*Warehouse*/
    /*----------material---------*/
    Route::get('materials','Warehouse\MaterialController@index');
    Route::get('statistics', 'Warehouse\StatisticController@index');
    /*FoodsAndDrink*/
    /*----------product---------*/
    Route::get('products', 'FoodsAndDrinks\ProductController@index')->middleware('can:index, App\Models\Product');

    /*ManageReceipts*/
    /*----------table---------*/
    Route::get('tables', 'ManageReceipts\TableController@index');
    /*----------receipt---------*/
    Route::get('receipts', 'ManageReceipts\ReceiptController@index')->name('receipts');
    /*----------promotion---------*/
    Route::get('promotions', 'ManageReceipts\PromotionController@index');
});


/* Route API for Web Call */
Route::group(['middleware' => 'checkloggedin', 'prefix' => 'axios'], function (){

    /*Manage Users*/
    /*----------users---------*/
    Route::get('users', 'ManageUsers\UserController@show')->middleware('can:view, App\Models\User');
    Route::delete('user/delete', 'ManageUsers\UserController@destroy')->middleware('can:delete, App\Models\User');
    Route::delete('user/forceDelete', 'ManageUsers\UserController@forceDelete')->middleware('can:forceDelete, App\Models\User');
    Route::get('user', 'ManageUsers\UserController@getUser')->middleware('can:getUser, App\Models\User');
    Route::patch('user/update','ManageUsers\UserController@edit')->middleware('can:update, App\Models\User');
    Route::post('user/new','ManageUsers\UserController@create')->middleware('can:create, App\Models\User');
    Route::get('user/search', 'ManageUsers\UserController@search')->middleware('can:search, App\Models\User');
    /*----------schedule---------*/
    Route::post('schedule/new','ManageUsers\ScheduleController@createSchedule')->middleware('can:createSchedule, App\Models\Schedule');
    Route::get('getAllUsersWithoutTrashed', 'ManageUsers\ScheduleController@getAllUsersWithoutTrashed')->middleware('can:getAllUsersWithoutTrashed, App\Models\Schedule');
    Route::get('getScheduleToday', 'ManageUsers\ScheduleController@getScheduleToday')->middleware('can:getScheduleToday, App\Models\Schedule');
    Route::delete('schedule/delete','ManageUsers\ScheduleController@deleteSchedule')->middleware('can:deleteSchedule, App\Models\Schedule');
    Route::delete('schedule','ManageUsers\ScheduleController@getSchedule')->middleware('can:getSchedule, App\Models\Schedule');
    Route::post('getListScheduleFillter', 'ManageUsers\ScheduleController@getListScheduleFillter')->middleware('can:getListScheduleFillter, App\Models\Schedule');
    Route::post('schedules/export', 'ManageUsers\ScheduleController@exportScheduleCsv')->middleware('can:exportScheduleCsv, App\Models\Schedule');
    Route::post('schedules/checkin/{schedule}','ManageUsers\ScheduleController@checkin')->middleware('can:checkin, App\Models\Schedule');
    Route::post('schedules/checkout/{schedule}','ManageUsers\ScheduleController@checkout')->middleware('can:checkout, App\Models\Schedule');

    /*Warehouse*/
    /*----------material---------*/
    Route::get('materials','Warehouse\MaterialController@show');
    Route::post('material/new', 'Warehouse\MaterialController@create');
    Route::post('material', 'Warehouse\MaterialController@getMaterial');
    Route::patch('material/update', 'Warehouse\MaterialController@update');
    Route::delete('material/delete', 'Warehouse\MaterialController@delete');
    Route::get('material/search','Warehouse\MaterialController@search');
    Route::get('material/export', 'Warehouse\MaterialController@exportCsv');
    /*----------statistic---------*/
    Route::get('statistics/diagram1','Warehouse\StatisticController@dataDiagram1');
    Route::get('statistics/diagram2','Warehouse\StatisticController@dataDiagram2');
    Route::get('statistics/diagram3','Warehouse\StatisticController@dataDiagram3');
    /*FoodsAndDrink*/
    /*----------product---------*/
    Route::resource('products', FoodsAndDrinks\ProductController::class)->except(['index', 'update']);
    Route::post('products/{product}','FoodsAndDrinks\ProductController@update');
    Route::post('products/updateIngredient/{product}','FoodsAndDrinks\ProductController@updateIngredient');
    Route::delete('products/deleteIngredient/{product}/{material}', 'FoodsAndDrinks\ProductController@deleteIngredient');

    /*Manage Receipt*/
    /*----------table---------*/
    Route::resource('tables', ManageReceipts\TableController::class)->except(['index', 'update']);
    Route::post('tables/{table}','ManageReceipts\TableController@update');
    Route::get('tables/changeUserUsing/{table}', 'ManageReceipts\TableController@changeUserUsing');
    Route::get('tables/changeStatus/{table}', 'ManageReceipts\TableController@changeStatus');
    /*----------receipt---------*/
    Route::resource('receipts', ManageReceipts\ReceiptController::class)->except(['index', 'update']);
    Route::post('getListReceiptFillter', 'ManageReceipts\ReceiptController@getListReceiptFillter');
    Route::post('receipts/export', 'ManageReceipts\ReceiptController@exportReceiptCsv');
    Route::get('receipts/billing/{receipt}', 'ManageReceipts\ReceiptController@billing');
    Route::get('receipts/receipt/{receipt}', 'ManageReceipts\ReceiptController@receipt');
    Route::post('receipts/updateProductInReceipt/{receipt}', 'ManageReceipts\ReceiptController@updateProductInReceipt');  //author
    /*----------promotion---------*/
    Route::delete('promotions/{promotion}', 'ManageReceipts\PromotionController@destroy');
    Route::post('promotions', 'ManageReceipts\PromotionController@create');
    Route::get('promotions/{promotion}', 'ManageReceipts\PromotionController@show');
    Route::post('promotions/{promotion}', 'ManageReceipts\PromotionController@update');
    Route::get('promotions/showProducts/{promotion}', 'ManageReceipts\PromotionController@showProducts');//author
    Route::post('promotions/updateProducts/{promotion}', 'ManageReceipts\PromotionController@updateProducts');//author
});
