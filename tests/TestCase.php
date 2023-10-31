<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Supaapps\Supalara\SupalaraServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SupalaraServiceProvider::class,
        ];
    }
}
