<?php

namespace Tests\Feature\Controllers\CrudTraits;

use Tests\Stubs\SupaLaraExampleModel;
use Tests\TestCase;

class CrudStoreTraitTest extends TestCase
{
    public function testItCanStoreTheModel(): void
    {
        $model = SupaLaraExampleModel::factory()
            ->make();

        $response = $this->postJson("/examples", $model->toArray());

        $response->assertCreated()
            ->assertJson($model->toArray());

        $this->assertDatabaseHas($model->getTable(), [
            'id' => 1,
            'label' => $model->label,
        ]);
    }
}
