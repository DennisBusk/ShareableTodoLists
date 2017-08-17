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

Route::auth();

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/users/{user_id}/todolist/{list_id}/task','TaskController@create')->middleware(['web','auth']);
Route::post('/users/{user_id}/todolist/{list_id}/task/{task_id}','TaskController@update')->middleware(['web','auth']);
