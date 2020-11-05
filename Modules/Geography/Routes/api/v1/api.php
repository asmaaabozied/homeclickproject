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

// Route::middleware('auth:api')->get('/geography', function (Request $request) {
//     return $request->user();
// });

// Route::group(['prefix' => '{locale}' , 'middleware'=>'auth:api'], function() {
Route::group(['prefix' => '{locale}' ], function() {
        //subscriptions
    Route::prefix('/geographies')->group( function(){
        Route::get('/' , 'api\v1\GeographyController@index');
    });
    Route::prefix('/countries')->group( function(){
        Route::get('/' , 'api\v1\GeographyController@countries');
    });
    Route::prefix('/cities')->group( function(){
        Route::get('/{country_id}' , 'api\v1\GeographyController@cities');
    });
});