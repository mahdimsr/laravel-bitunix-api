<?php

namespace Msr\LaravelBitunixApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Msr\LaravelBitunixApi\LaravelBitunixApi
 */
class LaravelBitunixApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Msr\LaravelBitunixApi\LaravelBitunixApi::class;
    }
}
