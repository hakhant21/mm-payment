<?php

namespace Hakhant\Payments\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hakhant\Payments\Payment
 */
class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}
