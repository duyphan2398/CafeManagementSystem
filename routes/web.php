<?php

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
    Route::get('users', 'ManageUsers\UserController@index');
    /*------------schedule------------*/
    Route::get('schedules', 'ManageUsers\ScheduleController@schedule');

    /*Warehouse*/
    /*----------material---------*/
    Route::get('materials','Warehouse\MaterialController@index');

    /*FoodsAndDrink*/
    /*----------product---------*/
    Route::get('products', 'FoodsAndDrinks\ProductController@index');

    /*ManageReceipts*/
    /*----------table---------*/
    Route::get('tables', 'ManageReceipts\TableController@index');
});


/* Route API for Web Call */
Route::group(['middleware' => 'checkloggedin', 'prefix' => 'axios'], function (){

    /*Manage Users*/
    /*----------users---------*/
    Route::get('users', 'ManageUsers\UserController@show');
    Route::delete('user/delete', 'ManageUsers\UserController@delete');
    Route::delete('user/forceDelete', 'ManageUsers\UserController@forceDelete');
    Route::get('user', 'ManageUsers\UserController@getUser');
    Route::patch('user/update','ManageUsers\UserController@update');
    Route::post('user/new','ManageUsers\UserController@create');
    Route::get('user/search', 'ManageUsers\UserController@search');
    /*----------schedule---------*/
    Route::post('schedule/new','ManageUsers\ScheduleController@createSchedule');
    Route::get('getAllUsersWithoutTrashed', 'ManageUsers\ScheduleController@getAllUsersWithoutTrashed');
    Route::get('getScheduleToday', 'ManageUsers\ScheduleController@getScheduleToday');
    Route::delete('schedule/delete','ManageUsers\ScheduleController@deleteSchedule');
    Route::delete('schedule','ManageUsers\ScheduleController@getSchedule');
    Route::post('getListScheduleFillter', 'ManageUsers\ScheduleController@getListScheduleFillter');
    Route::post('schedules/export', 'ManageUsers\ScheduleController@exportScheduleCsv');
    Route::post('schedules/checkin/{schedule}','ManageUsers\ScheduleController@checkin');
    Route::post('schedules/checkout/{schedule}','ManageUsers\ScheduleController@checkout');
    /*Warehouse*/
    /*----------material---------*/
    Route::get('materials','Warehouse\MaterialController@show');
    Route::post('material/new', 'Warehouse\MaterialController@create');
    Route::post('material', 'Warehouse\MaterialController@getMaterial');
    Route::patch('material/update', 'Warehouse\MaterialController@update');
    Route::delete('material/delete', 'Warehouse\MaterialController@delete');
    Route::get('material/search','Warehouse\MaterialController@search');

    /*FoodsAndDrink*/
    /*----------product---------*/
    Route::resource('products', FoodsAndDrinks\ProductController::class)->except(['index', 'update']);
    Route::post('products/{product}','FoodsAndDrinks\ProductController@update');
    Route::post('products/updateIngredient/{product}','FoodsAndDrinks\ProductController@updateIngredient');
    Route::delete('products/deleteIngredient/{product}/{material}', 'FoodsAndDrinks\ProductController@deleteIngredient');

    /*Manage Receipt*/
    /*----------table---------*/
    Route::resource('tables', ManageReceipts\TableController::class)->except(['index', 'update']);;
    Route::post('tables/{table}','ManageReceipts\TableController@update');


});

