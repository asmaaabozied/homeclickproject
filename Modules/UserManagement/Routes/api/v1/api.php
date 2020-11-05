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


Route::group(['prefix' => '{locale}' , 'middleware'=>'auth:api'], function() {
    
    // Route::middleware('auth:api')->get('/userManagement', function (Request $request) {
    //     return $request->user();
    // });

    Route::prefix('user/profile')->group( function(){
        Route::get('/' , 'api\v1\UserController@show')->name('showProfile');
        Route::post('/edit' , 'api\v1\UserController@update')->name('updateProfile');

    });
});