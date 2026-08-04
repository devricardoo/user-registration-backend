<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
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

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);
Route::get('/me', [AuthController::class, 'me']);

Route::group(['middleware' => ['auth:api']], function () {
  Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'create']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::patch('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'delete']);
  });

  Route::prefix('profile')->group(function () {
    Route::post('/', [UserController::class, 'createprofile']);
    Route::post('/{id}', 'App\Http\Controllers\ProfileController@update');
    Route::delete('/{id}', 'App\Http\Controllers\ProfileController@delete');
  });

  Route::prefix('address')->group(function () {
    Route::get('/', [AddressController::class, 'index']);
    Route::get('/{id}', [AddressController::class, 'show']);
    Route::get('/cep/{cep}', [AddressController::class, 'searchByCep'])
      ->where('cep', '[0-9-]+');
    Route::post('/', [AddressController::class, 'store']);
    Route::put('/{id}', [AddressController::class, 'update']); // optional
    Route::patch('/{id}', [AddressController::class, 'update']); // optional
    Route::delete('/{id}', [AddressController::class, 'delete']);
  });

  Route::prefix('search')->group(function () {
    Route::get('/', [SearchController::class, 'search']);
  });
});
