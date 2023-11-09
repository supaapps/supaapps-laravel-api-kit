<?php

namespace Tests\Feature\Controllers\CrudTraits;

use Tests\TestCase;
use Tests\Stubs\SupaLaraExampleModel;

class CrudDestroyTraitTest extends TestCase
{
    public function testItCanNotDeleteModelWhenNonDeletePropertyIsFalse(): void
    {
        $model = SupaLaraExampleModel::factory()->create();

        $response = $this->deleteJson("/non-deletable-example/{$model->id}");

        $response->assertUnauthorized();
        $this->assertModelExists($model);
    }

    public function testItCanNotDeleteModelWhenReadOnlyPropertyIsTrue(): void
    {
        $model = SupaLaraExampleModel::factory()->create();

        $response = $this->deleteJson("/readonly-example/{$model->id}");

        $response->assertUnauthorized();
        $this->assertModelExists($model);
    }

    public function testItCanDeleteTheModel(): void
    {
        $models = SupaLaraExampleModel::factory(2)
            ->create();

        $response = $this->deleteJson("/examples/{$models->first()->id}");

        $response->assertNoContent();
        $this->assertModelMissing($models->first());
        $this->assertModelExists($models->get(1));
    }
}
