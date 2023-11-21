<?php

namespace Tests\Feature\Controllers\CrudTraits;

use Tests\Stubs\SupaLaraExampleModel;
use Tests\TestCase;

class CrudUpdateTraitTest extends TestCase
{
    public function testItCanNotUpdateReadonlyModel(): void
    {
        $model = SupaLaraExampleModel::factory()
            ->create();

        $response = $this->patchJson("/readonly-example/{$model->id}", [
            'label' => 'SUPA'
        ]);

        $response->assertUnauthorized();

        $this->assertDatabaseHas("supa_lara_example_models", [
            'id' => 1,
            'label' => $model->label,
        ]);
    }

    public function testItCanStoreTheModel(): void
    {
        $modelId = SupaLaraExampleModel::factory(2)
            ->create()
            ->first()
            ->id;

        $response = $this->patchJson('/examples/1', [
            'label' => 'SUPA'
        ]);

        $response->assertOk()
            ->assertJson([
                'id' => 1,
                'label' => 'SUPA',
            ]);

        $this->assertDatabaseHas("supa_lara_example_models", [
            'id' => 1,
            'label' => 'SUPA',
        ]);
    }
}
