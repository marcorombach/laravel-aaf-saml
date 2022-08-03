<?php

namespace Marcorombach\LaravelAafSAML\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Marcorombach\LaravelAafSAML\LaravelAafSAML
 */
class LaravelAafSAML extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-aaf-saml';
    }
}
