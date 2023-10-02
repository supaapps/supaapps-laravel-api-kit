<?php

namespace Tests\Feature\Controllers\CrudTraits;

use Tests\TestCase;
use Tests\Stubs\SupaLaraExampleModel;

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

    public function testItGetsPaginatedRecordsUsingIndexMethod()
    {
        SupaLaraExampleModel::factory(2)->create();

        $response = $this->getJson('/paginated-examples');

        $response->assertOk()
            ->assertJson([
                'current_page' => 1,
                'from' => 1,
                'last_page' => 1,
                'per_page' => 50,
                'to' => 2,
                'total' => 2,
                'data' => [
                    ['id' => 1],
                    ['id' => 2]
                ],
            ]);
    }
}
