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

    public function testItFiltersByGivenFilters()
    {
        SupaLaraExampleModel::factory(3)->create();

        $response = $this->getJson('/examples?ids[]=1&ids[]=2');

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                ['id' => 1],
                ['id' => 2],
            ]);
    }

    public function testItFiltersByGivenMinDate()
    {
        $this->travel(-1)->day(); // set date to yesterday
        SupaLaraExampleModel::factory()->create();
        $this->travelBack(); // set date back to present

        SupaLaraExampleModel::factory()->create();
        $today = date('Y-m-d');

        $response = $this->getJson("/examples?created_at_min={$today}");

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                ['id' => 2],
            ]);
    }

    public function testItFiltersByGivenMaxDate()
    {
        $this->travel(-1)->day(); // set date to yesterday
        SupaLaraExampleModel::factory()->create();
        $yesterday = now()->toDateString();
        $this->travelBack(); // set date back to present

        SupaLaraExampleModel::factory()->create();

        $response = $this->getJson("/examples?created_at_max={$yesterday}");

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                ['id' => 1],
            ]);
    }

    public function testItFiltersCreatedAtByGivenRange()
    {
        $this->travel(-10)->day(); // set date to 10 days ago
        SupaLaraExampleModel::factory()->create();

        $this->travel(9)->day(); // set date to yesterday
        SupaLaraExampleModel::factory()->create(); // id = 2
        $min = now()->toDateString();

        $this->travelBack(); // set date back to present
        SupaLaraExampleModel::factory()->create(); // id = 3
        $max = now()->toDateString();

        $this->travel(2)->day(); // set date to day after tomorrow
        SupaLaraExampleModel::factory()->create();
        $this->travelBack(); // set date back to present

        $query = http_build_query([
            'created_at_min' => $min,
            'created_at_max' => $max,
        ]);

        $response = $this->getJson("/examples?{$query}");

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                ['id' => 2],
                ['id' => 3],
            ]);
    }

    public function testItGetsNullLabelRecords()
    {
        SupaLaraExampleModel::factory()->create([
            'label' => null
        ]);
        SupaLaraExampleModel::factory()->create();

        $response = $this->getJson('/examples?is_empty[label]=true');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                ['id' => 1],
            ]);
    }

    public function testItGetsNotNullLabelRecords()
    {
        SupaLaraExampleModel::factory()->create([
            'label' => null
        ]);
        SupaLaraExampleModel::factory()->create();

        $response = $this->getJson('/examples?is_empty[label]=false');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                ['id' => 2],
            ]);
    }
}
