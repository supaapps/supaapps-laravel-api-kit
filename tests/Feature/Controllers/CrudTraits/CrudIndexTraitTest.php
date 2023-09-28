<?php

namespace Tests\Feature\Controllers\CrudTraits;

use Tests\TestCase;

class CrudIndexTraitTest extends TestCase
{
    public function testItGetsAllRecordsUsingIndexMethod()
    {
        $this->markTestSkipped();

        $response = $this->getJson('/examples');

        $response->assertStatus(500);
    }
}
