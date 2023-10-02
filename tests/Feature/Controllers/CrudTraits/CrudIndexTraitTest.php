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

    public function testItSearchesRecordsWithSearchField()
    {
        SupaLaraExampleModel::factory(12)->create();

        $response = $this->getJson('/paginated-examples?search=2');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    ['id' => 2],
                    ['id' => 12]
                ]
            ]);
    }

    public function testItSearchesRecordsWithSimilarFields()
    {
        SupaLaraExampleModel::factory()->create(['label' => 'Google']);
        SupaLaraExampleModel::factory()->create(['label' => 'Github']);

        $response = $this->getJson('/examples?search=oo');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'label' => 'Google'
                ]
            ]);
    }

    public function testItSearchesRecordsWithExactFields()
    {
        SupaLaraExampleModel::factory(2)->create();

        $response = $this->getJson('/examples?search=2');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                ['id' => 2]
            ]);
    }

    public function testItSearchesRecordsWithDateFields()
    {
        $this->travel(-1)->day(); // set date to yesterday
        SupaLaraExampleModel::factory()->create();
        $this->travelBack(); // set date back to present

        SupaLaraExampleModel::factory()->create();
        $today = date('Y-m-d');

        $response = $this->getJson("/examples?search={$today}");

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                ['id' => 2]
            ]);
    }
}
