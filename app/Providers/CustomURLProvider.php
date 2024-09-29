<?php

namespace App\Providers;

use App;
use App\CustomURL;
use Illuminate\Support\ServiceProvider;

class CustomURLProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        App::bind('custom_url',function(){
            return new CustomURL;
        });
    }
}
