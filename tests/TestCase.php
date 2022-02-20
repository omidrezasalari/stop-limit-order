<?php

namespace Omidrezasalari\StopLimitTest;

abstract class TestCase extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['Omidrezasalari\StopLimit\Providers\StopLimitServiceProvider'];
    }
}
