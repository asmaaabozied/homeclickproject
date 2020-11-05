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
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {
        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
            //user routes
            Route::resource('manage_users', 'UserManagementController')->except(['show']);
            Route::post('manage_users/block/{id}', 'UserManagementController@block')->name('manage_users.block');

            Route::resource('manage_employees', 'EmployeeManagmentController')->except(['show']);
            Route::post('manage_employees/block/{id}', 'EmployeeManagmentController@block')->name('manage_employees.block');

        });
});
