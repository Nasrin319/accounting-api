<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/exchange-currency', ['as' => 'index', 'uses' => 'App\Http\Controllers\CurrencyController@index']);
Route::post('/exchange-currency', ['as' => 'store', 'uses' => 'App\Http\Controllers\CurrencyController@store']);
Route::get('/asset', ['as' => 'index', 'uses' => 'App\Http\Controllers\CurrencyController@fetchOurAssets']);
