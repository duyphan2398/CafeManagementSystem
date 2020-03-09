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
Route::group(['middleware' => 'checklogin'], function (){
    Route::get('login', 'Auth\LoginController@index')->name('login');
    Route::post('login', 'Auth\LoginController@create');

});


Route::group(['middleware' => 'checkloggedin'], function (){
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('/', function () {
        return view('homepage');
    });
});
