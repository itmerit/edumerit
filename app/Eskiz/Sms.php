<?php

namespace App\Eskiz;


use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Sms';
    }
}
