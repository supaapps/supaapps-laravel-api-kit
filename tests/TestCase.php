<?php

namespace Tests;

use Tests\Stubs\ExampleController;
use Illuminate\Support\Facades\Route;
use Supaapps\Supalara\SupalaraServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();

        // register curd example routes
        Route::apiResource('examples', ExampleController::class);

        return $app;
    }

    protected function getPackageProviders($app)
    {
        return [
            SupalaraServiceProvider::class,
        ];
    }
}
