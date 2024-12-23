<?php


namespace Api;

use Illuminate\Support\Facades\Facade;

class AuthApi extends Facade
{
    protected static function getFacadeAccessor():string
    {
        return 'auth.api';
    }
}
