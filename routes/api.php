<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', 'Auth\RegisterController@registration');
Route::get('/register/verify/{confirmation_code}', 'Auth\RegisterController@confirm');
Route::post('/login', 'Auth\LoginController@authenticate');
Route::post('/reset', 'Auth\ResetPasswordController@reset');

Route::post('/goods/create', 'GoodsController@create');
Route::put('/goods/{id}', 'GoodsController@update');
Route::delete('/goods/{id}', 'GoodsController@delete');
Route::get('/goods/', 'GoodsController@get');
