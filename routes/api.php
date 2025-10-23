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

Route::post('/auth/login', 'App\Http\Controllers\UserController@login');
Route::post('/auth/logout', 'App\Http\Controllers\AuthController@logout');
Route::post('/auth/refresh', 'App\Http\Controllers\AuthController@refresh');
Route::get('/me', 'App\Http\Controllers\AuthController@me');

Route::group(['middleware' => ['auth:api']], function () {
  Route::prefix('user')->group(function () {
    Route::get('/', 'App\Http\Controllers\UserController@index');
    Route::post('/', 'App\Http\Controllers\UserController@create');
    Route::get('/{id}', 'App\Http\Controllers\UserController@show');
    Route::put('/{id}', 'App\Http\Controllers\UserController@update');
    Route::patch('/{id}', 'App\Http\Controllers\UserController@update');
    Route::delete('/{id}', 'App\Http\Controllers\UserController@delete');
  });

  Route::prefix('profile')->group(function () {
    Route::post('/', 'App\Http\Controllers\UserController@createprofile');
    Route::post('/{id}', 'App\Http\Controllers\ProfileController@update');
    Route::delete('/{id}', 'App\Http\Controllers\ProfileController@delete');
  });

  Route::prefix('address')->group(function () {
    Route::get('/', 'App\Http\Controllers\AddressController@index');
    Route::get('/{id}', 'App\Http\Controllers\AddressController@show');
    Route::get('/cep/{cep}', 'App\Http\Controllers\AddressController@searchByCep');
    Route::post('/', 'App\Http\Controllers\AddressController@store');
    Route::put('/{id}', 'App\Http\Controllers\AddressController@update'); // optional
    Route::patch('/{id}', 'App\Http\Controllers\AddressController@update'); // optional
    Route::delete('/{id}', 'App\Http\Controllers\AddressController@delete');
  });

  Route::prefix('search')->group(function () {
    Route::get('/', 'App\Http\Controllers\SearchController@search');
  });
});