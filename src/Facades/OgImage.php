<?php

namespace Petersuhm\Ogkit\Facades;

use Illuminate\Support\Facades\Facade;

class OgImage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ogkit.service';
    }
}
