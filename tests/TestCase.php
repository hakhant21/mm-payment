<?php

namespace Hakhant\Payments\Tests;

use Hakhant\Payments\Providers\PaymentServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PaymentServiceProvider::class,
        ];
    }
}
