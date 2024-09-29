<?php 

    namespace App\Facades;
    use Illuminate\Support\Facades\Facade;

class CustomURL extends Facade{
    protected static function getFacadeAccessor(){
        return "custom_url";
    }
}