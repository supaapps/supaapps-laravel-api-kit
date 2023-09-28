<?php

namespace Tests\Feature\Controllers\CrudTraits;

use Tests\Stubs\SupaLaraExampleModel;
use Tests\TestCase;

class CrudIndexTraitTest extends TestCase
{
    public function testItGetsAllRecordsUsingIndexMethod()
    {
        SupaLaraExampleModel::factory(2)->create();

        $response = $this->getJson('/examples');

        $response->assertOk()
            ->assertJson([
                ['id' => 1],
                ['id' => 2]
            ]);
    }
}
