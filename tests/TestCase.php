<?php

namespace Tests;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\Stubs\SupaLaraExampleController;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tests\Stubs\SupaLaraPaginatedExampleController;
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
        Route::apiResource('examples', SupaLaraExampleController::class);
        Route::get('paginated-examples', [SupaLaraPaginatedExampleController::class, 'index']);
    }
}
