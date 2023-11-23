<?php

namespace Tests\Unit\Observers;

use Tests\Stubs\SupaLaraExampleModel;
use Tests\Stubs\SupaLaraUserModel;
use Tests\TestCase;

class UserSignatureObserverTest extends TestCase
{
    public function testItStoreAuthUserIdToOnCreatingAModel(): void
    {
        $user = SupaLaraUserModel::factory()
            ->create();
        $this->actingAs($user);

        $model = SupaLaraExampleModel::create();

        $this->assertEquals($user->id, $model->created_by_id);

        $this->assertEquals($user->id, $model->updated_by_id);
    }

    public function testItStoreAuthUserIdToOnUpdatingAModel(): void
    {
        $user1 = SupaLaraUserModel::factory()
            ->create();
        $user2 = SupaLaraUserModel::factory()
            ->create();

        $this->actingAs($user1);
        $model = SupaLaraExampleModel::create();

        $this->actingAs($user2);
        $model->update(['label' => time()]);

        $this->assertEquals($user1->id, $model->created_by_id);

        $this->assertEquals($user2->id, $model->updated_by_id);
    }
}
