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
    Route::get('/', 'HomeController@index');

    /*Manage Users*/
    /*--------------users----------------*/
    Route::get('users', 'ManageUsersController@index');
    /*------------schedule------------*/
    Route::get('schedule', 'ManageUsersController@schedule');
});


/* Route API for Web Call */
Route::group(['middleware' => 'checkloggedin', 'prefix' => 'axios'], function (){
    /*Manage Users*/
    /*----------users---------*/
    Route::get('getAllUsers', 'ManageUsersController@getAllUsers');
    Route::get('users', 'ManageUsersController@show');
    Route::delete('user/delete', 'ManageUsersController@delete');
    Route::delete('user/forceDelete', 'ManageUsersController@forceDelete');
    Route::get('user', 'ManageUsersController@getUser');
    Route::patch('user/update','ManageUsersController@update');
    Route::post('user/new','ManageUsersController@create');
    Route::get('user/search', 'ManageUsersController@search');
    /*----------schedule---------*/
    Route::post('schedule/new','ManageUsersController@createSchedule');
    Route::get('getAllUsersWithoutTrashed', 'ManageUsersController@getAllUsersWithoutTrashed');
    Route::get('getScheduleToday', 'ManageUsersController@getScheduleToday');

});
