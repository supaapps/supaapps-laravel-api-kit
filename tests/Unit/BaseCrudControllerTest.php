<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Stubs\Controllers\FakeCrudController;
use Supaapps\LaravelApiKit\Exceptions\CrudModelIsNotDefinedException;

class BaseCrudControllerTest extends TestCase
{
    public function testItFailsIfModelIsNotDefined(): void
    {
        $this->expectException(CrudModelIsNotDefinedException::class);
        $this->expectExceptionCode(500);

        $controller = app(FakeCrudController::class);

        $controller->index();
    }
}
