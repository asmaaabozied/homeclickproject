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

// Route::middleware('auth:api')->get('/chatting', function (Request $request) {
//     return $request->user();
// });
Route::group(['prefix' => '{locale}' , 'middleware'=>'auth:api'], function() {
        Route::post('/upload-chatting-image' , 'api\v1\ChattingController@upload_chatting_image');
        Route::post('/save-message' , 'api\v1\ChattingController@save_message');
});