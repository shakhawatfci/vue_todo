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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('todo','TodoListController@index');
Route::get('todo/edit/{todoList}','TodoListController@edit');
Route::delete('todo/delete/{id}','TodoListController@destroy');
Route::put('todo/update/{id}','TodoListController@update');
Route::get('todoList','TodoListController@TodoList');

Route::post('todo','TodoListController@store');

Route::post('/users', 'UserController@store');
Route::get('/users', 'UserController@index');
Route::get('/users/{id}', 'UserController@edit');
Route::put('/users/{id}', 'UserController@update');
Route::delete('/users/{id}', 'UserController@destroy');
