<?php

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

Route::get('login', 'Admin\LoginController@redirectToProvider')->name('login');
Route::get('callback-auth', 'Admin\LoginController@handleProviderCallback');

Route::get('/', 'Face\HomeController@index');
Route::get('/coordinates', 'Face\HomeController@getCoordinates');
Route::get('/map', 'Face\HomeController@mapView');

Route::prefix('admin')->middleware('auth')->group(function() {
    Route::get('/', 'Admin\HomeController@index');

    Route::prefix('api')->group(function() {
        Route::apiResource('projects', 'Admin\ProjectController');
        Route::post('projects/{project}/activate', 'Admin\ProjectController@makeActive');
    });
});

Route::post('request-funding', 'Admin\ProjectController@store');
