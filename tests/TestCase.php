<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Supaapps\LaravelApiKit\LaravelApiKitServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelApiKitServiceProvider::class,
        ];
    }
}
