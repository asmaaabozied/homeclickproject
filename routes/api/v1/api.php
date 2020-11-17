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

Route::group(['prefix' => '{locale}'], function () {
    Route::post('/testing', 'api\v1\AuthController@testing');
    Route::middleware(['auth:api'])->post('/notify', 'api\v1\NotificationController@sendNotify');

});


Route::group(['prefix' => '{locale}'], function () {
// #  user
    Route::post('login', 'api\v1\AuthController@login');

    Route::post('/register', 'api\v1\AuthController@register');

    Route::get('reset_password', 'api\v1\AuthController@reset_password');

    Route::get('new_password', 'api\v1\AuthController@new_password');


    Route::Put('updateprofile', 'api\v1\AuthController@updateprofile')->middleware(['auth:api']);


    Route::get('showprofile', 'api\v1\AuthController@showprofile')->middleware(['auth:api']);


    Route::get('logout', 'api\v1\AuthController@logout')->middleware(['auth:api']);


//end of user

    Route::post('contact_us', 'api\v1\ContactController@contact_us');

    Route::get('listofcomplain', 'api\v1\ContactController@listofcomplain');

    Route::post('create_address', 'api\v1\AuthController@create_address')->middleware(['auth:api']);

    Route::put('update_address', 'api\v1\AuthController@update_address')->middleware(['auth:api']);

    Route::get('show_address', 'api\v1\AuthController@show_address')->middleware(['auth:api']);

    Route::get('listofoffers', 'api\v1\productController@listofoffers');

    Route::get('listofofferofcatogeries', 'api\v1\CatogeryController@listofofferofcatogeries');


//products

    Route::get('listofproducts', 'api\v1\ProductController@listofproducts');

    Route::get('filterproduct', 'api\v1\ProductController@filterproduct');

    Route::post('add_ratingproducts', 'api\v1\ProductController@add_ratingproducts');


    Route::get('favourite_product', 'api\v1\ProductController@favourite_product')->middleware(['auth:api']);

    Route::get('listofcatogeries_product', 'api\v1\ProductController@listofcatogeries_product');


    Route::post('add_favourite_product', 'api\v1\ProductController@add_favourite_product')->middleware(['auth:api']);


    Route::get('detailsproduct', 'api\v1\ProductController@detailsproduct');


    Route::get('listofcatogery', 'api\v1\CatogeryController@listofcatogery');

//end of products


    //stores


    Route::post('add_ratingsellers', 'api\v1\StoreController@add_ratingsellers');

    Route::get('filtersellers', 'api\v1\StoreController@filtersellers');



    Route::get('listofsellers', 'api\v1\StoreController@listofsellers');

    Route::get('details_sellers', 'api\v1\StoreController@details_sellers');

    Route::post('add_stores', 'api\v1\StoreController@add_stores');

    Route::get('listofsubscriptions', 'api\v1\StoreController@listofsubscriptions');

    Route::post('add_favourite_seller', 'api\v1\StoreController@add_favourite_seller')->middleware(['auth:api']);

    Route::get('favourite_seller', 'api\v1\StoreController@favourite_seller')->middleware(['auth:api']);


    //end of stores


    // carts homeclick

    //    Route::post('add_cart','api\v1\CartController@add_cart')->middleware(['auth:api']);

    Route::post('add_cart', 'api\v1\CartController@add_cart');

    Route::get('show_cart', 'api\v1\CartController@show_cart');

    Route::get('check_code_capons', 'api\v1\CartController@check_code_capons');


// End of carts


    // Oreders

    Route::get('show_orders', 'api\v1\OrderController@show_orders')->middleware(['auth:api']);
    Route::get('details_orders', 'api\v1\OrderController@details_orders')->middleware(['auth:api']);
    Route::post('add_order', 'api\v1\OrderController@add_order')->middleware(['auth:api']);

    Route::get ('canceled_order', 'api\v1\OrderController@canceled_order')->middleware(['auth:api']);

    // End of Orders


    //project ahm
//

    Route::post('uploadimages', 'api\v1\AuthController@uploadimages');

//users by asmaa

    Route::post('visitors', 'api\v1\VisitorController@AddVisitor');


    Route::get('listofcities', 'api\v1\CityController@cities');

    Route::get('listofcountry', 'api\v1\CityController@listofcountry');



    //consulations


//notification  countnotifications  update_countnotifications

    Route::get('shownotifications', 'api\v1\NotifyController@shownotifications')->middleware(['auth:api']);
    Route::get('countnotifications', 'api\v1\NotifyController@countnotifications')->middleware(['auth:api']);
    Route::post('update_countnotifications', 'api\v1\NotifyController@update_countnotifications');


    //end notifications


    //users


    Route::post('password/email', 'api\v1\ForgotPasswordController@sendResetLinkEmail');


    Route::post('changepassword', 'api\v1\AuthController@changepassword');


    Route::get('settings', 'api\v1\SettingController@settings');

    Route::post('social_login', 'api\v1\SettingController@social_login');



});



