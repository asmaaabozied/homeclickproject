<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {

        Route::get('/clear', function() {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            Artisan::call('config:cache');
            return "Cache is cleared";
        });

        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {

            Route::get('/', 'WelcomeController@index')->name('welcome');


            //messages
            Route::resource('contacts', 'ContactController')->except(['show']);
            //end message

            //complain
            Route::resource('catogeryjobs', 'CatogeryjobController')->except(['show']);

            Route::Post('catogeryjobs/status/{id}', 'CatogeryjobController@change_status')->name('catogeryjobs.status');
            // end complain

            //products  CategoryController
            Route::resource('catogeries', 'CatogeryController')->except(['show']);

            Route::resource('categories', 'CategoryController')->except(['show']);


            Route::resource('products', 'ProductController')->except(['show']);

            Route::Post('products/status/{id}', 'ProductController@change_status')->name('products.status');


            //end products
            //offers
            Route::resource('offers', 'OfferController')->except(['show']);
            //endoffers


            //subscriptions   $$ sellers
            Route::resource('subscriptions', 'SubscriptionController')->except(['show']);

            Route::resource('sellers', 'SellerController')->except(['show']);

            Route::Post('sellers/status/{id}', 'SellerController@change_status')->name('sellers.status');

            Route::resource('capons', 'CaponController')->except(['show']);


            //end subscriptions


//home click  reports


            Route::get('reportusers', 'ReportController@reportusers')->name('reportusers');

            Route::get('reportseller', 'ReportController@reportseller')->name('reportseller');


            Route::get('reportvisitor', 'ReportController@reportvisitor')->name('reportvisitor');

            Route::delete('visitors/destroy/{id}', 'ReportController@destroy')->name('visitors.destroy');


            Route::Post('contacts/status/{id}', 'ContactController@change_status')->name('contacts.status');

            //end of reports

            // orders

            Route::resource('orders', 'OrderController');


            Route::get('download_file/{id}', 'OrderController@downloadFile')->name('download_file.downloadSingleFile');


            Route::get('reportorders', 'OrderController@reportorders')->name('reportorders');


            Route::post('orders/status/{id}', 'OrderController@change_status')->name('orders.status');

            Route::put('SendEmail/{id}', 'SellerController@SendEmail')->name('SendEmail');


            Route::get('sendEmailorders/{id}', 'SellerController@sendEmailorders')->name('sendEmailorders');


            //End of Orders


            //tag routes
            Route::resource('tags', 'TagController')->except(['show']);


            //user routes
            Route::resource('users', 'UserController')->except(['show']);
            //setting
            Route::resource('settings', 'SettingController');

            Route::post('settings', 'SettingController@updateAll')->name('settings.updateAll');

            Route::post('users/block/{id}', 'UserController@block')->name('users.block');

            Route::resource('roles', 'RoleController')->except(['show']);

        });//end of dashboard routes
    });




