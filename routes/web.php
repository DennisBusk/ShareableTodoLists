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
Route::group(['middleware' => ['web','auth']], function () {
  
  Route::get('/home', 'HomeController@index')->name('home');
  Route::group([ 'prefix' => '/users/{user}' ], function () {
    
    Route::post('/todolists', 'TodoListController@store');
    Route::delete('/todolists/{todolist}', 'TodoListController@delete');
    Route::get('/todolists/{todolist}/get_shared_with', 'TodoListController@getSharedWith');
    Route::post('/todolists/{todolist}/share', 'TodoListController@share');
    
    Route::group([ 'prefix' => '/todolists/{list_id}' ], function () {
      
      Route::post('/tasks', 'TaskController@store');
      Route::post('/tasks/{task_id}', 'TaskController@update');
      Route::delete('/tasks/{task_id}', 'TaskController@delete');
      
      
    });
  });
});
