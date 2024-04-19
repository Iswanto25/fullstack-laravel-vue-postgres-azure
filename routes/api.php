<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/person', 'App\Http\Controllers\Api\PersonController@index');
Route::post('/person', 'App\Http\Controllers\Api\PersonController@store');