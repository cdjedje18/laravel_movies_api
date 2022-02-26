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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("movies", 'MovieController@index');
Route::get("movies/{id}", 'MovieController@show');
Route::post("movies", 'MovieController@store');
Route::put("movies/{movie}", 'MovieController@update');


Route::get("actors", 'ActorController@index');
Route::get("actors/{id}", 'ActorController@show');
Route::post("actors", 'ActorController@store');
Route::put("actors/{actor}", 'ActorController@update');


Route::get("casts", 'CastController@index');
Route::get("casts/{id}", 'CastController@show');
Route::post("casts", 'CastController@store');
Route::put("casts/{cast}", 'CastController@update');
