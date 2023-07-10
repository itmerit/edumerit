<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EskizSmsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'eskizsms';
    }
}
