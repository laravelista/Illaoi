<?php

namespace Laravelista\Illaoi\Facades;

use Illuminate\Support\Facades\Facade;

class Illaoi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'illaoi';
    }

}