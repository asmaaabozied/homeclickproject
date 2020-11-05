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

Route::group(['prefix' => '{locale}' ], function() {
    Route::prefix('/pages')->group( function($router){
        $router->pattern('slug', '^(?=.*)((?!\/).)*$');
        Route::get('{slug}', 'api\v1\PagesController@index');
    });
});
