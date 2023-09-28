<?php

namespace Tests\Unit;

use Supaapps\Supalara\Exceptions\CrudModelIsNotDefinedException;
use Tests\Stubs\FakeCrudController;
use Tests\TestCase;

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
