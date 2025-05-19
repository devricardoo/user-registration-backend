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

Route::get('/404', 'App\Http\Controllers\AuthController@unauthorized');

Route::post('/auth/login', 'App\Http\Controllers\AuthController@login');
Route::post('/auth/logout', 'App\Http\Controllers\AuthController@logout');
Route::post('/auth/refresh', 'App\Http\Controllers\AuthController@refresh');
Route::get('/me', 'App\Http\Controllers\AuthController@me');
Route::post('/user', 'App\Http\Controllers\AuthController@create');

Route::get('/user', 'App\Http\Controllers\UserController@index');
Route::get('/user/{id}', 'App\Http\Controllers\UserController@show');
Route::put('/user/{id}', 'App\Http\Controllers\UserController@update');
Route::patch('/user/{id}', 'App\Http\Controllers\UserController@update');
Route::delete('/user/{id}', 'App\Http\Controllers\UserController@delete');

Route::post('/profile', 'App\Http\Controllers\UserController@createprofile');
Route::post('/profile/{id}', 'App\Http\Controllers\ProfileController@update');
Route::delete('/profile/{id}', 'App\Http\Controllers\ProfileController@delete');

Route::get('/address', 'App\Http\Controllers\AddressController@index');
Route::get('/address/{id}', 'App\Http\Controllers\AddressController@show');
Route::post('/address', 'App\Http\Controllers\AddressController@store');
//Route::put('/address/{id}', 'App\Http\Controllers\AddressController@update'); optional
//Route::patch('/address/{id}', 'App\Http\Controllers\AddressController@update'); optional
Route::delete('/address/{id}', 'App\Http\Controllers\AddressController@delete');

Route::get('/search', 'App\Http\Controllers\SearchController@search');
