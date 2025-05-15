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
Route::post('/user', 'App\Http\Controllers\AuthController@create');

Route::get('/user', 'App\Http\Controllers\UserController@index');
Route::put('/user/{id}', 'App\Http\Controllers\UserController@update');
Route::patch('/user/{id}', 'App\Http\Controllers\UserController@update');
Route::delete('/user/{id}', 'App\Http\Controllers\UserController@delete');

Route::post('/profile', 'App\Http\Controllers\UserController@createprofile');
Route::post('/profile/{id}', 'App\Http\Controllers\ProfileController@updateprofile');
Route::delete('/profile/{id}', 'App\Http\Controllers\ProfileController@deleteprofile');

Route::get('/address', 'App\Http\Controllers\AddressController@index');
Route::post('/address', 'App\Http\Controllers\AddressController@createaddress');
Route::put('/address/{id}', 'App\Http\Controllers\AddressController@updateaddress');
Route::patch('/address/{id}', 'App\Http\Controllers\AddressController@updateaddress');
Route::delete('/address/{id}', 'App\Http\Controllers\AddressController@deleteaddress');
