<?php

namespace App\Providers;

use Request;
use Illuminate\Support\ServiceProvider;
use Schema;
use Config;
use function GuzzleHttp\Promise\all;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);

    }
}
