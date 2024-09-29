<?php

namespace App\Providers;

use App;
use Illuminate\Support\ServiceProvider;

class UploadFacadesServiceProvider extends ServiceProvider
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
        App::bind('upload',function(){
            return new App\Upload;
        });
    }
}
