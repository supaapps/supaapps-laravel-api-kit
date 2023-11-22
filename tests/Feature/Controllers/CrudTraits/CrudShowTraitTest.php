<?php

namespace Tests\Feature\Controllers\CrudTraits;

use Tests\Stubs\SupaLaraExampleModel;
use Tests\TestCase;

class CrudShowTraitTest extends TestCase
{
    public function testItCanGetTheModel(): void
    {
        $models = SupaLaraExampleModel::factory(2)
            ->create();

        $response = $this->getJson("/examples/{$models->first()->id}");

        $response->assertOk()
            ->assertJson($models->first()->toArray());
    }
}
