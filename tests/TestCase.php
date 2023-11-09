<?php

namespace Tests;

use Tests\Stubs\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Supaapps\LaravelApiKit\LaravelApiKitServiceProvider;

class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();

        $this->scaffoldingExampleModel();

        return $app;
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelApiKitServiceProvider::class,
        ];
    }

    private function scaffoldingExampleModel(): void
    {
        // create fake examples table for testing
        Schema::create('supa_lara_example_models', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // register curd example routes
        Route::apiResource('examples', Controllers\SupaLaraExampleController::class);
        Route::get('paginated-examples', [
            Controllers\SupaLaraPaginatedExampleController::class,
            'index'
        ]);
        Route::apiResource(
            'non-deletable-example',
            Controllers\SupaLaraNonDeletableExampleController::class
        )->only('destroy');
        Route::apiResource(
            'readonly-example',
            Controllers\SupaLaraReadonlyExampleController::class
        )->only('destroy');
    }
}
