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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/jela', 'DishController@index')->name('dish.index');
//Route::get('/jela/upit', 'DishController@show')->name('dish.show');

Route::get('/meals', 'DishController@index')->name('dish.index');