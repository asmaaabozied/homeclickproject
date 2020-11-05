<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::post('password/savenewpassword', 'api/v1/ForgotPasswordController@reset');

Route::get('/', function () {
    return redirect()->route('dashboard.welcome');
    //return Redirect::to('public/ar/dashboard');
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {
        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
            //user routes
            Route::resource('catogery', 'CatogeryController')->except(['show']);


        });
    });

;
