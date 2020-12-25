<?php
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

Route::group(['middleware' => ['json.response','english.numbers']], function () {

    // public routes
    Route::post('/login', 'Api\AuthController@login')->name('login.api');

    Route::get('/users','Api\UserController@index')->name('users.all');
    Route::post('/interview/next', 'Api\UserController@next_interview')->name('users.interview.notify');
        
    // private routes
    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', 'Api\AuthController@logout')->name('logout.mobile');
    });
});