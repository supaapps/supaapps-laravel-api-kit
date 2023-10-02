<?php

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Tests\Stubs\SupaLaraExampleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Supaapps\Supalara\SupalaraServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tests\Stubs\SupaLaraPaginatedExampleController;

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
            SupalaraServiceProvider::class,
        ];
    }

    private function scaffoldingExampleModel(): void
    {
        // create fake examples table for testing
        Schema::create('supa_lara_example_models', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        // register curd example routes
        Route::apiResource('examples', SupaLaraExampleController::class);
        Route::get('paginated-examples', [SupaLaraPaginatedExampleController::class, 'index']);
    }
}
